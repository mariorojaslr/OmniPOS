<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\Purchase;
use App\Models\OrdenPago;
use App\Models\OrdenPagoPago;
use App\Models\OrdenPagoImputacion;
use App\Models\Cheque;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupplierAccountService
{
    /**
     * Registrar una Orden de Pago a un proveedor
     */
    public function registrarPagoProveedor($supplierId, $monto, $fecha = null, $pagosDiferenciados = [], $comprasEspecificas = [], $autoImputar = true)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;
        $fecha = $fecha ?: date('Y-m-d');

        return DB::transaction(function () use ($supplierId, $monto, $fecha, $empresaId, $user, $pagosDiferenciados, $comprasEspecificas, $autoImputar) {
            
            if (!empty($pagosDiferenciados)) {
                $monto = 0;
                foreach ($pagosDiferenciados as $p) {
                    $monto += (float) $p['monto'];
                }
            }

            // 1. Generar número de orden de pago
            $numeroOrden = $this->generarNumeroOrden($empresaId);

            // 2. Crear la Orden de Pago
            $ordenPago = OrdenPago::create([
                'empresa_id'   => $empresaId,
                'supplier_id'  => $supplierId,
                'user_id'      => $user->id,
                'numero_orden' => $numeroOrden,
                'monto_total'  => $monto,
                'fecha'        => $fecha,
            ]);

            // 3. Guardar medios de pago
            if (!empty($pagosDiferenciados)) {
                foreach ($pagosDiferenciados as $p) {
                    if ((float) $p['monto'] > 0) {
                        $pagoDetail = OrdenPagoPago::create([
                            'orden_pago_id' => $ordenPago->id,
                            'metodo_pago'   => $p['metodo_pago'],
                            'monto'         => $p['monto'],
                            'referencia'    => $p['referencia'] ?? null,
                            'cheque_id'     => $p['cheque_id'] ?? null,
                        ]);

                        // Si usamos un cheque de terceros, marcarlo como entregado
                        if ($p['metodo_pago'] === 'cheque_tercero' && !empty($p['cheque_id'])) {
                            $cheque = Cheque::find($p['cheque_id']);
                            if ($cheque) {
                                $cheque->update([
                                    'estado'      => 'entregado',
                                    'supplier_id' => $supplierId
                                ]);
                            }
                        }

                        // Si usamos un cheque propio, emitirlo desde la chequera
                        if ($p['metodo_pago'] === 'cheque_propio' && !empty($p['chequera_id'])) {
                            $chequera = \App\Models\Chequera::find($p['chequera_id']);
                            if ($chequera) {
                                $vto = $p['fecha_pago'] ?? $fecha;
                                $num = $p['numero'] ?? null;
                                $cheque = $chequera->emitirCheque($p['monto'], $vto, $supplierId, $empresaId, $num);
                                
                                // Vincular el cheque recién creado a la línea de pago
                                $pagoDetail->update(['cheque_id' => $cheque->id]);
                            }
                        }
                    }
                }
            } else {
                OrdenPagoPago::create([
                    'orden_pago_id' => $ordenPago->id,
                    'metodo_pago'   => 'efectivo',
                    'monto'         => $monto,
                ]);
            }

            // 4. Registrar en el Ledger del Proveedor
            $supplier = Supplier::find($supplierId);
            $movimientoPago = SupplierLedger::create([
                'empresa_id'     => $empresaId,
                'supplier_id'    => $supplierId,
                'reference_type' => OrdenPago::class,
                'reference_id'   => $ordenPago->id,
                'type'           => 'credit',
                'amount'         => $monto,
                'pending_amount' => $monto, // Para poder imputarlo a deudas
                'description'    => "Orden de Pago #{$numeroOrden}",
                'paid'           => false,
                'created_at'     => $fecha . ' ' . date('H:i:s'),
            ]);

            // 5. MOTOR DE IMPUTACIÓN
            if ($autoImputar) {
                $restante = $this->imputarMontoCredito($supplierId, $monto, $ordenPago, $comprasEspecificas);
                
                $movimientoPago->pending_amount = $restante;
                if ($restante <= 0.009) {
                    $movimientoPago->paid = true;
                    $movimientoPago->pending_amount = 0;
                }
                $movimientoPago->save();
            }

            // 6. Incrementar contador
            $user->empresa->increment('proximo_numero_orden_pago');
            
            $supplier->recalcularSaldo();

            return $ordenPago;
        });
    }

    /**
     * Aplica el crédito de un pago a las deudas (purchases) más antiguas
     */
    public function imputarMontoCredito($supplierId, $montoDisponible, $ordenPago = null, $comprasEspecificas = [])
    {
        $query = SupplierLedger::where('supplier_id', $supplierId)
            ->where('type', 'debit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0);
            
        if (!empty($comprasEspecificas)) {
            $query->whereIn('id', $comprasEspecificas);
        } else {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        }

        $deudas = $query->get();

        foreach ($deudas as $deuda) {
            if ($montoDisponible <= 0) break;

            $aPagar = min($montoDisponible, $deuda->pending_amount);
            
            $deuda->pending_amount -= $aPagar;
            if ($deuda->pending_amount <= 0.009) {
                $deuda->paid = true;
                $deuda->pending_amount = 0;
            }
            $deuda->save();

            if ($ordenPago) {
                OrdenPagoImputacion::create([
                    'orden_pago_id'  => $ordenPago->id,
                    'ledger_id'      => $deuda->id,
                    'monto_aplicado' => $aPagar,
                ]);
            }

            $montoDisponible -= $aPagar;
        }

        return $montoDisponible;
    }

    protected function generarNumeroOrden($empresaId)
    {
        $empresa = \App\Models\Empresa::find($empresaId);
        $pv = str_pad($empresa->punto_venta ?? 1, 4, '0', STR_PAD_LEFT);
        $prox = $empresa->proximo_numero_orden_pago ?? 1;
        
        return "OP-" . $pv . "-" . str_pad($prox, 8, '0', STR_PAD_LEFT);
    }
}
