@extends('layouts.empresa')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN VISUAL INSTITUCIONAL
|--------------------------------------------------------------------------
*/
$config = $empresa->config ?? null;

$primary   = $config?->color_primary   ?? '#2563eb';
$secondary = $config?->color_secondary ?? '#16a34a';
@endphp

<style>
/* =========================================================
   GLASSMORPHISM PREMIUM - EMPRESA ADMIN DASHBOARD
========================================================= */

.dashboard-wrapper {
    position: relative;
    padding: 0;
    margin-top: 180px !important; /* Margen masivo para forzar la bajada definitiva */
    z-index: 1;
}

/* Background suave animado — CONTENIDO dentro del dashboard, NO fixed */
.empresa-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0; 
    bottom: 0;
    z-index: -1;
    background: radial-gradient(circle at 10% 20%, {{ $primary }}10, transparent 40%),
                radial-gradient(circle at 90% 80%, {{ $secondary }}10, transparent 40%);
    pointer-events: none;
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

.sidebar-logo img { 
    height: 52px; 
    width: 52px;
    object-fit: contain;
    background: white;
    padding: 8px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.glass-icon {
    position: absolute;
    top: -10px;
    right: -10px;
    font-size: 6rem;
    opacity: 0.45;
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


<div class="container-fluid dashboard-wrapper" style="margin-top: 200px !important;">

    {{-- Fondo decorativo CONTENIDO dentro del dashboard --}}
    <div class="empresa-bg"></div>

    {{-- ======================================================
        NOTIFICACIONES / TAREAS PENDIENTES
    ====================================================== --}}
    @if(count($reminders) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="row g-2">
                @foreach($reminders as $rem)
                <div class="col-md-6">
                    <div class="glass-panel" style="border-left: 4px solid {{ $rem['type'] == 'warning' ? '#f59e0b' : $primary }}; padding: 0.8rem 1.2rem; background: #ffffff;">
                        <div class="d-flex align-items-center">
                            <div class="fs-3 me-3 opacity-75">{{ $rem['icon'] }}</div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem; color: {{ $rem['type'] == 'warning' ? '#b45309' : $primary }};">
                                    {{ $rem['title'] }}
                                </h6>
                                <p class="small mb-1 text-muted" style="font-size: 0.75rem;">{{ $rem['message'] }}</p>
                                <a href="{{ $rem['link'] }}" class="fw-bold text-decoration-none text-uppercase" style="font-size: 0.65rem; color: {{ $rem['type'] == 'warning' ? '#f59e0b' : $primary }}; letter-spacing: 0.5px;">
                                    {{ $rem['btn'] }} <i class="bi bi-chevron-right"></i>
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
        DASHBOARD ESTRATÉGICO DUAL
    ====================================================== --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <h5 class="fw-bold mb-4 text-center text-uppercase letter-spacing-1" style="color: {{ $primary }};">🏪 Operación Local (Punto de Venta)</h5>
            <div class="glass-panel p-4">
                <div class="row g-3">
                    <div class="col-md-4 text-center">
                        <div id="chartLocalVentas" style="min-height: 160px;"></div>
                        <div class="stat-label small">Ritmo Ventas</div>
                        <div class="fw-bold text-success">$ {{ number_format($ventasLocalHoy, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div id="chartLocalEgresos" style="min-height: 160px;"></div>
                        <div class="stat-label small">Gasto vs Inversión</div>
                        <div class="small text-muted">G: <span class="text-danger">${{ number_format($gastosHoy, 0) }}</span> | I: <span class="text-info">${{ number_format($comprasHoy, 0) }}</span></div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div id="chartLocalEval" style="min-height: 160px;"></div>
                        <div class="stat-label small">Evaluación Neta</div>
                        <div class="fw-bold {{ $balanceLocal > 0 ? 'text-success' : 'text-danger' }}">
                             {{ $balanceLocal > 0 ? 'Positiva' : 'Alerta' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <h5 class="fw-bold mb-4 text-center text-uppercase letter-spacing-1" style="color: #8b5cf6;">🌐 Operación Internet (Catálogo)</h5>
            <div class="glass-panel p-4" style="border-top: 4px solid #8b5cf6;">
                <div class="row g-3">
                    <div class="col-md-6 text-center">
                        <div id="chartInternetVentas" style="min-height: 160px;"></div>
                        <div class="stat-label small">Ventas Online</div>
                        <div class="fw-bold" style="color: #8b5cf6;">$ {{ number_format($ventasInternetHoy, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="d-flex flex-column justify-content-center h-100 p-3">
                             <div class="stat-label mb-2">Pedidos Pendientes</div>
                             <h3 class="fw-bold {{ $pedidosPendientes > 0 ? 'text-warning' : 'text-muted' }}">{{ $pedidosPendientes }}</h3>
                             <a href="{{ route('empresa.orders.index') }}" class="btn btn-sm rounded-pill mt-2" style="background: #8b5cf6; color: white;">Gestionar Pedidos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isMedical)
    <h5 class="fw-bold mb-3" style="color: #0d6efd;">Monitor Médico (Hoy)</h5>
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid #0d6efd; background: #f0f7ff;">
                <div class="stat-label">Turnos Programados</div>
                <div class="stat-value text-primary">{{ $turnosHoy }}</div>
                <p class="small text-muted mb-0">Atenciones agendadas para hoy.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid #16a34a; background: #f0fdf4;">
                <div class="stat-label">Pacientes Atendidos</div>
                <div class="stat-value text-success">{{ $pacientesAtendidosHoy }}</div>
                <p class="small text-muted mb-0">Historias clínicas generadas hoy.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid #f59e0b; background: #fffbeb;">
                <div class="stat-label">Cobros Coseguros</div>
                <div class="stat-value text-warning">$ {{ number_format($cobrosCosegurosHoy, 0, ',', '.') }}</div>
                <p class="small text-muted mb-0">Ingresos por diferenciales sociales.</p>
            </div>
        </div>
    </div>
    @endif

    <h5 class="fw-bold mb-3 text-secondary">Rendimiento Consolidado (Hoy)</h5>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid #22c55e; min-height: 120px;">
                <div class="glass-icon text-success" style="font-size: 4rem; top: 10px;">💰</div>
                <div class="stat-label">Total Ventas (Mix)</div>
                <div class="stat-value text-success">$ {{ number_format($ventasHoy, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid {{ $primary }}; min-height: 120px;">
                <div class="glass-icon" style="color: {{ $primary }}; font-size: 4rem; top: 10px;">📊</div>
                <div class="stat-label">Ventas del Mes</div>
                <div class="stat-value" style="color: {{ $primary }};">$ {{ number_format($ventasMes, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid #8b5cf6; min-height: 120px;">
                <div class="glass-icon" style="color: #8b5cf6; font-size: 4rem; top: 10px;">🧾</div>
                <div class="stat-label">Operaciones Realizadas</div>
                <div class="stat-value" style="color: #8b5cf6;">{{ $cantidadVentasHoy }}</div>
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <h5 class="fw-bold mb-3 text-secondary">Recursos y Gestión</h5>
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4">
                <div class="stat-label">Empleados y Cajeros</div>
                <div class="stat-value mb-3">{{ $usuariosCount }}</div>
                <a href="{{ route('empresa.usuarios.index') }}" class="btn btn-sm btn-glass px-4">Administrar</a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4">
                <div class="stat-label">Cartera de Clientes</div>
                <div class="stat-value mb-3">{{ $clientesCount ?? 0 }}</div>
                <a href="{{ route('empresa.clientes.index') }}" class="btn btn-sm px-4 text-white" style="background: {{ $primary }};">Cuentas Corrientes</a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4">
                <div class="stat-label">Ventas por Catálogo</div>
                <div class="stat-value mb-1 {{ $pedidosPendientes > 0 ? 'text-warning' : '' }}">{{ $pedidosPendientes }}</div>
                <a href="{{ route('empresa.orders.index') }}" class="btn btn-sm w-100 mb-1 fw-bold" style="background: #8b5cf6; color: white;">Gestionar Pedidos</a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4">
                <div class="stat-label">Motor de Reportes</div>
                <div class="stat-value mb-3">5+</div>
                <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-sm btn-success px-4">Generar PDF/Excel</a>
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-clock-history me-2"></i> Actividad Reciente</h5>
    <div class="glass-panel p-4 mb-5">
        <div class="activity-feed" style="max-height: 400px; overflow-y: auto;">
            @forelse($recentActivities as $activity)
                <div class="d-flex mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-lightning-charge text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold small">{{ $activity->user->name ?? 'Sistema' }}</div>
                        <div class="small text-muted">{{ $activity->description }}</div>
                        <div class="x-small text-muted opacity-50">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">Sin actividad reciente.</p>
            @endforelse
        </div>
    </div>

</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const commonOptions = {
        chart: { type: 'radialBar', height: 180, sparkline: { enabled: true } },
        plotOptions: {
            radialBar: {
                startAngle: -90, endAngle: 90,
                track: { background: "#eee", strokeWidth: '97%' },
                dataLabels: {
                    name: { show: false },
                    value: { offsetY: -2, fontSize: '20px', fontWeight: '800' }
                }
            }
        },
        grid: { padding: { top: -10 } },
        stroke: { lineCap: "round" }
    };

    new ApexCharts(document.querySelector("#chartLocalVentas"), {
        ...commonOptions, series: [{{ $saludLocal }}], colors: ["#22c55e"]
    }).render();

    new ApexCharts(document.querySelector("#chartLocalEgresos"), {
        ...commonOptions, series: [{{ $gastosPerc }}], colors: ["#ef4444"]
    }).render();

    new ApexCharts(document.querySelector("#chartLocalEval"), {
        ...commonOptions, series: [{{ $evaluacionLocal }}], colors: ["{{ $balanceLocal >= 0 ? '#22c55e' : '#ef4444' }}"]
    }).render();

    new ApexCharts(document.querySelector("#chartInternetVentas"), {
        ...commonOptions, series: [{{ $saludInternet }}], colors: ["#8b5cf6"]
    }).render();
</script>
@endsection
