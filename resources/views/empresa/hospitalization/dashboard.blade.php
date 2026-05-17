@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">🏥 Mapa de Camas e Internaciones</h4>
            <p class="text-muted small mb-0">Monitoreo y administración en tiempo real de la ocupación y traslados de pacientes.</p>
        </div>
        <div class="d-flex gap-2 mt-2 mt-sm-0">
            <a href="{{ route('empresa.hospitalization.rooms') }}" class="btn btn-outline-primary fw-bold shadow-sm">
                <i class="bi bi-door-open me-2"></i> Configurar Camas
            </a>
            <a href="{{ route('empresa.hospitalization.create') }}" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-2"></i> Ingresar Paciente
            </a>
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

    {{-- FILTROS DE ESTADO --}}
    <div class="card shadow-sm border-0 mb-4 bg-white rounded-3">
        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <span class="small fw-bold text-muted me-2"><i class="bi bi-funnel"></i> Filtrar:</span>
                <button type="button" class="btn btn-sm btn-outline-secondary active px-3 rounded-pill filter-btn" data-filter="all">Todas</button>
                <button type="button" class="btn btn-sm btn-outline-success px-3 rounded-pill filter-btn" data-filter="libre">Libres</button>
                <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill filter-btn" data-filter="ocupada">Ocupadas</button>
                <button type="button" class="btn btn-sm btn-outline-warning px-3 rounded-pill filter-btn" data-filter="mantenimiento">Mantenimiento</button>
            </div>
            <div class="d-flex align-items-center gap-4 text-muted small">
                <div><span class="badge bg-success rounded-circle p-1 me-1">&nbsp;</span> Libres</div>
                <div><span class="badge bg-danger rounded-circle p-1 me-1">&nbsp;</span> Ocupadas</div>
                <div><span class="badge bg-warning rounded-circle p-1 me-1">&nbsp;</span> Mantenimiento</div>
            </div>
        </div>
    </div>

    @if($rooms->isEmpty())
        <div class="card shadow border-0 p-5 text-center bg-white rounded-4">
            <div class="my-4 text-muted">
                <i class="bi bi-hospital display-1 text-primary opacity-25"></i>
                <h5 class="fw-bold mt-3">Sin Infraestructura Registrada</h5>
                <p class="small text-muted mb-4">Primero debes crear habitaciones y registrar camas clínicas para comenzar a internar pacientes.</p>
                <a href="{{ route('empresa.hospitalization.rooms') }}" class="btn btn-primary fw-bold rounded-pill px-4 shadow">
                    <i class="bi bi-plus-circle me-2"></i> Crear Habitaciones y Camas
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($rooms as $room)
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100 shadow border-0 overflow-hidden bg-white rounded-4 transition-hover">
                        {{-- CABECERA DE HABITACIÓN SEGÚN TIPO --}}
                        @php
                            $bgClass = 'bg-primary';
                            $icon = 'bi-door-open-fill';
                            if ($room->tipo === 'terapia_intensiva') { $bgClass = 'bg-danger'; $icon = 'bi-activity'; }
                            elseif ($room->tipo === 'terapia_intermedia') { $bgClass = 'bg-warning text-dark'; $icon = 'bi-heart-pulse-fill'; }
                            elseif ($room->tipo === 'quirofano') { $bgClass = 'bg-info text-dark'; $icon = 'bi-scissors'; }
                        @endphp
                        <div class="card-header {{ $bgClass }} bg-gradient border-0 px-4 py-3 d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                <i class="bi {{ $icon }}"></i>
                                {{ $room->nombre }}
                            </h5>
                            <span class="badge bg-black bg-opacity-25 text-uppercase small" style="font-size: 0.75rem;">
                                {{ str_replace('_', ' ', $room->tipo) }}
                            </span>
                        </div>

                        <div class="card-body p-4 bg-light bg-opacity-25">
                            @if($room->beds->isEmpty())
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-slash-circle h4"></i>
                                    <p class="small mb-0">Sin camas asignadas</p>
                                    <a href="{{ route('empresa.hospitalization.rooms') }}" class="btn btn-link btn-sm text-primary p-0 mt-2">Agregar Cama</a>
                                </div>
                            @else
                                <div class="d-flex flex-column gap-3">
                                    @foreach($room->beds as $bed)
                                        @php
                                            $isOccupied = isset($occupiedBeds[$bed->id]);
                                            $activeH = $isOccupied ? $occupiedBeds[$bed->id] : null;
                                            
                                            $cardBorder = 'border-success';
                                            $badgeBg = 'bg-success';
                                            $stateLabel = 'Libre';
                                            if ($isOccupied) {
                                                $cardBorder = 'border-danger';
                                                $badgeBg = 'bg-danger';
                                                $stateLabel = 'Ocupada';
                                            } elseif ($bed->estado === 'mantenimiento') {
                                                $cardBorder = 'border-warning';
                                                $badgeBg = 'bg-warning text-dark';
                                                $stateLabel = 'Mantenimiento';
                                            }
                                        @endphp
                                        
                                        <div class="card bed-card border-start border-4 {{ $cardBorder }} shadow-sm bg-white rounded-3 p-3 transition-hover" data-state="{{ $isOccupied ? 'ocupada' : $bed->estado }}">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-circle p-2 bg-light text-muted">
                                                        <i class="bi bi-hospital fs-4" style="color: {{ $isOccupied ? '#dc3545' : ($bed->estado === 'mantenimiento' ? '#ffc107' : '#198754') }}"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-0 text-dark">{{ $bed->nombre }}</h6>
                                                        <span class="badge {{ $badgeBg }} px-2 py-1 small rounded" style="font-size: 0.7rem;">{{ $stateLabel }}</span>
                                                    </div>
                                                </div>

                                                {{-- BOTONES DE ACCIÓN DE CADA CAMA --}}
                                                <div>
                                                    @if($isOccupied && $activeH)
                                                        <a href="{{ route('empresa.hospitalization.dossier', $activeH->id) }}" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3">
                                                            <i class="bi bi-journal-medical"></i> Dossier
                                                        </a>
                                                    @elseif($bed->estado === 'mantenimiento')
                                                        <form action="{{ route('empresa.hospitalization.beds.toggle_mantenimiento', $bed->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary fw-bold rounded-pill px-3" title="Quitar de Mantenimiento">
                                                                <i class="bi bi-wrench"></i> Habilitar
                                                            </button>
                                                        </form>
                                                    @else
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ route('empresa.hospitalization.create', ['cama_id' => $bed->id]) }}" class="btn btn-sm btn-primary fw-bold rounded-pill px-3">
                                                                Internar
                                                            </a>
                                                            <form action="{{ route('empresa.hospitalization.beds.toggle_mantenimiento', $bed->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-link text-warning p-1" title="Poner en Mantenimiento">
                                                                    <i class="bi bi-wrench"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- MOSTRAR DETALLES DEL PACIENTE SI ESTÁ OCUPADA --}}
                                            @if($isOccupied && $activeH)
                                                <div class="mt-3 pt-3 border-top border-light">
                                                    <div class="row g-2">
                                                        <div class="col-12">
                                                            <span class="text-muted small">Paciente:</span>
                                                            <div class="fw-bold text-primary">{{ $activeH->patient->nombre ?? 'Desconocido' }}</div>
                                                        </div>
                                                        <div class="col-6">
                                                            <span class="text-muted small d-block">Ingreso:</span>
                                                            <strong class="small">{{ $activeH->fecha_ingreso->format('d/m/Y H:i') }}</strong>
                                                        </div>
                                                        <div class="col-6 text-end">
                                                            <span class="text-muted small d-block">Motivo:</span>
                                                            <span class="badge bg-light text-dark text-truncate d-block small fw-normal border" style="max-width: 140px;" title="{{ $activeH->motivo_ingreso }}">{{ $activeH->motivo_ingreso }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.transition-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.transition-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}
.bed-card {
    transition: all 0.2s ease-in-out;
}
.bed-card:hover {
    transform: scale(1.01);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const bedCards = document.querySelectorAll('.bed-card');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Quitar clase active
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            bedCards.forEach(card => {
                if (filter === 'all') {
                    card.closest('.bed-card').style.display = 'block';
                } else {
                    const state = card.getAttribute('data-state');
                    if (state === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });
});
</script>
@endsection
