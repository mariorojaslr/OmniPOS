@extends('layouts.empresa')

@section('content')

@php
    $user = auth()->user();
@endphp

<style>
/* =========================================================
   GLASSMORPHISM PREMIUM - EMPRESA ADMIN DASHBOARD
========================================================= */

.dashboard-container {
    padding: 0;
    padding-bottom: 2.5rem;
    margin-left: 0; /* Ya lo maneja el padre #main-content, pero aseguramos limpieza */
    position: relative;
    z-index: 1;
}

/* Forzar que el interior respete el espacio si hay algún conflicto de ancho */
.dashboard-container .row {
    margin-left: 0;
    margin-right: 0;
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
    100% { transform: scale(1.05); }
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

{{-- Fondo estático para estabilidad --}}
<div class="empresa-bg" style="background: radial-gradient(circle at 10% 20%, {{ $primary }}05, transparent 50%);"></div>

<div class="dashboard-container" style="padding-left: 10px;">

    {{-- ======================================================
        CENTRO DE MANDO MÓVIL (SOLO CELULARES)
    ====================================================== --}}
    <div class="d-md-none mb-5 animate__animated animate__fadeInDown">
        <h5 class="stat-label mb-3 text-center">🚀 Operativa de Campo</h5>
        <div class="row g-3">
            <div class="col-6">
                <a href="{{ route('empresa.gastos.quick') }}" class="glass-panel text-center d-flex flex-column align-items-center justify-content-center py-4 text-decoration-none border-danger border-opacity-25" style="background: rgba(239, 68, 68, 0.08);">
                    <div class="fs-1 mb-2">💸</div>
                    <div class="fw-bold text-danger small text-uppercase">Gasto Rápido</div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('empresa.personal.asistencia.qr') }}" class="glass-panel text-center d-flex flex-column align-items-center justify-content-center py-4 text-decoration-none border-primary border-opacity-25" style="background: rgba(37, 99, 235, 0.08);">
                    <div class="fs-1 mb-2">📸</div>
                    <div class="fw-bold text-primary small text-uppercase">Fichaje QR</div>
                </a>
            </div>
            <div class="col-12">
                <a href="{{ route('empresa.pos.index') }}" class="glass-panel text-center d-flex align-items-center justify-content-center py-3 text-decoration-none border-success border-opacity-25" style="background: rgba(34, 197, 94, 0.08);">
                    <div class="fs-3 me-3">🛒</div>
                    <div class="fw-bold text-success text-uppercase">Nueva Venta (POS)</div>
                </a>
            </div>
        </div>
    </div>

    {{-- Header removido para usar el del layout --}}

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
        DASHBOARD ESTRATÉGICO DUAL (LOCAL VS INTERNET)
    ====================================================== --}}
    <div class="row g-4 mb-5">
        
        {{-- COLUMNA 1: OPERACIÓN LOCAL (POS) --}}
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

        {{-- COLUMNA 2: OPERACIÓN INTERNET (CATÁLOGO) --}}
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
                             <a href="{{ route('empresa.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill mt-2">Gestionar Pedidos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ======================================================
        RESUMEN COMERCIAL RÁPIDO
    ====================================================== --}}
    <h5 class="fw-bold mb-3 text-secondary">Rendimiento Consolidado (Hoy)</h5>
    <div class="row g-4 mb-4">

        {{-- Total Ventas --}}
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid #22c55e; min-height: 120px;">
                <div class="glass-icon text-success" style="font-size: 4rem; top: 10px;">💰</div>
                <div class="stat-label">Total Ventas (Mix)</div>
                <div class="stat-value text-success">$ {{ number_format($ventasHoy, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Facturacion Mes --}}
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid {{ $primary }}; min-height: 120px;">
                <div class="glass-icon" style="color: {{ $primary }}; font-size: 4rem; top: 10px;">📊</div>
                <div class="stat-label">Ventas del Mes</div>
                <div class="stat-value" style="color: {{ $primary }};">$ {{ number_format($ventasMes, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Ticket Promedio --}}
        <div class="col-md-4">
            <div class="glass-panel" style="border-left: 4px solid #8b5cf6; min-height: 120px;">
                <div class="glass-icon" style="color: #8b5cf6; font-size: 4rem; top: 10px;">🧾</div>
                <div class="stat-label">Operaciones Realizadas</div>
                <div class="stat-value" style="color: #8b5cf6;">{{ $cantidadVentasHoy }}</div>
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

        {{-- GASTO RÁPIDO (RESTAURADO) --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="glass-panel text-center pb-4" style="background: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.2);">
                <div class="stat-label text-danger">⚠️ CONTROL DE GASTOS</div>
                <div class="stat-value text-danger mb-3">RÁPIDO</div>
                <a href="{{ route('empresa.gastos.quick') }}" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm">
                    <i class="bi bi-phone-fill me-1"></i> REGISTRAR PAGO
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
                    <span class="fw-bold">{{ is_object($empresa->fecha_vencimiento) ? $empresa->fecha_vencimiento->format('d/m/Y') : ($empresa->fecha_vencimiento ?? 'N/A') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-bold">Almacenamiento de Activos:</span>
                    <span class="fw-bold text-success">Sincronizado</span>
                </div>
            </div>
        </div>
    </div>


    <hr class="section-divider">

    {{-- ======================================================
        BLOQUE 4 · BITÁCORA DE ACTIVIDAD RECIENTE
    ====================================================== --}}
    <h5 class="fw-bold mb-3 text-secondary">
        <i class="bi bi-clock-history me-2"></i> Bitácora de Actividad del Equipo
    </h5>
    <div class="glass-panel p-0 mb-5 overflow-hidden">
        <div class="p-4 bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0 text-primary">Historial de Auditoría</h6>
                <p class="x-small text-muted mb-0">Seguimiento en tiempo real de operaciones críticas.</p>
            </div>
            <i class="bi bi-shield-check text-primary fs-3"></i>
        </div>
        <div class="activity-feed p-4" style="max-height: 450px; overflow-y: auto;">
            @forelse($recentActivities as $activity)
                <div class="d-flex mb-4 position-relative">
                    {{-- Timeline Line --}}
                    @if(!$loop->last)
                    <div style="position: absolute; left: 17px; top: 35px; bottom: -20px; width: 2px; background: rgba(0,0,0,0.05);"></div>
                    @endif

                    <div class="flex-shrink-0">
                        <div class="bg-white shadow-sm border rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; z-index: 2; position: relative;">
                            @if(str_contains($activity->description, 'venta'))
                                <i class="bi bi-cart-check text-success"></i>
                            @elseif(str_contains($activity->description, 'usuario'))
                                <i class="bi bi-person-badge text-info"></i>
                            @elseif(str_contains($activity->description, 'producto'))
                                <i class="bi bi-box-seam text-primary"></i>
                            @elseif(str_contains($activity->description, 'presupuesto'))
                                <i class="bi bi-file-earmark-text text-warning"></i>
                            @else
                                <i class="bi bi-lightning-charge text-muted"></i>
                            @endif
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="mb-1 fw-bold small text-dark">
                                {{ $activity->user->name ?? 'Sistema' }}
                            </h6>
                            <span class="text-muted x-small">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="mb-0 text-muted small" style="line-height: 1.4;">
                            {{ $activity->description }}
                        </p>
                        @if($activity->ip_address)
                        <div class="x-small text-muted opacity-50">{{ $activity->ip_address }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-x fs-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-2">No se han registrado actividades aún.</p>
                </div>
            @endforelse
        </div>
        <div class="p-3 bg-light text-center border-top">
            <a href="#" class="text-decoration-none small fw-bold text-muted opacity-75">Ver Reporte de Auditoría Completo</a>
        </div>
    </div>

</div>

<style>
/* Estilos adicionales para la bitácora */
.activity-feed::-webkit-scrollbar { width: 5px; }
.activity-feed::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
.x-small { font-size: 0.75rem; }
</style>

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

    // --- SECTOR LOCAL ---
    
    // 1. Ritmo Ventas Local (Verde)
    new ApexCharts(document.querySelector("#chartLocalVentas"), {
        ...commonOptions,
        series: [{{ $saludLocal }}],
        colors: ["#22c55e"]
    }).render();

    // 2. Gasto vs Inversión Local (Doble Arco Concéntrico Premium)
    new ApexCharts(document.querySelector("#chartLocalEgresos"), {
        chart: { 
            type: 'radialBar', 
            height: 200, 
            sparkline: { enabled: true },
            animations: { enabled: true, easing: 'easeinout', speed: 800 }
        },
        series: [{{ $gastosPerc }}, {{ $comprasPerc }}],
        colors: ["#ef4444", "#3b82f6"], // Rojo: Gasto | Azul: Inversión
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,
                hollow: { size: '45%' }, // Espacio central elegante
                track: { 
                    background: "#f1f5f9", 
                    strokeWidth: '95%',
                    margin: 5 // Separación sutil entre los dos arcos
                },
                dataLabels: {
                    name: { show: false },
                    value: { show: false }
                }
            }
        },
        grid: { padding: { top: -20 } },
        stroke: { lineCap: "round", width: 2 }
    }).render();

    // 3. Evaluación Local (Verde/Rojo)
    new ApexCharts(document.querySelector("#chartLocalEval"), {
        ...commonOptions,
        series: [{{ $evaluacionLocal }}],
        colors: ["{{ $balanceLocal >= 0 ? '#22c55e' : '#ef4444' }}"]
    }).render();

    // --- SECTOR INTERNET ---

    // 1. Ventas Online (Púrpura)
    new ApexCharts(document.querySelector("#chartInternetVentas"), {
        ...commonOptions,
        series: [{{ $saludInternet }}],
        colors: ["#8b5cf6"]
    }).render();

</script>
@endsection
