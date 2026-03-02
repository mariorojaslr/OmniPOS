@extends('layouts.empresa')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN VISUAL INSTITUCIONAL
|--------------------------------------------------------------------------
*/
$config = $empresa->configuracion ?? null;

$primary   = $config->color_primario   ?? '#2563eb';
$secondary = $config->color_secundario ?? '#16a34a';
@endphp


<style>
/* =========================================================
   MEJORA VISUAL RESPONSIVE SIN ROMPER NADA
========================================================= */

.dashboard-container {
    width: 100%;
    max-width: 1600px; /* ← ahora usa mejor monitores grandes */
    margin: auto;
}

.card {
    border-radius: 14px;
}

.dashboard-title {
    font-weight: 700;
}

.section-divider {
    margin: 40px 0;
    opacity: .15;
}
</style>



<div class="dashboard-container">

{{-- ======================================================
    CABECERA EMPRESA
====================================================== --}}
<div class="mb-4">
    <h1 class="fw-bold dashboard-title">
        {{ $empresa->nombre_comercial }}
    </h1>
    <p class="text-muted mb-0">Panel de control de la empresa</p>
</div>



{{-- ======================================================
    BLOQUE 1 · COMERCIAL
====================================================== --}}
<div class="row g-4">

    {{-- Ventas hoy --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ventas hoy</small>
                <h2 class="fw-bold text-success">
                    $ {{ number_format($ventasHoy, 2) }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Ventas del mes --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ventas del mes</small>
                <h2 class="fw-bold text-success">
                    $ {{ number_format($ventasMes, 2) }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Facturas hoy --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Facturas hoy</small>
                <h2 class="fw-bold text-primary">
                    {{ $cantidadVentasHoy }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Estado comercial --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Estado comercial</small>
                <h4 class="fw-bold text-success">Óptimo</h4>
                <small class="text-muted">Rendimiento normal</small>
            </div>
        </div>
    </div>

</div>

<hr class="section-divider">



{{-- ======================================================
    BLOQUE 2 · GESTIÓN DEL NEGOCIO
====================================================== --}}
<div class="row g-4">

    {{-- Usuarios --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 text-center h-100">
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


    {{-- CLIENTES ACTIVADO --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <h6 class="text-muted">Clientes / Ctas Ctes</h6>

                {{-- CONTADOR REAL --}}
                <h3 class="fw-bold">
                    {{ $clientesCount ?? 0 }}
                </h3>

                <a href="{{ route('empresa.clientes.index') }}"
                   class="btn btn-sm mt-2"
                   style="background: {{ $primary }}; color:white;">
                    Ver clientes
                </a>
            </div>
        </div>
    </div>


    {{-- REPORTES --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <h6 class="text-muted">Reportes de ventas</h6>
                <h3 class="fw-bold">Panel</h3>

                <a href="{{ route('empresa.reportes.panel') }}"
                   class="btn btn-success">
                    Ver reportes
                </a>
            </div>
        </div>
    </div>


    {{-- Listas de precios --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <h6 class="text-muted">Listas de precios</h6>
                <h3 class="fw-bold">--</h3>
                <span class="badge bg-secondary">Próximamente</span>
            </div>
        </div>
    </div>

</div>

<hr class="section-divider">



{{-- ======================================================
    BLOQUE 3 · OPERATIVO
====================================================== --}}
<div class="row g-4">

    {{-- Productos cargados --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Productos cargados</small>
                <h2 class="fw-bold">{{ $productosCount }}</h2>
            </div>
        </div>
    </div>

    {{-- Stock bajo --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Stock bajo</small>
                <h2 class="fw-bold text-warning">{{ $stockBajo }}</h2>
            </div>
        </div>
    </div>

    {{-- Ranking productos --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ranking productos</small>
                <h4 class="fw-bold">--</h4>
                <span class="badge bg-secondary">Próximamente</span>
            </div>
        </div>
    </div>

    {{-- Ranking vendedores --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ranking vendedores</small>
                <h4 class="fw-bold">--</h4>
                <span class="badge bg-secondary">Próximamente</span>
            </div>
        </div>
    </div>

</div>

<hr class="section-divider">



{{-- ======================================================
    BLOQUE 4 · RECURSOS CONTRATADOS
====================================================== --}}
<div class="row g-4">

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Recursos contratados</h5>

                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <small class="text-muted">Plan</small>
                        <h5 class="fw-bold">Profesional</h5>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <small class="text-muted">Facturas usadas</small>
                        <h5 class="fw-bold">420 / 1000</h5>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <small class="text-muted">Productos</small>
                        <h5 class="fw-bold">{{ $productosCount }} / 200</h5>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <small class="text-muted">Espacio</small>
                        <h5 class="fw-bold">120MB / 500MB</h5>
                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
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

</div>

@endsection
