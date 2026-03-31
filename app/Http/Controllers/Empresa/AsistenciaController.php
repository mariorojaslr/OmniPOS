<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\CajaCierre;
use App\Models\Venta;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    /**
     * 🟢 INICIAR TURNO (Check-in)
     * Crea el registro de asistencia y abre la sesión de caja si es cajero.
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $isCajero = $user->esCajero();

        if ($isCajero) {
            $request->validate([
                'vuelto_inicial' => 'required|numeric|min:0',
            ]);
        }

        $empresaId = $user->empresa_id;
        $userId    = $user->id;

        // Evitar doble check-in
        $existe = Asistencia::where('user_id', $userId)
            ->whereNull('salida')
            ->first();

        if ($existe) {
            return back()->with('error', 'Ya tienes un turno activo o caja abierta. Debes cerrar la sesión actual antes de iniciar otra.');
        }

        DB::beginTransaction();
        try {
            // 1. Crear registro de Asistencia
            $asistencia = Asistencia::create([
                'user_id'        => $userId,
                'empresa_id'     => $empresaId,
                'entrada'        => now(),
                'ip_entrada'     => $request->ip(),
                'vuelto_inicial' => $isCajero ? $request->vuelto_inicial : 0,
                'observaciones'  => $request->observaciones
            ]);

            // 2. Abrir Caja (Solo si es Cajero)
            if ($isCajero) {
                CajaCierre::create([
                    'empresa_id'     => $empresaId,
                    'user_id'        => $userId,
                    'asistencia_id'  => $asistencia->id,
                    'fecha_apertura' => now(),
                    'saldo_inicial'  => $request->vuelto_inicial,
                    'estado'         => 'abierta',
                    'observaciones'  => 'Apertura de turno (Cajero): ' . ($request->observaciones ?? 'Sin detalles.')
                ]);
            }

            DB::commit();
            $msg = $isCajero ? 'Turno iniciado y caja abierta correctamente.' : 'Entrada registrada correctamente. ¡Buen trabajo!';
            return back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al iniciar turno: ' . $e->getMessage());
        }
    }

    /**
     * 🔴 FINALIZAR TURNO (Check-out)
     * Realiza el arqueo si es cajero y cierra la sesión de asistencia.
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $isCajero = $user->esCajero();

        if ($isCajero) {
            $request->validate([
                'vuelto_final' => 'required|numeric|min:0',
            ]);
        }

        $empresaId = $user->empresa_id;
        $userId    = $user->id;
        $now       = now();
        
        $asistencia = Asistencia::where('user_id', $userId)
            ->whereNull('salida')
            ->latest()
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No se encontró un turno activo para tu usuario.');
        }

        DB::beginTransaction();
        try {
            if ($isCajero) {
                // Buscamos la caja abierta vinculada a esta asistencia
                $caja = CajaCierre::where('asistencia_id', $asistencia->id)
                    ->where('estado', 'abierta')
                    ->first();

                // --- CÁLCULO DE MÉTRICAS DEL TURNO ---
                $fechaInicio = $asistencia->entrada;
                $fechaFin    = $now;

                $ventasEfectivo = Venta::where('empresa_id', $empresaId)
                    ->where('user_id', $userId)
                    ->where('metodo_pago', 'efectivo')
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->sum('total_con_iva');

                $ventasDigital = Venta::where('empresa_id', $empresaId)
                    ->where('user_id', $userId)
                    ->where('metodo_pago', '!=', 'efectivo')
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->sum('total_con_iva');

                $egresosTurno = Expense::where('empresa_id', $empresaId)
                    ->where('user_id', $userId)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->sum('amount');

                $saldoInicial  = $asistencia->vuelto_inicial ?? 0;
                $saldoEsperado = ($saldoInicial + $ventasEfectivo) - $egresosTurno;
                $saldoReal     = $request->vuelto_final;
                $diferencia    = $saldoReal - $saldoEsperado;

                if ($caja) {
                    $caja->update([
                        'fecha_cierre'    => $fechaFin,
                        'ventas_efectivo' => $ventasEfectivo,
                        'ventas_digital'  => $ventasDigital,
                        'egresos'         => $egresosTurno,
                        'saldo_esperado'  => $saldoEsperado,
                        'saldo_real'      => $saldoReal,
                        'diferencia'      => $diferencia,
                        'estado'          => 'cerrada'
                    ]);
                }
            }

            // --- ACTUALIZAR ASISTENCIA ---
            $asistencia->update([
                'salida'       => $now,
                'ip_salida'    => $request->ip(),
                'vuelto_final' => $isCajero ? $request->vuelto_final : 0,
                'observaciones' => $asistencia->observaciones . ' | Salida registrada.'
            ]);

            DB::commit();

            if ($isCajero) {
                // Mensaje de feedback amigable con el resultado
                $feedback = "Turno cerrado.";
                if($diferencia < 0) $feedback .= " ⚠️ Faltante: $ " . number_format(abs($diferencia), 2);
                elseif($diferencia > 0) $feedback .= " ✨ Sobrante: $ " . number_format($diferencia, 2);
                else $feedback .= " ✅ Caja Cuadrada.";

                return back()->with('success', $feedback);
            }

            return back()->with('success', 'Salida registrada correctamente. ¡A descansar!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cerrar caja/turno: ' . $e->getMessage());
        }
    }
}
