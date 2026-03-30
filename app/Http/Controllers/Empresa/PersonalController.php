<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Venta;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PersonalController extends Controller
{
    /**
     * 👥 Tablero de Rendimiento de Empleados
     */
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        // KPI: Rendimiento de ventas por usuario
        $empleados = User::where('empresa_id', $empresaId)
            ->where('role', '!=', 'OWNER') // Excluimos al dueño del KPI operativo si se desea
            ->with(['asistencias' => function($q) {
                $q->latest()->limit(1);
            }])
            ->get()
            ->map(function($user) {
                // Métricas de ventas en el mes actual
                $stats = DB::table('ventas')
                    ->where('user_id', $user->id)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->select(
                        DB::raw('COUNT(*) as total_trans'),
                        DB::raw('SUM(total_con_iva) as total_ventas'),
                        DB::raw('AVG(total_con_iva) as promedio_ticket')
                    )
                    ->first();

                $user->total_mes = $stats->total_ventas ?? 0;
                $user->total_trans = $stats->total_trans ?? 0;
                $user->promedio_ticket = $stats->promedio_ticket ?? 0;
                $user->ultima_entrada = $user->asistencias->first()?->entrada;
                $user->en_turno = $user->asistencias->first() && !$user->asistencias->first()->salida;

                return $user;
            })
            ->sortByDesc('total_mes');

        return view('empresa.personal.rendimiento', compact('empleados'));
    }

    /**
     * 📊 Perfil de Desempeño Individual
     */
    public function desempeno(User $user)
    {
        $empresaId = Auth::user()->empresa_id;

        // Validar multi-tenant
        if ($user->empresa_id != $empresaId) abort(403);

        // Periodo: Mes Actual
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // 1. Horas Trabajadas (Mes Actual)
        $asistencias = Asistencia::where('user_id', $user->id)
            ->whereBetween('entrada', [$startOfMonth, $endOfMonth])
            ->latest('entrada')
            ->get();

        $totalHoras = 0;
        foreach ($asistencias as $as) {
            if ($as->salida) {
                // Usamos float para tener decimales exactos
                $totalHoras += $as->entrada->diffInMinutes($as->salida) / 60;
            }
        }

        // 2. Total Ventas (Mes Actual)
        $ventas = DB::table('ventas')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('COUNT(*) as cant'),
                DB::raw('SUM(total_con_iva) as monto')
            )
            ->first();

        // 3. Faltantes de Caja (Sumatoria de diferencias negativas en el mes)
        $faltantes = \App\Models\CajaCierre::where('user_id', $user->id)
            ->whereBetween('fecha_cierre', [$startOfMonth, $endOfMonth])
            ->where('diferencia', '<', 0)
            ->sum('diferencia');
        
        $faltantes = abs($faltantes); // Lo pasamos a positivo para mostrar el monto faltante

        // 4. Cálculo de Asistencia (%) - Basado en 22 días laborales al mes
        $diasLaborales = 22;
        $diasPresente = $asistencias->groupBy(function($date) {
            return $date->entrada->format('Y-m-d');
        })->count();
        $porcentajeAsistencia = ($diasLaborales > 0) ? ($diasPresente / $diasLaborales) * 100 : 0;
        if($porcentajeAsistencia > 100) $porcentajeAsistencia = 100;

        // 5. Puntaje Operativo (Sistema de Pesos Ponderados)
        // Meta de ventas sugerida para 100% en este rubro: $100.000 (ajustable por config)
        $metaVentas = 100000; 
        $montoVentas = $ventas->monto ?? 0;
        $porcentajeVentas = ($metaVentas > 0) ? ($montoVentas / $metaVentas) * 100 : 0;
        if($porcentajeVentas > 100) $porcentajeVentas = 100;

        // Penalización por faltantes (cada $1 de faltante resta puntos, tope 20%)
        $puntosCaja = 20;
        if ($faltantes > 0) {
            $puntosCaja -= ($faltantes / 100); // Ejemplo: $1000 de faltante anula los 20 pts
            if($puntosCaja < 0) $puntosCaja = 0;
        }

        $score = ($porcentajeAsistencia * 0.4) + ($porcentajeVentas * 0.4) + $puntosCaja;
        $score = round($score, 0);

        return view('empresa.usuarios.desempeno', compact(
            'user', 
            'totalHoras', 
            'asistencias', 
            'ventas', 
            'faltantes',
            'porcentajeAsistencia',
            'diasPresente',
            'score'
        ));
    }
}


