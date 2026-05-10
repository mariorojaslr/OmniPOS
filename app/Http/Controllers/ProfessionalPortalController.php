<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfesionalConfig;
use App\Models\Turno;
use Carbon\Carbon;

class ProfessionalPortalController extends Controller
{
    public function index($token)
    {
        $config = ProfesionalConfig::where('token_portal', $token)->with('user.empresa.config')->firstOrFail();
        $user = $config->user;
        $empresa = $user->empresa;

        // 1. Total Cumplidos (Histórico)
        $totalCumplidos = Turno::where('user_id', $user->id)
            ->where('estado', 'confirmado')
            ->count();

        // 2. Turnos de Hoy
        $turnosHoy = Turno::where('user_id', $user->id)
            ->where('fecha', now()->format('Y-m-d'))
            ->with('cliente', 'servicio')
            ->orderBy('hora_inicio')
            ->get();

        // 3. Turnos de la Semana (Próximos 7 días)
        $turnosSemana = Turno::where('user_id', $user->id)
            ->whereBetween('fecha', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')])
            ->with('cliente', 'servicio')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy('fecha');

        // 4. Turnos del Mes
        $turnosMes = Turno::where('user_id', $user->id)
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->with('cliente', 'servicio')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy('fecha');

        return view('portales.profesional.index', compact(
            'user', 'empresa', 'config',
            'totalCumplidos', 'turnosHoy', 'turnosSemana', 'turnosMes'
        ));
    }

    /**
     * Marcar un turno como finalizado desde el portal.
     */
    public function completeTurno($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->update(['estado' => 'finalizado']);

        return response()->json([
            'success' => true,
            'message' => '¡Tarea finalizada con éxito!'
        ]);
    }
}
