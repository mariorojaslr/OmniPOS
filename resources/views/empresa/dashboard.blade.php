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

    {{-- Ventas hoy (Monto) --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ventas hoy</small>
                <h2 class="fw-bold text-success">
                    $ <span id="montoHoy">{{ number_format($ventasHoy, 2) }}</span>
                </h2>
            </div>
        </div>
    </div>

   {{-- Cantidad ventas hoy --}}
<div class="col-md-3">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
            <small class="text-muted">Cantidad ventas hoy</small>
            <h2 class="fw-bold text-primary">{{ $cantidadVentasHoy }}</h2>
        </div>
    </div>
</div>


    {{-- Ventas mes (Monto) --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <small class="text-muted">Ventas del mes</small>
                <h2 class="fw-bold text-success">
                    $ <span id="montoMes">{{ number_format($ventasMes, 2) }}</span>
                </h2>
            </div>
        </div>
    </div>

    {{-- Cantidad ventas mes --}}
<div class="col-md-3">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
            <small class="text-muted">Cantidad ventas mes</small>
            <h2 class="fw-bold text-primary">{{ $cantidadVentasMes }}</h2>
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

</div>

<script>
/*
======================================================
ACTUALIZACION AUTOMATICA DEL DASHBOARD (TIEMPO REAL)
======================================================
*/

async function actualizarDashboard() {
    try {
        const res = await fetch("{{ route('empresa.dashboard.resumen') }}");
        const data = await res.json();

        if (document.getElementById('ventasHoy'))
            document.getElementById('ventasHoy').innerText = data.ventas_hoy;

        if (document.getElementById('montoHoy'))
            document.getElementById('montoHoy').innerText = data.monto_hoy;

        if (document.getElementById('ventasMes'))
            document.getElementById('ventasMes').innerText = data.ventas_mes;

        if (document.getElementById('montoMes'))
            document.getElementById('montoMes').innerText = data.monto_mes;

    } catch (e) {
        console.log('Error actualizando dashboard');
    }
}

setInterval(actualizarDashboard, 3000);
actualizarDashboard();
</script>

@endsection
