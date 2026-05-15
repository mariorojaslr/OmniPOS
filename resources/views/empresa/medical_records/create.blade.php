@extends('layouts.empresa')

@section('content')
<style>
    .medical-bg {
        position: relative;
        padding: 40px 0;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }
    .form-label-premium {
        font-size: 0.75rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .form-control-premium {
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        padding: 12px 16px;
        transition: all 0.3s;
    }
    .form-control-premium:focus {
        border-color: var(--color-primario);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }
    .btn-save-medical {
        background: linear-gradient(135deg, var(--color-primario), #3b82f6);
        border: none;
        border-radius: 15px;
        padding: 16px;
        font-weight: 800;
        transition: transform 0.3s;
    }
    .btn-save-medical:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
    }
</style>

<div class="container medical-bg">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-800 text-dark mb-1">Nueva Evolución Médica</h2>
                    <p class="text-muted">Registro oficial para la Historia Clínica del paciente.</p>
                </div>
                <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-light rounded-pill px-4 fw-bold border">
                    <i class="bi bi-arrow-left me-2"></i> VOLVER
                </a>
            </div>

            <form action="{{ route('empresa.medical_records.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="glass-card p-4 p-md-5">
                            <div class="mb-4">
                                <label class="form-label-premium">Paciente / Afiliado</label>
                                <select name="client_id" class="form-select form-control-premium @error('client_id') is-invalid @enderror" required>
                                    <option value="">Seleccione un paciente...</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}" {{ $selectedPatientId == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->documento ?? 'Sin DNI' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-premium">Especialidad</label>
                                    <input type="text" name="specialty" class="form-control form-control-premium" placeholder="Ej: Clínica General" value="{{ old('specialty') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-premium">Motivo de la Visita</label>
                                    <input type="text" name="reason_for_visit" class="form-control form-control-premium" placeholder="Ej: Control de rutina" value="{{ old('reason_for_visit') }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-premium">Diagnóstico y Evolución</label>
                                <textarea name="diagnosis" class="form-control form-control-premium" rows="8" placeholder="Describa el estado del paciente y hallazgos...">{{ old('diagnosis') }}</textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label-premium">Tratamiento / Plan de Acción</label>
                                <textarea name="treatment" class="form-control form-control-premium" rows="4" placeholder="Indicaciones médicas, recetas o próximos pasos...">{{ old('treatment') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="glass-card p-4 mb-4" style="background: rgba(248, 250, 252, 0.8);">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i>Notas Internas</h6>
                            <p class="x-small text-muted mb-3">Información confidencial para el equipo médico. No se imprime en informes para el paciente.</p>
                            <textarea name="internal_notes" class="form-control form-control-premium" rows="6" placeholder="Escriba notas privadas aquí...">{{ old('internal_notes') }}</textarea>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-save-medical shadow-lg">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> GUARDAR REGISTRO
                            </button>
                            <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-link text-muted text-decoration-none text-center small">Descartar cambios</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
