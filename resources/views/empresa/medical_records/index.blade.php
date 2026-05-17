@extends('layouts.empresa')

@section('content')
<style>
    .medical-container { padding: 30px 0; }
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }
    .table thead th {
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        color: #64748b;
        font-weight: 800;
        text-transform: uppercase;
        border-bottom: 2px solid rgba(0, 0, 0, 0.03);
        padding: 1.2rem 1rem;
    }
    .table tbody td {
        padding: 1.2rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.02);
    }
    .patient-name { font-weight: 700; color: #1e293b; font-size: 0.95rem; }
    .patient-dni { font-size: 0.75rem; color: #94a3b8; }
    .specialty-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 8px;
        background: rgba(13, 110, 253, 0.05);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.1);
    }
    .btn-action {
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 8px 16px;
        transition: all 0.3s;
    }
</style>

<div class="container medical-container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-800 text-dark mb-1">Historias Clínicas</h1>
            <p class="text-muted mb-0">Legajos, evoluciones y registros médicos estructurados de tus pacientes.</p>
        </div>
        <a href="{{ route('empresa.medical_records.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-800 shadow-lg" style="background: linear-gradient(135deg, #0d6efd, #3b82f6);">
            <i class="bi bi-plus-lg me-2"></i> NUEVA ATENCIÓN
        </a>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="p-4 bg-white bg-opacity-50 border-bottom border-white border-opacity-25">
            <form method="GET" action="{{ route('empresa.medical_records.index') }}">
                <div class="row align-items-center g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 rounded-end-pill py-2" placeholder="Buscar por DNI, nombre o correo del paciente..." value="{{ $search ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Buscar</button>
                        @if(!empty($search))
                            <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-light border rounded-pill px-4 fw-bold">Limpiar</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">PACIENTE</th>
                        <th>OBRA SOCIAL</th>
                        <th class="text-center">ATENCIONES</th>
                        <th>ÚLTIMA VISITA</th>
                        <th>ÚLTIMO PROFESIONAL</th>
                        <th class="text-end pe-4">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td class="ps-4">
                            <div class="patient-name">{{ $patient->name }}</div>
                            <div class="patient-dni">DNI: {{ $patient->document ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @if($patient->obraSocial)
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-3 py-1 rounded-pill small fw-bold">
                                    🏥 {{ $patient->obraSocial->nombre }}
                                </span>
                            @else
                                <span class="text-muted small">Sin Obra Social</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($patient->medical_records_count > 0)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-3 py-1 rounded-pill small fw-bold">
                                    {{ $patient->medical_records_count }} {{ $patient->medical_records_count == 1 ? 'atención' : 'atenciones' }}
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-3 py-1 rounded-pill small fw-bold">
                                    Sin atenciones
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small fw-600">
                            @if($patient->latestMedicalRecord)
                                {{ $patient->latestMedicalRecord->created_at->format('d/m/Y') }}<br>
                                <span class="text-opacity-50" style="font-size: 0.65rem;">{{ $patient->latestMedicalRecord->created_at->format('H:i') }} Hs</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark" style="font-size: 0.85rem;">
                            @if($patient->latestMedicalRecord && $patient->latestMedicalRecord->doctor)
                                Dr. {{ $patient->latestMedicalRecord->doctor->name }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('empresa.medical_records.patient_history', $patient->id) }}" class="btn btn-primary btn-action text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #0d6efd, #3b82f6);">
                                    <i class="bi bi-folder2-open me-1"></i> VER LEGAJO
                                </a>
                                <a href="{{ route('empresa.medical_records.create', ['patient_id' => $patient->id]) }}" class="btn btn-outline-secondary btn-action">
                                    <i class="bi bi-plus-lg me-1"></i> NUEVA CONSULTA
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-file-earmark-medical text-muted opacity-25" style="font-size: 4rem;"></i>
                                <h5 class="mt-4 text-muted fw-bold">Sin pacientes coincidentes</h5>
                                <p class="text-muted small">No encontramos pacientes registrados con ese nombre o DNI.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
        <div class="p-4 border-top border-white border-opacity-25 bg-white bg-opacity-20">
            {{ $patients->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
