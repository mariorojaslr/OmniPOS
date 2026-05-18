<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Turno;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Clase TurneroPortalController
 * Controla el Portal Público de TV/Smart TV de sala de espera para llamada de pacientes en tiempo real.
 */
class TurneroPortalController extends Controller
{
    /**
     * Renderizar el portal turnero OLED para la Smart TV del hospital.
     */
    public function index($slug)
    {
        $empresa = Empresa::where('slug', $slug)->firstOrFail();
        return view('portales.turnero.tv', compact('empresa'));
    }

    /**
     * Endpoint API para que la Smart TV consulte el llamado activo actual (polling de 3 seg).
     */
    public function activeCall($slug)
    {
        $empresa = Empresa::where('slug', $slug)->firstOrFail();

        // Obtener los últimos 10 turnos llamados o en consulta
        $historicos = Turno::where('empresa_id', $empresa->id)
            ->whereIn('estado_cola', ['llamado', 'en_consulta'])
            ->with(['cliente', 'user'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        $mappedHistoricos = $historicos->map(function($t) {
            return [
                'id' => $t->id,
                'paciente' => $t->cliente?->name ?? $t->cliente_nombre_manual ?? 'Paciente',
                'doctor' => $t->user?->name ?? 'Médico',
                'consultorio' => $t->user?->consultorio ?? 'Consultorio',
                'hora' => Carbon::parse($t->updated_at)->format('H:i'),
                'estado_cola' => $t->estado_cola,
                'called_at' => $t->updated_at->timestamp
            ];
        });

        // Verificar si el primer turno (el más reciente) está en estado 'llamado' y es una llamada activa reciente (<= 120 seg)
        $firstTurno = $historicos->first();
        $isRecent = $firstTurno && $firstTurno->estado_cola === 'llamado' && abs($firstTurno->updated_at->timestamp - time()) <= 120;

        if ($isRecent) {
            return response()->json([
                'success' => true,
                'called' => true,
                'paciente' => $firstTurno->cliente?->name ?? $firstTurno->cliente_nombre_manual ?? 'Paciente',
                'doctor' => $firstTurno->user?->name ?? 'Médico Asignado',
                'consultorio' => $firstTurno->user?->consultorio ?? 'Box Consultorio',
                'called_at' => $firstTurno->updated_at->timestamp,
                'historicos' => $mappedHistoricos
            ]);
        }

        return response()->json([
            'success' => true,
            'called' => false,
            'historicos' => $mappedHistoricos
        ]);
    }
}
