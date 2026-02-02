@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold">{{ $empresa->nombre_comercial }}</h1>
    <p class="text-muted mb-0">Panel de control de la empresa</p>
</div>

<div class="row g-4">

    {{-- Usuarios --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Usuarios</small>
                <h2 class="fw-bold text-primary">{{ $usuariosCount }}</h2>
            </div>
        </div>
    </div>

    {{-- Artículos --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Artículos cargados</small>
                <h2 class="fw-bold text-dark">{{ $productosCount }}</h2>
            </div>
        </div>
    </div>

    {{-- Ventas hoy --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ventas hoy</small>
                <h2 class="fw-bold text-success">$ {{ number_format($ventasHoy, 2) }}</h2>
                <span class="badge bg-light text-muted">próximamente</span>
            </div>
        </div>
    </div>

    {{-- Ventas mes --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ventas del mes</small>
                <h2 class="fw-bold text-success">$ {{ number_format($ventasMes, 2) }}</h2>
                <span class="badge bg-light text-muted">próximamente</span>
            </div>
        </div>
    </div>

    {{-- Stock bajo --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Stock bajo</small>
                <h2 class="fw-bold text-warning">{{ $stockBajo }}</h2>
                <span class="badge bg-light text-muted">próximamente</span>
            </div>
        </div>
    </div>

    {{-- Vencimiento --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Vencimiento</small>
                <h4 class="fw-bold text-warning">
                    {{ \Carbon\Carbon::parse($empresa->fecha_vencimiento)->format('d/m/Y') }}
                </h4>
            </div>
        </div>
    </div>

    {{-- Estado --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Estado</small>
                <h4 class="fw-bold text-success">Activa</h4>
            </div>
        </div>
    </div>

    {{-- Canales --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Canales</small>
                <ul class="list-unstyled mb-0">
                    <li>🛒 Catálogo <span class="text-muted">(próx.)</span></li>
                    <li>🏪 POS <span class="text-muted">(próx.)</span></li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection
