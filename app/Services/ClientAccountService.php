<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientLedger;
use App\Models\Recibo;
use App\Models\ReciboImputacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClientAccountService
{
    /**
     * Registrar un cobro oficial y aplicarlo a la deuda (FIFO u opcional facturas)
     */
    public function registrarCobro($clientId, $monto, $metodo = 'Varios', $referencia = null, $fecha = null, $facturasEspecificas = [], $pagosDiferenciados = [], $autoImputar = true)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;
        $fecha = $fecha ?: date('Y-m-d');

        return DB::transaction(function () use ($clientId, $monto, $metodo, $referencia, $fecha, $empresaId, $user, $facturasEspecificas, $pagosDiferenciados, $autoImputar) {
            
            // Si mandamos un array de pagos diferenciados, recalculamos el total para prevención de errores.
            if (!empty($pagosDiferenciados)) {
                $monto = 0;
                foreach ($pagosDiferenciados as $p) {
                    $monto += (float) $p['monto'];
                }
                $metodo = 'Múltiple'; // Override del método principal para que quede claro.
            }

            // 1. Generar número de recibo correlativo
            $numeroRecibo = $this->generarNumeroRecibo($empresaId);

            // 2. Crear el Recibo
            $recibo = Recibo::create([
                'empresa_id'    => $empresaId,
                'client_id'     => $clientId,
                'user_id'       => $user->id,
                'numero_recibo' => $numeroRecibo,
                'monto_total'   => $monto,
                'metodo_pago'   => $metodo,
                'referencia'    => $referencia,
                'fecha'         => $fecha,
            ]);

            // Guardar subdivisión de métodos de pago en la nueva tabla
            if (!empty($pagosDiferenciados)) {
                foreach ($pagosDiferenciados as $p) {
                    if ((float) $p['monto'] > 0) {
                        $reciboPago = \App\Models\ReciboPago::create([
                            'recibo_id'   => $recibo->id,
                            'metodo_pago' => $p['metodo_pago'],
                            'monto'       => $p['monto'],
                            'referencia'  => $p['referencia'] ?? null,
                        ]);

                        if ($p['metodo_pago'] == 'cheque' || $p['metodo_pago'] == 'cheque_tercero') {
                            \App\Models\Cheque::create([
                                'empresa_id'    => $empresaId,
                                'numero'        => $p['numero'] ?? ($p['referencia'] ?? 'S/N'),
                                'banco'         => $p['banco'] ?? 'Pendiente',
                                'monto'         => $p['monto'],
                                'fecha_emision' => $fecha,
                                'fecha_pago'    => $p['fecha_pago'] ?? $fecha,
                                'estado'        => 'en_cartera',
                                'tipo'          => 'tercero',
                                'client_id'     => $clientId,
                            ]);
                        }
                    }
                }
            } else {
                // Retrocompatibilidad o Pago Único Tradicional
                $reciboPago = \App\Models\ReciboPago::create([
                    'recibo_id'   => $recibo->id,
                    'metodo_pago' => $metodo,
                    'monto'       => $monto,
                    'referencia'  => $referencia,
                ]);

                if ($metodo == 'cheque' || $metodo == 'cheque_tercero') {
                    \App\Models\Cheque::create([
                        'empresa_id'    => $empresaId,
                        'numero'        => $referencia ?? 'S/N',
                        'banco'         => 'Pendiente',
                        'monto'         => $monto,
                        'fecha_emision' => $fecha,
                        'fecha_pago'    => $fecha,
                        'estado'        => 'en_cartera',
                        'tipo'          => 'tercero',
                        'client_id'     => $clientId,
                    ]);
                }
            }

            // 3. Crear el movimiento de crédito en el Ledger
            $movimientoPago = ClientLedger::create([
                'empresa_id'     => $empresaId,
                'client_id'      => $clientId,
                'reference_type' => Recibo::class,
                'reference_id'   => $recibo->id,
                'type'           => 'credit',
                'amount'         => $monto,
                'description'    => "Recibo de Pago #{$numeroRecibo}",
                'paid'           => !$autoImputar, // Si no se imputa auto, ¿queda "paid"? No, debe quedar con pending_amount. El boot_loader ya le setea el pending_amount al amount.
                'created_at'     => $fecha . ' ' . date('H:i:s'),
            ]);

            // Restablecemos el estatus general para asegurar que tenga saldo disponible.
            $movimientoPago->paid = false;
            $movimientoPago->pending_amount = $monto;
            $movimientoPago->save();

            // 4. MOTOR DE IMPUTACIÓN (FIFO o Selección)
            if ($autoImputar) {
                $restante = $this->imputarMontoDeuda($clientId, $monto, $recibo, $facturasEspecificas);

                // Si quedó restante es porque el usuario pagó de más a las facturas que seleccionó, o no había más deuda. 
                // Aplicamos un fallback FIFO para el dinero sobrante si mandó facturas específicas.
                if ($restante > 0 && !empty($facturasEspecificas)) {
                    $restante = $this->imputarMontoDeuda($clientId, $restante, $recibo, []);
                }
                
                // Actualizar credit residual final:
                $movimientoPago->pending_amount = $restante;
                if ($restante <= 0.009) {
                    $movimientoPago->paid = true;
                    $movimientoPago->pending_amount = 0;
                }
                $movimientoPago->save();
            }

            // 5. Incrementar contador de recibos en la empresa
            $user->empresa->increment('proximo_numero_recibo');

            return $recibo;
        });
    }

    /**
     * Aplica un monto de crédito a las deudas más antiguas (FIFO) o facturas elegidas
     */
    public function imputarMontoDeuda($clientId, $montoDisponible, $recibo = null, $facturasEspecificas = [])
    {
        // Traer débitos con saldo pendiente, priorizando si hay IDs específicos
        $query = ClientLedger::where('client_id', $clientId)
            ->where('type', 'debit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0);
            
        if (!empty($facturasEspecificas)) {
            // Asegurar que si hay facturas seleccionadas, se paguen solo esas
            $query->whereIn('id', $facturasEspecificas);
        } else {
            // Si no hay, usar FIFO (las más antiguas)
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        }

        $deudas = $query->get();

        foreach ($deudas as $deuda) {
            if ($montoDisponible <= 0) break;

            $aPagar = min($montoDisponible, $deuda->pending_amount);
            
            // Actualizar deuda
            $deuda->pending_amount -= $aPagar;
            if ($deuda->pending_amount <= 0.009) { // Evitar problemas de coma flotante
                $deuda->paid = true;
                $deuda->pending_amount = 0;
            }
            $deuda->save();

            // Registrar imputación si hay recibo
            if ($recibo) {
                ReciboImputacion::create([
                    'recibo_id'      => $recibo->id,
                    'ledger_id'      => $deuda->id,
                    'monto_aplicado' => $aPagar,
                ]);
            }

            $montoDisponible -= $aPagar;
        }

        return $montoDisponible; // Retorna si sobró dinero (Saldo a favor)
    }

    /**
     * Busca saldos a favor (créditos con saldo pendiente) y los aplica 
     * manual y EXCLUSIVAMENTE a las facturas que el dueño selecciona.
     */
    public function aplicarSaldosAFavorEspecificos($clientId, $facturasEspecificas)
    {
        if (empty($facturasEspecificas)) return; // Si no hay facturas a las que aplicar, salir.

        $creditosFlotantes = ClientLedger::where('client_id', $clientId)
            ->where('type', 'credit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($creditosFlotantes as $credito) {
            if ($credito->pending_amount <= 0.009) continue;

            $recibo = $credito->reference_type === Recibo::class ? $credito->reference : null;
            
            // Imputar el dinero de ESTE crédito flotante a las facturas seleccionadas
            $restante = $this->imputarMontoDeuda($clientId, $credito->pending_amount, $recibo, $facturasEspecificas);

            // Actualizar el saldo restante en el recibo original
            $credito->pending_amount = $restante;
            if ($restante <= 0.009) {
                $credito->paid = true;
                $credito->pending_amount = 0;
            }
            $credito->save();

            // Si imputarMontoDeuda devolvió EXACTAMENTE lo que mandamos, significa que las facturas elegidas 
            // ya se pagaron al 100% (o no quedan más). Podemos detener el loop.
            if (abs($restante - $credito->pending_amount) < 0.01) {
                // Saliendo anticipadamente si ya no hay más deuda en esas facturas
                // Nota: el save de arriba guardó, y continuamos, 
                // pero si no consumió plata, ya podemos romper el ciclo exterior.
            }
        }
    }

    protected function generarNumeroRecibo($empresaId)
    {
        $empresa = \App\Models\Empresa::find($empresaId);
        $pv = str_pad($empresa->punto_venta ?? 1, 4, '0', STR_PAD_LEFT);
        $prox = $empresa->proximo_numero_recibo ?? 1;
        
        return "R-" . $pv . "-" . str_pad($prox, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener resumen de saldos de la cuenta corriente
     */
    public function getAccountSummary($clientId)
    {
        $ledgers = ClientLedger::where('client_id', $clientId)->get();

        $totalDebit = $ledgers->where('type', 'debit')->sum('amount');
        $totalCredit = $ledgers->where('type', 'credit')->sum('amount');
        
        $pendingDebit = $ledgers->where('type', 'debit')->where('paid', false)->sum('pending_amount');
        $pendingCredit = $ledgers->where('type', 'credit')->where('paid', false)->sum('pending_amount');

        return [
            'total_debit'    => (float) $totalDebit,
            'total_credit'   => (float) $totalCredit,
            'pending_debit'  => (float) $pendingDebit,
            'pending_credit' => (float) $pendingCredit,
            'saldo_global'   => (float) ($totalDebit - $totalCredit),
        ];
    }
}
