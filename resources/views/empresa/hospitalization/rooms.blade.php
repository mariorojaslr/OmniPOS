@extends('layouts.empresa')

@section('content')
<div class="container px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">⚙️ Configuración de Habitaciones y Camas</h4>
            <p class="text-muted small mb-0">Crea, edita y administra la infraestructura física y complejidad de tu centro de internación.</p>
        </div>
        <div>
            <a href="{{ route('empresa.hospitalization.dashboard') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver al Mapa de Camas
            </a>
        </div>
    </div>

    {{-- ALERTAS --}}
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

    <div class="row g-4">
        {{-- COLUMNA IZQUIERDA: FORMULARIOS DE REGISTRO --}}
        <div class="col-lg-5">
            {{-- FORMULARIO HABITACIÓN --}}
            <div class="card shadow border-0 mb-4 bg-white rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-door-open-fill me-2"></i>Nueva Habitación</h5>
                    <form action="{{ route('empresa.hospitalization.rooms.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nombre / Identificador</label>
                            <input type="text" name="nombre" class="form-control shadow-sm" placeholder="Ej: Habitación 101, Piso 1 - UTI" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nivel de Complejidad</label>
                            <select name="tipo" class="form-select shadow-sm" required>
                                <option value="comun">Internación Común (General)</option>
                                <option value="terapia_intensiva">Terapia Intensiva (UCI / UTI)</option>
                                <option value="terapia_intermedia">Terapia Intermedia</option>
                                <option value="quirofano">Quirófano / Cirugía</option>
                            </select>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary fw-bold w-100 rounded-pill shadow-sm">
                                <i class="bi bi-plus-circle"></i> Agregar Habitación
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- FORMULARIO CAMA --}}
            <div class="card shadow border-0 bg-white rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-success mb-3"><i class="bi bi-hospital me-2"></i>Nueva Cama Clínica</h5>
                    <form action="{{ route('empresa.hospitalization.beds.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Habitación Destino</label>
                            <select name="habitacion_id" class="form-select shadow-sm" required>
                                <option value="" disabled selected>Seleccione la habitación...</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->nombre }} ({{ str_replace('_', ' ', $room->tipo) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nombre / Número de Cama</label>
                            <input type="text" name="nombre" class="form-control shadow-sm" placeholder="Ej: Cama A, Cama 1, Cama 202-B" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success fw-bold w-100 rounded-pill shadow-sm text-white">
                                <i class="bi bi-plus-circle"></i> Agregar Cama
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: INFRAESTRUCTURA DE HABITACIONES Y CAMAS --}}
        <div class="col-lg-7">
            <div class="card shadow border-0 bg-white rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-layers-half me-2"></i>Habitaciones y Camas Existentes</h5>
                    
                    @if($rooms->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-door-closed display-4 opacity-25 d-block mb-3"></i>
                            No hay habitaciones registradas en este inquilino todavía.
                        </div>
                    @else
                        <div class="accordion" id="roomsAccordion">
                            @foreach($rooms as $room)
                                <div class="accordion-item border rounded-3 mb-3 overflow-hidden shadow-sm">
                                    <h2 class="accordion-header" id="heading-{{ $room->id }}">
                                        <button class="accordion-button collapsed px-4 py-3 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $room->id }}">
                                            <div class="w-100 d-flex align-items-center justify-content-between pe-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <i class="bi bi-door-open-fill fs-5 text-primary"></i>
                                                    <div>
                                                        <span class="fw-bold text-dark fs-6">{{ $room->nombre }}</span>
                                                        <span class="badge bg-secondary ms-2 small rounded text-uppercase">{{ str_replace('_', ' ', $room->tipo) }}</span>
                                                    </div>
                                                </div>
                                                <span class="badge bg-primary rounded-pill px-2 py-1 small">{{ $room->beds->count() }} camas</span>
                                            </div>
                                        </button>
                                    </h2>
                                    
                                    <div id="collapse-{{ $room->id }}" class="accordion-collapse collapse" data-bs-parent="#roomsAccordion">
                                        <div class="accordion-body bg-white p-4">
                                            
                                            {{-- DETALLES Y ACCIONES DE HABITACIÓN --}}
                                            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-light flex-wrap gap-2">
                                                <div>
                                                    <span class="small text-muted">Estado de Habitación:</span>
                                                    <span class="badge {{ $room->activo ? 'bg-success' : 'bg-danger' }} ms-1">
                                                        {{ $room->activo ? 'Activa' : 'Inactiva' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    <form action="{{ route('empresa.hospitalization.rooms.toggle', $room->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary fw-bold rounded-pill px-3">
                                                            {{ $room->activo ? 'Desactivar' : 'Activar' }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('empresa.hospitalization.rooms.delete', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta habitación y todas sus camas asociadas? Esta acción no se puede deshacer.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- LISTADO DE CAMAS DE ESTA HABITACIÓN --}}
                                            <h6 class="fw-bold text-muted small mb-3">LISTADO DE CAMAS CLINICAS:</h6>
                                            @if($room->beds->isEmpty())
                                                <p class="text-muted small italic my-2">No hay camas cargadas en esta habitación.</p>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-hover align-middle border">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="small fw-bold py-2">Identificador</th>
                                                                <th class="small fw-bold py-2 text-center">Estado</th>
                                                                <th class="small fw-bold py-2 text-end">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($room->beds as $bed)
                                                                <tr>
                                                                    <td class="fw-bold text-dark py-2">
                                                                        <i class="bi bi-hospital text-muted me-2"></i> {{ $bed->nombre }}
                                                                    </td>
                                                                    <td class="text-center py-2">
                                                                        @php
                                                                            $badgeClass = 'bg-success';
                                                                            if ($bed->estado === 'ocupada') { $badgeClass = 'bg-danger'; }
                                                                            elseif ($bed->estado === 'mantenimiento') { $badgeClass = 'bg-warning text-dark'; }
                                                                        @endphp
                                                                        <span class="badge {{ $badgeClass }} small rounded">{{ $bed->estado }}</span>
                                                                    </td>
                                                                    <td class="text-end py-2">
                                                                        @if($bed->estado !== 'ocupada')
                                                                            <form action="{{ route('empresa.hospitalization.beds.toggle_mantenimiento', $bed->id) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                <button type="submit" class="btn btn-link text-warning p-1" title="Mantenimiento / Habilitar">
                                                                                    <i class="bi bi-wrench"></i>
                                                                                </button>
                                                                            </form>
                                                                            <form action="{{ route('empresa.hospitalization.beds.delete', $bed->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta cama?');">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-link text-danger p-1" title="Eliminar Cama">
                                                                                    <i class="bi bi-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        @else
                                                                            <span class="text-muted small italic">Cama Ocupada</span>
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
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
