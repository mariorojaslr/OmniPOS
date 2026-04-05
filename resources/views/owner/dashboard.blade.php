@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --oled-black: #000000;
        --oled-card: #0a0a0a;
        --oled-border: rgba(255, 255, 255, 0.1);
        --accent-blue: #3b82f6;
        --accent-purple: #a855f7;
        --accent-green: #22c55e;
        --accent-yellow: #eab308;
    }

    body {
        background-color: var(--oled-black) !important;
        font-family: 'Outfit', sans-serif;
    }

    .premium-bg {
        background: radial-gradient(circle at 10% 10%, rgba(59, 130, 246, 0.05), transparent 30%),
                    radial-gradient(circle at 90% 90%, rgba(168, 85, 247, 0.05), transparent 30%);
    }

    .header-title {
        font-weight: 800;
        font-size: 2.2rem;
        background: linear-gradient(to right, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    .oled-card {
        background: var(--oled-card);
        border: 1px solid var(--oled-border);
        border-radius: 20px;
        padding: 1.8rem;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
    }

    .oled-card:hover {
        transform: translateY(-8px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8);
    }

    .oled-card::after {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        opacity: 0;
        transition: opacity 0.4s;
    }

    .oled-card:hover::after { opacity: 1; }

    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #60a5fa; /* Celeste atenuado */
        font-weight: 700;
        margin-bottom: 0.5rem;
        padding-left: 2px;
    }

    .stat-value {
        font-size: 2.8rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-diff {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .text-glow-primary { text-shadow: 0 0 20px rgba(59, 130, 246, 0.4); }
    .text-glow-success { text-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }
    .text-glow-purple { text-shadow: 0 0 20px rgba(168, 85, 247, 0.4); }
    .text-glow-warning { text-shadow: 0 0 20px rgba(234, 179, 8, 0.4); }

    .infra-tag {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 6px;
        background: rgba(255,255,255,0.05);
        color: #94a3b8;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .command-btn {
        background: var(--oled-card);
        border: 1px solid var(--oled-border);
        color: #cbd5e1;
        border-radius: 14px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        text-align: left;
        margin-bottom: 12px;
        text-decoration: none;
    }

    .command-btn:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.2);
        color: #fff;
        transform: translateX(5px);
    }

    .command-btn i {
        font-size: 1.2rem;
        opacity: 0.7;
    }

    .scanline {
        width: 100%;
        height: 1px;
        background: rgba(59, 130, 246, 0.1);
        margin: 2rem 0;
    }

    .stat-mini-label { font-size: 0.7rem; color: #ffffff; letter-spacing: 1px; opacity: 0.8; }

    /* Animaciones & Interactividad Premium */
    .clickable-card {
        display: block;
        text-decoration: none !important;
        color: inherit;
        cursor: pointer;
        outline: none;
    }
    .clickable-card:active {
        transform: scale(0.97) !important;
        border-color: #fff;
    }
    .radar-sweep {
        position: absolute;
        top: 0; left: 0;
        width: 150%; height: 100%;
        background: linear-gradient(110deg, transparent, rgba(168, 85, 247, 0.15), transparent);
        animation: radar 3s linear infinite;
        opacity: 0.8;
        pointer-events: none;
    }
    .radar-sweep-green {
        background: linear-gradient(110deg, transparent, rgba(34, 197, 94, 0.15), transparent);
    }
    .radar-sweep-blue {
        background: linear-gradient(110deg, transparent, rgba(59, 130, 246, 0.15), transparent);
    }
    @keyframes radar {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    @keyframes pulse-border {
        0% { border-color: rgba(59, 130, 246, 0.1); }
        50% { border-color: rgba(59, 130, 246, 0.6); }
        100% { border-color: rgba(59, 130, 246, 0.1); }
    }

    .live-indicator {
        width: 8px; height: 8px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        box-shadow: 0 0 10px #22c55e;
        animation: blink 2s infinite;
    }
    .live-indicator-fast {
        width: 8px; height: 8px;
        background: #ef4444;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        box-shadow: 0 0 15px #ef4444;
        animation: blink 1s infinite;
    }
        width: 8px; height: 8px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        box-shadow: 0 0 10px #22c55e;
        animation: blink 2s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
</style>
@endsection

@section('content')
<div class="px-2 pb-5">

    {{-- HUD HEADER --}}
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <div class="stat-mini-label mb-1">
                <span class="live-indicator"></span> SISTEMA OPERATIVO MASTER · v1.02.0
            </div>
            <h1 class="header-title mb-0">CENTRO DE MANDO MultiPOS</h1>
        </div>
        <div class="text-end">
            <a href="{{ route('owner.empresas.create') }}" class="btn btn-primary px-4 py-2 fw-bold" style="border-radius: 12px; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);">
                + DESPLEGAR EMPRESA
            </a>
        </div>
    </div>

    {{-- RACE CONTROL: VELOCÍMETROS DE SALUD --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="oled-card text-center p-3">
                <div id="chartSales" style="min-height: 200px;"></div>
                <div class="stat-label">VELOCIDAD DE VENTAS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="oled-card text-center p-3">
                <div id="chartExpenses" style="min-height: 200px;"></div>
                <div class="stat-label">TASA DE GASTOS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="oled-card text-center p-3">
                <div id="chartGlobal" style="min-height: 200px;"></div>
                <div class="stat-label">ESTADO GLOBAL DE LA MISIÓN</div>
            </div>
        </div>
    </div>

    {{-- METRICAS CORE (LEVEL 1) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('owner.empresas.index') }}" class="oled-card clickable-card">
                <div class="stat-label">Empresas Activas</div>
                <div class="stat-value text-glow-primary">{{ $empresasCount }}</div>
                <div class="mt-3">
                    <span class="stat-mini-label">{{ $empresasActivas }} OPERATIVAS</span>
                    <div class="progress mt-1" style="height: 3px; background: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-primary" style="width: 85%"></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('owner.empresas.index') }}" class="oled-card clickable-card">
                <div class="stat-label">Usuarios Globales</div>
                <div class="stat-value text-glow-purple">{{ $usuariosCount }}</div>
                <div class="mt-3">
                    <span class="stat-mini-label">TRÁFICO CONCURRENTE</span>
                    <div class="progress mt-1" style="height: 3px; background: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-purple" style="width: 40%"></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('owner.facturacion.index') }}" class="oled-card clickable-card" style="border-color: rgba(34, 197, 94, 0.3);">
                <div class="radar-sweep radar-sweep-green"></div>
                <div class="stat-label text-success">MRR Proyectado</div>
                <div class="stat-value text-glow-success" style="font-size: 2.2rem;">{{ $mrr }}</div>
                <div class="mt-3">
                    <span class="stat-mini-label">CRECIMIENTO +12%</span>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('owner.facturacion.index') }}" class="oled-card clickable-card">
                <div class="stat-label text-warning">Ventas del Mes</div>
                <div class="stat-value text-glow-warning" style="font-size: 2.2rem;">{{ $facturacionMes }}</div>
                <div class="mt-3">
                    <span class="stat-mini-label">RENOVACIONES OK</span>
                </div>
            </a>
        </div>
    </div>

    {{-- RADAR DE ADQUISICIÓN Y TRÁFICO (LEVEL 1.5) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-12 mb-1">
            <h5 class="stat-mini-label mb-0"><span class="live-indicator-fast"></span> RADAR DE ADQUISICIÓN EXTERNA (EN VIVO)</h5>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="oled-card clickable-card" style="border-color: rgba(168, 85, 247, 0.4); box-shadow: 0 0 20px rgba(168, 85, 247, 0.1) inset;">
                <div class="radar-sweep"></div>
                <div class="stat-label" style="color: #c084fc;"><i class="bi bi-globe2 me-1"></i> Visitantes Landing Page</div>
                <div class="d-flex align-items-end gap-3 mb-1 mt-2">
                    <span class="fs-1 fw-bold text-white text-glow-purple lh-1">{{ $landingVisits }}</span>
                    <span class="text-success small fw-bold mb-1"><i class="bi bi-arrow-up-right"></i> +14%</span>
                </div>
                <div class="stat-mini-label">PERSONAS HOY</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="oled-card clickable-card" style="border-color: rgba(59, 130, 246, 0.4); box-shadow: 0 0 20px rgba(59, 130, 246, 0.1) inset;">
                <div class="radar-sweep radar-sweep-blue"></div>
                <div class="stat-label" style="color: #93c5fd;"><i class="bi bi-play-circle me-1"></i> Entradas al DEMO</div>
                <div class="d-flex align-items-end gap-3 mb-1 mt-2">
                    <span class="fs-1 fw-bold text-white text-glow-primary lh-1">{{ $demoEntries }}</span>
                    <span class="text-success small fw-bold mb-1"><i class="bi bi-arrow-up-right"></i> Activos</span>
                </div>
                <div class="stat-mini-label">PRUEBAS EN CURSO</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="oled-card clickable-card" style="border-color: rgba(34, 197, 94, 0.4); box-shadow: 0 0 20px rgba(34, 197, 94, 0.1) inset;">
                <div class="radar-sweep radar-sweep-green"></div>
                <div class="stat-label" style="color: #86efac;"><i class="bi bi-funnel me-1"></i> Conversión de Bot</div>
                <div class="d-flex align-items-end gap-3 mb-1 mt-2">
                    <span class="fs-1 fw-bold text-white text-glow-success lh-1">{{ $conversionRate }}%</span>
                    <span class="text-white-50 small fw-bold mb-1">Efectividad</span>
                </div>
                <div class="stat-mini-label">LEADS CALIENTES GENERADOS</div>
            </a>
        </div>
    </div>

    <div class="scanline"></div>

    {{-- INFRAESTRUCTURA & BUNNY.NET (LEVEL 2) --}}
    <div class="row g-5 mb-5">
        
        <div class="col-md-8">
            <h5 class="stat-mini-label mb-4">RECURSOS DE INFRAESTRUCTURA (BUNNY.NET)</h5>
            <div class="row g-4">
                
                <div class="col-md-6 text-center">
                    <div class="oled-card border-0 bg-transparent text-start p-0">
                        <div class="stat-label mb-2"><i class="bi bi-hdd-network text-info me-1"></i> Almacenamiento Consumido</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fs-1 fw-bold text-white">{{ explode(' ', $consumoStorage)[0] }}</span>
                            <span class="text-muted fw-bold">GB</span>
                        </div>
                        <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.05);">
                            <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width: 25%"></div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <span class="infra-tag">99.9% Uptime</span>
                                <span class="infra-tag">Tier 1 SSD</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted" style="font-size: 0.65rem;">Costo Proyectado</small>
                                <div class="text-info fw-bold">{{ $costoStorage }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 text-center">
                    <div class="oled-card border-0 bg-transparent text-start p-0">
                        <div class="stat-label mb-2"><i class="bi bi-activity text-warning me-1"></i> Tráfico de Red</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fs-1 fw-bold text-white">{{ explode(' ', $consumoTrafico)[0] }}</span>
                            <span class="text-muted fw-bold">GB</span>
                        </div>
                        <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.05);">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: 60%"></div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <span class="infra-tag">Global Edge</span>
                                <span class="infra-tag">SSL Active</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted" style="font-size: 0.65rem;">Costo Proyectado</small>
                                <div class="text-warning fw-bold">{{ $costoTrafico }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="oled-card">
                        <div class="row align-items-center text-center">
                            <div class="col-md-4">
                                <div class="stat-label">Streaming Activo</div>
                                <div class="fs-4 fw-bold text-white">{{ $streamingMensual }}</div>
                                <div class="stat-mini-label mt-1">BUNNY STREAM</div>
                            </div>
                            <div class="col-md-4 border-start border-end border-white border-opacity-10">
                                <div class="stat-label">Contenido Multimedia</div>
                                <div class="fs-4 fw-bold text-white">{{ $archivosSubidos }}</div>
                                <div class="stat-mini-label mt-1">TOTAL ARCHIVOS</div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-label">Contenido Visual</div>
                                <div class="fs-4 fw-bold text-white">{{ $imagenesSubidas }}</div>
                                <div class="stat-mini-label mt-1">IMÁGENES</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-4">
            <h5 class="stat-mini-label mb-4">PANEL DE OPERACIONES</h5>
            
            <a href="{{ route('owner.crm.index') }}" class="command-btn border-primary border-opacity-50" style="background: linear-gradient(90deg, rgba(59, 130, 246, 0.15), transparent);">
                <i class="bi bi-people-fill text-primary"></i>
                <div>
                    <div class="text-white fw-bold">CRM DE PROSPECTOS</div>
                    <small class="text-primary opacity-75" style="font-size: 0.7rem;">Nuevos Leads & Pagos pendientes</small>
                </div>
            </a>

            <a href="{{ route('owner.empresas.index') }}" class="command-btn">
                <i class="bi bi-building"></i>
                <div>
                    <div>GESTIÓN DE EMPRESAS</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Control de suscripciones</small>
                </div>
            </a>

            <a href="{{ route('owner.facturacion.index') }}" class="command-btn">
                <i class="bi bi-wallet2"></i>
                <div>
                    <div>CENTRO FINANCIERO</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Cobranzas globales</small>
                </div>
            </a>

            <a href="{{ route('owner.soporte.index') }}" class="command-btn">
                <i class="bi bi-headset"></i>
                <div>
                    <div>CENTRAL DE SOPORTE</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Gestión de tickets</small>
                </div>
            </a>

            <a href="{{ route('owner.planes.index') }}" class="command-btn border-primary border-opacity-25" style="background: rgba(59, 130, 246, 0.05);">
                <i class="bi bi-gear-wide-connected text-primary"></i>
                <div>
                    <div>PLANES Saas</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Precios y servicios</small>
                </div>
            </a>

            <a href="{{ route('owner.updates.index') }}" class="command-btn">
                <i class="bi bi-broadcast-pin"></i>
                <div>
                    <div>COMUNICADOS</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Logs de actualización</small>
                </div>
            </a>
        </div>

    </div>

    <div class="scanline"></div>

    {{-- RECENT COMPANIES (LEVEL 3) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-12">
            <h5 class="stat-mini-label mb-4">ÚLTIMAS EMPRESAS DESPLEGADAS</h5>
            <div class="oled-card p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark mb-0 align-middle" style="--bs-table-bg: transparent; --bs-table-border-color: rgba(255,255,255,0.05);">
                        <thead class="stat-mini-label border-bottom">
                            <tr>
                                <th class="ps-4 py-3">EMPRESA / RAZÓN SOCIAL</th>
                                <th>PLAN</th>
                                <th class="text-center">FECHA ALTA</th>
                                <th class="text-center">ESTADO</th>
                                <th class="text-end pe-4">COMANDOS</th>
                            </tr>
                        </thead>
                        <tbody class="small fw-normal" style="color: #94a3b8;">
                            @foreach($ultimasEmpresas as $emp)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                                <td class="ps-4">
                                    <div class="fw-bold text-white fs-6">{{ $emp->nombre_comercial }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $emp->cuit }}</div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-dark text-primary border border-primary border-opacity-25 px-3">
                                        {{ $emp->plan->nombre ?? 'BÁSICO' }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $emp->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($emp->activo)
                                        <span class="text-success small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> ONLINE</span>
                                    @else
                                        <span class="text-danger small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> OFFLINE</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ url('owner/empresas/' . $emp->id . '/edit') }}" class="btn btn-sm btn-outline-secondary border-0 opacity-75">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @php $admin = $emp->users()->where('role', 'empresa')->first() ?? $emp->users->first(); @endphp
                                        @if($admin)
                                            <a href="{{ url('owner/mimetizar/empresa/' . $emp->id . '/usuario/' . $admin->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Entrar como Admin">
                                                <i class="bi bi-person-fill-gear"></i> ENTRAR
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3 text-end">
                <a href="{{ route('owner.empresas.index') }}" class="text-primary text-decoration-none small fw-bold" style="letter-spacing: 1px;">
                    VER TODAS LAS EMPRESAS <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Configuración Base para Velocímetros OLED
    const commonOptions = {
        chart: { type: 'radialBar', height: 250, sparkline: { enabled: true } },
        plotOptions: {
            radialBar: {
                startAngle: -90, endAngle: 90,
                track: { background: "#111", strokeWidth: '97%', margin: 5 },
                dataLabels: {
                    name: { show: false },
                    value: { offsetY: -2, fontSize: '30px', fontWeight: '800', color: '#fff', formatter: (val) => val + '%' }
                }
            }
        },
        grid: { padding: { top: -10 } },
        stroke: { lineCap: "round" }
    };

    // 1. SALES VELOCITY (AZUL NEON)
    new ApexCharts(document.querySelector("#chartSales"), {
        ...commonOptions,
        series: [{{ $saludVentas }}],
        colors: ["#3b82f6"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#60a5fa'], stops: [0, 100] } }
    }).render();

    // 2. BURN RATE (ROJO/AMARILLO)
    new ApexCharts(document.querySelector("#chartExpenses"), {
        ...commonOptions,
        series: [{{ $saludGastos }}],
        colors: ["#ef4444"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#f59e0b'], stops: [0, 100] } }
    }).render();

    // 3. GLOBAL STATUS (VERDE NEON)
    new ApexCharts(document.querySelector("#chartGlobal"), {
        ...commonOptions,
        series: [{{ $saludGlobal }}],
        colors: ["#22c55e"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#4ade80'], stops: [0, 100] } }
    }).render();
</script>
@endsection
