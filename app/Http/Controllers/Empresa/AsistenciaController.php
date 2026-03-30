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
     * Crea el registro de asistencia y abre la sesión de caja.
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'vuelto_inicial' => 'required|numeric|min:0',
        ]);

        $empresaId = Auth::user()->empresa_id;
        $userId    = Auth::id();

        // Evitar doble check-in
        $existe = Asistencia::where('user_id', $userId)
            ->whereNull('salida')
            ->first();

        if ($existe) {
            return back()->with('error', 'Ya tienes un turno activo o caja abierta. Debat cerrar la sesión actual antes de iniciar otra.');
        }

        DB::beginTransaction();
        try {
            // 1. Crear registro de Asistencia
            $asistencia = Asistencia::create([
                'user_id'        => $userId,
                'empresa_id'     => $empresaId,
                'entrada'        => now(),
                'ip_entrada'     => $request->ip(),
                'vuelto_inicial' => $request->vuelto_inicial,
                'observaciones'  => $request->observaciones
            ]);

            // 2. Abrir Caja (CajaCierre)
            CajaCierre::create([
                'empresa_id'     => $empresaId,
                'user_id'        => $userId,
                'asistencia_id'   => $asistencia->id,
                'fecha_apertura' => now(),
                'saldo_inicial'  => $request->vuelto_inicial,
                'estado'         => 'abierta',
                'observaciones'  => 'Apertura de turno: ' . ($request->observaciones ?? 'Sin detalles.')
            ]);

            DB::commit();
            return back()->with('success', 'Turno iniciado y caja abierta correctamente. ¡Buen trabajo!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al iniciar turno: ' . $e->getMessage());
        }
    }

    /**
     * 🔴 FINALIZAR TURNO (Check-out)
     * Realiza el arqueo de caja y cierra la sesión de asistencia.
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'vuelto_final' => 'required|numeric|min:0',
        ]);

        $empresaId = Auth::user()->empresa_id;
        $userId    = Auth::id();
        $now       = now();
        
        $asistencia = Asistencia::where('user_id', $userId)
            ->whereNull('salida')
            ->latest()
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No se encontró un turno activo para tu usuario.');
        }

        // Buscamos la caja abierta vinculada a esta asistencia
        $caja = CajaCierre::where('asistencia_id', $asistencia->id)
            ->where('estado', 'abierta')
            ->first();

        DB::beginTransaction();
        try {
            // --- CÁLCULO DE MÉTRICAS DEL TURNO ---
            $fechaInicio = $asistencia->entrada;
            $fechaFin    = $now;

            // 1. Ventas en Efectivo
            $ventasEfectivo = Venta::where('empresa_id', $empresaId)
                ->where('user_id', $userId)
                ->where('metodo_pago', 'efectivo')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total_con_iva');

            // 2. Ventas Digitales
            $ventasDigital = Venta::where('empresa_id', $empresaId)
                ->where('user_id', $userId)
                ->where('metodo_pago', '!=', 'efectivo')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('total_con_iva');

            // 3. Egresos (Gastos de Caja registrados por el usuario)
            $egresosTurno = Expense::where('empresa_id', $empresaId)
                ->where('user_id', $userId)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('amount');

            // 4. Saldo Esperado (Lógica: Inicial + Efectivo - Gastos)
            $saldoInicial  = $asistencia->vuelto_inicial ?? 0;
            $saldoEsperado = ($saldoInicial + $ventasEfectivo) - $egresosTurno;
            $saldoReal     = $request->vuelto_final; // Lo declarado físicamente
            $diferencia    = $saldoReal - $saldoEsperado;

            // --- ACTUALIZAR Y CERRAR CAJA ---
            if ($caja) {
                $caja->update([
                    'fecha_cierre'    => $fechaFin,
                    'ventas_efectivo' => $ventasEfectivo,
                    'ventas_digital'  => $ventasDigital,
                    'egresos'         => $egresosTurno,
                    'saldo_esperado'  => $saldoEsperado,
                    'saldo_real'      => $saldoReal,
                    'diferencia'      => $diferencia,
                    'observaciones'   => $caja->observaciones . ' | ' . $request->observaciones,
                    'estado'          => 'cerrada'
                ]);
            } else {
                // Fallback: Si no se encontró el CajaCierre inicial, lo creamos ahora
                CajaCierre::create([
                    'empresa_id'      => $empresaId,
                    'user_id'         => $userId,
                    'asistencia_id'   => $asistencia->id,
                    'fecha_apertura'  => $fechaInicio,
                    'fecha_cierre'    => $fechaFin,
                    'saldo_inicial'   => $saldoInicial,
                    'ventas_efectivo' => $ventasEfectivo,
                    'ventas_digital'  => $ventasDigital,
                    'egresos'         => $egresosTurno,
                    'saldo_esperado'  => $saldoEsperado,
                    'saldo_real'      => $saldoReal,
                    'diferencia'      => $diferencia,
                    'observaciones'   => 'Cierre de emergencia sin registro de apertura inicial | ' . $request->observaciones,
                    'estado'          => 'cerrada'
                ]);
            }

            // --- ACTUALIZAR ASISTENCIA ---
            $asistencia->update([
                'salida'       => $fechaFin,
                'ip_salida'    => $request->ip(),
                'vuelto_final' => $saldoReal,
                'observaciones' => $asistencia->observaciones . ' | Arqueo completado.'
            ]);

            DB::commit();

            // Mensaje de feedback amigable con el resultado
            $feedback = "Turno cerrado.";
            if($diferencia < 0) $feedback .= " ⚠️ Faltante: $ " . number_format(abs($diferencia), 2);
            elseif($diferencia > 0) $feedback .= " ✨ Sobrante: $ " . number_format($diferencia, 2);
            else $feedback .= " ✅ Caja Cuadrada.";

            return back()->with('success', $feedback);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cerrar caja: ' . $e->getMessage());
        }
    }
}

