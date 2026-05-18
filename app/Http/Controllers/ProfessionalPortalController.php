<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfesionalConfig;
use App\Models\Turno;
use App\Models\Hospitalization;
use App\Models\HospitalizationAdministration;
use App\Models\User;
use App\Models\Bed;
use App\Models\BedPresenceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProfessionalPortalController extends Controller
{
    public function index($token)
    {
        $config = ProfesionalConfig::where('token_portal', $token)->with('user.empresa.config')->firstOrFail();
        $user = $config->user;
        $empresa = $user->empresa;

        // Si es enfermero, derivamos al Portal de Enfermería Móvil
        if ($user->esEnfermero()) {
            $internaciones = Hospitalization::where('empresa_id', $empresa->id)
                ->where('estado', 'activo')
                ->with(['patient', 'assignments.bed.room', 'administrations.user'])
                ->get()
                ->sortBy(function($h) {
                    // Ordenar por habitación y cama para facilitar el recorrido del enfermero
                    $bedAssignment = $h->currentBedAssignment;
                    $roomName = $bedAssignment?->bed?->room?->nombre ?? 'ZZZ';
                    $bedName = $bedAssignment?->bed?->nombre ?? 'ZZZ';
                    return $roomName . ' - ' . $bedName;
                });

            // Tareas programadas próximas
            $proximasTareas = HospitalizationAdministration::whereIn('internacion_id', $internaciones->pluck('id'))
                ->whereNull('fecha_registro') // Programadas por el médico
                ->orWhere(function($q) {
                    $q->whereDate('proxima_aplicacion', now()->toDateString());
                })
                ->with('hospitalization.patient')
                ->orderBy('proxima_aplicacion')
                ->get();

            return view('portales.profesional.enfermeria', compact(
                'user', 'empresa', 'config', 'internaciones', 'proximasTareas'
            ));
        }

        // --- FLUJO ESTÁNDAR: MÉDICOS / ESPECIALISTAS ---
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

    /**
     * Registrar la administración de un control o rondín por parte del enfermero.
     */
    public function storeNurseAdministration(Request $request)
    {
        $request->validate([
            'internacion_id' => 'required|exists:internaciones,id',
            'tipo' => 'required|in:medicamento,signos,descartable,notas',
            'nombre' => 'required|string|max:191',
            'cantidad' => 'nullable|numeric|min:0',
            'dosis' => 'nullable|string|max:100',
            'via' => 'nullable|string|max:100',
            'notas' => 'nullable|string',
            'proxima_aplicacion_horas' => 'nullable|integer|min:1'
        ]);

        $internacion = Hospitalization::findOrFail($request->internacion_id);
        
        // Calcular próxima aplicación si aplica
        $proximaAplicacion = null;
        if ($request->proxima_aplicacion_horas) {
            $proximaAplicacion = now()->addHours($request->proxima_aplicacion_horas);
        }

        // Precios por defecto si existiera catálogo
        $precioUnitario = 0.00;

        $admin = HospitalizationAdministration::create([
            'internacion_id' => $internacion->id,
            'user_id' => $request->user_id ?? Auth::id(), // Fallback por token sin sesión con cookie
            'tipo' => $request->tipo,
            'nombre' => $request->nombre,
            'cantidad' => $request->cantidad ?? 1.00,
            'dosis' => $request->dosis,
            'via' => $request->via,
            'precio_unitario' => $precioUnitario,
            'fecha_registro' => now(),
            'proxima_aplicacion' => $proximaAplicacion,
            'notas' => $request->notas,
        ]);

        return response()->json([
            'success' => true,
            'message' => '¡Rondín registrado con éxito en la Historia Clínica!',
            'data' => $admin
        ]);
    }

    /**
     * Trazabilidad física: Escanear código QR de la cama.
     */
    public function verifyBedPresence($token)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'No autorizado');
        }

        // Buscar la cama
        $bed = Bed::where('empresa_id', $user->empresa_id)
            ->where('qr_token', $token)
            ->first();

        if (!$bed) {
            return redirect()->route('dashboard')->with('error', 'Cama clínica no encontrada o no pertenece a su empresa.');
        }

        // Identificar asignación activa
        $activeAssignment = \App\Models\HospitalizationBed::where('cama_id', $bed->id)
            ->whereNull('fecha_hasta')
            ->first();

        $internacion = null;
        if ($activeAssignment) {
            $internacion = Hospitalization::where('id', $activeAssignment->internacion_id)->first();
        }

        // Registrar la auditoría física inmutable
        BedPresenceLog::create([
            'empresa_id' => $user->empresa_id,
            'bed_id' => $bed->id,
            'user_id' => $user->id,
            'internacion_id' => $internacion ? $internacion->id : 0,
            'scanned_at' => now(),
        ]);

        // Buscar portal token
        $profesionalConfig = ProfesionalConfig::where('user_id', $user->id)->first();
        $tokenPortal = $profesionalConfig ? $profesionalConfig->token_portal : null;

        if (!$tokenPortal) {
            return redirect()->route('dashboard')->with('success', "🛡️ Presencia Física Validada con Éxito en Cama: {$bed->nombre}");
        }

        if ($internacion) {
            // Guardar en sesión para destacar visualmente en la vista
            session()->flash('focus_internacion_id', $internacion->id);
            session()->flash('focus_bed_scanned', true);

            return redirect()->route('portal.profesional.index', $tokenPortal)
                ->with('success', "🛡️ Presencia Física Validada en Cama: {$bed->nombre} (Paciente: {$internacion->patient->name})");
        }

        return redirect()->route('portal.profesional.index', $tokenPortal)
            ->with('success', "🛡️ Presencia Física Validada en Cama: {$bed->nombre} (Sin pacientes internados actualmente).");
    }
}
