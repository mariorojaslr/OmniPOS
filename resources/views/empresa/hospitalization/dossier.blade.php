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
                                <form action="{{ route('empresa.hospitalization.administrations.store', $hospitalization->id) }}" method="POST">
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
                                        <label class="form-label small fw-bold text-muted">Anotaciones Clínicas (Signos vitales, evolución)</label>
                                        <textarea name="notas" rows="4" class="form-control shadow-sm" placeholder="Temperatura: 36.5 °C&#10;Presión: 120/80 mmHg&#10;Frecuencia Cardíaca: 75 lpm&#10;Notas de evolución..." required></textarea>
                                    </div>
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
                                <div class="timeline-container">
                                    @foreach($controles as $reg)
                                        <div class="card mb-3 border-0 bg-light shadow-sm">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-2 border-bottom border-light pb-2">
                                                    <span class="fw-bold text-primary small"><i class="bi bi-clipboard-pulse"></i> {{ $reg->nombre }}</span>
                                                    <span class="text-muted small fw-normal"><i class="bi bi-calendar3"></i> {{ $reg->fecha_registro->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <p class="mb-0 text-dark small font-monospace" style="white-space: pre-line;">{{ $reg->notas }}</p>
                                                <div class="text-end mt-2">
                                                    <small class="text-muted small">Registrado por: <strong>{{ $reg->user->nombre ?? 'Profesional' }}</strong></small>
                                                </div>
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
                                                    <td class="text-center small">{{ $med->fecha_registro->format('d/m H:i') }}</td>
                                                    <td class="text-center small">
                                                        @if($med->proxima_aplicacion)
                                                            <span class="text-danger fw-bold">{{ $med->proxima_aplicacion->format('d/m H:i') }}</span>
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
                                                        <div class="x-small text-muted">{{ $ins->fecha_registro->format('d/m/Y H:i') }}</div>
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
                                                Desde: <strong>{{ $ass->fecha_desde->format('d/m/Y H:i') }}</strong> | 
                                                Hasta: <strong>{{ $ass->fecha_hasta ? $ass->fecha_hasta->format('d/m/Y H:i') : 'Actualidad' }}</strong>
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
