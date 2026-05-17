@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    
    {{-- BANNER DE INFORMACIÓN DEL PACIENTE --}}
    <div class="card shadow border-0 bg-dark text-white rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4 bg-gradient position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-primary bg-opacity-25 border border-primary border-opacity-50">
                        <i class="bi bi-person-fill-gear fs-2 text-primary"></i>
                    </div>
                    <div>
                        <span class="badge bg-danger mb-2 px-2 py-1 text-uppercase small" style="font-size: 0.65rem; letter-spacing: 1px;">Internación Activa</span>
                        <h4 class="fw-bold mb-1 text-white">{{ $hospitalization->patient->nombre }}</h4>
                        <div class="small text-muted text-light opacity-75">
                            <i class="bi bi-card-text me-1"></i> Documento: <strong>{{ $hospitalization->patient->cuit_cuil ?? 'No Registrado' }}</strong> | 
                            <i class="bi bi-telephone me-1 ms-2"></i> Tel: <strong>{{ $hospitalization->patient->telefono ?? 'Sin Teléfono' }}</strong>
                        </div>
                    </div>
                </div>
                
                {{-- DETALLES FÍSICOS DE UBICACIÓN --}}
                <div class="d-flex gap-4 align-items-center text-center text-md-start flex-wrap">
                    <div class="p-3 bg-white bg-opacity-5 border border-white border-opacity-10 rounded-3">
                        <span class="small text-muted d-block uppercase text-xs" style="font-size: 0.7rem;">HABITACIÓN</span>
                        <strong class="text-white fs-6"><i class="bi bi-door-open-fill me-1 text-primary"></i> {{ $hospitalization->currentBedAssignment->bed->room->nombre ?? 'N/A' }}</strong>
                    </div>
                    <div class="p-3 bg-white bg-opacity-5 border border-white border-opacity-10 rounded-3">
                        <span class="small text-muted d-block uppercase text-xs" style="font-size: 0.7rem;">CAMA CLINICA</span>
                        <strong class="text-white fs-6"><i class="bi bi-hospital me-1 text-success"></i> {{ $hospitalization->currentBedAssignment->bed->nombre ?? 'N/A' }}</strong>
                    </div>
                    <div class="p-3 bg-white bg-opacity-5 border border-white border-opacity-10 rounded-3">
                        <span class="small text-muted d-block uppercase text-xs" style="font-size: 0.7rem;">DÍAS HOSPITALIZADO</span>
                        <strong class="text-white fs-6">
                            <i class="bi bi-hourglass-split me-1 text-warning"></i>
                            @php
                                $dias = max(1, $hospitalization->fecha_ingreso->diffInDays(now()));
                                echo $dias . ($dias === 1 ? ' día' : ' días');
                            @endphp
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTAS DE OPERACIÓN --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- MENU DE PESTAÑAS (TABS CLINICOS) --}}
    <ul class="nav nav-pills mb-4 nav-justified bg-white p-2 rounded shadow-sm border" id="dossierTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="evolucion-tab" data-bs-toggle="tab" data-bs-target="#evolucion" type="button" role="tab">🩺 Evolución y Signos</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="medicacion-tab" data-bs-toggle="tab" data-bs-target="#medicacion" type="button" role="tab">💊 Medicamentos Aplicados</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="consumos-tab" data-bs-toggle="tab" data-bs-target="#consumos" type="button" role="tab">📦 Consumos y Materiales</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-warning" id="traslados-tab" data-bs-toggle="tab" data-bs-target="#traslados" type="button" role="tab">🔄 Traslados de Cama</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-danger" id="alta-tab" data-bs-toggle="tab" data-bs-target="#alta" type="button" role="tab">🟢 Alta Médica</button>
        </li>
    </ul>

    {{-- CONTENIDO DE LAS PESTAÑAS --}}
    <div class="card shadow border-0 bg-white rounded-4 overflow-hidden mb-5">
        <div class="card-body p-4 p-md-5">
            <div class="tab-content" id="dossierTabsContent">
                
                {{-- PESTAÑA 1: EVOLUCIÓN Y SIGNOS --}}
                <div class="tab-pane fade show active" id="evolucion" role="tabpanel">
                    <div class="row g-4">
                        {{-- FORMULARIO PARA CARGAR CONTROLES --}}
                        <div class="col-lg-5">
                            <div class="p-4 border rounded-4 bg-light">
                                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-heart-pulse-fill me-1"></i> Registrar Control Clínico / Visita</h6>
                                <form action="{{ route('empresa.hospitalization.administrations.store', $hospitalization->id) }}" method="POST" id="inpatientControlForm">
                                    @csrf
                                    <input type="hidden" name="tipo" value="control_signos">
                                    <input type="hidden" name="cantidad" value="1">
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Descripción del Control</label>
                                        <input type="text" name="nombre" class="form-control shadow-sm" placeholder="Ej: Control de Signos Vitales, Visita Médica Matutina" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Fecha y Hora de Registro</label>
                                        <input type="datetime-local" name="fecha_registro" value="{{ now()->format('Y-m-d\TH:i') }}" class="form-control shadow-sm" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Anotación de Evolución / Notas de la Visita</label>
                                        <textarea id="control_evolucion" rows="3" class="form-control shadow-sm" placeholder="Escribe el control clínico general, evolución del día..." required></textarea>
                                    </div>

                                    <!-- Módulo Opcional Estructurado -->
                                    <label class="form-label small fw-bold text-muted d-block mb-2"><i class="bi bi-patch-plus text-primary"></i> Data Especializada (Opcional)</label>
                                    <ul class="nav nav-pills gap-1 mb-2" id="controlTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active btn-sm py-1 px-2 fw-bold border" id="vitals-control-tab" data-bs-toggle="pill" data-bs-target="#vitals-control" type="button" role="tab" style="font-size:0.75rem;">🩺 Signos</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link btn-sm py-1 px-2 fw-bold border" id="labs-control-tab" data-bs-toggle="pill" data-bs-target="#labs-control" type="button" role="tab" style="font-size:0.75rem;">🧪 Lab</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link btn-sm py-1 px-2 fw-bold border" id="tags-control-tab" data-bs-toggle="pill" data-bs-target="#tags-control" type="button" role="tab" style="font-size:0.75rem;">🏷️ Tags</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content p-3 border rounded-3 bg-white mb-3" id="controlTabsContent">
                                        <!-- TAB 1: SIGNOS VITALES -->
                                        <div class="tab-pane fade show active" id="vitals-control" role="tabpanel">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">Temperatura (°C)</label>
                                                    <input type="number" step="0.1" id="vital_temp" class="form-control form-control-sm" placeholder="36.5" min="0">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">F. Cardíaca (lpm)</label>
                                                    <input type="number" id="vital_heart" class="form-control form-control-sm" placeholder="75" min="0">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">P. Sistólica (mmHg)</label>
                                                    <input type="number" id="vital_sys" class="form-control form-control-sm" placeholder="120" min="0">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">P. Diastólica (mmHg)</label>
                                                    <input type="number" id="vital_dia" class="form-control form-control-sm" placeholder="80" min="0">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">Sat. Oxígeno (%)</label>
                                                    <input type="number" id="vital_sat" class="form-control form-control-sm" placeholder="98" min="0">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">Glucemia (mg/dL)</label>
                                                    <input type="number" id="vital_glucose" class="form-control form-control-sm" placeholder="110" min="0">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">Peso Corporal (kg)</label>
                                                    <input type="number" step="0.1" id="vital_weight" class="form-control form-control-sm" placeholder="72.4" min="0">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TAB 2: LABORATORIO -->
                                        <div class="tab-pane fade" id="labs-control" role="tabpanel">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">Hemoglobina (g/dL)</label>
                                                    <input type="number" step="0.1" id="lab_hemo" class="form-control form-control-sm" placeholder="14.2">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">G. Rojos (M/uL)</label>
                                                    <input type="number" step="0.1" id="lab_red" class="form-control form-control-sm" placeholder="4.8">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">G. Blancos (/uL)</label>
                                                    <input type="number" id="lab_white" class="form-control form-control-sm" placeholder="6500">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted x-small fw-bold mb-1" style="font-size: 0.65rem;">Plaquetas (/uL)</label>
                                                    <input type="number" id="lab_platelets" class="form-control form-control-sm" placeholder="250000">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TAB 3: TAGS PERSONALIZADOS -->
                                        <div class="tab-pane fade" id="tags-control" role="tabpanel">
                                            <div class="row g-1 align-items-end mb-2">
                                                <div class="col-5">
                                                    <input type="text" id="dyn_key" class="form-control form-control-sm" placeholder="Propiedad (Ej: Dolor)" style="font-size:0.75rem;">
                                                </div>
                                                <div class="col-5">
                                                    <input type="text" id="dyn_value" class="form-control form-control-sm" placeholder="Valor (Ej: Lumbar)" style="font-size:0.75rem;">
                                                </div>
                                                <div class="col-2 text-end">
                                                    <button type="button" class="btn btn-primary btn-sm w-100 fw-bold py-1" onclick="addDynamicFieldInpatient()"><i class="bi bi-plus-lg"></i></button>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 mt-2" id="dynamicFieldsContainerInpatient"></div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="notas" id="notas_serialized">

                                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">
                                        Guardar Registro Clínico
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- HISTORIAL CLINICO --}}
                        <div class="col-lg-7">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history me-1 text-muted"></i> Expediente Clínico de la Internación</h5>
                            
                            {{-- DATOS DE INGRESO --}}
                            <div class="p-3 border rounded-3 bg-light bg-opacity-50 border-primary mb-4 shadow-inner">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <span class="small text-muted d-block">Motivo de Ingreso:</span>
                                        <strong class="text-dark">{{ $hospitalization->motivo_ingreso }}</strong>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <span class="small text-muted d-block">Diagnóstico de Ingreso:</span>
                                        <strong class="text-danger">{{ $hospitalization->diagnostico_ingreso }}</strong>
                                    </div>
                                </div>
                            </div>

                            @php
                                $controles = $hospitalization->administrations->where('tipo', 'control_signos');
                            @endphp

                            @if($controles->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-activity display-4 opacity-25 d-block mb-3"></i>
                                    No se han cargado controles clínicos en esta internación todavía.
                                </div>
                            @else
                                <div class="timeline-container" style="max-height: 600px; overflow-y: auto; padding-right: 5px;">
                                    @foreach($controles as $reg)
                                        @php
                                            $isJson = false;
                                            $clinicalData = null;
                                            if ($reg->notas) {
                                                $decoded = json_decode($reg->notas, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                    $isJson = true;
                                                    $clinicalData = $decoded;
                                                }
                                            }
                                        @endphp
                                        <div class="card mb-3 border-0 shadow-sm rounded-4 bg-white border-start border-4 border-primary">
                                            <div class="card-body p-3">
                                                {{-- Cabecera del control --}}
                                                <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom border-light">
                                                    <div>
                                                        <span class="fw-extrabold text-primary small"><i class="bi bi-shield-pulse me-1"></i> {{ $reg->nombre }}</span>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge bg-light text-dark border x-small fw-bold"><i class="bi bi-calendar3 me-1"></i> {{ $reg->fecha_registro ? \Carbon\Carbon::parse($reg->fecha_registro)->format('d/m/Y H:i') : 'Sin fecha' }}</span>
                                                    </div>
                                                </div>

                                                {{-- Anotaciones Clínicas / Evolución --}}
                                                <div class="mb-2 mt-2">
                                                    @if($isJson)
                                                        <p class="mb-0 text-dark small" style="white-space: pre-line; line-height: 1.4;">{{ $clinicalData['evolucion'] ?? 'Sin anotaciones' }}</p>
                                                    @else
                                                        <p class="mb-0 text-dark small font-monospace" style="white-space: pre-line; line-height: 1.4;">{{ $reg->notas }}</p>
                                                    @endif
                                                </div>

                                                {{-- Si es JSON, renderizar Signos Vitales, Laboratorio y Tags --}}
                                                @if($isJson)
                                                    {{-- Signos Vitales --}}
                                                    @php
                                                        $vitals = $clinicalData['vitals'] ?? [];
                                                        $hasVitals = collect($vitals)->filter()->isNotEmpty();
                                                    @endphp
                                                    @if($hasVitals)
                                                        <div class="mb-2 mt-3">
                                                            <span class="text-muted fw-bold d-block mb-1" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="bi bi-heart-pulse text-danger"></i> Signos Vitales</span>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @if(!empty($vitals['temp']))
                                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        🌡️ {{ $vitals['temp'] }} °C
                                                                    </span>
                                                                @endif
                                                                @if(!empty($vitals['heart']))
                                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        💓 {{ $vitals['heart'] }} lpm
                                                                    </span>
                                                                @endif
                                                                @if(!empty($vitals['sys']) || !empty($vitals['dia']))
                                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        🩺 {{ $vitals['sys'] ?? 'N/A' }}/{{ $vitals['dia'] ?? 'N/A' }} mmHg
                                                                    </span>
                                                                @endif
                                                                @if(!empty($vitals['sat']))
                                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        𫓧 {{ $vitals['sat'] }} %
                                                                    </span>
                                                                @endif
                                                                @if(!empty($vitals['glucose']))
                                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        🩸 {{ $vitals['glucose'] }} mg/dL
                                                                    </span>
                                                                @endif
                                                                @if(!empty($vitals['weight']))
                                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        ⚖️ {{ $vitals['weight'] }} kg
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- Laboratorio --}}
                                                    @php
                                                        $labs = $clinicalData['labs'] ?? [];
                                                        $hasLabs = collect($labs)->filter()->isNotEmpty();
                                                    @endphp
                                                    @if($hasLabs)
                                                        <div class="mb-2 mt-2">
                                                            <span class="text-muted fw-bold d-block mb-1" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="bi bi-activity text-info"></i> Laboratorio</span>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @if(!empty($labs['hemo']))
                                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        🩸 Hemo: {{ $labs['hemo'] }} g/dL
                                                                    </span>
                                                                @endif
                                                                @if(!empty($labs['red']))
                                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        🔴 G.Rojos: {{ $labs['red'] }} M/uL
                                                                    </span>
                                                                @endif
                                                                @if(!empty($labs['white']))
                                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        ⚪ G.Blancos: {{ $labs['white'] }} /uL
                                                                    </span>
                                                                @endif
                                                                @if(!empty($labs['platelets']))
                                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-2 py-1 rounded-2 text-start x-small">
                                                                        🧬 Plaq: {{ $labs['platelets'] }} /uL
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- Tags --}}
                                                    @php
                                                        $tags = $clinicalData['dynamic_fields'] ?? [];
                                                    @endphp
                                                    @if(!empty($tags))
                                                        <div class="mb-0 mt-2">
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach($tags as $key => $val)
                                                                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill x-small">
                                                                        <strong>{{ $key }}:</strong> {{ $val }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="text-end mt-2 pt-2 border-top border-light">
                                                    <small class="text-muted x-small">Registrado por: <strong class="text-secondary">{{ $reg->user->nombre ?? 'Profesional' }}</strong></small>
                                                </div>

                                                @if($isJson)
                                                    {{-- Botón para ver JSON Técnico --}}
                                                    <div class="mt-2 pt-2 border-top border-light d-flex justify-content-between align-items-center">
                                                        <button class="btn btn-xs btn-outline-secondary border-dashed px-2 py-1 text-uppercase fw-bold rounded-2 font-monospace" type="button" data-bs-toggle="collapse" data-bs-target="#jsonCollapse-{{ $reg->id }}" aria-expanded="false" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                                            <i class="bi bi-braces me-1"></i> Ver JSON Técnico
                                                        </button>
                                                        <span class="x-small text-muted font-monospace" style="font-size: 0.6rem;">ID Control: #{{ $reg->id }}</span>
                                                    </div>
                                                    <div class="collapse mt-2" id="jsonCollapse-{{ $reg->id }}">
                                                        <div class="card card-body bg-dark text-success p-3 border-0 rounded-3 position-relative shadow-inner" style="background-color: #0c0f16 !important;">
                                                            <button class="btn btn-link btn-xs text-secondary position-absolute top-0 end-0 m-2 p-0 text-decoration-none" type="button" onclick="copyToClipboardInInpatient('{{ addslashes(json_encode($clinicalData)) }}', this)" title="Copiar al portapapeles">
                                                                <i class="bi bi-clipboard"></i>
                                                            </button>
                                                            <pre class="mb-0 font-monospace text-success" style="font-size: 0.75rem; white-space: pre-wrap; overflow-x: auto; line-height: 1.3; color: #10b981 !important;"><code>{{ json_encode($clinicalData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PESTAÑA 2: MEDICAMENTOS --}}
                <div class="tab-pane fade" id="medicacion" role="tabpanel">
                    <div class="row g-4">
                        {{-- FORMULARIO REGISTRAR MEDICACION --}}
                        <div class="col-lg-5">
                            <div class="p-4 border rounded-4 bg-light">
                                <h6 class="fw-bold text-success mb-3"><i class="bi bi-capsule me-1"></i> Registrar Aplicación de Medicamento</h6>
                                <form action="{{ route('empresa.hospitalization.administrations.store', $hospitalization->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tipo" value="medicamento">
                                    <input type="hidden" name="cantidad" value="1">
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Nombre del Medicamento</label>
                                        <input type="text" name="nombre" class="form-control shadow-sm" placeholder="Ej: Paracetamol 1g Ampolla, Ibuprofeno 400mg" required>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Dosis</label>
                                            <input type="text" name="dosis" class="form-control shadow-sm" placeholder="Ej: 1 ampolla, 500mg">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Vía de Adm.</label>
                                            <input type="text" name="via" class="form-control shadow-sm" placeholder="Ej: Endovenosa, Oral">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Frecuencia de Toma</label>
                                        <input type="text" name="frecuencia" class="form-control shadow-sm" placeholder="Ej: Cada 8 horas, Dosis única">
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Fecha Aplicación</label>
                                            <input type="datetime-local" name="fecha_registro" value="{{ now()->format('Y-m-d\TH:i') }}" class="form-control shadow-sm" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Próxima Dosis (Opcional)</label>
                                            <input type="datetime-local" name="proxima_aplicacion" class="form-control shadow-sm">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Observaciones / Notas</label>
                                        <textarea name="notas" rows="2" class="form-control shadow-sm" placeholder="Paciente tolera correctamente el fármaco..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success text-white w-100 fw-bold rounded-pill shadow-sm">
                                        Registrar Administración
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- TIMELINE DE MEDICACION APLICADA --}}
                        <div class="col-lg-7">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-prescription me-1 text-success"></i> Medicación Suministrada</h5>
                            
                            @php
                                $medicamentos = $hospitalization->administrations->where('tipo', 'medicamento');
                            @endphp

                            @if($medicamentos->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-capsule-capsules display-4 opacity-25 d-block mb-3"></i>
                                    No se han registrado aplicaciones de medicamentos todavía.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="small fw-bold">Medicamento</th>
                                                <th class="small fw-bold">Dosis / Vía</th>
                                                <th class="small fw-bold text-center">Frecuencia</th>
                                                <th class="small fw-bold text-center">Fecha</th>
                                                <th class="small fw-bold text-center">Próxima</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($medicamentos as $med)
                                                <tr>
                                                    <td>
                                                        <strong class="text-dark">{{ $med->nombre }}</strong>
                                                        @if($med->notas)
                                                            <div class="x-small text-muted italic">{{ $med->notas }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="small">{{ $med->dosis ?? 'N/A' }}</span> <br>
                                                        <span class="badge bg-light text-dark small border">{{ $med->via ?? 'N/A' }}</span>
                                                    </td>
                                                    <td class="text-center small">{{ $med->frecuencia ?? 'N/A' }}</td>
                                                    <td class="text-center small">{{ $med->fecha_registro ? \Carbon\Carbon::parse($med->fecha_registro)->format('d/m H:i') : 'Sin fecha' }}</td>
                                                    <td class="text-center small">
                                                        @if($med->proxima_aplicacion)
                                                            <span class="text-danger fw-bold">{{ \Carbon\Carbon::parse($med->proxima_aplicacion)->format('d/m H:i') }}</span>
                                                        @else
                                                            <span class="text-muted italic">No programada</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PESTAÑA 3: CONSUMOS Y INSUMOS --}}
                <div class="tab-pane fade" id="consumos" role="tabpanel">
                    <div class="row g-4">
                        {{-- FORMULARIO CARGAR INSUMOS --}}
                        <div class="col-lg-5">
                            <div class="p-4 border rounded-4 bg-light">
                                <h6 class="fw-bold text-info mb-3"><i class="bi bi-boxes me-1"></i> Cargar Consumo de Material o Medicamento</h6>
                                <form action="{{ route('empresa.hospitalization.administrations.store', $hospitalization->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tipo" value="descartable">
                                    <input type="hidden" name="fecha_registro" value="{{ now()->format('Y-m-d\TH:i') }}">
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Nombre del Insumo / Material</label>
                                        <input type="text" name="nombre" class="form-control shadow-sm" placeholder="Ej: Jeringa 10cc, Gasas estériles, Suero Fisiológico 500ml" required>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Cantidad Utilizada</label>
                                            <input type="number" step="0.01" name="cantidad" value="1" class="form-control shadow-sm text-center" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Precio Unitario ($)</label>
                                            <input type="number" step="0.01" name="precio_unitario" class="form-control shadow-sm text-center" placeholder="Opcional">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Notas de consumo</label>
                                        <textarea name="notas" rows="2" class="form-control shadow-sm" placeholder="Notas adicionales..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-info text-white w-100 fw-bold rounded-pill shadow-sm">
                                        Cargar Consumo
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- DETALLE DE MATERIALES UTILIZADOS --}}
                        <div class="col-lg-7">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cart-dash me-1 text-info"></i> Insumos y Descartables Gastados</h5>
                            
                            @php
                                $insumos = $hospitalization->administrations->where('tipo', 'descartable');
                                $totalAcumulado = 0;
                            @endphp

                            @if($insumos->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-cart-check display-4 opacity-25 d-block mb-3"></i>
                                    No se han cargado insumos médicos en esta hospitalización.
                                </div>
                            @else
                                <div class="table-responsive mb-3">
                                    <table class="table table-hover align-middle border">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="small fw-bold">Material / Insumo</th>
                                                <th class="small fw-bold text-center">Cantidad</th>
                                                <th class="small fw-bold text-end">P. Unitario</th>
                                                <th class="small fw-bold text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($insumos as $ins)
                                                @php
                                                    $sub = $ins->cantidad * ($ins->precio_unitario ?? 0);
                                                    $totalAcumulado += $sub;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <strong class="text-dark">{{ $ins->nombre }}</strong>
                                                        <div class="x-small text-muted">{{ $ins->fecha_registro ? \Carbon\Carbon::parse($ins->fecha_registro)->format('d/m/Y H:i') : 'Sin fecha' }}</div>
                                                    </td>
                                                    <td class="text-center fw-bold">{{ number_format($ins->cantidad, 2) }}</td>
                                                    <td class="text-end">${{ number_format($ins->precio_unitario ?? 0, 2) }}</td>
                                                    <td class="text-end fw-bold text-primary">${{ number_format($sub, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-muted">Valor de Consumos Clínicos:</span>
                                    <span class="fs-4 fw-extrabold text-success">$ {{ number_format($totalAcumulado, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PESTAÑA 4: TRASLADOS --}}
                <div class="tab-pane fade" id="traslados" role="tabpanel">
                    <div class="row g-4">
                        {{-- FORMULARIO TRASLADO --}}
                        <div class="col-lg-5">
                            <div class="p-4 border rounded-4 bg-light border-warning">
                                <h6 class="fw-bold text-warning mb-3"><i class="bi bi-arrow-left-right me-1"></i> Realizar Traslado Clínico / Físico</h6>
                                <form action="{{ route('empresa.hospitalization.transfer', $hospitalization->id) }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Seleccionar Nueva Cama Libre</label>
                                        <select name="cama_id" class="form-select shadow-sm" required>
                                            <option value="" disabled selected>Elige cama de destino...</option>
                                            @foreach($availableBeds as $bed)
                                                <option value="{{ $bed->id }}">
                                                    {{ $bed->room->nombre }} — {{ $bed->nombre }} ({{ str_replace('_', ' ', $bed->room->tipo) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Fecha y Hora de Traslado</label>
                                        <input type="datetime-local" name="fecha_traslado" value="{{ now()->format('Y-m-d\TH:i') }}" class="form-control shadow-sm" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Notas / Motivo del Traslado</label>
                                        <textarea name="notas" rows="3" class="form-control shadow-sm" placeholder="Ej: Traslado a Terapia Intensiva por descompensación respiratoria..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-warning text-dark w-100 fw-bold rounded-pill shadow-sm">
                                        Confirmar Traslado
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- TIMELINE HISTORICO DE TRASLADOS --}}
                        <div class="col-lg-7">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-map me-1 text-warning"></i> Historial de Estancia / Camas Asignadas</h5>
                            
                            <div class="timeline-container">
                                @foreach($hospitalization->assignments as $index => $ass)
                                    <div class="card mb-3 border-start border-4 border-warning shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <strong class="text-dark">{{ $ass->bed->room->nombre ?? 'N/A' }} — Cama {{ $ass->bed->nombre ?? 'N/A' }}</strong>
                                                <span class="badge bg-light text-dark border small text-uppercase">{{ str_replace('_', ' ', $ass->tipo_internacion) }}</span>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                Desde: <strong>{{ $ass->fecha_desde ? \Carbon\Carbon::parse($ass->fecha_desde)->format('d/m/Y H:i') : 'N/A' }}</strong> | 
                                                Hasta: <strong>{{ $ass->fecha_hasta ? \Carbon\Carbon::parse($ass->fecha_hasta)->format('d/m/Y H:i') : 'Actualidad' }}</strong>
                                            </div>
                                            @if($ass->notas)
                                                <p class="mb-0 small text-muted font-monospace italic">Nota: {{ $ass->notas }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PESTAÑA 5: ALTA MÉDICA --}}
                <div class="tab-pane fade" id="alta" role="tabpanel">
                    <div class="p-4 p-md-5 border rounded-4 bg-light border-danger text-center" style="max-width: 700px; margin: 0 auto;">
                        <div class="display-3 text-danger mb-3">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <h4 class="fw-bold text-danger">Registrar Egreso / Alta Médica</h4>
                        <p class="text-muted small mb-4">Esta acción registrará el alta médica del paciente, cerrará el expediente de internación y liberará la cama clínica ocupada en el sistema.</p>
                        
                        <form action="{{ route('empresa.hospitalization.discharge', $hospitalization->id) }}" method="POST">
                            @csrf
                            <div class="row g-3 text-start mb-4">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-dark">Fecha y Hora de Egreso / Alta</label>
                                    <input type="datetime-local" name="fecha_egreso" value="{{ now()->format('Y-m-d\TH:i') }}" class="form-control shadow-sm border-2 border-danger border-opacity-25" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-dark">Diagnóstico de Alta / Epicrisis Resumida</label>
                                    <textarea name="diagnostico_egreso" rows="4" class="form-control shadow-sm border-2 border-danger border-opacity-25" placeholder="Escribe el diagnóstico definitivo de egreso, estado de salud del paciente al egresar y pautas de alarma..." required></textarea>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold rounded-pill shadow-sm" onsubmit="return confirm('¿Está seguro de emitir el Alta Médica? La cama será liberada.');">
                                <i class="bi bi-check-circle-fill"></i> Emitir Alta Médica y Liberar Cama
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    let dynamicFields = {};

    function addDynamicFieldInpatient() {
        const keyInput = document.getElementById('dyn_key');
        const valInput = document.getElementById('dyn_value');
        const key = keyInput.value.trim();
        const val = valInput.value.trim();

        if (!key || !val) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor ingresa un nombre de propiedad y su valor.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500
            });
            return;
        }

        dynamicFields[key] = val;
        renderDynamicFields();

        keyInput.value = '';
        valInput.value = '';
        keyInput.focus();
    }

    function removeDynamicFieldInpatient(key) {
        delete dynamicFields[key];
        renderDynamicFields();
    }

    function renderDynamicFields() {
        const container = document.getElementById('dynamicFieldsContainerInpatient');
        container.innerHTML = '';
        Object.entries(dynamicFields).forEach(([key, val]) => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-light text-dark border px-2 py-1 rounded-pill x-small me-1 mb-1';
            badge.innerHTML = `<strong>${key}:</strong> ${val} <i class="bi bi-x-circle text-danger ms-1 cursor-pointer" style="font-size: 0.85rem; vertical-align: middle;" onclick="removeDynamicFieldInpatient('${key}')"></i>`;
            container.appendChild(badge);
        });
    }

    document.getElementById('inpatientControlForm').addEventListener('submit', function(e) {
        const evolucion = document.getElementById('control_evolucion').value.trim();
        
        const vitals = {
            temp: document.getElementById('vital_temp').value || '',
            heart: document.getElementById('vital_heart').value || '',
            sys: document.getElementById('vital_sys').value || '',
            dia: document.getElementById('vital_dia').value || '',
            sat: document.getElementById('vital_sat').value || '',
            glucose: document.getElementById('vital_glucose').value || '',
            weight: document.getElementById('vital_weight').value || ''
        };

        const labs = {
            hemo: document.getElementById('lab_hemo').value || '',
            red: document.getElementById('lab_red').value || '',
            white: document.getElementById('lab_white').value || '',
            platelets: document.getElementById('lab_platelets').value || ''
        };

        const payload = {
            evolucion: evolucion,
            vitals: vitals,
            labs: labs,
            dynamic_fields: dynamicFields
        };

        document.getElementById('notas_serialized').value = JSON.stringify(payload);
    });

    function copyToClipboardInInpatient(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const icon = btn.querySelector('i');
            icon.classList.replace('bi-clipboard', 'bi-check-lg');
            icon.classList.add('text-success');
            setTimeout(() => {
                icon.classList.replace('bi-check-lg', 'bi-clipboard');
                icon.classList.remove('text-success');
            }, 2000);
        });
    }
</script>
@endsection
