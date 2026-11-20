@extends('layouts.empresa')

@php
    $config = auth()->user()->empresa?->config;
    $modoOscuro = ($config?->theme === 'dark');
    $colorPrimario = $config?->color_primario ?? '#0d6efd';
@endphp

@section('content')
<style>
    .medical-bg {
        position: relative;
        padding: 40px 0;
    }
    .glass-card {
        background: {{ $modoOscuro ? 'rgba(11, 17, 32, 0.7)' : 'rgba(255, 255, 255, 0.75)' }};
        backdrop-filter: blur(15px);
        border: 1px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.06)' : 'rgba(255, 255, 255, 0.4)' }} !important;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, {{ $modoOscuro ? '0.4' : '0.04' }});
    }
    .form-label-premium {
        font-size: 0.75rem;
        font-weight: 800;
        color: {{ $modoOscuro ? '#94a3b8' : '#64748b' }};
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .form-control-premium {
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#ffffff' }} !important;
        border-radius: 14px !important;
        border: 1px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.12)' }} !important;
        padding: 12px 16px !important;
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }} !important;
        transition: all 0.3s;
    }
    .form-control-premium:focus {
        border-color: var(--color-primario, #0d6efd) !important;
        box-shadow: 0 0 15px {{ $colorPrimario }}30 !important;
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.04)' : '#ffffff' }} !important;
    }
    .form-control-premium option {
        background: {{ $modoOscuro ? '#0b1120' : '#ffffff' }};
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }};
    }
    .btn-save-medical {
        background: linear-gradient(135deg, var(--color-primario, #0d6efd), #3b82f6);
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

    /* Estilo premium de Pestañas Nav Pills */
    .nav-pills .nav-link {
        color: {{ $modoOscuro ? '#94a3b8' : '#64748b' }};
        background: transparent;
        border-radius: 12px;
        transition: all 0.3s;
        border: 1px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)' }} !important;
    }
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, var(--color-primario, #0d6efd), #3b82f6) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        border: none !important;
    }
    .tab-content {
        background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.4)' : '#ffffff' }} !important;
        border: 1px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.06)' : 'rgba(0, 0, 0, 0.08)' }} !important;
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }} !important;
    }
    .table th {
        color: {{ $modoOscuro ? '#94a3b8' : '#64748b' }} !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }
    .table td {
        color: {{ $modoOscuro ? '#cbd5e1' : '#334155' }} !important;
    }
    .table input, .table select {
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#ffffff' }} !important;
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }} !important;
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.12)' }} !important;
        border-radius: 8px !important;
    }
</style>

