<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $search = $request->input('search');

        $query = Client::where('empresa_id', $empresaId)
            ->withCount('medicalRecords')
            ->with(['latestMedicalRecord.doctor', 'obraSocial']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('name', 'asc')->paginate(15);

        return view('empresa.medical_records.index', compact('patients', 'search'));
    }

    public function create(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $patients = Client::where('empresa_id', $empresaId)->get();
        $selectedPatientId = $request->query('patient_id');

        // Obtener profesionales y servicios médicos para permitir agendar el próximo turno directamente
        $profesionales = \App\Models\User::where('empresa_id', $empresaId)->where('activo', true)->get();
        $servicios = \App\Models\Servicio::where('empresa_id', $empresaId)->where('activo', true)->get();

        // Arrastrar el odontograma acumulado y resumen interdisciplinario si el paciente fue seleccionado previamente
        $odontogramaAcumulado = [];
        $resumenInterdisciplinario = null;
        if ($selectedPatientId) {
            $patient = Client::where('empresa_id', $empresaId)->find($selectedPatientId);
            if ($patient) {
                $odontogramaAcumulado = $this->getPatientConsolidatedOdontograma($patient->id);
                // Resumen directo para la carga inicial
                $resumenInterdisciplinario = json_decode($this->apiPatientInterdisciplinarySummary($patient->id)->getContent(), true);
            }
        }

        return view('empresa.medical_records.create', compact('patients', 'selectedPatientId', 'odontogramaAcumulado', 'resumenInterdisciplinario', 'profesionales', 'servicios'));
    }

    /**
     * Obtiene el odontograma consolidado acumulado a lo largo de todas las evoluciones del paciente
     */
    public function getPatientConsolidatedOdontograma($patientId)
    {
        $records = MedicalRecord::where('client_id', $patientId)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->oldest()
            ->get();

        $consolidado = [];
        foreach ($records as $r) {
            $data = $r->specialized_data ?? [];
            if (is_string($data)) {
                $data = json_decode($data, true) ?? [];
            }
            if (!empty($data['odontograma']) && is_array($data['odontograma'])) {
                foreach ($data['odontograma'] as $pieza => $info) {
                    $consolidado[$pieza] = $info;
                }
            }
        }

        return $consolidado;
    }

    /**
     * Endpoint API JSON para obtener el odontograma acumulado al cambiar de paciente en tiempo real
     */
    public function apiPatientOdontograma($patientId)
    {
        $consolidado = $this->getPatientConsolidatedOdontograma($patientId);
        return response()->json([
            'patient_id' => $patientId,
            'odontograma' => $consolidado
        ]);
    }

    /**
     * Endpoint API JSON que devuelve el resumen de actividades interdisciplinarias del paciente.
     * Detecta si el paciente tiene historial en Odontología, Cardiología, Kinesiología o Clínica General.
     */
    public function apiPatientInterdisciplinarySummary($patientId)
    {
        $records = MedicalRecord::where('client_id', $patientId)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->with('doctor')
            ->latest()
            ->get();

        $summary = [
            'has_odonto' => false,
            'has_cardio' => false,
            'has_kine' => false,
            'has_clinica' => false,
            'odonto_count' => 0,
            'cardio_count' => 0,
            'kine_count' => 0,
            'clinica_count' => 0,
            'last_odonto' => null,
            'last_cardio' => null,
            'last_kine' => null,
            'last_clinica' => null,
            'consolidated_odontograma' => [],
        ];

        foreach ($records as $r) {
            $data = $r->specialized_data ?? [];
            if (is_string($data)) {
                $data = json_decode($data, true) ?? [];
            }
            if (!is_array($data)) $data = [];

            $docName = $r->doctor ? $r->doctor->name : 'Profesional';
            $dateStr = $r->created_at->format('d/m/Y');

            // 1. Odontología
            if (!empty($data['odontograma']) && is_array($data['odontograma']) && count($data['odontograma']) > 0) {
                $summary['has_odonto'] = true;
                $summary['odonto_count']++;
                if (!$summary['last_odonto']) {
                    $summary['last_odonto'] = [
                        'date' => $dateStr,
                        'doctor' => $docName,
                        'diagnosis' => $r->diagnosis,
                        'treatment' => $r->treatment,
                        'piezas_count' => count($data['odontograma'])
                    ];
                }
                foreach ($data['odontograma'] as $p => $info) {
                    if (!isset($summary['consolidated_odontograma'][$p])) {
                        $summary['consolidated_odontograma'][$p] = $info;
                    }
                }
            }

            // 2. Cardiología
            $c = $data['cardio'] ?? [];
            if (!empty($c['riesgo_cardiovascular']) || !empty($c['nyha']) || !empty($c['ecg_ritmo']) || !empty($c['fevi']) || !empty($c['hallazgos'])) {
                $summary['has_cardio'] = true;
                $summary['cardio_count']++;
                if (!$summary['last_cardio']) {
                    $summary['last_cardio'] = [
                        'date' => $dateStr,
                        'doctor' => $docName,
                        'riesgo' => $c['riesgo_cardiovascular'] ?? '',
                        'nyha' => $c['nyha'] ?? '',
                        'ecg' => $c['ecg_ritmo'] ?? '',
                        'fevi' => $c['fevi'] ?? '',
                        'hallazgos' => $c['hallazgos'] ?? '',
                        'diagnosis' => $r->diagnosis,
                    ];
                }
            }

            // 3. Kinesiología
            $k = $data['kinesio'] ?? [];
            if (!empty($k['region']) || (isset($k['eva']) && $k['eva'] !== '') || !empty($k['rom']) || !empty($k['plan'])) {
                $summary['has_kine'] = true;
                $summary['kine_count']++;
                if (!$summary['last_kine']) {
                    $summary['last_kine'] = [
                        'date' => $dateStr,
                        'doctor' => $docName,
                        'region' => $k['region'] ?? '',
                        'eva' => $k['eva'] ?? '',
                        'rom' => $k['rom'] ?? '',
                        'plan' => $k['plan'] ?? '',
                        'diagnosis' => $r->diagnosis,
                    ];
                }
            }

            // 4. Clínica General (Vástago vitales o diagnóstico clínico)
            $v = $data['vitals'] ?? [];
            if (!empty($v['sys']) || !empty($v['glucose']) || !empty($v['heart']) || !empty($v['weight'])) {
                $summary['has_clinica'] = true;
                $summary['clinica_count']++;
                if (!$summary['last_clinica']) {
                    $summary['last_clinica'] = [
                        'date' => $dateStr,
                        'doctor' => $docName,
                        'vitals' => $v,
                        'diagnosis' => $r->diagnosis,
                    ];
                }
            }
        }

        return response()->json($summary);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'        => 'required|exists:clients,id',
            'specialty'       => 'nullable|string|max:255',
            'reason_for_visit' => 'nullable|string|max:500',
            'diagnosis'        => 'nullable|string',
            'treatment'        => 'nullable|string',
            'internal_notes'   => 'nullable|string',
        ]);

        $record = MedicalRecord::create([
            'empresa_id'       => Auth::user()->empresa_id,
            'client_id'        => $request->client_id,
            'user_id'          => Auth::id(), // El médico que atiende
            'specialty'       => $request->specialty,
            'reason_for_visit' => $request->reason_for_visit,
            'diagnosis'        => $request->diagnosis,
            'treatment'        => $request->treatment,
            'internal_notes'   => $request->internal_notes,
            'specialized_data' => $request->has('specialized_data') ? json_decode($request->specialized_data, true) : null,
        ]);

        // Procesar Adjuntos (Radiografías, estudios, etc.) -> Bunny.net
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('medical_records/' . $record->id, 'bunny_storage');
                \App\Models\MedicalRecordAttachment::create([
                    'medical_record_id' => $record->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Programar Próximo Turno de forma directa desde la Consulta Médica si fue solicitado
        if ($request->filled('proximo_turno_activo') && $request->proximo_turno_activo == '1' && $request->filled('proximo_turno_fecha') && $request->filled('proximo_turno_hora')) {
            try {
                $paciente = Client::find($request->client_id);
                $profesionalId = $request->proximo_turno_user_id ?: Auth::id();
                $servicioId = $request->proximo_turno_servicio_id;
                
                // Fallback de servicio si no se seleccionó
                if (!$servicioId) {
                    $primerServicio = \App\Models\Servicio::where('empresa_id', Auth::user()->empresa_id)->first();
                    $servicioId = $primerServicio ? $primerServicio->id : null;
                }

                $horaInicio = Carbon::parse($request->proximo_turno_fecha . ' ' . $request->proximo_turno_hora);
                $horaFin = $horaInicio->copy()->addMinutes(30)->format('H:i:s');

                \App\Models\Turno::create([
                    'empresa_id' => Auth::user()->empresa_id,
                    'user_id' => $profesionalId,
                    'created_by_user_id' => Auth::id(),
                    'origen_creacion' => 'medico_consulta',
                    'client_id' => $request->client_id,
                    'servicio_id' => $servicioId,
                    'cliente_nombre_manual' => $paciente ? $paciente->name : 'Paciente',
                    'fecha' => $request->proximo_turno_fecha,
                    'hora_inicio' => $request->proximo_turno_hora,
                    'hora_fin' => $horaFin,
                    'estado' => 'pendiente',
                    'monto' => 0,
                    'notas' => 'Próximo turno agendado por médico en consulta de evolución HCE #' . $record->id . '. ' . ($request->proximo_turno_notas ?? ''),
                ]);
            } catch (\Exception $e) {
                \Log::warning('No se pudo agendar el próximo turno desde HCE: ' . $e->getMessage());
            }
        }

        if ($request->has('redirect_to_history')) {
            return redirect()->route('empresa.medical_records.patient_history', $request->client_id)
                ->with('success', 'Estudio o archivo adjunto subido correctamente.');
        }

        return redirect()->route('empresa.medical_records.index')
            ->with('success', 'Historia clínica y archivos adjuntos guardados correctamente.');
    }

    public function show(MedicalRecord $medical_record)
    {
        // Chequeo manual de seguridad para asegurar el aislamiento de datos (Multi-tenancy)
        if ($medical_record->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        return view('empresa.medical_records.show', compact('medical_record'));
    }

    public function edit(MedicalRecord $medical_record)
    {
        if ($medical_record->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $empresaId = Auth::user()->empresa_id;
        $patients = Client::where('empresa_id', $empresaId)->get();
        $selectedPatientId = $medical_record->client_id;

        return view('empresa.medical_records.edit', compact('medical_record', 'patients', 'selectedPatientId'));
    }

    public function update(Request $request, MedicalRecord $medical_record)
    {
        if ($medical_record->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'client_id'        => 'required|exists:clients,id',
            'specialty'       => 'nullable|string|max:255',
            'reason_for_visit' => 'nullable|string|max:500',
            'diagnosis'        => 'nullable|string',
            'treatment'        => 'nullable|string',
            'internal_notes'   => 'nullable|string',
        ]);

        $medical_record->update([
            'client_id'        => $request->client_id,
            'specialty'       => $request->specialty,
            'reason_for_visit' => $request->reason_for_visit,
            'diagnosis'        => $request->diagnosis,
            'treatment'        => $request->treatment,
            'internal_notes'   => $request->internal_notes,
            'specialized_data' => $request->has('specialized_data') ? json_decode($request->specialized_data, true) : null,
        ]);

        // Procesar Adjuntos Nuevos -> Bunny.net
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('medical_records/' . $medical_record->id, 'bunny_storage');
                \App\Models\MedicalRecordAttachment::create([
                    'medical_record_id' => $medical_record->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        if ($request->has('redirect_to_history')) {
            return redirect()->route('empresa.medical_records.patient_history', $request->client_id)
                ->with('success', 'Evolución y estudios actualizados correctamente.');
        }

        return redirect()->route('empresa.medical_records.index')
            ->with('success', 'Evolución médica actualizada correctamente.');
    }

    public function patientHistory(Client $client)
    {
        $client->load(['obraSocial', 'affiliatePlan']);

        $records = MedicalRecord::where('client_id', $client->id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->with(['doctor', 'attachments'])
            ->latest()
            ->get();

        return view('empresa.medical_records.patient_history', compact('client', 'records'));
    }

    public function attachFile(Request $request, MedicalRecord $medical_record)
    {
        if ($medical_record->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'attachments' => 'required|array',
            'attachments.*' => 'file|max:20480', // 20MB por archivo
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('medical_records/' . $medical_record->id, 'bunny_storage');
                \App\Models\MedicalRecordAttachment::create([
                    'medical_record_id' => $medical_record->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('empresa.medical_records.patient_history', $medical_record->client_id)
            ->with('success', 'Estudios o reportes cargados y asociados correctamente a la visita.');
    }
}
