@extends('layouts.empresa')

@section('content')

@php
$config = $empresa->configuracion ?? null;
$primary   = $config->color_primario   ?? '#2563eb';
$secondary = $config->color_secundario ?? '#16a34a';
@endphp

<style>
/* =========================================================
   GLASSMORPHISM PREMIUM - EMPLEADO/CAJERO DASHBOARD
========================================================= */

.dashboard-container {
    width: 100%;
    max-width: 1400px;
    margin: auto;
    padding-bottom: 2rem;
}

/* Fondo para el empleado, un poco más relajado y brillante */
.cajero-bg {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: -1;
    background: radial-gradient(circle at 80% 10%, {{ $secondary }}15, transparent 40%),
                radial-gradient(circle at 20% 90%, {{ $primary }}15, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(var(--bs-primary-rgb), 0.05), transparent 50%);
    animation: bgFloat 10s infinite alternate ease-in-out;
}

@keyframes bgFloat {
    0% { transform: scale(1) translate(0,0); }
    100% { transform: scale(1.05) translate(-15px, 15px); }
}

.header-title {
    font-weight: 800;
    letter-spacing: -0.5px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.glass-panel-user {
    background: rgba(var(--bs-body-bg-rgb), 0.65);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border: 1px solid rgba(128, 128, 128, 0.15);
    border-radius: 20px;
    padding: 1.8rem;
    box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
    position: relative;
    overflow: hidden;
}

.glass-panel-user::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 4px;
    background: linear-gradient(90deg, {{ $primary }}, {{ $secondary }});
    opacity: 0;
    transition: opacity 0.3s ease;
}

.glass-panel-user:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px -10px rgba(0,0,0,0.12);
}

.glass-panel-user:hover::before {
    opacity: 1;
}

.stat-label-user {
    font-size: 0.95rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value-user {
    font-size: 2.8rem;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -1.5px;
    background: linear-gradient(135deg, {{ $primary }}, {{ $secondary }});
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
}

/* Cajas de atajos directos */
.shortcut-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 1rem;
    border-radius: 20px;
    background: rgba(var(--bs-body-bg-rgb), 0.5);
    backdrop-filter: blur(10px);
    border: 1px dashed rgba(128, 128, 128, 0.3);
    color: var(--bs-body-color);
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
    overflow: hidden;
}

.shortcut-box::after {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    width: 0; height: 0;
    background: rgba(var(--bs-primary-rgb), 0.05);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
    z-index: -1;
}

.shortcut-box:hover::after {
    width: 300px;
    height: 300px;
}

.shortcut-box:hover {
    border-color: {{ $primary }};
    border-style: solid;
    transform: translateY(-5px);
    box-shadow: 0 15px 30px -10px rgba(var(--bs-primary-rgb), 0.2);
}

.shortcut-icon {
    font-size: 3rem;
    margin-bottom: 15px;
    transition: transform 0.3s ease;
}

.shortcut-box:hover .shortcut-icon {
    transform: scale(1.15) rotate(5deg);
}

.progress-container {
    background: rgba(128,128,128,0.1);
    border-radius: 10px;
    height: 8px;
    width: 100%;
    overflow: hidden;
    margin-top: 15px;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, {{ $primary }}, {{ $secondary }});
    border-radius: 10px;
    transition: width 1s ease-in-out;
}
</style>

{{-- Fondo inyectado --}}
<div class="cajero-bg"></div>

