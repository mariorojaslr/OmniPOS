<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;
        $records = MedicalRecord::where('empresa_id', $empresaId)
            ->with(['patient', 'doctor'])
            ->latest()
            ->paginate(15);

        return view('empresa.medical_records.index', compact('records'));
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

        MedicalRecord::create([
            'client_id'        => $request->client_id,
            'user_id'          => Auth::id(), // El médico que atiende
            'specialty'       => $request->specialty,
            'reason_for_visit' => $request->reason_for_visit,
            'diagnosis'        => $request->diagnosis,
            'treatment'        => $request->treatment,
            'internal_notes'   => $request->internal_notes,
        ]);

        return redirect()->route('empresa.medical_records.index')
            ->with('success', 'Historia clínica guardada correctamente.');
    }

    public function show(MedicalRecord $medical_record)
    {
        $this->authorize('view', $medical_record);
        return view('empresa.medical_records.show', compact('medical_record'));
    }

    public function patientHistory(Client $client)
    {
        $records = MedicalRecord::where('client_id', $client->id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->with('doctor')
            ->latest()
            ->get();

        return view('empresa.medical_records.patient_history', compact('client', 'records'));
    }
}
