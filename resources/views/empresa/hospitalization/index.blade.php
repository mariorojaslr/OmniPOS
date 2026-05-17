@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">📋 Historial Clínico de Internaciones</h4>
            <p class="text-muted small mb-0">Listado general y archivo histórico de todas las admisiones hospitalarias registradas.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.hospitalization.dashboard') }}" class="btn btn-outline-primary fw-bold shadow-sm">
                <i class="bi bi-grid-3x3-gap me-2"></i> Mapa de Camas
            </a>
            <a href="{{ route('empresa.hospitalization.create') }}" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-2"></i> Nuevo Ingreso
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

    {{-- BUSCADOR Y FILTRADO --}}
    <div class="card shadow-sm border-0 mb-4 bg-white rounded-3">
        <div class="card-body py-3">
            <form action="{{ route('empresa.hospitalization.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-9 col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 shadow-none" placeholder="Buscar por nombre de paciente, DNI, CUIL, o teléfono...">
                    </div>
                </div>
                <div class="col-md-3 col-sm-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill">
                        Buscar Paciente
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLA DE HISTORIAL --}}
    <div class="card shadow border-0 bg-white rounded-4 overflow-hidden mb-5">
        <div class="card-body p-0">
            @if($hospitalizations->isEmpty())
                <div class="text-center py-5 my-4 text-muted">
                    <i class="bi bi-clock-history display-3 opacity-25 d-block mb-3"></i>
                    No se encontraron internaciones registradas.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="px-4 py-3 small fw-bold">Paciente</th>
                                <th class="py-3 small fw-bold">Ubicación</th>
                                <th class="py-3 small fw-bold">Fecha Ingreso</th>
                                <th class="py-3 small fw-bold">Fecha Alta / Egreso</th>
                                <th class="py-3 small fw-bold">Diagnóstico Ingreso</th>
                                <th class="py-3 small fw-bold text-center">Estado</th>
                                <th class="px-4 py-3 small fw-bold text-end">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hospitalizations as $h)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-dark">{{ $h->patient->nombre }}</div>
                                        <small class="text-muted">Doc: {{ $h->patient->cuit_cuil ?? 'Sin Documento' }}</small>
                                    </td>
                                    <td class="py-3">
                                        @if($h->estado === 'activo' && $h->currentBedAssignment)
                                            <span class="text-dark fw-bold">
                                                <i class="bi bi-door-open text-primary me-1"></i>
                                                {{ $h->currentBedAssignment->bed->room->nombre ?? 'N/A' }} 
                                                ({{ $h->currentBedAssignment->bed->nombre ?? 'N/A' }})
                                            </span>
                                        @else
                                            <span class="text-muted italic small">Cama Liberada (Alta)</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-dark small">
                                        {{ $h->fecha_ingreso->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-3 small">
                                        @if($h->fecha_egreso)
                                            <span class="text-secondary">{{ $h->fecha_egreso->format('d/m/Y H:i') }}</span>
                                        @else
                                            <span class="text-danger fw-bold"><i class="bi bi-hourglass-split"></i> Internado</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-truncate small" style="max-width: 250px;" title="{{ $h->diagnostico_ingreso }}">
                                        {{ $h->diagnostico_ingreso }}
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($h->estado === 'activo')
                                            <span class="badge bg-success px-2 py-1 small rounded">Activa</span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1 small rounded">Alta Médica</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <a href="{{ route('empresa.hospitalization.dossier', $h->id) }}" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">
                                            <i class="bi bi-eye"></i> Ver Ficha
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- PAGINACIÓN --}}
                @if($hospitalizations->hasPages())
                    <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-center">
                        {{ $hospitalizations->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
