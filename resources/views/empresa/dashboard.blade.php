@extends('layouts.empresa')

@section('content')

{{-- ======================================================
    CABECERA EMPRESA
====================================================== --}}
<div class="mb-4">
    <h1 class="fw-bold">{{ $empresa->nombre_comercial }}</h1>
    <p class="text-muted mb-0">Panel de control de la empresa</p>
</div>


{{-- ======================================================
    BLOQUE 1 · COMERCIAL
====================================================== --}}
<div class="row g-4">

    {{-- Ventas hoy --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ventas hoy</small>
                <h2 class="fw-bold text-success">$ {{ number_format($ventasHoy, 2) }}</h2>
            </div>
        </div>
    </div>

    {{-- Ventas del mes --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ventas del mes</small>
                <h2 class="fw-bold text-success">$ {{ number_format($ventasMes, 2) }}</h2>
            </div>
        </div>
    </div>

    {{-- Facturas hoy --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Facturas hoy</small>
                <h2 class="fw-bold text-primary">{{ $cantidadVentasHoy }}</h2>
            </div>
        </div>
    </div>

    {{-- Estado comercial --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Estado comercial</small>
                <h4 class="fw-bold text-success">Óptimo</h4>
                <small class="text-muted">Rendimiento normal</small>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">


{{-- ======================================================
    BLOQUE 2 · GESTIÓN DEL NEGOCIO
====================================================== --}}
<div class="row g-4">

    {{-- Usuarios --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Usuarios</h6>
                <h3 class="fw-bold">{{ $usuariosCount }}</h3>

                <a href="{{ route('empresa.usuarios.index') }}"
                   class="btn btn-sm btn-outline-primary mt-2">
                    Gestionar
                </a>
            </div>
        </div>
    </div>

    {{-- Clientes --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Clientes / Ctas Ctes</h6>
                <h3 class="fw-bold">--</h3>
                <span class="badge bg-secondary">Próximamente</span>
            </div>
        </div>
    </div>

    {{-- 🔴 REPORTES ACTIVADO (RUTA CORRECTA) --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Reportes de ventas</h6>
                <h3 class="fw-bold">Panel</h3>

                {{-- IMPORTANTE: nombre real de la ruta --}}
                <a href="{{ route('empresa.reportes.panel') }}"
                    class="btn btn-success">
                    Ver reportes
                </a>

            </div>
        </div>
    </div>

    {{-- Listas de precios --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Listas de precios</h6>
                <h3 class="fw-bold">--</h3>
                <span class="badge bg-secondary">Próximamente</span>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">


{{-- ======================================================
    BLOQUE 3 · OPERATIVO
====================================================== --}}
<div class="row g-4">

    {{-- Productos cargados --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Productos cargados</small>
                <h2 class="fw-bold">{{ $productosCount }}</h2>
            </div>
        </div>
    </div>

    {{-- Stock bajo --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Stock bajo</small>
                <h2 class="fw-bold text-warning">{{ $stockBajo }}</h2>
            </div>
        </div>
    </div>

    {{-- Ranking productos --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ranking productos</small>
                <h4 class="fw-bold">--</h4>
                <span class="badge bg-secondary">Próximamente</span>
            </div>
        </div>
    </div>

    {{-- Ranking vendedores --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ranking vendedores</small>
                <h4 class="fw-bold">--</h4>
                <span class="badge bg-secondary">Próximamente</span>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">


{{-- ======================================================
    BLOQUE 4 · RECURSOS CONTRATADOS
====================================================== --}}
<div class="row g-4">

    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Recursos contratados</h5>

                <div class="row">

                    <div class="col-md-3">
                        <small class="text-muted">Plan</small>
                        <h5 class="fw-bold">Profesional</h5>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted">Facturas usadas</small>
                        <h5 class="fw-bold">420 / 1000</h5>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted">Productos</small>
                        <h5 class="fw-bold">{{ $productosCount }} / 200</h5>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted">Espacio</small>
                        <h5 class="fw-bold">120MB / 500MB</h5>
                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-warning fw-bold">
                        Cerca del límite — Recomendado upgrade
                    </span>
                    <button class="btn btn-sm btn-outline-primary">
                        Ver planes
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
