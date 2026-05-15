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
            <p class="text-muted mb-0">Evoluciones y registros médicos de tus pacientes.</p>
        </div>
        <a href="{{ route('empresa.medical_records.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-800 shadow-lg" style="background: linear-gradient(135deg, #0d6efd, #3b82f6);">
            <i class="bi bi-plus-lg me-2"></i> NUEVA ATENCIÓN
        </a>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="p-4 bg-white bg-opacity-50 border-bottom border-white border-opacity-25">
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 rounded-end-pill py-2" placeholder="Buscar por paciente o médico...">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">PACIENTE</th>
                        <th>ESPECIALIDAD</th>
                        <th>FECHA ATENCIÓN</th>
                        <th>PROFESIONAL</th>
                        <th class="text-end pe-4">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td class="ps-4">
                            <div class="patient-name">{{ $record->patient->name }}</div>
                            <div class="patient-dni">DNI: {{ $record->patient->document ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="specialty-badge">{{ $record->specialty ?? 'General' }}</span>
                        </td>
                        <td class="text-muted small fw-600">
                            {{ $record->created_at->format('d/m/Y') }}<br>
                            <span class="text-opacity-50" style="font-size: 0.65rem;">{{ $record->created_at->format('H:i') }} Hs</span>
                        </td>
                        <td class="fw-bold text-dark" style="font-size: 0.85rem;">
                            Dr. {{ $record->doctor->name }}
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('empresa.medical_records.show', $record->id) }}" class="btn btn-outline-primary btn-action">
                                <i class="bi bi-eye-fill me-1"></i> DETALLES
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-file-earmark-medical text-muted opacity-25" style="font-size: 4rem;"></i>
                                <h5 class="mt-4 text-muted fw-bold">Sin registros médicos</h5>
                                <p class="text-muted small">Aún no has registrado atenciones para tus pacientes.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="p-4 border-top border-white border-opacity-25 bg-white bg-opacity-20">
            {{ $records->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
