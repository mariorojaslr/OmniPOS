@extends('layouts.empresa')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN VISUAL INSTITUCIONAL
|--------------------------------------------------------------------------
*/
$config = $empresa->config ?? null;

$primary   = $config->color_primario   ?? '#2563eb';
$secondary = $config->color_secundario ?? '#16a34a';
@endphp

<style>
/* =========================================================
   GLASSMORPHISM PREMIUM - EMPRESA ADMIN DASHBOARD
========================================================= */

.dashboard-container {
    width: 100%;
    max-width: 1600px;
    margin: auto;
    padding-bottom: 2rem;
}

/* Background suave animado sutil basado en el color primario de la empresa */
.empresa-bg {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: -1;
    background: radial-gradient(circle at 10% 20%, {{ $primary }}15, transparent 35%),
                radial-gradient(circle at 90% 80%, {{ $secondary }}15, transparent 35%);
    animation: bgPulse 12s infinite alternate ease-in-out;
}

@keyframes bgPulse {
    0% { transform: scale(1); }
    100% { transform: scale(1.1); }
}

.header-title {
    font-weight: 800;
    letter-spacing: -0.5px;
    color: var(--bs-heading-color, inherit);
}

.glass-panel {
    background: rgba(var(--bs-body-bg-rgb), 0.65);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(128, 128, 128, 0.15);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.glass-panel:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 45px -10px rgba(0,0,0,0.12);
}

.glass-icon {
    position: absolute;
    top: -10px;
    right: -10px;
    font-size: 6rem;
    opacity: 0.45; /* Aumentada considerablemente la visibilidad */
    transform: rotate(-10deg);
}

.stat-label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.stat-value {
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1.1;
}

.section-divider {
    margin: 30px 0;
    border-color: rgba(128, 128, 128, 0.1);
}

/* Botones Premium */
.btn-glass {
    background: rgba(var(--bs-body-bg-rgb), 0.4);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(128, 128, 128, 0.2);
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}
.btn-glass:hover {
    background: {{ $primary }}20;
    border-color: {{ $primary }}50;
    color: {{ $primary }};
}

.badge-glow {
    box-shadow: 0 0 10px rgba(108, 117, 125, 0.4);
}
</style>

{{-- Fondo animado inyectado --}}
<div class="empresa-bg"></div>

