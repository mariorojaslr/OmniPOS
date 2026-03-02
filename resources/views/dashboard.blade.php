@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold text-primary">
        {{ auth()->user()->empresa->nombre_comercial ?? 'Panel Empresa' }}
    </h1>
    <p class="text-muted mb-0">Panel de control de la empresa</p>
</div>

<div class="row g-3">

    <!-- POS -->
    <div class="col-md-3">
        <a href="{{ route('empresa.pos.index') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 text-center p-3">
                <h5>🧾 POS</h5>
                <small class="text-muted">Ventas en tiempo real</small>
            </div>
        </a>
    </div>

    <!-- PRODUCTOS -->
    <div class="col-md-3">
        <a href="{{ route('empresa.products.index') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 text-center p-3">
                <h5>📦 Productos</h5>
                <small class="text-muted">Gestión de catálogo</small>
            </div>
        </a>
    </div>

    <!-- CLIENTES -->
    <div class="col-md-3">
        <a href="{{ route('empresa.clientes.index') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 text-center p-3">
                <h5>👥 Clientes</h5>
                <small class="text-muted">Cuentas corrientes</small>
            </div>
        </a>
    </div>

    <!-- REPORTES -->
    <div class="col-md-3">
        <a href="{{ route('empresa.reportes.panel') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 text-center p-3">
                <h5>📊 Reportes</h5>
                <small class="text-muted">Estadísticas</small>
            </div>
        </a>
    </div>

</div>

@endsection
