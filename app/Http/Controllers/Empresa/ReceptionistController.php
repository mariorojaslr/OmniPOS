<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Clase ReceptionistController
 * Controla la sala de espera y recepción clínica con búsqueda inteligente por DNI/Nombre y auditoría de puntualidad.
 */
class ReceptionistController extends Controller
{
    /**
     * Mostrar la pantalla principal del Hub de Recepción.
     */
    public function index()
    {
        $empresa = Auth::user()->empresa;
        return view('empresa.turnos.recepcion', compact('empresa'));
    }

    /**
     * Búsqueda en tiempo real (asíncrona) con filtro Fuzzy Multivariable y análisis de puntualidad.
     */
    public function search(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $search = trim($request->input('search'));

        // Query base para turnos de HOY
        $query = Turno::where('empresa_id', $empresaId)
            ->whereDate('fecha', Carbon::today())
            ->with(['cliente', 'user', 'studies']);

        // Algoritmo Fuzzy Multivariable
        if (!empty($search)) {
            $terms = array_filter(explode(' ', $search)); // Dividir por espacios

            foreach ($terms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereHas('cliente', function ($qc) use ($term) {
                        $qc->where('name', 'like', "%{$term}%")
                           ->orWhere('document', 'like', "%{$term}%")
                           ->orWhere('phone', 'like', "%{$term}%")
                           ->orWhere('email', 'like', "%{$term}%");
                    })
                    ->orWhereHas('user', function ($qd) use ($term) {
                        $qd->where('name', 'like', "%{$term}%");
                    });
                });
            }
        }

        $turnos = $query->orderBy('hora_inicio', 'asc')->get();

        // Mapear los turnos para la interfaz dinámica
        $data = $turnos->map(function ($turno) {
            $horaTurno = Carbon::parse($turno->hora_inicio);
            $horaArribo = $turno->hora_arribo ? Carbon::parse($turno->hora_arribo) : null;
            
            $puntualidad = 'Pendiente';
            $puntualidadClass = 'bg-secondary text-white';

            if ($horaArribo) {
                // Calcular diferencia en minutos (positivo si es tarde, negativo si es temprano)
                $diffInMinutes = $horaTurno->diffInMinutes($horaArribo, false);
                
                if (abs($diffInMinutes) <= 15) {
                    $puntualidad = 'A tiempo';
                    $puntualidadClass = 'bg-success text-white';
                } elseif ($diffInMinutes > 15) {
                    $puntualidad = 'Tarde (' . $diffInMinutes . ' min)';
                    $puntualidadClass = 'bg-danger text-white';
                } else {
                    $puntualidad = 'Temprano (' . abs($diffInMinutes) . ' min)';
                    $puntualidadClass = 'bg-primary text-white';
                }
            }

            return [
                'id' => $turno->id,
                'paciente_nombre' => $turno->cliente?->name ?? $turno->cliente_nombre_manual ?? 'Paciente Sin Nombre',
                'paciente_documento' => $turno->cliente?->document ?? 'S/D',
                'paciente_telefono' => $turno->cliente?->phone ?? 'S/D',
                'paciente_email' => $turno->cliente?->email ?? 'S/D',
                'doctor_id' => $turno->user_id,
                'doctor_nombre' => $turno->user?->name ?? 'Médico Asignado',
                'consultorio' => $turno->user?->consultorio ?? 'Sin consultorio',
                'hora_turno' => Carbon::parse($turno->hora_inicio)->format('H:i'),
                'hora_arribo' => $turno->hora_arribo ? Carbon::parse($turno->hora_arribo)->format('H:i') : null,
                'estado_cola' => $turno->estado_cola ?? 'pendiente',
                'puntualidad' => $puntualidad,
                'puntualidad_class' => $puntualidadClass,
                'notas' => $turno->notas ?? '',
                'whatsapp_url' => $turno->whatsapp_link,
                'estudios' => $turno->studies->map(function ($st) {
                    return [
                        'file_path' => $st->file_path,
                        'file_name' => $st->file_name
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'turnos' => $data
        ]);
    }

    /**
     * Marcar el arribo físico en recepción (UX Cero Clics).
     */
    public function markArrival($id)
    {
        $empresaId = Auth::user()->empresa_id;

        $turno = Turno::where('id', $id)
            ->where('empresa_id', $empresaId)
            ->firstOrFail();

        $turno->hora_arribo = Carbon::now()->format('H:i:s');
        $turno->estado_cola = 'sala_espera';
        $turno->save();

        return response()->json([
            'success' => true,
            'message' => 'Llegada registrada con éxito. Paciente ingresado a Sala de Espera.'
        ]);
    }

    /**
     * Actualizar el estado de cola del paciente en tiempo real (pendiente, sala_espera, llamado, en_consulta, finalizado, ausente).
     */
    public function updateQueueState($id, Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $request->validate([
            'estado_cola' => 'required|string|in:pendiente,sala_espera,llamado,en_consulta,finalizado,ausente'
        ]);

        $turno = Turno::where('id', $id)
            ->where('empresa_id', $empresaId)
            ->firstOrFail();

        $turno->estado_cola = $request->estado_cola;
        $turno->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado de cola actualizado correctamente.'
        ]);
    }

    /**
     * Cargar y digitalizar un estudio/documentación entregada por el paciente en recepción.
     */
    public function uploadStudy(Request $request, $turnoId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Máximo 10MB
        ]);

        $empresaId = Auth::user()->empresa_id;
        $turno = Turno::where('id', $turnoId)
            ->where('empresa_id', $empresaId)
            ->firstOrFail();

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = $file->store('public/studies'); // Guarda en storage/app/public/studies

        $study = \App\Models\ClientStudy::create([
            'empresa_id' => $empresaId,
            'client_id' => $turno->client_id,
            'turno_id' => $turno->id,
            'file_name' => $fileName,
            'file_path' => str_replace('public/', 'storage/', $path),
            'file_size' => $file->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Documentación digitalizada y asociada con éxito al paciente.',
            'study' => $study
        ]);
    }

    /**
     * Obtener estudios cargados para un turno específico.
     */
    public function getStudies($turnoId)
    {
        $empresaId = Auth::user()->empresa_id;
        $studies = \App\Models\ClientStudy::where('turno_id', $turnoId)
            ->where('empresa_id', $empresaId)
            ->get();

        return response()->json([
            'success' => true,
            'studies' => $studies
        ]);
    }

    /**
     * Actualizar el consultorio asignado a un médico en tiempo real.
     */
    public function updateDoctorOffice(Request $request, $doctorId)
    {
        $request->validate([
            'consultorio' => 'required|string|max:50'
        ]);

        $doctor = \App\Models\User::where('id', $doctorId)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->firstOrFail();

        $doctor->consultorio = $request->consultorio;
        $doctor->save();

        return response()->json([
            'success' => true,
            'message' => 'Consultorio del médico actualizado con éxito.'
        ]);
    }
}
