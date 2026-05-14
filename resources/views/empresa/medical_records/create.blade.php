@extends('layouts.empresa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0D6EFD;">Nueva Atención Médica</h2>
            <p class="text-muted">Complete los detalles de la consulta para la historia clínica.</p>
        </div>
        <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
            <i class="bi bi-arrow-left me-2"></i> VOLVER
        </a>
    </div>

    <form action="{{ route('empresa.medical_records.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Datos de la Consulta</h5>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small">PACIENTE</label>
                            <select name="client_id" class="form-select form-select-lg border-primary border-opacity-25" required>
                                <option value="">Seleccione un paciente...</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" {{ $selectedPatientId == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->documento ?? 'Sin DNI' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">ESPECIALIDAD</label>
                                <input type="text" name="specialty" class="form-control" placeholder="Ej: Cardiología, Pediatría...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">MOTIVO DE CONSULTA</label>
                                <input type="text" name="reason_for_visit" class="form-control" placeholder="Ej: Control anual, Dolor abdominal...">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small">DIAGNÓSTICO / EVOLUCIÓN</label>
                            <textarea name="diagnosis" class="form-control" rows="6" placeholder="Escriba aquí el diagnóstico detallado..."></textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold text-muted small">TRATAMIENTO / RECETA</label>
                            <textarea name="treatment" class="form-control" rows="4" placeholder="Indicaciones, medicación, estudios solicitados..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #f8fafc;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Notas Internas</h5>
                        <p class="text-muted small">Estas notas no serán visibles para el paciente en posibles informes.</p>
                        <textarea name="internal_notes" class="form-control" rows="5" placeholder="Notas administrativas o confidenciales..."></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow" style="border-radius: 12px; height: 60px;">
                        <i class="bi bi-cloud-check me-2"></i> GUARDAR HISTORIA CLÍNICA
                    </button>
                    <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-link text-muted text-decoration-none">Cancelar y descartar</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