<div class="dashboard-container">

    {{-- ======================================================
        CABECERA (ADMIN EMPRESA)
    ====================================================== --}}
    <div class="mb-5 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="header-title mb-1">
                Panel de Administración: <span style="color: {{ $primary }};">{{ $empresa->nombre_comercial }}</span>
            </h2>
            <p class="text-muted mb-0">Control integral de sucursales, ventas y reportes operativos.</p>
        </div>
    </div>

    {{-- ======================================================
        NOTIFICACIONES / TAREAS PENDIENTES
    ====================================================== --}}
    @if(count($reminders) > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h5 class="fw-bold mb-3 text-secondary">Acciones Sugeridas</h5>
            <div class="row g-3">
                @foreach($reminders as $rem)
                <div class="col-md-6">
                    <div class="glass-panel" style="border-left: 5px solid {{ $rem['type'] == 'warning' ? '#f59e0b' : '#3b82f6' }}; padding: 1rem 1.5rem;">
                        <div class="d-flex align-items-center">
                            <div class="fs-2 me-3">{{ $rem['icon'] }}</div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 {{ $rem['type'] == 'warning' ? 'text-warning' : 'text-primary' }}">
                                    {{ $rem['title'] }}
                                </h6>
                                <p class="small mb-2 text-muted">{{ $rem['message'] }}</p>
                                <a href="{{ $rem['link'] }}" class="btn btn-sm py-1 px-3" style="background: {{ $rem['type'] == 'warning' ? '#f59e0b' : '#3b82f6' }}; color: white; border-radius: 8px; font-size: 0.75rem;">
                                    {{ $rem['btn'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif


    {{-- ======================================================
        BLOQUE 1 · MÉTRICAS COMERCIALES (HOY/MES)
    ====================================================== --}}
    <h5 class="fw-bold mb-3 text-secondary">Rendimiento Comercial</h5>
    <div class="row g-4 mb-4">

        {{-- Ventas hoy --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel" style="border-left: 4px solid #22c55e;">
                <div class="glass-icon text-success">💰</div>
                <div class="stat-label">Ventas Hoy</div>
                <div class="stat-value text-success">
                    $ {{ number_format($ventasHoy, 2) }}
                </div>
            </div>
        </div>

        {{-- Ventas del mes --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel" style="border-left: 4px solid {{ $primary }};">
                <div class="glass-icon" style="color: {{ $primary }};">📊</div>
                <div class="stat-label">Ventas del Mes</div>
                <div class="stat-value" style="color: {{ $primary }};">
                    $ {{ number_format($ventasMes, 2) }}
                </div>
            </div>
        </div>

        {{-- Facturas hoy --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel" style="border-left: 4px solid #8b5cf6;">
                <div class="glass-icon" style="color: #8b5cf6;">🧾</div>
                <div class="stat-label">Operaciones (Hoy)</div>
                <div class="stat-value" style="color: #8b5cf6;">
                    {{ $cantidadVentasHoy }}
                </div>
            </div>
        </div>

        {{-- Estado comercial --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel" style="border-left: 4px solid #14b8a6;">
                <div class="glass-icon" style="color: #14b8a6;">📈</div>
                <div class="stat-label">Estado del Sistema</div>
                <div class="stat-value text-teal" style="color: #14b8a6; font-size: 1.8rem;">
                    Óptimo
                </div>
                <div class="small text-muted mt-1">Conexión y BD estables</div>
            </div>
        </div>

    </div>

    <hr class="section-divider">

    {{-- ======================================================
        BLOQUE 2 · GESTIÓN DEL NEGOCIO
    ====================================================== --}}
    <h5 class="fw-bold mb-3 text-secondary">Recursos y Gestión</h5>
    <div class="row g-4 mb-4">

        {{-- Usuarios --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4">
                <div class="stat-label">Empleados y Cajeros</div>
                <div class="stat-value mb-3">{{ $usuariosCount }}</div>
                <a href="{{ route('empresa.usuarios.index') }}" class="btn btn-sm btn-glass px-4">
                    Administrar
                </a>
            </div>
        </div>

        {{-- CLIENTES ACTIVADO --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4">
                <div class="stat-label">Cartera de Clientes</div>
                <div class="stat-value mb-3">{{ $clientesCount ?? 0 }}</div>
                <a href="{{ route('empresa.clientes.index') }}" class="btn btn-sm px-4 text-white" style="background: {{ $primary }};">
                    Cuentas Corrientes
                </a>
            </div>
        </div>

        {{-- PEDIDOS CATALOGO NUEVO --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4" style="border: 2px solid {{ $pedidosPendientes > 0 ? '#f59e0b' : 'transparent' }};">
                <div class="stat-label">Ventas por Catálogo</div>
                <div class="stat-value mb-1 {{ $pedidosPendientes > 0 ? 'text-warning' : '' }}">
                    {{ $pedidosPendientes }}
                </div>
                <div class="small text-muted mb-3">Pedidos Pendientes</div>
                <a href="{{ route('empresa.orders.index') }}" class="btn btn-sm btn-dark w-100 mb-1">
                    Gestionar Pedidos
                </a>
                <div class="x-small text-muted" style="font-size: 0.7rem;">{{ $pedidosTotales }} totales registrados</div>
            </div>
        </div>

        {{-- REPORTES --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4">
                <div class="stat-label">Motor de Reportes</div>
                <div class="stat-value mb-3">5+</div>
                <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-sm btn-success px-4" style="background: {{ $secondary }}; border-color:{{ $secondary }};">
                    Generar PDF/Excel
                </a>
            </div>
        </div>

        {{-- Listas de precios --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4 opacity-75">
                <div class="stat-label">Listas de Precios</div>
                <div class="stat-value text-muted mb-2">--</div>
                <span class="badge bg-secondary badge-glow mt-2">Próximamente</span>
            </div>
        </div>

    </div>

    <hr class="section-divider">

    {{-- ======================================================
        BLOQUE 3 · INVENTARIO Y OPERATIVA
    ====================================================== --}}
    <div class="row g-4">
        <div class="col-md-8">
            <h5 class="fw-bold mb-3 text-secondary">Inventario</h5>
            <div class="row g-4">
                {{-- Productos cargados --}}
                <div class="col-sm-6">
                    <div class="glass-panel">
                        <div class="stat-label text-info">Productos en Catálogo</div>
                        <div class="fs-2 fw-bold">{{ $productosCount }}</div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar" style="width: {{ min(($productosCount/200)*100, 100) }}%; background: {{ $primary }};"></div>
                        </div>
                        <div class="small mt-1 text-muted">{{ $productosCount }} / 200 límite plan</div>
                    </div>
                </div>

                {{-- Stock bajo --}}
                <div class="col-sm-6">
                    <div class="glass-panel" style="border-left: 4px solid #f59e0b;">
                        <div class="glass-icon" style="color: #f59e0b;">⚠️</div>
                        <div class="stat-label text-warning">Alertas de Stock</div>
                        <div class="fs-2 fw-bold text-warning mb-2">{{ $stockBajo }}</div>
                        <p class="small text-muted mb-3">Productos por debajo del mínimo</p>
                        <a href="{{ route('empresa.stock.faltantes') }}" class="btn btn-sm btn-outline-warning w-100">
                            🤖 Gestionar Faltantes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <h5 class="fw-bold mb-3 text-secondary">Suscripción SaaS</h5>
            <div class="glass-panel d-flex flex-column justify-content-center">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold">Plan Actual:</span>
                    <span class="badge" style="background: {{ $primary }};">Profesional</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold">Vencimiento:</span>
                    <span class="fw-bold">{{ optional($empresa->fecha_vencimiento)->format('d/m/Y') ?? 'N/A' }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-bold">Storage S3 (Bunny):</span>
                    <span class="fw-bold text-success">Sincronizado</span>
                </div>
            </div>
        </div>
    </div>


</div>

@endsection