<div class="container medical-bg">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
                <div>
                    <h2 class="fw-extrabold mb-1" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};">🩺 Nueva Evolución Médica</h2>
                    <p class="small mb-0 {{ $modoOscuro ? 'text-muted' : 'text-secondary' }}">Registro oficial estructurado para la Historia Clínica del paciente.</p>
                </div>
                <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> VOLVER AL LISTADO
                </a>
            </div>

            <form action="{{ route('empresa.medical_records.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8 col-md-12">
                        <div class="glass-card p-4 p-md-5">
                            <div class="mb-4">
                                <label class="form-label-premium">Paciente / Afiliado *</label>
                                <select name="client_id" class="form-select form-control-premium @error('client_id') is-invalid @enderror" required>
                                    <option value="">Seleccione un paciente...</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}" {{ $selectedPatientId == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} (Doc: {{ $p->document ?? 'Sin DNI' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row mb-4 g-3">
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label-premium">Especialidad</label>
                                    <input type="text" name="specialty" class="form-control form-control-premium" placeholder="Ej: Clínica General" value="{{ old('specialty') }}">
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label-premium">Motivo de la Visita</label>
                                    <input type="text" name="reason_for_visit" class="form-control form-control-premium" placeholder="Ej: Control de rutina" value="{{ old('reason_for_visit') }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-premium">Diagnóstico y Evolución *</label>
                                <textarea name="diagnosis" class="form-control form-control-premium" rows="6" placeholder="Describa el estado del paciente y hallazgos..." required>{{ old('diagnosis') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-premium">Tratamiento / Plan de Acción</label>
                                <textarea name="treatment" class="form-control form-control-premium" rows="3" placeholder="Indicaciones médicas generales o próximos pasos...">{{ old('treatment') }}</textarea>
                            </div>

                            <div class="mb-4 p-4 rounded-4 border border-primary border-opacity-10 shadow-sm" style="background: {{ $modoOscuro ? 'rgba(13, 110, 253, 0.03)' : 'rgba(13, 110, 253, 0.01)' }};">
                                <label class="form-label-premium text-primary"><i class="bi bi-tags-fill me-1"></i> Campos Dinámicos & Tags HCE</label>
                                <p class="x-small text-muted mb-3">Agrega etiquetas personalizadas con valores clave para adaptar la consulta (ej. Odontología, Kinesiología, Oftalmología).</p>
                                
                                <div class="row g-2 mb-3">
                                    <div class="col-md-5 col-sm-12">
                                        <input type="text" id="dyn_key" class="form-control form-control-premium py-2" placeholder="Propiedad (Ej: Piezas Dentales, Dolor)">
                                    </div>
                                    <div class="col-md-5 col-sm-12">
                                        <input type="text" id="dyn_value" class="form-control form-control-premium py-2" placeholder="Valor (Ej: 14 y 15, Lumbar, 8/10)">
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <button type="button" class="btn btn-primary w-100 py-2 fw-bold" onclick="addDynamicField()" style="border-radius: 12px; height: 48px;">
                                            <i class="bi bi-plus-lg fs-5"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2" id="dynamicFieldsContainer">
                                    <!-- Aquí se inyectarán las cápsulas de tags -->
                                </div>
                            </div>

                            <!-- Barra de Acceso Interdisciplinario Bajo Demanda (Solo si el paciente tiene historial en otras áreas) -->
                            <div id="interdisciplinary_bar" class="mb-4 d-none">
                                <div class="p-3 rounded-4 border border-secondary border-opacity-25" style="background: {{ $modoOscuro ? 'rgba(30, 41, 59, 0.4)' : '#f8fafc' }};">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-diagram-3-fill text-primary fs-5"></i>
                                            <div>
                                                <strong class="text-dark small d-block" style="color: {{ $modoOscuro ? '#f1f5f9' : '#1e293b' }} !important;">
                                                    Historial Interdisciplinario del Paciente
                                                </strong>
                                                <span class="x-small text-muted">
                                                    Consulte las intervenciones de otros especialistas solo si lo necesita clínicamente (modo solo lectura).
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2" id="interdisciplinary_buttons">
                                            <!-- Botones inyectados dinámicamente si hay actividad registrada -->
                                        </div>
                                    </div>

                                    <!-- Panel Desplegable de Consulta Interdisciplinaria -->
                                    <div id="interdisciplinary_viewer_box" class="d-none mt-3 pt-3 border-top border-light">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1 x-small fw-bold" id="interdisciplinary_viewer_title">Detalle</span>
                                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0 px-2 x-small" onclick="cerrarVisorInterdisciplinario()">
                                                <i class="bi bi-x-lg me-1"></i> Cerrar visor
                                            </button>
                                        </div>
                                        <div id="interdisciplinary_viewer_content" class="p-3 bg-white rounded-3 border small shadow-sm text-dark">
                                            <!-- Contenido cargado al pulsar el botón -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="opacity: 0.15;">

                            <!-- Módulo de Pestañas de Carga Especializada Estructurada -->
                            @php
                                $esOdonto = $config?->isOdontologica() || $config?->hasOdontograma();
                            @endphp
                            <div class="mb-0">
                                <label class="form-label-premium mb-3"><i class="bi bi-braces-asterisk me-1 text-primary"></i> Data Especializada Estructurada</label>
                                
                                <ul class="nav nav-pills nav-justified gap-2 mb-4" id="specializedTabs" role="tablist">
                                    @if($esOdonto)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active fw-bold border py-2" id="odonto-tab" data-bs-toggle="tab" data-bs-target="#odonto-pane" type="button" role="tab">
                                                <span class="fs-6 me-1">🦷</span> Odontograma Clínico
                                            </button>
                                        </li>
                                    @endif
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ !$esOdonto ? 'active' : '' }} fw-bold border py-2" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals-pane" type="button" role="tab"><i class="bi bi-heart-pulse-fill me-2"></i>Signos Vitales</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-bold border py-2" id="meds-tab" data-bs-toggle="tab" data-bs-target="#meds-pane" type="button" role="tab"><i class="bi bi-capsule me-2"></i>Receta / Meds</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-bold border py-2" id="labs-tab" data-bs-toggle="tab" data-bs-target="#labs-pane" type="button" role="tab"><i class="bi bi-clipboard2-pulse-fill me-2"></i>Laboratorio</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-bold border py-2" id="cardio-tab" data-bs-toggle="tab" data-bs-target="#cardio-pane" type="button" role="tab"><span class="fs-6 me-1">🫀</span>Cardiología</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-bold border py-2" id="kine-tab" data-bs-toggle="tab" data-bs-target="#kine-pane" type="button" role="tab"><span class="fs-6 me-1">🏃</span>Kinesiología</button>
                                    </li>
                                </ul>

                                <div class="tab-content p-3 p-md-4 rounded-4 border" id="specializedTabsContent">
                                    @if($esOdonto)
                                        <!-- PESTAÑA: ODONTOGRAMA CLÍNICO -->
                                        <div class="tab-pane fade show active" id="odonto-pane" role="tabpanel">
                                            <div id="odontograma_history_alert" class="alert alert-info py-2 px-3 rounded-3 d-flex align-items-center justify-content-between mb-3 {{ empty($odontogramaAcumulado) ? 'd-none' : '' }}" style="background: rgba(13, 110, 253, 0.08); border: 1px solid rgba(13, 110, 253, 0.2);">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-clock-history fs-5 text-primary"></i>
                                                    <span class="small text-primary fw-bold" id="odontograma_history_msg">
                                                        Arrastrando estado bucal acumulado de consultas anteriores. Registre nuevos tratamientos o hallazgos sobre la gráfica.
                                                    </span>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-pill x-small fw-bold" onclick="recargarOdontoPaciente()">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Re-sincronizar
                                                </button>
                                            </div>

                                            @include('empresa.medical_records.components.odontograma', [
                                                'readonly' => false, 
                                                'initialData' => $odontogramaAcumulado ?? [],
                                                'containerId' => 'odontograma_create'
                                            ])
                                        </div>
                                    @endif

                                    <!-- PESTAÑA: SIGNOS VITALES CON ALERTAS CROMÁTICAS INTELIGENTES -->
                                    <div class="tab-pane fade {{ !$esOdonto ? 'show active' : '' }}" id="vitals-pane" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="small text-muted"><i class="bi bi-shield-check text-success me-1"></i>Los valores se analizan en tiempo real indicando normalidad o alertas críticas.</span>
                                            <div id="vitals_general_badge" class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-bold rounded-pill">Parámetros Normales</div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6 col-sm-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label text-muted small fw-bold">Presión Arterial Sistólica (mmHg)</label>
                                                    <span id="badge_sys" class="badge bg-secondary-subtle text-secondary small">--</span>
                                                </div>
                                                <input type="number" id="vital_sys" class="form-control form-control-premium" placeholder="Ej: 120" min="0" oninput="evaluarSignosVitales()">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label text-muted small fw-bold">Presión Arterial Diastólica (mmHg)</label>
                                                    <span id="badge_dia" class="badge bg-secondary-subtle text-secondary small">--</span>
                                                </div>
                                                <input type="number" id="vital_dia" class="form-control form-control-premium" placeholder="Ej: 80" min="0" oninput="evaluarSignosVitales()">
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label text-muted small fw-bold">Glucemia (mg/dL)</label>
                                                    <span id="badge_glucose" class="badge bg-secondary-subtle text-secondary small">--</span>
                                                </div>
                                                <input type="number" id="vital_glucose" class="form-control form-control-premium" placeholder="Ej: 95" min="0" oninput="evaluarSignosVitales()">
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label text-muted small fw-bold">Frecuencia Cardíaca (lpm)</label>
                                                    <span id="badge_heart" class="badge bg-secondary-subtle text-secondary small">--</span>
                                                </div>
                                                <input type="number" id="vital_heart" class="form-control form-control-premium" placeholder="Ej: 72" min="0" oninput="evaluarSignosVitales()">
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label text-muted small fw-bold">Peso Corporal (kg)</label>
                                                    <span id="badge_weight" class="badge bg-secondary-subtle text-secondary small">--</span>
                                                </div>
                                                <input type="number" step="0.1" id="vital_weight" class="form-control form-control-premium" placeholder="Ej: 75.5" min="0" oninput="evaluarSignosVitales()">
                                            </div>
                                        </div>

                                        <!-- Panel Interactivo Privado con la IA para el Médico -->
                                        <div class="mt-4 p-3 rounded-4 border border-info border-opacity-25" style="background: rgba(14, 165, 233, 0.04);">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold mb-0 text-info d-flex align-items-center gap-2">
                                                    <i class="bi bi-robot fs-5"></i> Interconsulta Privada con Asistente IA
                                                </h6>
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 x-small">100% Confidencial</span>
                                            </div>
                                            <p class="x-small text-muted mb-2">Consulte sugerencias diferenciales, interacciones farmacológicas o análisis de antecedentes en base a los datos registrados.</p>
                                            <div class="input-group mb-2">
                                                <input type="text" id="ai_clinical_query" class="form-control form-control-premium" placeholder="Ej: ¿Qué conducta seguir con glucemia 190 y dolor lumbar?">
                                                <button class="btn btn-info text-white fw-bold px-3 d-flex align-items-center gap-1" type="button" onclick="consultarIAClinica()">
                                                    <i class="bi bi-stars"></i> Consultar
                                                </button>
                                            </div>
                                            <div id="ai_clinical_response_box" class="d-none mt-2 p-3 rounded-3 bg-dark bg-opacity-75 border border-info border-opacity-25 text-white small" style="white-space: pre-line; line-height: 1.6;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PESTAÑA: RECETA / MEDICAMENTOS -->
                                    <div class="tab-pane fade" id="meds-pane" role="tabpanel">
                                        <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1 text-primary"></i>Agrega las prescripciones correspondientes para esta consulta, diferenciando entre <strong>tratamiento permanente/crónico</strong> o <strong>temporal con duración en días</strong>.</p>
                                        <div class="table-responsive">
                                            <table class="table align-middle" id="tableMeds">
                                                <thead>
                                                    <tr class="small text-secondary fw-bold border-bottom">
                                                        <th style="min-width: 170px;">Medicamento</th>
                                                        <th style="width: 130px;">Tipo de Toma</th>
                                                        <th style="width: 100px;">Duración (días)</th>
                                                        <th style="width: 120px;">Dosis</th>
                                                        <th style="width: 120px;">Frecuencia</th>
                                                        <th style="width: 130px;">Vía</th>
                                                        <th style="width: 40px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="medsRows">
                                                    <tr class="med-row">
                                                        <td><input type="text" class="form-control form-control-sm med-name" placeholder="Ej: Valsartán 160mg"></td>
                                                        <td>
                                                            <select class="form-select form-select-sm med-type" onchange="toggleMedDuration(this)">
                                                                <option value="permanente">Permanente / Crónico</option>
                                                                <option value="temporal">Tratamiento Temporal</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm med-duration" placeholder="Días" min="1" disabled>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm med-dose" placeholder="Ej: 1 comp"></td>
                                                        <td><input type="text" class="form-control form-control-sm med-freq" placeholder="Ej: cada 24 hs"></td>
                                                        <td>
                                                            <select class="form-select form-select-sm med-route">
                                                                <option value="Oral">Vía Oral</option>
                                                                <option value="Sublingual">Vía Sublingual</option>
                                                                <option value="Intravenosa">Vía Intravenosa</option>
                                                                <option value="Intramuscular">Vía Intramuscular</option>
                                                                <option value="Cutánea">Vía Tópica/Cutánea</option>
                                                                <option value="Inhalatoria">Vía Inhalatoria</option>
                                                            </select>
                                                        </td>
                                                        <td><button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeMedRow(this)"><i class="bi bi-trash"></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill mt-2" onclick="addMedRow()">
                                            <i class="bi bi-plus-lg me-1"></i> Agregar Fármaco
                                        </button>
                                    </div>

                                    <!-- PESTAÑA: LABORATORIO -->
                                    <div class="tab-pane fade" id="labs-pane" role="tabpanel">
                                        <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1 text-primary"></i>Ingresa valores críticos de análisis sanguíneo si aplica.</p>
                                        <div class="row g-3">
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Hemoglobina (g/dL)</label>
                                                <input type="number" step="0.1" id="lab_hemo" class="form-control form-control-premium" placeholder="Ej: 14.2">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Glóbulos Rojos (M/uL)</label>
                                                <input type="number" step="0.01" id="lab_red" class="form-control form-control-premium" placeholder="Ej: 4.8">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Glóbulos Blancos (/uL)</label>
                                                <input type="number" id="lab_white" class="form-control form-control-premium" placeholder="Ej: 6500">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Colesterol Total (mg/dL)</label>
                                                <input type="number" id="lab_chol" class="form-control form-control-premium" placeholder="Ej: 185">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PESTAÑA: CARDIOLOGÍA ESPECIALIZADA -->
                                    <div class="tab-pane fade" id="cardio-pane" role="tabpanel">
                                        <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1 text-danger"></i>Módulo cardiovascular: estratificación de riesgo, ECG, ecocardiograma y capacidad funcional.</p>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Riesgo Cardiovascular Global (Framingham / SCORE)</label>
                                                <select id="cardio_riesgo" class="form-select form-select-premium">
                                                    <option value="">Seleccionar nivel...</option>
                                                    <option value="Bajo (<5%)">Bajo (< 5%)</option>
                                                    <option value="Moderado (5-10%)">Moderado (5-10%)</option>
                                                    <option value="Alto (10-20%)">Alto (10-20%)</option>
                                                    <option value="Muy Alto (>20% o evento previo)">Muy Alto (> 20% o evento previo)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Clase Funcional Insuficiencia Cardíaca (NYHA)</label>
                                                <select id="cardio_nyha" class="form-select form-select-premium">
                                                    <option value="">No aplica / Asintomático</option>
                                                    <option value="Clase I: Actividad habitual sin disnea">Clase I: Actividad habitual sin disnea</option>
                                                    <option value="Clase II: Limitación leve en actividad ordinaria">Clase II: Limitación leve en actividad ordinaria</option>
                                                    <option value="Clase III: Limitación marcada con actividad menor">Clase III: Limitación marcada con actividad menor</option>
                                                    <option value="Clase IV: Síntomas y disnea en reposo">Clase IV: Síntomas y disnea en reposo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold">Ritmo Electrocardiográfico (ECG)</label>
                                                <select id="cardio_ecg_ritmo" class="form-select form-select-premium">
                                                    <option value="Sinusal regular">Sinusal regular</option>
                                                    <option value="Fibrilación Auricular (FA)">Fibrilación Auricular (FA)</option>
                                                    <option value="Flutter Auricular">Flutter Auricular</option>
                                                    <option value="Ritmo de Marcapasos">Ritmo de Marcapasos</option>
                                                    <option value="Taquicardia Supraventricular">Taquicardia Supraventricular</option>
                                                    <option value="Extrasístoles frecuentes">Extrasístoles frecuentes</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold">Intervalo PR / QTc (ms)</label>
                                                <input type="text" id="cardio_ecg_qtc" class="form-control form-control-premium" placeholder="Ej: PR 160ms / QTc 420ms">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold">FEVI - Fracción de Eyección (%)</label>
                                                <input type="number" id="cardio_fevi" class="form-control form-control-premium" placeholder="Ej: 58 (Normal >50%)" min="10" max="85">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label text-muted small fw-bold">Hallazgos Ecocardiográficos / Holter / Ergometría</label>
                                                <textarea id="cardio_hallazgos" class="form-control form-control-premium" rows="2" placeholder="Ej: Motilidad parietal conservada, sin valvulopatías severas ni signos de sobrecarga ventricular."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PESTAÑA: KINESIOLOGÍA Y REHABILITACIÓN -->
                                    <div class="tab-pane fade" id="kine-pane" role="tabpanel">
                                        <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1 text-success"></i>Módulo de Fisioterapia y Kinesiología: Escala de dolor EVA, movilidad articular y plan de rehabilitación.</p>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Zona Anatómica / Lesión Evaluada</label>
                                                <input type="text" id="kine_region" class="form-control form-control-premium" placeholder="Ej: Manguito rotador derecho / Región Lumbar">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Escala Visual Analógica de Dolor (EVA 1 - 10)</label>
                                                <div class="d-flex align-items-center gap-3">
                                                    <input type="range" class="form-range" id="kine_eva" min="0" max="10" step="1" value="4" oninput="document.getElementById('eva_val_label').innerText = this.value + '/10'">
                                                    <span id="eva_val_label" class="badge bg-danger px-3 py-2 fw-bold fs-6">4/10</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Rango de Movilidad Articular (ROM) y Palpación</label>
                                                <textarea id="kine_rom" class="form-control form-control-premium" rows="2" placeholder="Ej: Flexión limitada a 110°, dolor en rotación externa. Contractura moderada en paravertebrales."></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Plan Kinésico / Agentes Físicos Aplicados</label>
                                                <textarea id="kine_plan" class="form-control form-control-premium" rows="2" placeholder="Ej: Magnetoterapia 20 min, ultrasonido 1MHz, ejercicios de propiocepción y fortalecimiento excéntrico."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Entrada oculta que recibirá la serialización JSON del formulario antes de submit -->
                                <input type="hidden" name="specialized_data" id="specialized_data">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="glass-card p-4 mb-4" style="background: {{ $modoOscuro ? 'rgba(30, 41, 59, 0.3)' : 'rgba(248, 250, 252, 0.8)' }};">
                            <h6 class="fw-bold mb-3" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};"><i class="bi bi-paperclip me-2 text-primary"></i>Archivos y Estudios</h6>
                            <p class="x-small text-muted mb-3">Adjunte radiografías, informes externos o PDFs. Se guardarán de forma segura.</p>
                            <input type="file" name="attachments[]" class="form-control form-control-premium bg-white mb-2" multiple>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-white text-muted border x-small">PDF</span>
                                <span class="badge bg-white text-muted border x-small">JPG</span>
                                <span class="badge bg-white text-muted border x-small">PNG</span>
                            </div>
                        </div>

                        <!-- Tarjeta para Programar Próxima Cita / Turno -->
                        <div class="glass-card p-4 mb-4" style="background: {{ $modoOscuro ? 'rgba(30, 41, 59, 0.3)' : 'rgba(248, 250, 252, 0.8)' }}; border-left: 4px solid var(--color-primario, #0d6efd);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};">
                                    <i class="bi bi-calendar-plus me-2 text-primary"></i>Próxima Visita / Turno
                                </h6>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" id="proximo_turno_activo" name="proximo_turno_activo" value="1" onchange="toggleProximoTurno(this)">
                                </div>
                            </div>
                            <p class="x-small text-muted mb-3">El médico puede programar directamente el turno de control. Quedará registrado con su autoría médica.</p>

                            <div id="seccion_proximo_turno" class="d-none">
                                <div class="row g-2 mb-2">
                                    <div class="col-7">
                                        <label class="x-small text-muted fw-bold">Fecha</label>
                                        <input type="date" name="proximo_turno_fecha" class="form-control form-control-sm form-control-premium" min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-5">
                                        <label class="x-small text-muted fw-bold">Hora</label>
                                        <input type="time" name="proximo_turno_hora" class="form-control form-control-sm form-control-premium" value="09:00">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="x-small text-muted fw-bold">Profesional a Cargo</label>
                                    <select name="proximo_turno_user_id" class="form-select form-select-sm form-select-premium">
                                        @foreach($profesionales ?? [] as $prof)
                                            <option value="{{ $prof->id }}" {{ $prof->id == auth()->id() ? 'selected' : '' }}>
                                                {{ $prof->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="x-small text-muted fw-bold">Servicio / Motivo de Turno</label>
                                    <select name="proximo_turno_servicio_id" class="form-select form-select-sm form-select-premium">
                                        @foreach($servicios ?? [] as $serv)
                                            <option value="{{ $serv->id }}">{{ $serv->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="x-small text-muted fw-bold">Indicaciones para el Turno</label>
                                    <input type="text" name="proximo_turno_notas" class="form-control form-control-sm form-control-premium" placeholder="Ej: Control post-tratamiento 15 días">
                                </div>
                            </div>
                        </div>

                        <div class="glass-card p-4 mb-4" style="background: {{ $modoOscuro ? 'rgba(30, 41, 59, 0.3)' : 'rgba(248, 250, 252, 0.8)' }};">
                            <h6 class="fw-bold mb-3" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};"><i class="bi bi-shield-lock me-2 text-primary"></i>Notas Internas Privadas</h6>
                            <p class="x-small text-muted mb-3">Información confidencial para el equipo médico. No se imprime en informes para el paciente.</p>
                            <textarea name="internal_notes" class="form-control form-control-premium" rows="6" placeholder="Escriba notas privadas aquí...">{{ old('internal_notes') }}</textarea>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-save-medical shadow-lg text-white">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> GUARDAR REGISTRO DE HCE
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

@push('scripts')
<script>
    // Sincronización en vivo del Odontograma e Historial Interdisciplinario al seleccionar paciente
    document.addEventListener('DOMContentLoaded', function() {
        const patientSelect = document.querySelector('select[name="client_id"]');
        if (patientSelect) {
            // Si ya viene preseleccionado por URL
            if (patientSelect.value) {
                cargarHistorialOdontograma(patientSelect.value);
                cargarResumenInterdisciplinario(patientSelect.value);
            }

            patientSelect.addEventListener('change', function() {
                const patientId = this.value;
                if (!patientId) {
                    const alertBox = document.getElementById('odontograma_history_alert');
                    if (alertBox) alertBox.classList.add('d-none');
                    if (typeof window.cargarOdontogramaData === 'function') {
                        window.cargarOdontogramaData('odontograma_create', {});
                    }
                    ocultarBarraInterdisciplinaria();
                    return;
                }
                cargarHistorialOdontograma(patientId);
                cargarResumenInterdisciplinario(patientId);
            });
        }
    });

    let currentInterSummary = null;

    function cargarResumenInterdisciplinario(patientId) {
        fetch(`/empresa/api/patients/${patientId}/interdisciplinary-summary`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            currentInterSummary = data;
            renderInterdisciplinaryButtons(data);
        })
        .catch(err => {
            console.error('Error al cargar resumen interdisciplinario:', err);
        });
    }

    function renderInterdisciplinaryButtons(data) {
        const bar = document.getElementById('interdisciplinary_bar');
        const container = document.getElementById('interdisciplinary_buttons');
        if (!bar || !container) return;

        container.innerHTML = '';
        let countButtons = 0;

        // Botón Odontología
        if (data.has_odonto) {
            countButtons++;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-info rounded-pill fw-bold d-flex align-items-center gap-1 shadow-sm';
            btn.innerHTML = `<span class="fs-6">🦷</span> Actividad Odontología <span class="badge bg-info text-white rounded-pill ms-1">${data.odonto_count}</span>`;
            btn.onclick = () => mostrarDetalleInterdisciplinario('odonto');
            container.appendChild(btn);
        }

        // Botón Cardiología
        if (data.has_cardio) {
            countButtons++;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-danger rounded-pill fw-bold d-flex align-items-center gap-1 shadow-sm';
            btn.innerHTML = `<span class="fs-6">🫀</span> Actividad Cardiología <span class="badge bg-danger text-white rounded-pill ms-1">${data.cardio_count}</span>`;
            btn.onclick = () => mostrarDetalleInterdisciplinario('cardio');
            container.appendChild(btn);
        }

        // Botón Kinesiología
        if (data.has_kine) {
            countButtons++;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-success rounded-pill fw-bold d-flex align-items-center gap-1 shadow-sm';
            btn.innerHTML = `<span class="fs-6">🏃</span> Actividad Kinesiología <span class="badge bg-success text-white rounded-pill ms-1">${data.kine_count}</span>`;
            btn.onclick = () => mostrarDetalleInterdisciplinario('kine');
            container.appendChild(btn);
        }

        // Si tiene al menos una actividad de otra área, mostramos la barra; si no, queda totalmente oculta
        if (countButtons > 0) {
            bar.classList.remove('d-none');
        } else {
            bar.classList.add('d-none');
        }
        cerrarVisorInterdisciplinario();
    }

    function mostrarDetalleInterdisciplinario(tipo) {
        if (!currentInterSummary) return;

        const box = document.getElementById('interdisciplinary_viewer_box');
        const title = document.getElementById('interdisciplinary_viewer_title');
        const content = document.getElementById('interdisciplinary_viewer_content');

        box.classList.remove('d-none');

        if (tipo === 'odonto') {
            const last = currentInterSummary.last_odonto;
            title.className = 'badge bg-info text-white px-2 py-1 x-small fw-bold';
            title.innerHTML = '🦷 Historial Odontológico';
            content.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <strong class="text-dark">Última Atención Odontológica: ${last ? last.date : 'S/D'}</strong>
                        <div class="x-small text-muted">Profesional: <strong>${last ? last.doctor : 'Odontólogo'}</strong></div>
                    </div>
                    <span class="badge bg-info-subtle text-info border border-info-subtle fw-bold">${last ? last.piezas_count : 0} piezas registradas</span>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <span class="x-small text-muted fw-bold d-block">Diagnóstico / Hallazgos Bucomaxilofaciales:</span>
                        <p class="mb-0 small text-dark">${last && last.diagnosis ? last.diagnosis : 'Sin diagnóstico registrado'}</p>
                    </div>
                    <div class="col-md-6">
                        <span class="x-small text-muted fw-bold d-block">Tratamiento Odontológico Realizado:</span>
                        <p class="mb-0 small text-dark">${last && last.treatment ? last.treatment : 'Sin tratamiento registrado'}</p>
                    </div>
                </div>
                <div class="p-2 rounded bg-light border x-small text-muted">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Información en solo lectura para soporte clínico. No altera el odontograma ni la evolución dental actual.
                </div>
            `;
        } else if (tipo === 'cardio') {
            const last = currentInterSummary.last_cardio;
            title.className = 'badge bg-danger text-white px-2 py-1 x-small fw-bold';
            title.innerHTML = '🫀 Historial Cardiológico';
            content.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <strong class="text-dark">Última Evaluación Cardiológica: ${last ? last.date : 'S/D'}</strong>
                        <div class="x-small text-muted">Profesional: <strong>${last ? last.doctor : 'Cardiólogo'}</strong></div>
                    </div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold">FEVI: ${last && last.fevi ? last.fevi + '%' : 'S/D'}</span>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <span class="x-small text-muted fw-bold d-block">Riesgo Cardiovascular:</span>
                        <strong class="text-danger small">${last && last.riesgo ? last.riesgo : 'No estratificado'}</strong>
                    </div>
                    <div class="col-md-4">
                        <span class="x-small text-muted fw-bold d-block">Clase NYHA:</span>
                        <span class="text-dark small">${last && last.nyha ? last.nyha : 'Asintomático / No aplica'}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="x-small text-muted fw-bold d-block">Ritmo ECG:</span>
                        <strong class="text-primary small">${last && last.ecg ? last.ecg : 'Sin ECG cargado'}</strong>
                    </div>
                    <div class="col-12 mt-2">
                        <span class="x-small text-muted fw-bold d-block">Estudios / Eco / Holter:</span>
                        <p class="mb-0 small text-dark">${last && last.hallazgos ? last.hallazgos : 'Sin observaciones adicionales'}</p>
                    </div>
                </div>
            `;
        } else if (tipo === 'kine') {
            const last = currentInterSummary.last_kine;
            title.className = 'badge bg-success text-white px-2 py-1 x-small fw-bold';
            title.innerHTML = '🏃 Historial Kinésico / Fisioterapia';
            content.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <strong class="text-dark">Última Sesión de Kinesiología: ${last ? last.date : 'S/D'}</strong>
                        <div class="x-small text-muted">Profesional: <strong>${last ? last.doctor : 'Kinesiólogo'}</strong></div>
                    </div>
                    <span class="badge bg-danger text-white fw-bold">Dolor EVA: ${last && last.eva ? last.eva + '/10' : 'S/D'}</span>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <span class="x-small text-muted fw-bold d-block">Zona Anatómica / Lesión:</span>
                        <strong class="text-dark small">${last && last.region ? last.region : 'No especificada'}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="x-small text-muted fw-bold d-block">Movilidad (ROM) y Palpación:</span>
                        <p class="mb-0 small text-dark">${last && last.rom ? last.rom : 'Sin datos de movilidad'}</p>
                    </div>
                    <div class="col-12 mt-2">
                        <span class="x-small text-muted fw-bold d-block">Plan Fisiokinésico / Agentes:</span>
                        <p class="mb-0 small text-dark">${last && last.plan ? last.plan : 'Sin plan registrado'}</p>
                    </div>
                </div>
            `;
        }
    }

    function cerrarVisorInterdisciplinario() {
        const box = document.getElementById('interdisciplinary_viewer_box');
        if (box) box.classList.add('d-none');
    }

    function ocultarBarraInterdisciplinaria() {
        const bar = document.getElementById('interdisciplinary_bar');
        if (bar) bar.classList.add('d-none');
        cerrarVisorInterdisciplinario();
    }

    function cargarHistorialOdontograma(patientId) {
        const alertBox = document.getElementById('odontograma_history_alert');
        const alertMsg = document.getElementById('odontograma_history_msg');

        fetch(`/empresa/api/patients/${patientId}/odontograma`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            const odonto = data.odontograma || {};
            const keysCount = Object.keys(odonto).length;

            if (typeof window.cargarOdontogramaData === 'function') {
                window.cargarOdontogramaData('odontograma_create', odonto);
            }

            if (alertBox && alertMsg) {
                if (keysCount > 0) {
                    alertMsg.innerHTML = `<strong>¡Historial cargado!</strong> Se arrastraron <strong>${keysCount} piezas con antecedentes</strong> de visitas previas. Modifique o agregue las novedades clínicas de esta consulta.`;
                    alertBox.classList.remove('d-none');
                } else {
                    alertMsg.innerHTML = `El paciente no tiene registros odontológicos previos. Se inicia con odontograma limpio (boca sana).`;
                    alertBox.classList.remove('d-none');
                }
            }
        })
        .catch(err => {
            console.error('Error al sincronizar odontograma histórico:', err);
        });
    }

    function recargarOdontoPaciente() {
        const patientSelect = document.querySelector('select[name="client_id"]');
        if (patientSelect && patientSelect.value) {
            cargarHistorialOdontograma(patientSelect.value);
        }
    }
    function toggleProximoTurno(checkbox) {
        const sec = document.getElementById('seccion_proximo_turno');
        if (checkbox.checked) {
            sec.classList.remove('d-none');
        } else {
            sec.classList.add('d-none');
        }
    }

    function toggleMedDuration(select) {
        const row = select.closest('tr');
        const durationInput = row.querySelector('.med-duration');
        if (select.value === 'temporal') {
            durationInput.disabled = false;
            if (!durationInput.value) durationInput.value = '15';
            durationInput.focus();
        } else {
            durationInput.disabled = true;
            durationInput.value = '';
        }
    }

    function addMedRow() {
        const tbody = document.getElementById('medsRows');
        const tr = document.createElement('tr');
        tr.className = 'med-row';
        tr.innerHTML = `
            <td><input type="text" class="form-control form-control-sm med-name" placeholder="Ej: Valsartán 160mg / Amoxicilina 875mg"></td>
            <td>
                <select class="form-select form-select-sm med-type" onchange="toggleMedDuration(this)">
                    <option value="permanente">Permanente / Crónico</option>
                    <option value="temporal">Tratamiento Temporal</option>
                </select>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm med-duration" placeholder="Días" min="1" disabled>
            </td>
            <td><input type="text" class="form-control form-control-sm med-dose" placeholder="Ej: 1 comp"></td>
            <td><input type="text" class="form-control form-control-sm med-freq" placeholder="Ej: cada 8 hs"></td>
            <td>
                <select class="form-select form-select-sm med-route">
                    <option value="Oral">Vía Oral</option>
                    <option value="Sublingual">Vía Sublingual</option>
                    <option value="Intravenosa">Vía Intravenosa</option>
                    <option value="Intramuscular">Vía Intramuscular</option>
                    <option value="Cutánea">Vía Tópica/Cutánea</option>
                    <option value="Inhalatoria">Vía Inhalatoria</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeMedRow(this)"><i class="bi bi-trash"></i></button></td>
        `;
        tbody.appendChild(tr);
    }

    function removeMedRow(button) {
        const rows = document.querySelectorAll('.med-row');
        if (rows.length > 1) {
            button.closest('tr').remove();
        } else {
            // Limpiar valores de la única fila restante si es la última
            const row = button.closest('tr');
            row.querySelector('.med-name').value = '';
            row.querySelector('.med-dose').value = '';
            row.querySelector('.med-freq').value = '';
        }
    }

    // Función para agregar Campos Dinámicos / Tags
    function addDynamicField() {
        const keyInput = document.getElementById('dyn_key');
        const valInput = document.getElementById('dyn_value');
        if (!keyInput || !valInput) return;

        const key = keyInput.value.trim();
        const val = valInput.value.trim();

        if (!key || !val) {
            alert('Por favor, ingresa tanto la propiedad como el detalle del campo.');
            return;
        }

        const container = document.getElementById('dynamicFieldsContainer');
        
        // Crear elemento badge
        const badge = document.createElement('span');
        badge.className = 'badge bg-primary text-white d-flex align-items-center gap-2 px-3 py-2 dynamic-field-badge rounded-pill shadow-sm';
        badge.setAttribute('data-key', key);
        badge.setAttribute('data-value', val);
        badge.style.fontSize = '0.85rem';
        badge.style.border = '1px solid rgba(255,255,255,0.1)';
        badge.innerHTML = `
            <strong>${key}:</strong> ${val}
            <i class="bi bi-x-circle-fill text-white-50 ms-1 fs-6" style="cursor:pointer;" onclick="this.parentElement.remove()" title="Eliminar"></i>
        `;

        container.appendChild(badge);

        // Limpiar inputs
        keyInput.value = '';
        valInput.value = '';
        keyInput.focus();
    }

    // Interceptar el envío del formulario para serializar la data estructurada
    document.querySelector('form').addEventListener('submit', function(e) {
        // Signos Vitales
        const vitals = {
            sys: document.getElementById('vital_sys').value,
            dia: document.getElementById('vital_dia').value,
            glucose: document.getElementById('vital_glucose').value,
            heart: document.getElementById('vital_heart').value,
            weight: document.getElementById('vital_weight').value
        };

        // Recetas / Medicamentos
        const medications = [];
        document.querySelectorAll('.med-row').forEach(row => {
            const name = row.querySelector('.med-name').value.trim();
            if (name) {
                const type = row.querySelector('.med-type')?.value || 'permanente';
                const duration = row.querySelector('.med-duration')?.value ? parseInt(row.querySelector('.med-duration').value) : null;
                medications.push({
                    name: name,
                    type: type,
                    duration_days: duration,
                    dose: row.querySelector('.med-dose').value.trim(),
                    frequency: row.querySelector('.med-freq').value.trim(),
                    route: row.querySelector('.med-route').value
                });
            }
        });

        // Laboratorio
        const labs = {
            hemo: document.getElementById('lab_hemo').value,
            red: document.getElementById('lab_red').value,
            white: document.getElementById('lab_white').value,
            chol: document.getElementById('lab_chol').value
        };

        // Cardiología Especializada
        const cardio = {
            riesgo_cardiovascular: document.getElementById('cardio_riesgo')?.value || '',
            nyha: document.getElementById('cardio_nyha')?.value || '',
            ecg_ritmo: document.getElementById('cardio_ecg_ritmo')?.value || '',
            ecg_qtc: document.getElementById('cardio_ecg_qtc')?.value || '',
            fevi: document.getElementById('cardio_fevi')?.value || '',
            hallazgos: document.getElementById('cardio_hallazgos')?.value || ''
        };

        // Kinesiología Especializada
        const kinesio = {
            region: document.getElementById('kine_region')?.value || '',
            eva: document.getElementById('kine_eva')?.value || '',
            rom: document.getElementById('kine_rom')?.value || '',
            plan: document.getElementById('kine_plan')?.value || ''
        };

        // Obtener Campos Dinámicos / Tags si el contenedor existe
        const dynamicFields = {};
        document.querySelectorAll('.dynamic-field-badge').forEach(badge => {
            const key = badge.getAttribute('data-key');
            const val = badge.getAttribute('data-value');
            if (key && val) {
                dynamicFields[key] = val;
            }
        });

        // Combinar en un único objeto estructurado
        let odontogramaData = null;
        const odontoInput = document.querySelector('input[name="odontograma_data"]');
        if (odontoInput && odontoInput.value) {
            try {
                odontogramaData = JSON.parse(odontoInput.value);
            } catch(e) {
                odontogramaData = null;
            }
        }

        const specializedData = {
            vitals: vitals,
            medications: medications,
            labs: labs,
            cardio: cardio,
            kinesio: kinesio,
            dynamic_fields: dynamicFields,
            odontograma: odontogramaData
        };

        // Inyectar en el input oculto en formato string JSON
        document.getElementById('specialized_data').value = JSON.stringify(specializedData);
    });

    /**
     * Evaluación cromática en tiempo real de Signos Vitales
     */
    function evaluarSignosVitales() {
        const sys = parseFloat(document.getElementById('vital_sys')?.value);
        const dia = parseFloat(document.getElementById('vital_dia')?.value);
        const glucose = parseFloat(document.getElementById('vital_glucose')?.value);
        const heart = parseFloat(document.getElementById('vital_heart')?.value);

        let hayAlertaCritica = false;
        let hayPrecaucion = false;

        // Sistólica
        const badgeSys = document.getElementById('badge_sys');
        if (!isNaN(sys)) {
            if (sys >= 140) {
                badgeSys.className = 'badge bg-danger text-white'; badgeSys.innerText = 'Crisis / HTA II'; hayAlertaCritica = true;
            } else if (sys >= 130) {
                badgeSys.className = 'badge bg-warning text-dark'; badgeSys.innerText = 'Elevada'; hayPrecaucion = true;
            } else if (sys < 90) {
                badgeSys.className = 'badge bg-danger text-white'; badgeSys.innerText = 'Hipotensión'; hayAlertaCritica = true;
            } else {
                badgeSys.className = 'badge bg-success text-white'; badgeSys.innerText = 'Normal (Óptima)';
            }
        }

        // Diastólica
        const badgeDia = document.getElementById('badge_dia');
        if (!isNaN(dia)) {
            if (dia >= 90) {
                badgeDia.className = 'badge bg-danger text-white'; badgeDia.innerText = 'Elevada / HTA'; hayAlertaCritica = true;
            } else if (dia >= 85) {
                badgeDia.className = 'badge bg-warning text-dark'; badgeDia.innerText = 'Limítrofe'; hayPrecaucion = true;
            } else if (dia < 60) {
                badgeDia.className = 'badge bg-danger text-white'; badgeDia.innerText = 'Baja'; hayAlertaCritica = true;
            } else {
                badgeDia.className = 'badge bg-success text-white'; badgeDia.innerText = 'Normal';
            }
        }

        // Glucemia
        const badgeGluc = document.getElementById('badge_glucose');
        if (!isNaN(glucose)) {
            if (glucose > 180) {
                badgeGluc.className = 'badge bg-danger text-white'; badgeGluc.innerText = 'Hiperglucemia Severa'; hayAlertaCritica = true;
            } else if (glucose > 125) {
                badgeGluc.className = 'badge bg-warning text-dark'; badgeGluc.innerText = 'Glucemia Elevada'; hayPrecaucion = true;
            } else if (glucose < 70) {
                badgeGluc.className = 'badge bg-danger text-white'; badgeGluc.innerText = 'Hipoglucemia'; hayAlertaCritica = true;
            } else {
                badgeGluc.className = 'badge bg-success text-white'; badgeGluc.innerText = 'Normal (< 100 mg/dL)';
            }
        }

        // Frecuencia Cardíaca
        const badgeHeart = document.getElementById('badge_heart');
        if (!isNaN(heart)) {
            if (heart > 100) {
                badgeHeart.className = 'badge bg-danger text-white'; badgeHeart.innerText = 'Taquicardia'; hayAlertaCritica = true;
            } else if (heart < 60) {
                badgeHeart.className = 'badge bg-warning text-dark'; badgeHeart.innerText = 'Bradicardia'; hayPrecaucion = true;
            } else {
                badgeHeart.className = 'badge bg-success text-white'; badgeHeart.innerText = 'Normal (60-100 lpm)';
            }
        }

        // Insignia General
        const badgeGeneral = document.getElementById('vitals_general_badge');
        if (badgeGeneral) {
            if (hayAlertaCritica) {
                badgeGeneral.className = 'badge bg-danger text-white px-3 py-1 fw-bold rounded-pill animate__animated animate__pulse animate__infinite';
                badgeGeneral.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Alertas Críticas Detectadas';
            } else if (hayPrecaucion) {
                badgeGeneral.className = 'badge bg-warning text-dark px-3 py-1 fw-bold rounded-pill';
                badgeGeneral.innerHTML = '<i class="bi bi-exclamation-circle-fill me-1"></i> Parámetros en Observación';
            } else {
                badgeGeneral.className = 'badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-bold rounded-pill';
                badgeGeneral.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Parámetros Normales';
            }
        }
    }

    /**
     * Consulta confidencial de interconsulta médica asistida por IA
     */
    function consultarIAClinica() {
        const query = document.getElementById('ai_clinical_query')?.value.trim();
        const responseBox = document.getElementById('ai_clinical_response_box');
        const patientSelect = document.querySelector('select[name="client_id"]');
        const specialtyInput = document.querySelector('input[name="specialty"]');

        if (!query) {
            alert('Por favor redacte la consulta clínica para la IA.');
            return;
        }

        responseBox.classList.remove('d-none');
        responseBox.innerHTML = '<div class="d-flex align-items-center gap-2 text-info"><div class="spinner-border spinner-border-sm text-info"></div><span>Analizando historia clínica y parámetros con Inteligencia Artificial...</span></div>';

        const vitalsData = {
            sys: document.getElementById('vital_sys')?.value,
            dia: document.getElementById('vital_dia')?.value,
            glucose: document.getElementById('vital_glucose')?.value,
            heart: document.getElementById('vital_heart')?.value,
            weight: document.getElementById('vital_weight')?.value
        };

        fetch('{{ route("empresa.ai.clinical-consultation") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                query: query,
                patient_id: patientSelect ? patientSelect.value : null,
                specialty: specialtyInput ? specialtyInput.value : null,
                vitals: vitalsData
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let alertHtml = '';
                if (data.alerts && data.alerts.length > 0) {
                    alertHtml = '<div class="alert alert-danger py-1 px-2 mb-2 x-small fw-bold">⚠️ ' + data.alerts.join(' | ') + '</div>';
                }
                responseBox.innerHTML = alertHtml + '<div>' + data.response.replace(/\n/g, '<br>') + '</div><div class="x-small text-muted mt-2 border-top border-secondary pt-1 opacity-75">' + data.disclaimer + '</div>';
            } else {
                responseBox.innerHTML = '<div class="text-danger">No se pudo procesar la consulta: ' + (data.message || 'Error del servidor') + '</div>';
            }
        })
        .catch(err => {
            responseBox.innerHTML = '<div class="text-danger">Error de comunicación con el motor de IA.</div>';
        });
    }
</script>
@endpush
