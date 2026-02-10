@extends('layouts.empresa')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold text-primary">
        MI PANEL · USUARIO
    </h1>
    <p class="text-muted mb-0">Control personal del usuario</p>
</div>

{{-- ======================================================
BLOQUE 1 · MIS VENTAS
====================================================== --}}
<div class="row g-4">

    {{-- Ventas hoy --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Mis ventas hoy</small>
                <h2 class="fw-bold text-success">
                    $ {{ number_format($ventasHoy ?? 0, 2) }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Cantidad ventas hoy --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Cantidad hoy</small>
                <h2 class="fw-bold text-primary">
                    {{ $cantidadHoy ?? 0 }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Ventas mes --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Mis ventas mes</small>
                <h2 class="fw-bold text-success">
                    $ {{ number_format($ventasMes ?? 0, 2) }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Cantidad ventas mes --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Cantidad mes</small>
                <h2 class="fw-bold text-primary">
                    {{ $cantidadMes ?? 0 }}
                </h2>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">

{{-- ======================================================
BLOQUE 2 · OPERATIVO
====================================================== --}}
<div class="row g-4">

    {{-- Estado turno --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Estado turno</small>
                <h4 class="fw-bold text-success mb-1">Turno activo</h4>
                <small class="text-muted">(Próximamente control de caja)</small>
            </div>
        </div>
    </div>

    {{-- Caja --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Caja</small>
                <p class="text-muted mb-0">
                    Apertura / Cierre próximamente
                </p>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">

{{-- ======================================================
BLOQUE 3 · REPORTES (NUEVO)
====================================================== --}}
<div class="row g-4">

    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Reportes de ventas</h5>

                <div class="row g-3">

                    {{-- Ranking productos --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-bold">Ranking por productos</h6>
                            <p class="text-muted small mb-2">
                                Productos más vendidos
                            </p>
                            <span class="badge bg-secondary">
                                En construcción
                            </span>
                        </div>
                    </div>

                    {{-- Ranking clientes --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-bold">Ranking por clientes</h6>
                            <p class="text-muted small mb-2">
                                Clientes que más compran
                            </p>
                            <span class="badge bg-secondary">
                                En construcción
                            </span>
                        </div>
                    </div>

                    {{-- Ventas por fecha --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-bold">Ventas por fecha</h6>
                            <p class="text-muted small mb-2">
                                Listado filtrado por rango
                            </p>
                            <span class="badge bg-secondary">
                                En construcción
                            </span>
                        </div>
                    </div>

                </div>

                <hr>

                <small class="text-muted">
                    Próximamente podrás ver reportes en pantalla y exportarlos a PDF.
                </small>

            </div>
        </div>
    </div>

</div>

@endsection
