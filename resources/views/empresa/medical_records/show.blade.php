@extends('layouts.empresa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0D6EFD;">Detalle de Atención</h2>
            <p class="text-muted">ID Registro: #{{ $medical_record->id }} | {{ $medical_record->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary px-3 rounded-pill" onclick="window.print()">
                <i class="bi bi-printer me-2"></i> IMPRIMIR
            </button>
            <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-outline-secondary px-3 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i> VOLVER
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-start mb-5 border-bottom pb-4">
                        <div>
                            <h4 class="fw-bold text-primary mb-1">{{ $medical_record->patient->name }}</h4>
                            <p class="text-muted mb-0">DNI: {{ $medical_record->patient->documento ?? 'N/A' }} | Edad: {{ $medical_record->patient->age ?? 'N/A' }} años</p>
                        </div>
                        <div class="text-end">
                            <h6 class="fw-bold mb-1">Especialidad</h6>
                            <span class="badge bg-primary px-3 py-2">{{ $medical_record->specialty ?? 'General' }}</span>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h6 class="text-muted fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;">Motivo de Consulta</h6>
                        <p class="fs-5">{{ $medical_record->reason_for_visit ?: 'No especificado' }}</p>
                    </div>

                    <div class="mb-5">
                        <h6 class="text-muted fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;">Diagnóstico / Evolución</h6>
                        <div class="bg-light p-4 rounded-4" style="white-space: pre-line;">
                            {{ $medical_record->diagnosis ?: 'Sin diagnóstico registrado.' }}
                        </div>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-muted fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;">Tratamiento / Indicaciones</h6>
                        <div class="bg-primary bg-opacity-5 p-4 rounded-4 border-start border-primary border-4" style="white-space: pre-line;">
                            {{ $medical_record->treatment ?: 'Sin indicaciones registradas.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Información del Médico</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px;">
                            {{ substr($medical_record->doctor->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $medical_record->doctor->name }}</div>
                            <div class="text-muted small">M.N. / M.P. Registrada</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($medical_record->internal_notes)
            <div class="card shadow-sm border-0 mb-4 bg-warning bg-opacity-10 border-warning" style="border-radius: 16px;">
                <div class="card-body">
                    <h6 class="fw-bold text-warning-emphasis mb-2"><i class="bi bi-shield-lock me-2"></i> Notas Internas</h6>
                    <p class="small mb-0 text-warning-emphasis opacity-75">{{ $medical_record->internal_notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, #sidebar, .top-bar, #help-trigger-fixed { display: none !important; }
    #main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
    .card { border: none !important; box-shadow: none !important; }
    .container-fluid { width: 100% !important; }
}
</style>
@endsection
