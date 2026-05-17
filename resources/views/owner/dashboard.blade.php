@extends('layouts.owner')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --oled-black: #000000;
        --glass-border: rgba(255, 255, 255, 0.05);
        --glass-border-hover: rgba(255, 255, 255, 0.15);
        --neon-blue: #3b82f6;
        --neon-purple: #a855f7;
        --neon-cyan: #22d3ee;
        --neon-green: #22c55e;
        --neon-gold: #eab308;
        --neon-danger: #ef4444;
    }

    body {
        background-color: var(--oled-black) !important;
        font-family: 'Outfit', sans-serif;
        overflow-x: hidden;
    }

    /* ===== AMBIENT DEEP OLED BACKGROUND ===== */
    .command-center-bg { position: relative; min-height: 100vh; }
    .command-center-bg::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background:
            radial-gradient(ellipse at 15% 10%, rgba(59, 130, 246, 0.16) 0%, transparent 40%),
            radial-gradient(ellipse at 85% 15%, rgba(168, 85, 247, 0.14) 0%, transparent 40%),
            radial-gradient(ellipse at 50% 85%, rgba(34, 197, 94, 0.09) 0%, transparent 40%),
            radial-gradient(ellipse at 35% 55%, rgba(34, 211, 238, 0.08) 0%, transparent 35%);
        pointer-events: none;
        z-index: 0;
    }

    /* ===== HEADER ===== */
    .header-title {
        font-weight: 800; font-size: 2.3rem;
        background: linear-gradient(135deg, #ffffff 0%, #60a5fa 50%, #a78bfa 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
        text-shadow: 0 0 40px rgba(59, 130, 246, 0.1);
    }
    .header-subtitle { font-size: 0.65rem; letter-spacing: 3px; color: #64748b; font-weight: 600; }
    .header-version {
        font-size: 0.6rem; padding: 4px 12px; border-radius: 20px;
        background: rgba(34, 211, 238, 0.08); color: #22d3ee;
        border: 1px solid rgba(34, 211, 238, 0.25); font-weight: 700; letter-spacing: 1px;
        text-shadow: 0 0 10px rgba(34, 211, 238, 0.3);
    }

    /* ===== OLED CARDS — Premium Glassmorphism ===== */
    .oled-card {
        background: rgba(10, 10, 15, 0.75);
        backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 18px; padding: 1.6rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255,255,255,0.03);
        position: relative;
    }
    .oled-card:hover {
        border-color: rgba(255, 255, 255, 0.12);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.9), inset 0 1px 0 rgba(255,255,255,0.05);
        transform: translateY(-2px);
    }

    /* ===== KPI CARDS — GLOW ACCENTS ===== */
    .kpi-card {
        background: rgba(10, 10, 15, 0.7);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.04);
        border-radius: 16px; padding: 1.3rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important; display: block;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.7), inset 0 1px 0 rgba(255,255,255,0.02);
        position: relative; overflow: hidden;
    }
    .kpi-card::after {
        content: "";
        position: absolute;
        bottom: 0; left: 10%; right: 10%; height: 2px;
        background: var(--card-glow, #3b82f6);
        opacity: 0.15; transition: all 0.4s ease;
        filter: blur(1px);
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        border-color: var(--card-glow, #3b82f6);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8), 0 0 20px rgba(var(--card-glow-rgb, 59, 130, 246), 0.18);
    }
    .kpi-card:hover::after {
        opacity: 0.8; height: 3px; filter: blur(0px);
    }
    .kpi-card .kpi-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; margin-bottom: 0.7rem;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        transition: all 0.3s ease;
    }
    .kpi-card:hover .kpi-icon {
        background: rgba(var(--card-glow-rgb, 59, 130, 246), 0.1);
        border-color: rgba(var(--card-glow-rgb, 59, 130, 246), 0.3);
        transform: scale(1.05);
    }
    .kpi-card .kpi-value {
        font-size: 1.7rem; font-weight: 800; color: #fff;
        line-height: 1.1; margin-bottom: 0.3rem; position: relative; z-index: 2;
        text-shadow: 0 0 10px rgba(255,255,255,0.1);
    }
    .kpi-card .kpi-label {
        font-size: 0.58rem; letter-spacing: 1.5px; color: #64748b;
        font-weight: 700; text-transform: uppercase; position: relative; z-index: 2;
        transition: color 0.3s ease;
    }
    .kpi-card:hover .kpi-label { color: #94a3b8; }
    .kpi-card .kpi-bg {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        opacity: 0.4; z-index: 0; transition: opacity 0.4s ease;
    }
    .kpi-card:hover .kpi-bg { opacity: 0.65; }

    /* ===== GAUGE CONTAINER ===== */
    .gauge-card-container {
        background: rgba(10, 10, 15, 0.75);
        backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 18px; padding: 1.5rem; text-align: center;
        position: relative; overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255,255,255,0.03);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .gauge-card-container:hover {
        border-color: var(--gauge-glow, #3b82f6);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.9), 0 0 25px rgba(var(--gauge-glow-rgb, 59, 130, 246), 0.12);
        transform: translateY(-2px);
    }
    .gauge-label { font-size: 0.65rem; letter-spacing: 2px; font-weight: 800; margin-top: -5px; text-transform: uppercase; }

    /* ===== WIDGET HEADERS ===== */
    .widget-header {
        font-size: 0.7rem; letter-spacing: 2.5px; color: #f8fafc;
        font-weight: 800; text-transform: uppercase;
        display: flex; align-items: center; gap: 8px;
    }
    .widget-header i { font-size: 0.85rem; }
    .widget-link {
        font-size: 0.6rem; color: #94a3b8; text-decoration: none !important;
        letter-spacing: 1px; font-weight: 700; transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.08); padding: 4px 12px; border-radius: 8px;
        background: rgba(255,255,255,0.01);
    }
    .widget-link:hover {
        color: #3b82f6; border-color: rgba(59, 130, 246, 0.3);
        background: rgba(59, 130, 246, 0.05); box-shadow: 0 0 15px rgba(59,130,246,0.1);
    }

    /* ===== HOLOGRAPHIC RADAR CARDS ===== */
    .radar-card {
        background: rgba(10, 10, 15, 0.75);
        backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.04);
        border-radius: 16px; padding: 1.4rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important; display: block;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.7), inset 0 1px 0 rgba(255,255,255,0.02);
        position: relative; overflow: hidden;
    }
    .radar-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: 
            linear-gradient(rgba(255,255,255,0.01) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.01) 1px, transparent 1px);
        background-size: 15px 15px;
        pointer-events: none; opacity: 0.6;
    }
    .radar-card:hover {
        transform: translateY(-4px);
        border-color: var(--radar-glow, #3b82f6);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8), 0 0 25px rgba(var(--radar-glow-rgb, 59, 130, 246), 0.15);
    }

    /* ===== ACTIVITY TERMINAL ROWS ===== */
    .activity-row {
        background: rgba(255, 255, 255, 0.01);
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-left: 3px solid #22c55e;
        border-radius: 10px; padding: 0.8rem 1.1rem; margin-bottom: 0.6rem;
        transition: all 0.3s ease;
    }
    .activity-row:hover {
        background: rgba(34, 197, 94, 0.04);
        border-color: rgba(34, 197, 94, 0.15);
        border-left-color: #22c55e;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        transform: translateX(2px);
    }

    /* ===== COMMAND BUTTONS (Táctiles de Consola) ===== */
    .cmd-btn {
        background: rgba(15, 15, 25, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.04);
        color: #fff !important; border-radius: 12px; padding: 12px 16px;
        font-weight: 600; font-size: 0.8rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex; align-items: center; gap: 12px;
        width: 100%; text-align: left; margin-bottom: 8px;
        text-decoration: none !important;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.01), 0 4px 10px rgba(0,0,0,0.2);
    }
    .cmd-btn:hover {
        background: rgba(var(--btn-glow-rgb, 255, 255, 255), 0.05);
        border-color: var(--btn-glow, rgba(255, 255, 255, 0.2));
        color: #fff !important; transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.5), 0 0 15px rgba(var(--btn-glow-rgb, 255, 255, 255), 0.08);
    }
    .cmd-btn i {
        font-size: 1.1rem; opacity: 0.8; transition: all 0.3s;
        width: 24px; height: 24px; border-radius: 8px;
        background: rgba(255,255,255,0.02);
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .cmd-btn:hover i {
        opacity: 1; transform: scale(1.1);
        background: rgba(var(--btn-glow-rgb, 255, 255, 255), 0.1);
        border-color: rgba(var(--btn-glow-rgb, 255, 255, 255), 0.2);
    }
    .cmd-btn .cmd-label { font-size: 0.78rem; font-weight: 700; color: #f8fafc; }
    .cmd-btn .cmd-sub { font-size: 0.62rem; color: #64748b; font-weight: 500; margin-top: 1px; }

    /* ===== COMPANY & TICKETS ROWS ===== */
    .company-row {
        background: rgba(255, 255, 255, 0.01);
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 12px; padding: 0.85rem 1.1rem; margin-bottom: 0.6rem;
        transition: all 0.3s ease;
    }
    .company-row:hover {
        background: rgba(59, 130, 246, 0.03);
        border-color: rgba(59, 130, 246, 0.15);
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .company-avatar {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.72rem; color: #fff;
        border: 1px solid rgba(255,255,255,0.05);
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .ticket-row {
        border: 1px solid rgba(255,255,255,0.01);
        border-radius: 8px;
        margin-bottom: 0.4rem; padding: 0.6rem 0.8rem;
        transition: all 0.3s ease;
    }
    .ticket-row:hover {
        background: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.06);
        transform: translateX(2px);
    }

    /* ===== INFRASTRUCTURE HARDWARE MONITOR ===== */
    .resource-item {
        background: rgba(10, 10, 15, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 10px;
        padding: 0.7rem;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.01);
    }
    .resource-item:hover {
        border-color: rgba(234, 179, 8, 0.2);
        background: rgba(234, 179, 8, 0.03);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .resource-item .res-value { font-size: 0.95rem; font-weight: 800; color: #f8fafc; }
    .resource-item .res-label { font-size: 0.48rem; letter-spacing: 1px; color: #64748b; font-weight: 700; margin-top: 2px; }

    /* ===== LIVE STATUS INDICATORS ===== */
    .live-dot {
        width: 6px; height: 6px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        box-shadow: 0 0 10px #22c55e, 0 0 20px rgba(34, 197, 94, 0.4);
        animation: pulse-dot 2s infinite;
    }
    .live-dot-red {
        background: #ef4444;
        box-shadow: 0 0 10px #ef4444, 0 0 20px rgba(239, 68, 68, 0.4);
        animation: pulse-dot 1s infinite;
    }
    .live-dot-cyan {
        background: #22d3ee;
        box-shadow: 0 0 10px #22d3ee, 0 0 20px rgba(34, 211, 238, 0.4);
        animation: pulse-dot 1.5s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
    }

    /* ===== RADAR sweep scan effect ===== */
    .radar-sweep {
        position: absolute;
        top: 0; left: 0;
        width: 250%; height: 100%;
        background: linear-gradient(110deg, transparent 40%, rgba(255, 255, 255, 0.05) 50%, transparent 60%);
        animation: sweep 5s linear infinite;
        pointer-events: none;
    }
    @keyframes sweep {
        0% { transform: translateX(-60%); }
        100% { transform: translateX(60%); }
    }

    /* ===== ADVANCED ULTRA-SLIM SCROLLBAR ===== */
    .custom-scroll::-webkit-scrollbar { width: 4px; }
    .custom-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.01); border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 10px; transition: all 0.3s; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* ===== SECTION DIVIDER ===== */
    .section-line {
        height: 1px;
        background: linear-gradient(90deg, transparent 5%, rgba(59, 130, 246, 0.15) 30%, rgba(168, 85, 247, 0.1) 50%, rgba(34, 211, 238, 0.08) 70%, transparent 95%);
        margin: 1.8rem 0;
    }

    /* ===== EXECUTIVE ACTION BUTTONS ===== */
    .deploy-btn {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #fff;
        font-weight: 800;
        font-size: 0.75rem;
        padding: 10px 22px;
        border-radius: 10px;
        letter-spacing: 1px;
        box-shadow: 0 5px 20px rgba(29, 78, 216, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
    }
    .deploy-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(29, 78, 216, 0.55), 0 0 15px rgba(59, 130, 246, 0.2);
        color: #fff;
        border-color: rgba(59, 130, 246, 0.5);
    }
    .status-badge {
        font-size: 0.52rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 800;
        letter-spacing: 0.5px;
        backdrop-filter: blur(8px);
    }

    /* ===== METRICS GLOW TEXTS ===== */
    .text-glow-primary { text-shadow: 0 0 20px rgba(59, 130, 246, 0.4); }
    .text-glow-success { text-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }
    .text-glow-purple { text-shadow: 0 0 20px rgba(168, 85, 247, 0.4); }
    .text-glow-warning { text-shadow: 0 0 20px rgba(234, 179, 8, 0.4); }
</style>
@endsection

@section('content')
<div class="command-center-bg px-2 pb-5" style="position: relative; z-index: 1;">

    {{-- ═══════════════════════════════════════════
         HEADER: CENTRO DE MANDO
    ═══════════════════════════════════════════ --}}
    <div class="d-flex justify-content-between align-items-end mb-4 pt-3" style="padding: 0 10px;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div style="padding: 4px 14px; background: rgba(59,130,246,0.05); border: 1px solid rgba(59,130,246,0.25); border-radius: 20px; display: flex; align-items: center; gap: 8px;">
                    <span class="live-dot"></span>
                    <span style="font-size: 9px; font-weight: 800; color: #60a5fa; text-transform: uppercase; letter-spacing: 3px;">Sistema Operativo Master</span>
                </div>
                <span class="header-version">v1.02.0</span>
            </div>
            <h1 class="header-title mb-0">Centro de Mando</h1>
        </div>
        <div>
            <a href="{{ route('owner.empresas.create') }}" class="deploy-btn d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>DESPLEGAR EMPRESA</span>
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ROW 1: KPIs PRINCIPALES (6 métricas clave con resplandores)
    ═══════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <a href="{{ route('owner.empresas.index') }}" class="kpi-card" style="--card-glow: var(--neon-blue); --card-glow-rgb: 59, 130, 246;">
                <div class="kpi-bg" style="background: linear-gradient(135deg, rgba(30, 58, 138, 0.4) 0%, rgba(15, 23, 42, 0) 100%);"></div>
                <div class="kpi-icon"><i class="bi bi-building text-primary"></i></div>
                <div class="kpi-value text-glow-primary">{{ $empresasCount }}</div>
                <div class="kpi-label">EMPRESAS <span class="text-primary" style="font-weight:800; font-size:0.5rem;">· {{ $empresasActivas }} ON</span></div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('owner.empresas.index') }}" class="kpi-card" style="--card-glow: var(--neon-purple); --card-glow-rgb: 168, 85, 247;">
                <div class="kpi-bg" style="background: linear-gradient(135deg, rgba(88, 28, 135, 0.4) 0%, rgba(15, 23, 42, 0) 100%);"></div>
                <div class="kpi-icon"><i class="bi bi-people-fill" style="color: #c084fc;"></i></div>
                <div class="kpi-value text-glow-purple">{{ $usuariosCount }}</div>
                <div class="kpi-label">USUARIOS</div>
            </a>
        </div>
        <div class="col-md-2">
            <div class="kpi-card" style="--card-glow: var(--neon-cyan); --card-glow-rgb: 34, 211, 238;">
                <div class="kpi-bg" style="background: linear-gradient(135deg, rgba(14, 116, 144, 0.4) 0%, rgba(15, 23, 42, 0) 100%);"></div>
                <div class="kpi-icon"><i class="bi bi-box-seam" style="color: #22d3ee;"></i></div>
                <div class="kpi-value" style="text-shadow: 0 0 20px rgba(34, 211, 238, 0.4);">{{ $articulosCount }}</div>
                <div class="kpi-label">SKU TOTAL</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="kpi-card" style="--card-glow: var(--neon-green); --card-glow-rgb: 34, 197, 94;">
                <div class="kpi-bg" style="background: linear-gradient(135deg, rgba(20, 83, 45, 0.4) 0%, rgba(15, 23, 42, 0) 100%);"></div>
                <div class="kpi-icon"><i class="bi bi-person-check" style="color: #4ade80;"></i></div>
                <div class="kpi-value text-glow-success">{{ $clientesCount }}</div>
                <div class="kpi-label">CLIENTES</div>
            </div>
        </div>
        <div class="col-md-2">
            <a href="{{ route('owner.facturacion.index') }}" class="kpi-card" style="--card-glow: var(--neon-gold); --card-glow-rgb: 234, 179, 8;">
                <div class="kpi-bg" style="background: linear-gradient(135deg, rgba(234, 179, 8, 0.25) 0%, rgba(15, 23, 42, 0) 100%);"></div>
                <div class="kpi-icon"><i class="bi bi-graph-up-arrow" style="color: #facc15;"></i></div>
                <div class="kpi-value text-glow-warning" style="font-size: 1.4rem;">{{ $mrr }}</div>
                <div class="kpi-label">MRR MENSUAL</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('owner.facturacion.index') }}" class="kpi-card" style="--card-glow: var(--neon-green); --card-glow-rgb: 34, 197, 94;">
                <div class="kpi-bg" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.3) 0%, rgba(15, 23, 42, 0) 100%);"></div>
                <div class="kpi-icon"><i class="bi bi-cash-stack text-success"></i></div>
                <div class="kpi-value text-glow-success" style="font-size: 1.4rem;">{{ $facturacionMes }}</div>
                <div class="kpi-label">VENTAS MES</div>
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ROW 2: VELOCÍMETROS DE SALUD (3 gauges con cajas de cristal premium)
    ═══════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="gauge-card-container h-100 d-flex flex-column align-items-center justify-content-center p-4" style="--gauge-glow: var(--neon-blue); --gauge-glow-rgb: 59, 130, 246;">
                <div id="chartSales" style="min-height: 170px; width: 100%;"></div>
                <div class="gauge-label text-primary mt-2">VELOCIDAD DE VENTAS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gauge-card-container h-100 d-flex flex-column align-items-center justify-content-center p-4" style="--gauge-glow: var(--neon-danger); --gauge-glow-rgb: 239, 68, 68;">
                <div id="chartExpenses" style="min-height: 170px; width: 100%;"></div>
                <div class="gauge-label text-danger mt-2">CONTROL DE GASTOS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gauge-card-container h-100 d-flex flex-column align-items-center justify-content-center p-4" style="--gauge-glow: var(--neon-green); --gauge-glow-rgb: 34, 197, 94;">
                <div id="chartGlobal" style="min-height: 170px; width: 100%;"></div>
                <div class="gauge-label text-success mt-2">SALUD GLOBAL</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ROW 3: RADAR DE ADQUISICIÓN (3 cards holográficas)
    ═══════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-12 mb-1">
            <span class="widget-header" style="letter-spacing: 2px;"><span class="live-dot live-dot-red"></span> RADAR DE ADQUISICIÓN EXTERNA</span>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="radar-card" style="--radar-glow: var(--neon-purple); --radar-glow-rgb: 168, 85, 247;">
                <div class="radar-sweep"></div>
                <div class="kpi-label" style="color: #c084fc; font-size: 0.55rem; font-weight:800;">VISITANTES LANDING</div>
                <div class="d-flex align-items-end gap-2 mt-2">
                    <span class="kpi-value text-glow-purple" style="font-size: 2.5rem; font-weight: 800;">{{ $landingVisits }}</span>
                    <span class="text-success fw-bold mb-1" style="font-size: 0.72rem;"><i class="bi bi-arrow-up-right"></i> +14%</span>
                </div>
                <div class="kpi-label mt-1">PERSONAS HOY</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="radar-card" style="--radar-glow: var(--neon-blue); --radar-glow-rgb: 59, 130, 246;">
                <div class="radar-sweep" style="background: linear-gradient(110deg, transparent 40%, rgba(59, 130, 246, 0.05) 50%, transparent 60%);"></div>
                <div class="kpi-label" style="color: #93c5fd; font-size: 0.55rem; font-weight:800;">ENTRADAS AL DEMO</div>
                <div class="d-flex align-items-end gap-2 mt-2">
                    <span class="kpi-value text-glow-primary" style="font-size: 2.5rem; font-weight: 800;">{{ $demoEntries }}</span>
                    <span class="text-success fw-bold mb-1" style="font-size: 0.72rem;"><i class="bi bi-arrow-up-right"></i> Activos</span>
                </div>
                <div class="kpi-label mt-1">PRUEBAS EN CURSO</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="radar-card" style="--radar-glow: var(--neon-green); --radar-glow-rgb: 34, 197, 94;">
                <div class="radar-sweep" style="background: linear-gradient(110deg, transparent 40%, rgba(34, 197, 94, 0.05) 50%, transparent 60%);"></div>
                <div class="kpi-label" style="color: #86efac; font-size: 0.55rem; font-weight:800;">CONVERSIÓN DE BOT</div>
                <div class="d-flex align-items-end gap-2 mt-2">
                    <span class="kpi-value text-glow-success" style="font-size: 2.5rem; font-weight: 800;">{{ $conversionRate }}%</span>
                    <span class="text-white-50 fw-bold mb-1" style="font-size: 0.72rem;">Efectividad</span>
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
            {{-- WIDGET: RED DE REVENDEDORES (ESCALONADO) --}}
            <div class="oled-card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="widget-header"><i class="bi bi-diagram-3 me-1 text-info"></i> RED DE REVENDEDORES</span>
                    <a href="{{ route('owner.resellers.index') }}" class="widget-link">GESTIONAR <i class="bi bi-arrow-right"></i></a>
                </div>
                
                @php 
                    $resellersList = \App\Models\User::where('role', 'revendedor')->withCount('empresasAsignadas')->take(5)->get();
                @endphp
                
                @forelse($resellersList as $reseller)
                <div class="company-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="company-avatar" style="background: rgba(34, 211, 238, 0.08); border: 1px solid rgba(34, 211, 238, 0.25); color: #22d3ee; text-shadow: 0 0 10px rgba(34, 211, 238, 0.3);">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <div class="text-white fw-bold" style="font-size: 0.85rem;">{{ $reseller->name }}</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="status-badge" style="background: rgba(34, 211, 238, 0.08); color: #22d3ee; border: 1px solid rgba(34, 211, 238, 0.15);">{{ $reseller->empresas_asignadas_count }} CLIENTES</span>
                                <span class="text-muted" style="font-size: 0.62rem;">{{ $reseller->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('owner.resellers.impersonate', $reseller->id) }}" class="btn btn-sm btn-info px-3 fw-bold text-dark" style="font-size: 0.65rem; border-radius: 8px; box-shadow: 0 0 10px rgba(34, 211, 238, 0.25);"><i class="bi bi-box-arrow-in-right me-1"></i> ENTRAR</a>
                    </div>
                </div>
                @empty
                <div class="text-center py-3">
                    <span class="text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;"><i class="bi bi-person-x me-1"></i> NO HAY REVENDEDORES</span>
                </div>
                @endforelse
            </div>

            {{-- WIDGET: BITÁCORA GLOBAL DE OPERACIONES (TIEMPO REAL) --}}
            <div class="oled-card mb-3" style="border-color: rgba(34, 197, 94, 0.15); background: rgba(10, 10, 15, 0.85);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="widget-header" style="color: #fff; font-weight: 800;"><span class="live-dot"></span> <i class="bi bi-clock-history me-1 text-success"></i> MONITOR DE OPERACIONES GLOBAL (SaaS)</span>
                    <span class="header-version" style="background: rgba(34, 197, 94, 0.08); color: #22c55e; border-color: rgba(34, 197, 94, 0.25); text-shadow: 0 0 10px rgba(34, 197, 94, 0.3);">TIEMPO REAL</span>
                </div>
                <div class="custom-scroll" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                    @forelse($globalActivities as $log)
                    <div class="activity-row d-flex align-items-center gap-3" style="background: rgba(34, 197, 94, 0.04); border-color: rgba(34, 197, 94, 0.2); border-left-color: #22c55e;">
                        <div class="text-white-50 font-monospace text-center" style="font-size: 0.65rem; min-width: 45px;">
                            <div class="fw-bold text-success">{{ $log->created_at->format('H:i') }}</div>
                            <div style="font-size: 0.5rem; opacity: 0.7;">{{ $log->created_at->format('d/m') }}</div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold" style="font-size: 0.78rem; color: #fff;">{{ $log->empresa?->nombre_comercial ?? 'Sistema' }}</span>
                                <span class="status-badge" style="background: rgba(255, 255, 255, 0.03); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.08);">{{ $log->user?->name ?? 'Admin' }}</span>
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


            {{-- WIDGET: TICKETS DE SOPORTE --}}
            <div class="oled-card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="widget-header"><i class="bi bi-headset me-1 text-danger"></i> TICKETS DE SOPORTE</span>
                    <a href="{{ route('owner.soporte.index') }}" class="widget-link">VER TODOS <i class="bi bi-arrow-right"></i></a>
                </div>
                @forelse($ultimosTickets as $ticket)
                <div class="ticket-row d-flex justify-content-between align-items-center rounded p-2 mb-1" style="background: {{ str_contains($ticket->subject, '[CRÍTICO]') ? 'rgba(239, 68, 68, 0.08)' : 'transparent' }};">
                    <div class="d-flex align-items-center gap-2">
                        @if(str_contains($ticket->subject, '[CRÍTICO]'))
                            <span class="live-dot live-dot-red" title="Error de Sistema Detectado"></span>
                        @endif
                        <div>
                            <div class="text-white fw-bold" style="font-size: 0.8rem;">
                                {{ $ticket->empresa?->nombre_comercial ?? 'GENERAL' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                {{ Str::limit($ticket->subject, 48) }}
                            </div>
                        </div>
                    </div>
                    <span class="status-badge" style="background: rgba({{ $ticket->status == 'abierto' ? '239, 68, 68' : '34, 211, 238' }}, 0.1); color: {{ $ticket->status == 'abierto' ? '#ef4444' : '#22d3ee' }}; border: 1px solid rgba({{ $ticket->status == 'abierto' ? '239, 68, 68' : '34, 211, 238' }}, 0.2);">
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
                    <span class="widget-header"><i class="bi bi-building me-1 text-white-50"></i> ÚLTIMAS EMPRESAS DESPLEGADAS</span>
                    <a href="{{ route('owner.empresas.index') }}" class="widget-link">GESTIONAR <i class="bi bi-arrow-right"></i></a>
                </div>
                @php $colors = ['#3b82f6','#a855f7','#22c55e','#eab308','#ef4444']; @endphp
                @foreach($ultimasEmpresas as $idx => $emp)
                <div class="company-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="company-avatar" style="background: {{ $colors[$idx % 5] }}20; border: 1px solid {{ $colors[$idx % 5] }}30; color: {{ $colors[$idx % 5] }}; text-shadow: 0 0 10px {{ $colors[$idx % 5] }}40;">
                            {{ strtoupper(substr($emp->nombre_comercial, 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-white fw-bold" style="font-size: 0.85rem;">{{ $emp->nombre_comercial }}</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="status-badge" style="background: rgba(59, 130, 246, 0.08); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.15);">{{ $emp->plan?->name ?? $emp->plan?->nombre ?? 'BÁSICO' }}</span>
                                <span class="text-muted" style="font-size: 0.62rem;">{{ $emp->created_at->format('d/m/Y') }}</span>
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
                        <a href="{{ url('owner/mimetizar/empresa/' . $emp->id . '/usuario/' . $admin->id) }}" class="btn btn-sm btn-primary px-3 fw-bold" style="font-size: 0.65rem; border-radius: 8px;">ENTRAR</a>
                        @endif
                        <a href="{{ url('owner/empresas/' . $emp->id . '/edit') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border-color: rgba(255,255,255,0.08); color: #cbd5e1;"><i class="bi bi-pencil"></i></a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        {{-- ══ COLUMNA LATERAL (4/12) ══ --}}
        <div class="col-lg-4">

            {{-- WIDGET: PANEL DE OPERACIONES (Terminal de mandos táctil) --}}
            <div class="oled-card mb-3">
                <span class="widget-header d-block mb-3" style="color:#fff;"><i class="bi bi-grid-3x3-gap text-white-50"></i> PANEL DE CONTROL</span>

                <a href="{{ route('owner.crm.index') }}" class="cmd-btn" style="--btn-glow: var(--neon-blue); --btn-glow-rgb: 59, 130, 246; border-color: rgba(59, 130, 246, 0.15); background: rgba(59, 130, 246, 0.02);">
                    <i class="bi bi-people-fill text-primary"></i>
                    <div><div class="cmd-label">CRM ESTRATÉGICO</div><div class="cmd-sub">Prospectos · Kanban · Leads</div></div>
                </a>
                <a href="{{ route('owner.empresas.index') }}" class="cmd-btn" style="--btn-glow: var(--neon-cyan); --btn-glow-rgb: 34, 211, 238;">
                    <i class="bi bi-building text-info"></i>
                    <div><div class="cmd-label">GESTIÓN DE EMPRESAS</div><div class="cmd-sub">Suscripciones y despliegues</div></div>
                </a>
                <a href="{{ route('owner.facturacion.index') }}" class="cmd-btn" style="--btn-glow: var(--neon-green); --btn-glow-rgb: 34, 197, 94;">
                    <i class="bi bi-wallet2 text-success"></i>
                    <div><div class="cmd-label">CENTRO FINANCIERO</div><div class="cmd-sub">Cobranzas y MRR</div></div>
                </a>
                <a href="{{ route('owner.soporte.index') }}" class="cmd-btn" style="--btn-glow: var(--neon-danger); --btn-glow-rgb: 239, 68, 68;">
                    <i class="bi bi-headset text-danger"></i>
                    <div><div class="cmd-label">CENTRAL DE SOPORTE</div><div class="cmd-sub">Tickets y resolución</div></div>
                </a>
                <a href="{{ route('owner.planes.index') }}" class="cmd-btn" style="--btn-glow: var(--neon-gold); --btn-glow-rgb: 234, 179, 8;">
                    <i class="bi bi-gear-wide-connected text-warning"></i>
                    <div><div class="cmd-label">PLANES SaaS</div><div class="cmd-sub">Precios y servicios</div></div>
                </a>
                <a href="{{ route('owner.updates.index') }}" class="cmd-btn" style="--btn-glow: var(--neon-purple); --btn-glow-rgb: 168, 85, 247;">
                    <i class="bi bi-broadcast-pin" style="color: #a855f7;"></i>
                    <div><div class="cmd-label">COMUNICADOS</div><div class="cmd-sub">Logs de actualización</div></div>
                </a>
                <a href="{{ route('owner.notifications.index') }}" class="cmd-btn" style="--btn-glow: var(--neon-green); --btn-glow-rgb: 34, 197, 94; border-color: rgba(34, 197, 94, 0.15); background: rgba(34, 197, 94, 0.02);">
                    <i class="bi bi-bell-fill text-success"></i>
                    <div><div class="cmd-label">NOTIFICACIONES</div><div class="cmd-sub">Historial de avisos SaaS</div></div>
                </a>
                <button type="button" class="cmd-btn" style="--btn-glow: var(--neon-gold); --btn-glow-rgb: 234, 179, 8; border-color: rgba(234, 179, 8, 0.15); background: rgba(234, 179, 8, 0.02); cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalSettings">
                    <i class="bi bi-sliders text-warning"></i>
                    <div><div class="cmd-label text-warning">AJUSTES GLOBALES</div><div class="cmd-sub">Configuración maestra</div></div>
                </button>
            </div>

            {{-- WIDGET: MONITOR DE RECURSOS (HARDWARE RACK MONITOR) --}}
            <div class="oled-card">
                <span class="widget-header d-block mb-3"><i class="bi bi-cpu text-white-50"></i> INFRAESTRUCTURA</span>

                {{-- Costo Proyectado --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted" style="font-size: 0.72rem; font-weight:600;">Costo Proyectado</span>
                    <span class="text-warning fw-bold text-glow-warning" style="font-size: 0.9rem;">{{ $costoProyectado }}</span>
                </div>
                <div class="progress mb-3" style="height: 4px; background: rgba(255,255,255,0.03); border-radius: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 45%; background: linear-gradient(90deg, #eab308, #f97316); box-shadow: 0 0 10px rgba(234, 179, 8, 0.4);"></div>
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
                <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(34, 197, 94, 0.03); border: 1px solid rgba(34, 197, 94, 0.15);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="live-dot" style="width: 5px; height: 5px; margin: 0;"></span>
                        <span class="text-muted" style="font-size: 0.6rem; letter-spacing: 1px; font-weight: 700;">UPTIME</span>
                    </div>
                    <span class="text-success fw-bold text-glow-success" style="font-size: 0.78rem;">99.9%</span>
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
        <div class="modal-content" style="background: #080808; border: 1px solid rgba(234, 179, 8, 0.25); border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.9);">
            <div class="modal-header border-bottom border-white border-opacity-5 py-3 px-4">
                <h5 class="modal-title text-white fw-bold" style="font-size: 0.95rem;"><i class="bi bi-sliders me-2 text-warning"></i>Ajustes del Sistema</h5>
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
                    value: { offsetY: -2, fontSize: '38px', fontWeight: '800', color: '#fff', formatter: v => v + '%' }
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
