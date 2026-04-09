@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --oled-black: #000000;
        --glass-border-strong: rgba(255, 255, 255, 0.65);
    }

    body {
        background-color: var(--oled-black) !important;
        font-family: 'Outfit', sans-serif;
        overflow-x: hidden;
    }

    /* ===== AMBIENT BACKGROUND ===== */
    .command-center-bg { position: relative; min-height: 100vh; }
    .command-center-bg::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background:
            radial-gradient(ellipse at 10% 0%, rgba(59, 130, 246, 0.12) 0%, transparent 45%),
            radial-gradient(ellipse at 90% 10%, rgba(168, 85, 247, 0.09) 0%, transparent 45%),
            radial-gradient(ellipse at 50% 90%, rgba(34, 197, 94, 0.07) 0%, transparent 45%),
            radial-gradient(ellipse at 30% 50%, rgba(34, 211, 238, 0.05) 0%, transparent 40%);
        pointer-events: none;
        z-index: 0;
    }

    /* ===== HEADER ===== */
    .header-title {
        font-weight: 800; font-size: 2rem;
        background: linear-gradient(135deg, #fff 0%, #60a5fa 50%, #a78bfa 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }
    .header-subtitle { font-size: 0.65rem; letter-spacing: 3px; color: #64748b; font-weight: 600; }
    .header-version {
        font-size: 0.6rem; padding: 3px 10px; border-radius: 20px;
        background: rgba(34, 211, 238, 0.15); color: #22d3ee;
        border: 1px solid rgba(34, 211, 238, 0.45); font-weight: 700; letter-spacing: 1px;
    }

    /* ===== OLED CARDS — Cristal con borde blanco fuerte ===== */
    .oled-card {
        background: rgba(15, 20, 35, 0.80);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.65);
        border-radius: 16px; padding: 1.5rem;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative; overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.10);
    }
    .oled-card:hover {
        border-color: rgba(255,255,255,0.95);
        box-shadow: 0 8px 40px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.15);
    }

    /* ===== KPI CARDS — Cristal traslúcido con color propio ===== */
    .kpi-card {
        background: rgba(15, 20, 35, 0.75);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.60);
        border-radius: 14px; padding: 1.2rem 1rem;
        position: relative; overflow: hidden;
        transition: all 0.3s ease; text-decoration: none !important; display: block;
        box-shadow: 0 2px 16px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.08);
    }
    .kpi-card:hover {
        transform: translateY(-4px); border-color: rgba(255,255,255,0.95);
        box-shadow: 0 12px 32px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.15);
    }
    .kpi-card .kpi-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; margin-bottom: 0.8rem;
        border: 1px solid rgba(255,255,255,0.15);
    }
    .kpi-card .kpi-value { font-size: 1.6rem; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 0.3rem; }
    .kpi-card .kpi-label { font-size: 0.6rem; letter-spacing: 1.5px; color: #94a3b8; font-weight: 700; text-transform: uppercase; }
    .kpi-card .kpi-glow {
        position: absolute; top: -20px; right: -20px;
        width: 110px; height: 110px; border-radius: 50%;
        filter: blur(35px); opacity: 0.40; pointer-events: none;
    }

    /* ===== GAUGE CARDS ===== */
    .gauge-card {
        background: rgba(15, 20, 35, 0.75);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.65);
        border-radius: 16px; padding: 1rem; text-align: center;
        position: relative; overflow: hidden;
        box-shadow: 0 2px 20px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.07);
    }
    .gauge-label { font-size: 0.6rem; letter-spacing: 2px; font-weight: 700; margin-top: -5px; }

    /* ===== WIDGET HEADERS ===== */
    .widget-header { font-size: 0.65rem; letter-spacing: 2px; color: #e2e8f0; font-weight: 700; text-transform: uppercase; }
    .widget-header i { font-size: 0.8rem; opacity: 0.7; }
    .widget-link {
        font-size: 0.6rem; color: #94a3b8; text-decoration: none;
        letter-spacing: 1px; font-weight: 600; transition: all 0.2s;
        border: 1px solid rgba(255,255,255,0.25); padding: 3px 10px; border-radius: 6px;
    }
    .widget-link:hover { color: #3b82f6; border-color: rgba(59,130,246,0.55); }

    /* ===== ACTIVITY ROWS — Cristal AZUL ===== */
    .activity-row {
        background: rgba(59, 130, 246, 0.10);
        border: 1px solid rgba(59, 130, 246, 0.40);
        border-left: 3px solid rgba(59, 130, 246, 0.80);
        border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }
    .activity-row:hover {
        background: rgba(59, 130, 246, 0.18);
        border-color: rgba(59, 130, 246, 0.70);
        border-left-color: #3b82f6;
        box-shadow: 0 4px 16px rgba(59,130,246,0.15);
    }

    /* ===== COMMAND BUTTONS ===== */
    .cmd-btn {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.50);
        color: #94a3b8; border-radius: 12px; padding: 10px 14px;
        font-weight: 600; font-size: 0.8rem; transition: all 0.25s ease;
        display: flex; align-items: center; gap: 10px;
        width: 100%; text-align: left; margin-bottom: 8px; text-decoration: none;
    }
    .cmd-btn:hover {
        background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.90);
        color: #fff; transform: translateX(4px); box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    }
    .cmd-btn i { font-size: 1.1rem; opacity: 0.85; }
    .cmd-btn .cmd-label { font-size: 0.75rem; font-weight: 700; color: #e2e8f0; }
    .cmd-btn .cmd-sub { font-size: 0.6rem; color: #94a3b8; font-weight: 500; }

    /* ===== COMPANY ROWS — Cristal blanco ===== */
    .company-row {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.55);
        border-radius: 12px; padding: 0.8rem 1rem; margin-bottom: 0.5rem;
        transition: all 0.25s ease;
    }
    .company-row:hover {
        background: rgba(59, 130, 246, 0.12);
        border-color: rgba(59, 130, 246, 0.65);
        box-shadow: 0 4px 20px rgba(59,130,246,0.15);
    }
    .company-avatar {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.7rem; color: #fff;
        border: 1px solid rgba(255,255,255,0.20);
    }

    /* ===== TICKET ROWS — Cristal ROJO ===== */
    .ticket-row {
        margin-bottom: 0.4rem;
        transition: all 0.2s ease;
    }
    .ticket-row:hover {
        background: rgba(239, 68, 68, 0.06);
        border-color: rgba(239, 68, 68, 0.2);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.05);
    }

    /* ===== AGENT GRID (Cristal cyan) ===== */
    .agent-cell {
        background: rgba(34, 211, 238, 0.04);
        border: 1px solid rgba(34, 211, 238, 0.12);
        border-radius: 10px;
        padding: 0.6rem 0.4rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .agent-cell:hover {
        border-color: rgba(34, 211, 238, 0.35);
        background: rgba(34, 211, 238, 0.08);
        box-shadow: 0 4px 16px rgba(34, 211, 238, 0.08);
    }
    .agent-cell .agent-count {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        text-shadow: 0 0 15px rgba(34, 211, 238, 0.3);
    }
    .agent-cell .agent-name {
        font-size: 0.45rem;
        letter-spacing: 1.5px;
        color: #94a3b8;
        font-weight: 700;
        margin-top: 2px;
    }
    .agent-cell .agent-leads {
        font-size: 0.45rem;
        color: #22c55e;
        font-weight: 800;
    }

    /* ===== RESOURCE MONITOR (Cristal amarillo) ===== */
    .resource-item {
        background: rgba(234, 179, 8, 0.03);
        border: 1px solid rgba(234, 179, 8, 0.1);
        border-radius: 10px;
        padding: 0.6rem;
        text-align: center;
        transition: all 0.2s ease;
    }
    .resource-item:hover {
        border-color: rgba(234, 179, 8, 0.2);
        background: rgba(234, 179, 8, 0.06);
    }
    .resource-item .res-value {
        font-size: 0.9rem;
        font-weight: 800;
        color: #fff;
    }
    .resource-item .res-label {
        font-size: 0.45rem;
        letter-spacing: 1px;
        color: #94a3b8;
        font-weight: 600;
    }

    /* ===== LIVE INDICATORS ===== */
    .live-dot {
        width: 6px; height: 6px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        box-shadow: 0 0 10px #22c55e, 0 0 20px rgba(34, 197, 94, 0.3);
        animation: pulse-dot 2s infinite;
    }
    .live-dot-red {
        background: #ef4444;
        box-shadow: 0 0 10px #ef4444, 0 0 20px rgba(239, 68, 68, 0.3);
        animation: pulse-dot 1s infinite;
    }
    .live-dot-cyan {
        background: #22d3ee;
        box-shadow: 0 0 10px #22d3ee, 0 0 20px rgba(34, 211, 238, 0.3);
        animation: pulse-dot 1.5s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.8); }
    }

    /* ===== RADAR SWEEP ===== */
    .radar-sweep {
        position: absolute;
        top: 0; left: 0;
        width: 200%; height: 100%;
        background: linear-gradient(110deg, transparent 40%, rgba(34, 211, 238, 0.08) 50%, transparent 60%);
        animation: sweep 4s linear infinite;
        pointer-events: none;
    }
    @keyframes sweep {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(50%); }
    }

    /* ===== SCROLLBAR ===== */
    .custom-scroll::-webkit-scrollbar { width: 3px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }

    /* ===== SECTION DIVIDER ===== */
    .section-line {
        height: 1px;
        background: linear-gradient(90deg, transparent 5%, rgba(59, 130, 246, 0.2) 30%, rgba(168, 85, 247, 0.15) 50%, rgba(34, 211, 238, 0.1) 70%, transparent 95%);
        margin: 1.5rem 0;
    }

    /* ===== DEPLOY BUTTON ===== */
    .deploy-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: 1px solid rgba(96, 165, 250, 0.3);
        color: #fff;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 10px 24px;
        border-radius: 12px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 24px rgba(59, 130, 246, 0.35);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .deploy-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 36px rgba(59, 130, 246, 0.5);
        color: #fff;
    }

    /* ===== STATUS BADGE ===== */
    .status-badge {
        font-size: 0.5rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
        letter-spacing: 0.5px;
        backdrop-filter: blur(8px);
    }

    /* ===== TEXT GLOWS ===== */
    .text-glow-primary { text-shadow: 0 0 25px rgba(59, 130, 246, 0.5); }
    .text-glow-success { text-shadow: 0 0 25px rgba(34, 197, 94, 0.5); }
    .text-glow-purple { text-shadow: 0 0 25px rgba(168, 85, 247, 0.5); }
    .text-glow-warning { text-shadow: 0 0 25px rgba(234, 179, 8, 0.5); }
