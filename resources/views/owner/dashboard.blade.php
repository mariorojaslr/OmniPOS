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

    {{-- NIVEL 1: CORE METRICS --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="oled-card text-center p-3 h-100">
                <div id="chartSales" style="min-height: 180px;"></div>
                <div class="stat-label">VELOCIDAD DE VENTAS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="oled-card text-center p-3 h-100">
                <div id="chartExpenses" style="min-height: 180px;"></div>
                <div class="stat-label">TASA DE GASTOS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="oled-card text-center p-3 h-100">
                <div id="chartGlobal" style="min-height: 180px;"></div>
                <div class="stat-label">SALUD DE LA MISIÓN</div>
            </div>
        </div>
    </div>

    {{-- NIVEL 2: DASHBOARD 360 (WIDGETS) --}}
    <div class="row g-4">
        
        {{-- COLUMNA PRINCIPAL (ACTIVIDAD Y OPERACIONES) --}}
        <div class="col-md-8">
            
            {{-- WIDGET: BITÁCORA CRM --}}
            <div class="oled-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="stat-mini-label mb-0"><i class="bi bi-robot me-2"></i> BITÁCORA DE INTELIGENCIA COMERCIAL</h5>
                    <span class="live-indicator"></span>
                </div>
                <div class="custom-scrollbar" style="max-height: 350px; overflow-y: auto; padding-right: 10px;">
                    @forelse($crmActivities as $act)
                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded" style="background: rgba(255,255,255,0.02); border-left: 3px solid #3b82f6;">
                        <div class="text-white-50 small font-mono opacity-50">{{ $act->created_at->format('H:i') }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="text-info fw-bold" style="font-size: 0.7rem;">{{ strtoupper($act->channel) }}</span>
                                <span class="badge rounded-pill bg-dark border border-primary border-opacity-25 text-primary" style="font-size: 0.55rem;">{{ strtoupper($act->status) }}</span>
                            </div>
                            <div class="text-white small fw-bold mt-1 text-truncate" style="max-width: 400px;">{{ $act->target_name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($act->details, 80) }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 opacity-50">SIN ACTIVIDAD</div>
                    @endforelse
                </div>
            </div>

            {{-- WIDGET: SOPORTE TÉCNICO --}}
            <div class="oled-card mb-4" style="border-color: rgba(239, 68, 68, 0.2);">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="stat-mini-label mb-0 text-danger"><i class="bi bi-headset me-2"></i> TICKETS DE SOPORTE ACTIVOS</h5>
                    <a href="{{ route('owner.soporte.index') }}" class="text-muted text-decoration-none small">Ver todos</a>
                </div>
                <div class="space-y-2">
                    @forelse($ultimosTickets as $ticket)
                    <div class="d-flex justify-content-between align-items-center p-3 rounded mb-2" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                        <div>
                            <div class="text-white fw-bold small">{{ $ticket->empresa->nombre_comercial ?? 'General' }}</div>
                            <div class="text-muted small">{{ Str::limit($ticket->subject, 50) }}</div>
                        </div>
                        <span class="badge bg-{{ $ticket->status == 'open' ? 'danger' : 'info' }} bg-opacity-10 text-{{ $ticket->status == 'open' ? 'danger' : 'info' }} border border-{{ $ticket->status == 'open' ? 'danger' : 'info' }} border-opacity-25">
                            {{ strtoupper($ticket->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4 opacity-50">LIMPIO</div>
                    @endforelse
                </div>
            </div>

            {{-- WIDGET: ÚLTIMAS EMPRESAS --}}
            <div class="oled-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="stat-mini-label mb-0"><i class="bi bi-building me-2"></i> ÚLTIMOS DESPLEGUES</h5>
                    <a href="{{ route('owner.empresas.index') }}" class="text-muted text-decoration-none small">Gestionar</a>
                </div>
                @foreach($ultimasEmpresas as $emp)
                <div class="d-flex justify-content-between align-items-center p-3 rounded mb-2" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-20 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-building text-primary"></i>
                        </div>
                        <div>
                            <div class="text-white fw-bold small">{{ $emp->nombre_comercial }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $emp->plan->nombre ?? 'SaaS' }} · {{ $emp->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @php $admin = $emp->users()->where('role', 'empresa')->first() ?? $emp->users->first(); @endphp
                        @if($admin)
                        <a href="{{ url('owner/mimetizar/empresa/' . $emp->id . '/usuario/' . $admin->id) }}" class="btn btn-sm btn-outline-primary px-3 fw-bold" style="font-size: 0.65rem;">ENTRAR</a>
                        @endif
                        <a href="{{ url('owner/empresas/' . $emp->id . '/edit') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        {{-- COLUMNA LATERAL (HERRAMIENTAS E INFRAESTRUCTURA) --}}
        <div class="col-md-4">
            
            {{-- WIDGET: PANEL DE OPERACIONES --}}
            <div class="oled-card mb-4" style="background: linear-gradient(180deg, rgba(59, 130, 246, 0.05), transparent);">
                <h5 class="stat-mini-label mb-4">PANEL DE CONTROL Maestro</h5>
                
                <a href="{{ route('owner.crm.index') }}" class="command-btn border-primary border-opacity-50">
                    <i class="bi bi-people-fill text-primary"></i>
                    <div>
                        <div class="text-white fw-bold">CRM ESTRATÉGICO</div>
                        <small class="text-primary opacity-75">Prospectos y Ventas</small>
                    </div>
                </a>

                <a href="{{ route('owner.facturacion.index') }}" class="command-btn">
                    <i class="bi bi-wallet2"></i>
                    <div>
                        <div class="text-white fw-bold">CENTRO FINANCIERO</div>
                        <small class="text-muted">Cobros y MRR</small>
                    </div>
                </a>

                <a href="{{ route('owner.updates.index') }}" class="command-btn">
                    <i class="bi bi-broadcast-pin text-info"></i>
                    <div>
                        <div class="text-white fw-bold">COMUNICADOS</div>
                        <small class="text-muted">Logs de Sistema</small>
                    </div>
                </a>

                <button type="button" class="command-btn border-warning border-opacity-50" data-bs-toggle="modal" data-bs-target="#modalSettings">
                    <i class="bi bi-sliders text-warning"></i>
                    <div>
                        <div class="text-white fw-bold">AJUSTES GLOBALES</div>
                        <small class="text-warning opacity-75">Configuración Maestro</small>
                    </div>
                </button>
            </div>

            {{-- WIDGET: AGENTE SOCIAL LIVE --}}
            <div class="oled-card mb-4" style="border-color: rgba(56, 189, 248, 0.2);">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="stat-mini-label mb-0"><span class="live-indicator-fast"></span> AGENTE SOCIAL LIVE</h5>
                </div>
                <div class="row g-2">
                    @foreach($agent_data as $n => $d)
                    <div class="col-6">
                        <div class="p-2 rounded-3 text-center" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                            <div class="text-white fw-bold fs-5">{{ $d['scanned'] }}</div>
                            <div class="text-muted" style="font-size: 0.5rem; letter-spacing: 1px;">{{ strtoupper($n) }}</div>
                            <div class="text-success fw-bold" style="font-size: 0.5rem;">+{{ $d['hunted'] }} LEADS</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- WIDGET: MONITOR DE RECURSOS --}}
            <div class="oled-card" style="border-color: rgba(255,255,255,0.1);">
                <h5 class="stat-mini-label mb-4"><i class="bi bi-hdd-network me-2"></i> ESTADO DE RECURSOS</h5>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Costo Proyectado (Mes)</span>
                        <span class="text-warning fw-bold small">{{ $costoProyectado }}</span>
                    </div>
                    <div class="progress" style="height: 4px; background: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-warning" style="width: 45%"></div>
                    </div>
                </div>

                <div class="row g-2 mb-3 text-center">
                    <div class="col-6">
                        <div class="p-2 rounded bg-dark border border-white border-opacity-5">
                            <div class="text-muted" style="font-size: 0.6rem;">BASE DE DATOS</div>
                            <div class="text-white small fw-bold">{{ $dbSize }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded bg-dark border border-white border-opacity-5">
                            <div class="text-muted" style="font-size: 0.6rem;">NUBE (BUNNY)</div>
                            <div class="text-white small fw-bold">{{ $consumoStorage }}</div>
                        </div>
                    </div>
                </div>

                <div class="p-2 rounded bg-primary bg-opacity-5 border border-primary border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-mini-label text-primary mb-0" style="font-size: 0.6rem;">UPTIME DEL SISTEMA</div>
                        <span class="text-white fw-bold" style="font-size: 0.6rem;">99.9%</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ==========================================
     MODAL: AJUSTES GLOBALES
=========================================== --}}
<div class="modal fade" id="modalSettings" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content oled-card border-warning border-opacity-50" style="background: #000;">
            <div class="modal-header border-bottom border-white border-opacity-10 py-3">
                <h5 class="modal-title text-white fw-bold"><i class="bi bi-sliders me-2 text-warning"></i>Ajustes del Sistema</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('owner.settings.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="stat-label d-block mb-2">Video Tutorial AFIP (YouTube ID)</label>
                        <input type="text" name="afip_tutorial_video" value="{{ $settings['afip_tutorial_video'] ?? 'v6r4D3Ljuy8' }}" class="form-control bg-dark border-secondary text-white py-2" placeholder="Ej: v6r4D3Ljuy8" style="background: rgba(255,255,255,0.05) !important;">
                        <small class="text-muted d-block mt-2">Este video se mostrará a todas las empresas en el asistente de migración fiscal.</small>
                    </div>
                </div>
                <div class="modal-footer border-top border-white border-opacity-10 py-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold px-4">GUARDAR CAMBIOS</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Configuración Base para Velocímetros OLED
    const commonOptions = {
        chart: { type: 'radialBar', height: 230, sparkline: { enabled: true } },
        plotOptions: {
            radialBar: {
                startAngle: -90, endAngle: 90,
                track: { background: "#111", strokeWidth: '97%', margin: 5 },
                dataLabels: {
                    name: { show: false },
                    value: { offsetY: -2, fontSize: '24px', fontWeight: '800', color: '#fff', formatter: (val) => val + '%' }
                }
            }
        },
        grid: { padding: { top: -10 } },
        stroke: { lineCap: "round" }
    };

    // 1. SALES VELOCITY
    new ApexCharts(document.querySelector("#chartSales"), {
        ...commonOptions,
        series: [{{ $saludVentas }}],
        colors: ["#3b82f6"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#60a5fa'], stops: [0, 100] } }
    }).render();

    // 2. BURN RATE
    new ApexCharts(document.querySelector("#chartExpenses"), {
        ...commonOptions,
        series: [{{ $saludGastos }}],
        colors: ["#ef4444"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#f59e0b'], stops: [0, 100] } }
    }).render();

    // 3. GLOBAL STATUS
    new ApexCharts(document.querySelector("#chartGlobal"), {
        ...commonOptions,
        series: [{{ $saludGlobal }}],
        colors: ["#22c55e"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#4ade80'], stops: [0, 100] } }
    }).render();
</script>
@endsection
