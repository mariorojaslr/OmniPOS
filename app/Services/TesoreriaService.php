<?php

namespace App\Services;

use App\Models\FinanzaCuenta;
use App\Models\FinanzaMovimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TesoreriaService
{
    /**
     * Registrar un movimiento financiero y actualizar el saldo de la cuenta
     */
    public function registrarMovimiento($cuentaId, $tipo, $monto, $concepto, $params = [])
    {
        return DB::transaction(function () use ($cuentaId, $tipo, $monto, $concepto, $params) {
            $cuenta = FinanzaCuenta::findOrFail($cuentaId);
            $userId = Auth::id();
            $empresaId = Auth::user()->empresa_id;

            // 1. Crear el movimiento
            $movimiento = FinanzaMovimiento::create([
                'empresa_id'     => $empresaId,
                'cuenta_id'      => $cuentaId,
                'user_id'        => $userId,
                'tipo'           => $tipo,
                'monto'          => $monto,
                'fecha'          => $params['fecha'] ?? date('Y-m-d'),
                'concepto'       => $concepto,
                'categoria'      => $params['categoria'] ?? null,
                'reference_type' => $params['reference_type'] ?? null,
                'reference_id'   => $params['reference_id'] ?? null,
                'notas'          => $params['notas'] ?? null,
                'conciliado'     => $params['conciliado'] ?? false,
            ]);

            // 2. Actualizar el saldo de la cuenta
            if ($tipo == 'ingreso') {
                $cuenta->increment('saldo_actual', $monto);
            } elseif ($tipo == 'egreso') {
                $cuenta->decrement('saldo_actual', $monto);
            }
            // Para 'transferencia' se deberían crear dos movimientos, 
            // uno de egreso en el origen y uno de ingreso en el destino.

            return $movimiento;
        });
    }

    /**
     * Realizar una transferencia entre cuentas
     */
    public function transferir($origenId, $destinoId, $monto, $concepto = "Transferencia entre cuentas")
    {
        return DB::transaction(function () use ($origenId, $destinoId, $monto, $concepto) {
            $this->registrarMovimiento($origenId, 'egreso', $monto, $concepto . " (Origen)");
            $this->registrarMovimiento($destinoId, 'ingreso', $monto, $concepto . " (Destino)");
        });
    }
}