</style>
@endsection

@section('content')
<div class="command-center-bg px-2 pb-5" style="position: relative; z-index: 1;">

    {{-- ═══════════════════════════════════════════
         HEADER: CENTRO DE MANDO
    ═══════════════════════════════════════════ --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="live-dot"></span>
                <span class="header-subtitle">SISTEMA OPERATIVO MASTER</span>
                <span class="header-version">v1.02.0</span>
            </div>
            <h1 class="header-title mb-0">Centro de Mando</h1>
        </div>
        <a href="{{ route('owner.empresas.create') }}" class="deploy-btn">
            <i class="bi bi-plus-lg me-2"></i> DESPLEGAR EMPRESA
        </a>
    </div>

    {{-- ═══════════════════════════════════════════
         ROW 1: KPIs PRINCIPALES (6 métricas clave)
    ═══════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <a href="{{ route('owner.empresas.index') }}" class="kpi-card">
                <div class="kpi-glow" style="background: #3b82f6;"></div>
                <div class="kpi-icon" style="background: rgba(59, 130, 246, 0.1);"><i class="bi bi-building text-primary"></i></div>
                <div class="kpi-value text-glow-primary">{{ $empresasCount }}</div>
                <div class="kpi-label">EMPRESAS <span class="text-success">· {{ $empresasActivas }} ON</span></div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('owner.empresas.index') }}" class="kpi-card">
                <div class="kpi-glow" style="background: #a855f7;"></div>
                <div class="kpi-icon" style="background: rgba(168, 85, 247, 0.1);"><i class="bi bi-people-fill" style="color: #a855f7;"></i></div>
                <div class="kpi-value" style="text-shadow: 0 0 20px rgba(168, 85, 247, 0.4);">{{ $usuariosCount }}</div>
                <div class="kpi-label">USUARIOS</div>
            </a>
        </div>
        <div class="col-md-2">
            <div class="kpi-card">
                <div class="kpi-glow" style="background: #22d3ee;"></div>
                <div class="kpi-icon" style="background: rgba(34, 211, 238, 0.1);"><i class="bi bi-box-seam" style="color: #22d3ee;"></i></div>
                <div class="kpi-value" style="text-shadow: 0 0 20px rgba(34, 211, 238, 0.4);">{{ $articulosCount }}</div>
                <div class="kpi-label">SKU TOTAL</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="kpi-card">
                <div class="kpi-glow" style="background: #22c55e;"></div>
                <div class="kpi-icon" style="background: rgba(34, 197, 94, 0.1);"><i class="bi bi-person-check text-success"></i></div>
                <div class="kpi-value text-glow-success">{{ $clientesCount }}</div>
                <div class="kpi-label">CLIENTES</div>
            </div>
        </div>
        <div class="col-md-2">
            <a href="{{ route('owner.facturacion.index') }}" class="kpi-card" style="border-color: rgba(34, 197, 94, 0.15);">
                <div class="kpi-glow" style="background: #22c55e;"></div>
                <div class="kpi-icon" style="background: rgba(34, 197, 94, 0.1);"><i class="bi bi-graph-up-arrow text-success"></i></div>
                <div class="kpi-value text-glow-success" style="font-size: 1.4rem;">{{ $mrr }}</div>
                <div class="kpi-label">MRR MENSUAL</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('owner.facturacion.index') }}" class="kpi-card" style="border-color: rgba(234, 179, 8, 0.15);">
                <div class="kpi-glow" style="background: #eab308;"></div>
                <div class="kpi-icon" style="background: rgba(234, 179, 8, 0.1);"><i class="bi bi-cash-stack text-warning"></i></div>
                <div class="kpi-value text-glow-warning" style="font-size: 1.4rem;">{{ $facturacionMes }}</div>
                <div class="kpi-label">VENTAS MES</div>
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ROW 2: VELOCÍMETROS DE SALUD (3 gauges)
    ═══════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="gauge-card" style="--gauge-color: #3b82f6;">
                <div id="chartSales" style="min-height: 170px;"></div>
                <div class="gauge-label text-primary">VELOCIDAD DE VENTAS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gauge-card" style="--gauge-color: #ef4444;">
                <div id="chartExpenses" style="min-height: 170px;"></div>
                <div class="gauge-label text-danger">CONTROL DE GASTOS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gauge-card" style="--gauge-color: #22c55e;">
                <div id="chartGlobal" style="min-height: 170px;"></div>
                <div class="gauge-label text-success">SALUD GLOBAL</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ROW 3: RADAR DE ADQUISICIÓN (3 cards)
    ═══════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-12 mb-1">
            <span class="widget-header"><span class="live-dot live-dot-red"></span> RADAR DE ADQUISICIÓN EXTERNA</span>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="kpi-card" style="border-color: rgba(168, 85, 247, 0.2); padding: 1.4rem;">
                <div class="radar-sweep"></div>
                <div class="kpi-label" style="color: #c084fc; font-size: 0.55rem;">VISITANTES LANDING</div>
                <div class="d-flex align-items-end gap-2 mt-2">
                    <span class="kpi-value" style="font-size: 2.2rem; text-shadow: 0 0 30px rgba(168, 85, 247, 0.5);">{{ $landingVisits }}</span>
                    <span class="text-success fw-bold mb-1" style="font-size: 0.7rem;"><i class="bi bi-arrow-up-right"></i> +14%</span>
                </div>
                <div class="kpi-label mt-1">PERSONAS HOY</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="kpi-card" style="border-color: rgba(59, 130, 246, 0.2); padding: 1.4rem;">
                <div class="radar-sweep" style="background: linear-gradient(110deg, transparent 40%, rgba(59, 130, 246, 0.07) 50%, transparent 60%);"></div>
                <div class="kpi-label" style="color: #93c5fd; font-size: 0.55rem;">ENTRADAS AL DEMO</div>
                <div class="d-flex align-items-end gap-2 mt-2">
                    <span class="kpi-value" style="font-size: 2.2rem; text-shadow: 0 0 30px rgba(59, 130, 246, 0.5);">{{ $demoEntries }}</span>
                    <span class="text-success fw-bold mb-1" style="font-size: 0.7rem;"><i class="bi bi-arrow-up-right"></i> Activos</span>
                </div>
                <div class="kpi-label mt-1">PRUEBAS EN CURSO</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="kpi-card" style="border-color: rgba(34, 197, 94, 0.2); padding: 1.4rem;">
                <div class="radar-sweep" style="background: linear-gradient(110deg, transparent 40%, rgba(34, 197, 94, 0.07) 50%, transparent 60%);"></div>
                <div class="kpi-label" style="color: #86efac; font-size: 0.55rem;">CONVERSIÓN DE BOT</div>
                <div class="d-flex align-items-end gap-2 mt-2">
                    <span class="kpi-value" style="font-size: 2.2rem; text-shadow: 0 0 30px rgba(34, 197, 94, 0.5);">{{ $conversionRate }}%</span>
                    <span class="text-white-50 fw-bold mb-1" style="font-size: 0.7rem;">Efectividad</span>
                </div>
                <div class="kpi-label mt-1">LEADS CALIENTES</div>
            </a>
        </div>
    </div>

    <div class="section-line"></div>

    {{-- ═══════════════════════════════════════════
         ROW 4: DASHBOARD 360° (Layout principal)
    ═══════════════════════════════════════════ --}}
    <div class="row g-3">

        {{-- ══ COLUMNA PRINCIPAL (8/12) ══ --}}
        <div class="col-lg-8">

            {{-- WIDGET: BITÁCORA GLOBAL DE OPERACIONES (NUEVO) --}}
            <div class="oled-card mb-3" style="border-color: rgba(34, 197, 94, 0.45);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="widget-header"><span class="live-dot"></span> <i class="bi bi-clock-history me-1 text-success"></i> MONITOR DE OPERACIONES GLOBAL (SaaS)</span>
                    <span class="header-version" style="background: rgba(34, 197, 94, 0.1); color: #22c55e; border-color: rgba(34, 197, 94, 0.3);">TIEMPO REAL</span>
                </div>
                <div class="custom-scroll" style="max-height: 400px; overflow-y: auto;">
                    @forelse($globalActivities as $log)
                    <div class="activity-row d-flex align-items-center gap-3" style="background: rgba(34, 197, 94, 0.04); border-color: rgba(34, 197, 94, 0.2); border-left-color: #22c55e;">
                        <div class="text-white-50 font-monospace text-center" style="font-size: 0.65rem; min-width: 45px;">
                            <div class="fw-bold text-success">{{ $log->created_at->format('H:i') }}</div>
                            <div style="font-size: 0.5rem;">{{ $log->created_at->format('d/m') }}</div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold" style="font-size: 0.75rem; color: #fff;">{{ $log->empresa->nombre_comercial ?? 'Sistema' }}</span>
                                <span class="status-badge" style="background: rgba(255, 255, 255, 0.05); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.1);">{{ $log->user->name ?? 'Admin' }}</span>
                            </div>
                            <div class="text-white-50 mt-1" style="font-size: 0.75rem;">
                                @if(str_contains($log->description, 'venta'))
                                    <i class="bi bi-cart-check text-success me-1"></i>
                                @elseif(str_contains($log->description, 'usuario'))
                                    <i class="bi bi-person-badge text-info me-1"></i>
                                @elseif(str_contains($log->description, 'presupuesto'))
                                    <i class="bi bi-file-earmark-text text-warning me-1"></i>
                                @else
                                    <i class="bi bi-lightning-charge text-muted me-1"></i>
                                @endif
                                {{ $log->description }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-x text-muted" style="font-size: 2rem;"></i>
                        <div class="text-muted mt-2" style="font-size: 0.72rem; letter-spacing: 1px;">SIN ACTIVIDAD REGISTRADA</div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- WIDGET: BITÁCORA CRM --}}
            <div class="oled-card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="widget-header"><span class="live-dot live-dot-cyan"></span> <i class="bi bi-robot me-1 text-info"></i> SEÑALES DE INTELIGENCIA (CRM)</span>
                    <a href="{{ route('owner.crm.index') }}" class="widget-link">VER CRM <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="custom-scroll" style="max-height: 250px; overflow-y: auto;">
                    @forelse($crmActivities as $act)
                    <div class="activity-row d-flex align-items-center gap-3">
                        <div class="text-white-50 font-monospace" style="font-size: 0.65rem; min-width: 35px;">{{ $act->created_at->format('H:i') }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold" style="font-size: 0.65rem; color: #22d3ee;">{{ strtoupper($act->channel) }}</span>
                                <span class="status-badge" style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2);">{{ strtoupper($act->status) }}</span>
                            </div>
                            <div class="text-white fw-bold mt-1" style="font-size: 0.8rem;">{{ $act->target_name }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ Str::limit($act->details, 90) }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="bi bi-robot text-muted" style="font-size: 2rem;"></i>
                        <div class="text-muted mt-2" style="font-size: 0.7rem; letter-spacing: 1px;">ESPERANDO SEÑALES DE INTELIGENCIA</div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- WIDGET: TICKETS DE SOPORTE --}}
            <div class="oled-card mb-3" style="border-color: rgba(239, 68, 68, 0.1);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="widget-header"><i class="bi bi-headset me-1 text-danger"></i> TICKETS DE SOPORTE</span>
                    <a href="{{ route('owner.soporte.index') }}" class="widget-link">VER TODOS <i class="bi bi-arrow-right"></i></a>
                </div>
                @forelse($ultimosTickets as $ticket)
                <div class="ticket-row d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white fw-bold" style="font-size: 0.8rem;">{{ $ticket->empresa->nombre_comercial ?? 'General' }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ Str::limit($ticket->subject, 55) }}</div>
                    </div>
                    <span class="status-badge" style="background: rgba({{ $ticket->status == 'open' ? '239, 68, 68' : '34, 211, 238' }}, 0.1); color: {{ $ticket->status == 'open' ? '#ef4444' : '#22d3ee' }}; border: 1px solid rgba({{ $ticket->status == 'open' ? '239, 68, 68' : '34, 211, 238' }}, 0.2);">
                        {{ strtoupper($ticket->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-3">
                    <span class="text-success fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;"><i class="bi bi-check-circle me-1"></i> SIN TICKETS PENDIENTES</span>
                </div>
                @endforelse
            </div>

            {{-- WIDGET: ÚLTIMAS EMPRESAS --}}
            <div class="oled-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="widget-header"><i class="bi bi-building me-1"></i> ÚLTIMAS EMPRESAS DESPLEGADAS</span>
                    <a href="{{ route('owner.empresas.index') }}" class="widget-link">GESTIONAR <i class="bi bi-arrow-right"></i></a>
                </div>
                @php $colors = ['#3b82f6','#a855f7','#22c55e','#eab308','#ef4444']; @endphp
                @foreach($ultimasEmpresas as $idx => $emp)
                <div class="company-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="company-avatar" style="background: {{ $colors[$idx % 5] }}20; border: 1px solid {{ $colors[$idx % 5] }}30;">
                            {{ strtoupper(substr($emp->nombre_comercial, 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-white fw-bold" style="font-size: 0.85rem;">{{ $emp->nombre_comercial }}</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="status-badge" style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.15);">{{ $emp->plan->nombre ?? 'BÁSICO' }}</span>
                                <span class="text-muted" style="font-size: 0.6rem;">{{ $emp->created_at->format('d/m/Y') }}</span>
                                @if($emp->activo)
                                <span class="live-dot" style="width: 5px; height: 5px; margin: 0;"></span>
                                @else
                                <span class="live-dot live-dot-red" style="width: 5px; height: 5px; margin: 0;"></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @php $admin = $emp->users()->where('role', 'empresa')->first() ?? $emp->users->first(); @endphp
                        @if($admin)
                        <a href="{{ url('owner/mimetizar/empresa/' . $emp->id . '/usuario/' . $admin->id) }}" class="btn btn-sm btn-primary px-3 fw-bold" style="font-size: 0.6rem; border-radius: 8px;">ENTRAR</a>
                        @endif
                        <a href="{{ url('owner/empresas/' . $emp->id . '/edit') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;"><i class="bi bi-pencil"></i></a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        {{-- ══ COLUMNA LATERAL (4/12) ══ --}}
        <div class="col-lg-4">

            {{-- WIDGET: PANEL DE OPERACIONES --}}
            <div class="oled-card mb-3">
                <span class="widget-header d-block mb-3"><i class="bi bi-grid-3x3-gap me-1"></i> PANEL DE CONTROL</span>

                <a href="{{ route('owner.crm.index') }}" class="cmd-btn" style="border-color: rgba(59, 130, 246, 0.2); background: rgba(59, 130, 246, 0.03);">
                    <i class="bi bi-people-fill text-primary"></i>
                    <div><div class="cmd-label">CRM ESTRATÉGICO</div><div class="cmd-sub">Prospectos · Kanban · Leads</div></div>
                </a>
                <a href="{{ route('owner.empresas.index') }}" class="cmd-btn">
                    <i class="bi bi-building"></i>
                    <div><div class="cmd-label">GESTIÓN DE EMPRESAS</div><div class="cmd-sub">Suscripciones y despliegues</div></div>
                </a>
                <a href="{{ route('owner.facturacion.index') }}" class="cmd-btn">
                    <i class="bi bi-wallet2 text-success"></i>
                    <div><div class="cmd-label">CENTRO FINANCIERO</div><div class="cmd-sub">Cobranzas y MRR</div></div>
                </a>
                <a href="{{ route('owner.soporte.index') }}" class="cmd-btn">
                    <i class="bi bi-headset text-danger"></i>
                    <div><div class="cmd-label">CENTRAL DE SOPORTE</div><div class="cmd-sub">Tickets y resolución</div></div>
                </a>
                <a href="{{ route('owner.planes.index') }}" class="cmd-btn">
                    <i class="bi bi-gear-wide-connected text-info"></i>
                    <div><div class="cmd-label">PLANES SaaS</div><div class="cmd-sub">Precios y servicios</div></div>
                </a>
                <a href="{{ route('owner.updates.index') }}" class="cmd-btn">
                    <i class="bi bi-broadcast-pin" style="color: #a855f7;"></i>
                    <div><div class="cmd-label">COMUNICADOS</div><div class="cmd-sub">Logs de actualización</div></div>
                </a>
                <button type="button" class="cmd-btn" style="border-color: rgba(234, 179, 8, 0.2); background: rgba(234, 179, 8, 0.02); cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalSettings">
                    <i class="bi bi-sliders text-warning"></i>
                    <div><div class="cmd-label text-warning">AJUSTES GLOBALES</div><div class="cmd-sub">Configuración maestra</div></div>
                </button>
            </div>

            {{-- WIDGET: AGENTE SOCIAL LIVE --}}
            <div class="oled-card mb-3" style="border-color: rgba(34, 211, 238, 0.12);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="widget-header"><span class="live-dot live-dot-cyan"></span> AGENTE SOCIAL</span>
                </div>
                <div class="row g-2">
                    @foreach($agent_data as $n => $d)
                    <div class="col-4">
                        <div class="agent-cell">
                            <div class="agent-count">{{ $d['scanned'] }}</div>
                            <div class="agent-name">{{ strtoupper($n) }}</div>
                            <div class="agent-leads">+{{ $d['hunted'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- WIDGET: MONITOR DE RECURSOS --}}
            <div class="oled-card" style="border-color: rgba(234, 179, 8, 0.1);">
                <span class="widget-header d-block mb-3"><i class="bi bi-cpu me-1"></i> INFRAESTRUCTURA</span>

                {{-- Costo Proyectado --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted" style="font-size: 0.7rem;">Costo Proyectado</span>
                    <span class="text-warning fw-bold" style="font-size: 0.85rem;">{{ $costoProyectado }}</span>
                </div>
                <div class="progress mb-3" style="height: 3px; background: rgba(255,255,255,0.04); border-radius: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 45%; background: linear-gradient(90deg, #eab308, #f97316);"></div>
                </div>

                {{-- Recursos Grid --}}
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <div class="resource-item">
                            <div class="res-value">{{ $dbSize }}</div>
                            <div class="res-label">DATABASE</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="resource-item">
                            <div class="res-value">{{ $consumoStorage }}</div>
                            <div class="res-label">BUNNY CDN</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="resource-item">
                            <div class="res-value">{{ $archivosSubidos }}</div>
                            <div class="res-label">ARCHIVOS</div>
                        </div>
                    </div>
                </div>

                {{-- Uptime --}}
                <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(34, 197, 94, 0.04); border: 1px solid rgba(34, 197, 94, 0.1);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="live-dot" style="width: 5px; height: 5px; margin: 0;"></span>
                        <span class="text-muted" style="font-size: 0.6rem; letter-spacing: 1px;">UPTIME</span>
                    </div>
                    <span class="text-success fw-bold" style="font-size: 0.75rem;">99.9%</span>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL: AJUSTES GLOBALES
═══════════════════════════════════════════ --}}
<div class="modal fade" id="modalSettings" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #080808; border: 1px solid rgba(234, 179, 8, 0.2); border-radius: 16px;">
            <div class="modal-header border-bottom border-white border-opacity-5 py-3 px-4">
                <h5 class="modal-title text-white fw-bold" style="font-size: 0.9rem;"><i class="bi bi-sliders me-2 text-warning"></i>Ajustes del Sistema</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('owner.settings.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="d-block mb-2 text-muted" style="font-size: 0.7rem; letter-spacing: 1px; font-weight: 600;">VIDEO TUTORIAL AFIP (YOUTUBE ID)</label>
                        <input type="text" name="afip_tutorial_video" value="{{ $settings['afip_tutorial_video'] ?? 'v6r4D3Ljuy8' }}" class="form-control py-2" placeholder="Ej: v6r4D3Ljuy8" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 10px;">
                        <small class="text-muted d-block mt-2" style="font-size: 0.7rem;">Este video se mostrará a todas las empresas en el asistente de migración fiscal.</small>
                    </div>
                </div>
                <div class="modal-footer border-top border-white border-opacity-5 py-3 px-4">
                    <button type="button" class="btn btn-sm px-3" style="background: rgba(255,255,255,0.05); color: #94a3b8; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold px-4" style="border-radius: 8px;">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const gaugeOpts = {
        chart: { type: 'radialBar', height: 200, sparkline: { enabled: true } },
        plotOptions: {
            radialBar: {
                startAngle: -90, endAngle: 90,
                track: { background: "#111", strokeWidth: '97%', margin: 5 },
                dataLabels: {
                    name: { show: false },
                    value: { offsetY: -2, fontSize: '28px', fontWeight: '800', color: '#fff', formatter: v => v + '%' }
                }
            }
        },
        grid: { padding: { top: -10 } },
        stroke: { lineCap: "round" }
    };

    new ApexCharts(document.querySelector("#chartSales"), {
        ...gaugeOpts, series: [{{ $saludVentas }}], colors: ["#3b82f6"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#60a5fa'], stops: [0, 100] } }
    }).render();

    new ApexCharts(document.querySelector("#chartExpenses"), {
        ...gaugeOpts, series: [{{ $saludGastos }}], colors: ["#ef4444"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#f59e0b'], stops: [0, 100] } }
    }).render();

    new ApexCharts(document.querySelector("#chartGlobal"), {
        ...gaugeOpts, series: [{{ $saludGlobal }}], colors: ["#22c55e"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#4ade80'], stops: [0, 100] } }
    }).render();
</script>
@endsection
