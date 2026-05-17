@extends('layouts.reseller')

@section('content')
<div class="container py-5">
    <div class="mb-5 d-flex justify-content-between align-items-end">
        <div>
            <h2 class="fw-800 text-white mb-0">Reporte de Actividad: {{ $empresa->nombre_comercial }}</h2>
            <p class="text-white opacity-50">Monitoreo de métricas clave y uso del sistema.</p>
        </div>
        <a href="{{ route('revendedor.dashboard') }}" class="btn btn-outline-info rounded-pill px-4 fw-bold">
            <i class="bi bi-arrow-left me-2"></i> Volver al Panel
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-dark border-secondary rounded-4 p-4 text-center">
                <div class="text-info fs-1 mb-2"><i class="bi bi-people-fill"></i></div>
                <h3 class="text-white fw-bold mb-0">{{ $empresa->users()->count() }}</h3>
                <p class="text-muted small text-uppercase mb-0">Usuarios Registrados</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark border-secondary rounded-4 p-4 text-center">
                <div class="text-success fs-1 mb-2"><i class="bi bi-cart-check-fill"></i></div>
                <h3 class="text-white fw-bold mb-0">{{ $empresa->ventas()->count() }}</h3>
                <p class="text-muted small text-uppercase mb-0">Operaciones Totales</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark border-secondary rounded-4 p-4 text-center">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-hdd-network-fill"></i></div>
                <h3 class="text-white fw-bold mb-0">128 MB</h3>
                <p class="text-muted small text-uppercase mb-0">Espacio en Nube (Estudios)</p>
            </div>
        </div>
    </div>

    <div class="card bg-dark border-secondary rounded-4 p-5 text-center" style="border-style: dashed;">
        <i class="bi bi-bar-chart-line text-muted opacity-25 fs-1 mb-3"></i>
        <h4 class="text-white fw-bold">Análisis de Crecimiento en Proceso</h4>
        <p class="text-muted mx-auto" style="max-width: 500px;">
            Estamos procesando los datos históricos de <b>{{ $empresa->nombre_comercial }}</b> para mostrarte gráficos de ingresos y uso de módulos. 
            Próximamente verás aquí el desglose detallado de rentabilidad.
        </p>
    </div>
</div>

<style>
    body { background-color: #000 !important; }
</style>
@endsection