<div class="dashboard-container">

    {{-- CABECERA USUARIO --}}
    <div class="mb-5 d-flex justify-content-between align-items-center flex-wrap gap-3 glass-panel-user p-4" style="height: auto;">
        <div>
            <h2 class="header-title mb-2">
                Hola, <span style="background: linear-gradient(135deg, {{ $primary }}, {{ $secondary }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ auth()->user()->name }}</span> 👋
            </h2>
            <p class="text-muted mb-0 fs-5">Bienvenido a tu estación de trabajo en <strong class="text-body">{{ $empresa->nombre_comercial }}</strong>.</p>
        </div>
        
        <div>
            <a href="{{ route('empresa.pos.index') }}" class="btn text-white shadow-lg px-4 py-3 d-flex align-items-center gap-2" 
               style="background: linear-gradient(135deg, {{ $primary }}, {{ $secondary }}); border-radius: 15px; font-weight: 700; font-size: 1.1rem; border: none; transition: transform 0.2s ease;">
                <i class="fs-4">🛒</i> <span>Abrir Terminal POS</span>
            </a>
        </div>
    </div>


    {{-- BLOQUE 1 · MIS VENTAS GLOBALES --}}
    <div class="row g-4 mb-5">
        
        @php
            // Simularemos una meta diaria por ahora
            $metaDiaria = 10000;
            $ventasHoy = $ventasHoy ?? 6500; // Valor dummy para visualización
            $porcentajeMeta = min(($ventasHoy / $metaDiaria) * 100, 100);
        @endphp

        {{-- Ventas hoy con meta --}}
        <div class="col-md-6">
            <div class="glass-panel-user text-center d-flex flex-column justify-content-center">
                <div class="stat-label-user">Mi Recaudación (Hoy)</div>
                <div class="stat-value-user mt-2">
                    $ {{ number_format($ventasHoy, 2) }}
                </div>
                
                <div class="mt-4 px-3 w-100">
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>Progreso Meta Diaria</span>
                        <span class="fw-bold">{{ number_format($porcentajeMeta, 1) }}%</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $porcentajeMeta }}%;"></div>
                    </div>
                    <div class="text-end text-muted small mt-1">Meta: ${{ number_format($metaDiaria, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- Cantidad operaciones y resumen mes --}}
        <div class="col-md-6">
            <div class="row g-4 h-100">
                <div class="col-6">
                    <div class="glass-panel-user text-center d-flex flex-column justify-content-center">
                        <div class="stat-label-user">Operaciones (Hoy)</div>
                        <div class="stat-value-user mt-2" style="-webkit-text-fill-color: initial; color: {{ $primary }};">
                            {{ $cantidadHoy ?? 0 }}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="glass-panel-user text-center d-flex flex-column justify-content-center opacity-75">
                        <div class="stat-label-user">Operaciones (Mes)</div>
                        <div class="stat-value-user mt-2" style="-webkit-text-fill-color: initial; color: {{ $primary }};">
                            {{ $cantidadMes ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- BLOQUE 2 · ATAJOS OPERATIVOS Y ESTADO DE TURNO --}}
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-bold m-0" style="color: {{ $primary }};">Mi Turno y Atajos Rápidos</h4>
        <div class="flex-grow-1 ms-3" style="height: 1px; background: linear-gradient(90deg, rgba(var(--bs-primary-rgb), 0.2), transparent);"></div>
    </div>
    
    <div class="row g-4">
        
        {{-- Estado turno --}}
        <div class="col-md-6">
            <div class="glass-panel-user d-flex align-items-center justify-content-between" style="border-left: 5px solid {{ $secondary }};">
                <div>
                    <h5 class="fw-bold mb-2">Estado de Caja</h5>
                    <p class="text-muted mb-0">Gestión de apertura y cierres.</p>
                </div>
                <div class="text-end">
                    <div class="px-4 py-2 rounded-pill" style="background: rgba(22, 163, 74, 0.1); border: 1px solid rgba(22, 163, 74, 0.2);">
                        <h5 class="fw-bold text-success mb-0 d-flex align-items-center gap-2">
                            <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                            Turno Activo
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accesos Rápidos --}}
        <div class="col-md-3">
            <a href="{{ route('empresa.clientes.index') }}" class="shortcut-box">
                <div class="shortcut-icon" style="text-shadow: 0 5px 15px rgba(var(--bs-primary-rgb), 0.3);">👥</div>
                <h5 class="fw-bold m-0">Mis Clientes</h5>
                <p class="text-muted small mt-2 mb-0 text-center">Ver o registrar nuevos</p>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('empresa.reportes.panel') }}" class="shortcut-box">
                <div class="shortcut-icon" style="text-shadow: 0 5px 15px rgba(var(--bs-secondary-rgb), 0.3);">📊</div>
                <h5 class="fw-bold m-0">Mis Reportes</h5>
                <p class="text-muted small mt-2 mb-0 text-center">Rendimiento y comisiones</p>
            </a>
        </div>

    </div>

</div>

@endsection
