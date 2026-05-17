<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('empresa.medical_records.create', compact('patients', 'selectedPatientId'));
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
