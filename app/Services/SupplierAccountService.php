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
use App\Models\Chequera;
use App\Models\Empresa;
use App\Services\TesoreriaService;

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
            
            // 0. Obtener proveedor para descripción
            $supplier = Supplier::find($supplierId);
            
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
                            'finanza_cuenta_id' => $p['finanza_cuenta_id'] ?? null,
                            'metodo_pago'   => $p['metodo_pago'],
                            'monto'         => $p['monto'],
                            'referencia'    => $p['referencia'] ?? null,
                            'cheque_id'     => $p['cheque_id'] ?? null,
                        ]);

                        // IMPACTO EN TESORERIA (Si tiene cuenta elegida y no es cheque)
                        // Los cheques se concilian al cobrarse, pero el efectivo/transferencia impacta directo.
                        if (!empty($p['finanza_cuenta_id']) && $p['metodo_pago'] !== 'cheque_propio' && $p['metodo_pago'] !== 'cheque_tercero') {
                            app(TesoreriaService::class)->registrarMovimiento(
                                $p['finanza_cuenta_id'],
                                'egreso',
                                (float)$p['monto'],
                                "Pago Proveedor: " . ($supplier->name ?? 'S/N') . " (Orden Pago #{$numeroOrden})",
                                [
                                    'categoria'      => 'Pagos a Proveedores',
                                    'reference_type' => OrdenPago::class,
                                    'reference_id'   => $ordenPago->id,
                                    'fecha'          => $fecha
                                ]
                            );
                        }

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
                            $chequera = Chequera::find($p['chequera_id']);
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

        /** @var \App\Models\SupplierLedger[] $deudas */
        $deudas = $query->get();

        foreach ($deudas as $deuda) {
            if ($montoDisponible <= 0) break;

            $aPagar = min((float)$montoDisponible, (float)$deuda->pending_amount);
            
            $deuda->pending_amount = (float) $deuda->pending_amount - (float) $aPagar;
            if ($deuda->pending_amount <= 0.009) {
                $deuda->paid = true;
                $deuda->pending_amount = 0.0;
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

    /**
     * Aplica un crédito existente (Pago a cuenta / NC) a deudas pendientes
     */
    public function aplicarCreditoExistente($ledgerId, $comprasEspecificas = [])
    {
        return DB::transaction(function () use ($ledgerId, $comprasEspecificas) {
            $ledger = SupplierLedger::lockForUpdate()->findOrFail($ledgerId);

            if ($ledger->type !== 'credit') {
                throw new \Exception("El movimiento seleccionado no es un crédito.");
            }

            if ($ledger->paid) {
                throw new \Exception("Este crédito ya ha sido aplicado totalmente.");
            }

            $ordenPago = null;
            if ($ledger->reference_type === OrdenPago::class) {
                $ordenPago = $ledger->reference;
            }

            // Usamos el motor de imputación existente
            $restante = $this->imputarMontoCredito($ledger->supplier_id, $ledger->pending_amount, $ordenPago, $comprasEspecificas);

            $ledger->pending_amount = $restante;
            if ($restante <= 0.009) {
                $ledger->paid = true;
                $ledger->pending_amount = 0;
            }
            $ledger->save();

            $ledger->supplier->recalcularSaldo();

            return $ledger;
        });
    }

    protected function generarNumeroOrden($empresaId)
    {
        $empresa = Empresa::find($empresaId);
        $pv = str_pad($empresa->punto_venta ?? 1, 4, '0', STR_PAD_LEFT);
        $prox = $empresa->proximo_numero_orden_pago ?? 1;
        
        return "OP-" . $pv . "-" . str_pad($prox, 8, '0', STR_PAD_LEFT);
    }
}
