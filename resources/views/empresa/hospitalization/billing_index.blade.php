@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">💰 Reporte de Facturación de Internaciones</h4>
            <p class="text-muted small mb-0">Liquidación y facturación detallada por días de cama, insumos descartables, visitas médicas y medicaciones aplicadas.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.hospitalization.dashboard') }}" class="btn btn-outline-primary fw-bold shadow-sm">
                <i class="bi bi-grid-3x3-gap me-2"></i> Mapa de Camas
            </a>
        </div>
    </div>

    {{-- ALERTA --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- BUSCADOR --}}
    <div class="card shadow-sm border-0 mb-4 bg-white rounded-3">
        <div class="card-body py-3">
            <form action="{{ route('empresa.hospitalization.billing') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-9 col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 shadow-none" placeholder="Buscar liquidación por paciente, DNI, CUIL...">
                    </div>
                </div>
                <div class="col-md-3 col-sm-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill">
                        Filtrar Liquidaciones
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLA DE FACTURACIÓN --}}
    <div class="card shadow border-0 bg-white rounded-4 overflow-hidden mb-5">
        <div class="card-body p-0">
            @if($hospitalizations->isEmpty())
                <div class="text-center py-5 my-4 text-muted">
                    <i class="bi bi-currency-dollar display-3 opacity-25 d-block mb-3"></i>
                    No se encontraron liquidaciones de internación para facturar.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="px-4 py-3 small fw-bold">Paciente</th>
                                <th class="py-3 small fw-bold">Ubicación</th>
                                <th class="py-3 small fw-bold">Fecha Ingreso</th>
                                <th class="py-3 small fw-bold">Fecha Egreso</th>
                                <th class="py-3 small fw-bold">Renglones Logueados</th>
                                <th class="py-3 small fw-bold">Estado Clínico</th>
                                <th class="py-3 small fw-bold text-end">Total Liquidado ($)</th>
                                <th class="px-4 py-3 small fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hospitalizations as $h)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-dark">{{ $h->patient?->name ?? 'Paciente Desconocido' }}</div>
                                        <small class="text-muted">Doc: {{ $h->patient?->document ?? 'Sin Documento' }} | OS: {{ $h->patient?->obraSocial?->nombre ?? 'Particular' }}</small>
                                    </td>
                                    <td class="py-3">
                                        @if($h->estado === 'activo' && $h->currentBedAssignment)
                                            <span class="text-dark fw-bold">
                                                <i class="bi bi-door-open text-primary me-1"></i>
                                                {{ $h->currentBedAssignment?->bed?->room?->nombre ?? 'N/A' }} 
                                                <span class="text-muted font-normal">- Cama {{ $h->currentBedAssignment?->bed?->nombre ?? 'N/A' }}</span>
                                            </span>
                                        @else
                                            <span class="text-muted">Alta Médica (Egresado)</span>
                                        @endif
                                    </td>
                                    <td class="py-3 small text-muted">{{ $h->fecha_ingreso }}</td>
                                    <td class="py-3 small text-muted">{{ $h->fecha_egreso ?? 'En Curso' }}</td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-secondary bg-opacity-15 text-dark fw-bold rounded-pill px-3">
                                            {{ count($h->administrations) }} ítems
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        @if($h->estado === 'activo')
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 fw-bold rounded-pill">
                                                Internado Activo
                                            </span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 fw-bold rounded-pill">
                                                Alta Registrada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end fw-extrabold text-success fs-5">
                                        ${{ number_format($h->calculated_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('empresa.hospitalization.billing.report', $h->id) }}" class="btn btn-sm btn-primary fw-bold" title="Ver liquidación renglón por renglón">
                                                <i class="bi bi-file-earmark-spreadsheet-fill"></i> Liquidar
                                            </a>
                                            <a href="{{ route('empresa.hospitalization.billing.excel', $h->id) }}" class="btn btn-sm btn-outline-success" title="Descargar Excel/CSV">
                                                <i class="bi bi-file-earmark-excel"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="card-footer bg-white py-3 border-0">
                    {{ $hospitalizations->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
