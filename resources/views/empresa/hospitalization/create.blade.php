@extends('layouts.empresa')

@section('content')
<div class="container px-4" style="max-width: 800px;">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">🏥 Nueva Admisión / Ingreso a Internación</h4>
            <p class="text-muted small mb-0">Completa la ficha de ingreso clínico y asigna una cama disponible para el paciente.</p>
        </div>
        <div>
            <a href="{{ route('empresa.hospitalization.dashboard') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Cancelar
            </a>
        </div>
    </div>

    {{-- ALERTAS DE ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 bg-white rounded-4 overflow-hidden">
        <div class="card-header bg-primary bg-gradient px-4 py-3 text-white border-0">
            <h5 class="mb-0 fw-bold"><i class="bi bi-person-fill-add me-2"></i>Formulario de Hospitalización</h5>
        </div>
        <div class="card-body p-4 p-md-5">
            
            <form action="{{ route('empresa.hospitalization.store') }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    {{-- SELECCIONAR PACIENTE --}}
                    <div class="col-12">
                        <label class="form-label small fw-bold text-dark"><i class="bi bi-people-fill me-1 text-primary"></i> Seleccionar Paciente / Afiliado</label>
                        <select name="client_id" class="form-select form-select-lg shadow-sm border-2" required>
                            <option value="" disabled selected>Elige un paciente...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('client_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->nombre }} (DNI/CUIL: {{ $patient->cuit_cuil ?? 'Sin Documento' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text small text-muted">¿No está registrado? Primero debes cargarlo en la sección de Clientes/Afiliados.</div>
                    </div>

                    {{-- ASIGNAR CAMA --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark"><i class="bi bi-hospital me-1 text-success"></i> Asignar Cama Disponible</label>
                        <select name="cama_id" class="form-select shadow-sm border-2" required>
                            <option value="" disabled {{ !$preselectedCamaId ? 'selected' : '' }}>Elige una cama libre...</option>
                            @foreach($availableBeds as $bed)
                                <option value="{{ $bed->id }}" {{ (old('cama_id') == $bed->id || $preselectedCamaId == $bed->id) ? 'selected' : '' }}>
                                    {{ $bed->room->nombre }} — {{ $bed->nombre }} ({{ str_replace('_', ' ', $bed->room->tipo) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- FECHA DE INGRESO --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark"><i class="bi bi-calendar-check me-1 text-info"></i> Fecha y Hora de Ingreso</label>
                        <input type="datetime-local" name="fecha_ingreso" value="{{ old('fecha_ingreso', now()->format('Y-m-d\TH:i')) }}" class="form-control shadow-sm border-2" required>
                    </div>

                    {{-- MOTIVO DE INGRESO --}}
                    <div class="col-12">
                        <label class="form-label small fw-bold text-dark"><i class="bi bi-chat-left-text me-1 text-warning"></i> Motivo de Ingreso / Cuadro Clínico</label>
                        <textarea name="motivo_ingreso" rows="3" class="form-control shadow-sm border-2" placeholder="Escribe detalladamente la sintomatología o el cuadro por el cual el paciente requiere internación..." required>{{ old('motivo_ingreso') }}</textarea>
                    </div>

                    {{-- DIAGNÓSTICO DE INGRESO --}}
                    <div class="col-12">
                        <label class="form-label small fw-bold text-dark"><i class="bi bi-journal-medical me-1 text-danger"></i> Diagnóstico Médico de Ingreso</label>
                        <textarea name="diagnostico_ingreso" rows="3" class="form-control shadow-sm border-2" placeholder="Presunción diagnóstica, CIE-10 o código del diagnóstico correspondiente..." required>{{ old('diagnostico_ingreso') }}</textarea>
                    </div>

                    {{-- BOTONES DE CIERRE --}}
                    <div class="col-12 mt-5 border-top pt-4 text-end">
                        <a href="{{ route('empresa.hospitalization.dashboard') }}" class="btn btn-outline-secondary btn-lg fw-bold rounded-pill px-4 me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill px-5 shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Confirmar Ingreso
                        </button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection
