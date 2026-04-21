<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>{{ config('app.name', 'MultiPOS') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="{{ ($config?->theme ?? 'light') === 'dark' ? '#000000' : '#ffffff' }}">

<link rel="manifest" href="{{ asset('manifest.json') }}">
<link rel="apple-touch-icon" href="{{ asset('img/logo_v2.png') }}">
<link rel="icon" href="{{ asset('favicon.png') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .help-content img { max-width: 100%; border-radius: 12px; margin: 15px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
    .note-editor.note-frame { border: 1px solid rgba(255,255,255,0.1) !important; background: rgba(0,0,0,0.2); }
    .note-editable { color: #fff !important; background: #000 !important; }
</style>

@php
    $user = auth()->user();
    $empresa = $user->empresa ?? null;
    $config  = $empresa?->config ?? null;
    $colorPrimario = $config?->color_primary ?? '#0d6efd';
    $colorSecundario = $config?->color_secondary ?? '#6c757d';
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
    $logo = ($config && $config->logo_url) ? $config->logo_url : asset('images/logo_premium.png');
    
    // Asistencia activa
    $asistenciaActiva = \App\Models\Asistencia::where('user_id', $user?->id)
        ->whereNull('salida')
        ->latest()
        ->first();

    $rgb = [0,0,0];
    if (preg_match('/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/i', $colorPrimario, $matches)) {
        $rgb = [hexdec($matches[1]), hexdec($matches[2]), hexdec($matches[3])];
    }
@endphp

<style>
:root {
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 85px;
    --navbar-height: 70px;
    --color-primario: {{ $colorPrimario }};
    --color-secundario: {{ $colorSecundario }};
    --color-primario-rgb: {{ implode(',', $rgb) }};
    --transition-speed: 0.4s;
    --transition-curve: cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Variable Dinámica Maestra */
    --actual-sidebar-width: var(--sidebar-width);

    @if($modoOscuro)
    --bg-main: #000000;
    --sidebar-bg: #0a0c0e;
    --sidebar-border: rgba(255,255,255,0.08);
    --text-main: #f8fafc;
    --card-bg: #0f172a;
    @else
    --bg-main: #f4f7fa;
    --sidebar-bg: #111827;
    --sidebar-border: rgba(0,0,0,0.05);
    --text-main: #1e293b;
    --card-bg: #ffffff;
    @endif
}

body.sidebar-collapsed {
    --actual-sidebar-width: var(--sidebar-collapsed-width);
}

body.no-sidebar {
    --actual-sidebar-width: 0px;
}

* { font-family: 'Plus Jakarta Sans', sans-serif; }

body { 
    background: var(--bg-main); 
    color: var(--text-main); 
    overflow-x: hidden; 
    margin: 0;
}

/* =========================================================
   SIDEBAR "SPACE COMMAND"
   ========================================================= */
#sidebar {
    width: var(--actual-sidebar-width);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: var(--sidebar-bg);
    z-index: 2005; 
    transition: width var(--transition-speed) var(--transition-curve), transform var(--transition-speed) var(--transition-curve);
    display: flex;
    flex-direction: column;
    box-shadow: 10px 0 30px rgba(0,0,0,0.15);
    overflow: hidden;
    border-right: 1px solid var(--sidebar-border);
}

.sidebar-header {
    min-height: 100px;
    display: flex;
    align-items: center;
    padding: 20px;
    background: rgba(255,255,255,0.02);
    border-bottom: 1px solid var(--sidebar-border);
    overflow: hidden;
    white-space: nowrap;
}

.sidebar-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: white;
}

.sidebar-logo img { 
    height: 42px; 
    width: 42px;
    object-fit: contain;
    filter: drop-shadow(0 0 10px rgba(var(--color-primario-rgb), 0.3));
    transition: transform 0.3s;
}

.sidebar-logo span { 
    font-weight: 800; 
    font-size: 0.95rem; 
    color: #ffffff; 
    letter-spacing: -0.5px;
    transition: opacity 0.3s, transform 0.3s;
}

/* Estilos para estado colapsado coordinados por body class */
body.sidebar-collapsed #sidebar .sidebar-logo span,
body.sidebar-collapsed #sidebar .nav-label,
body.sidebar-collapsed #sidebar .nav-link-item span,
body.sidebar-collapsed #sidebar .submenu-collapse {
    display: none !important;
}

body.sidebar-collapsed #sidebar .nav-link-item {
    justify-content: center;
    padding: 12px 0;
    margin: 4px 10px;
}

body.sidebar-collapsed #sidebar .nav-link-item i {
    margin: 0;
    font-size: 1.3rem;
}

.sidebar-nav { 
    flex-grow: 1; 
    padding: 15px 0;
    overflow-y: auto; 
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.1) transparent;
}

.nav-label {
    padding: 20px 25px 8px;
    font-size: 0.65rem;
    font-weight: 800;
    color: rgba(255,255,255,0.5); 
    text-transform: uppercase;
    letter-spacing: 1.5px;
    white-space: nowrap;
}

.nav-link-item {
    display: flex;
    align-items: center;
    padding: 10px 18px;
    color: #ffffff !important; 
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    margin: 2px 14px;
    border-radius: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}

.nav-link-item:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff !important;
    transform: translateX(5px) scale(1.02); 
    text-shadow: 0 0 8px rgba(255,255,255,0.4); 
}

.nav-link-item.active {
    background: var(--color-primario);
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(var(--color-primario-rgb), 0.4);
}

.nav-link-item i { 
    width: 24px; 
    font-size: 1.1rem; 
    margin-right: 12px; 
    display: flex; 
    justify-content: center;
}

.submenu-collapse {
    background: rgba(255, 255, 255, 0.02);
    margin: 2px 14px 8px 14px;
    border-radius: 10px;
    padding: 5px 0;
}

.submenu-item {
    display: block;
    padding: 8px 18px 8px 45px;
    color: #ffffff !important; 
    text-decoration: none !important;
    font-size: 0.82rem;
    font-weight: 500;
    transition: all 0.3s ease;
    opacity: 0.85;
}

.submenu-item:hover {
    color: #ffffff !important;
    opacity: 1;
    background: rgba(255, 255, 255, 0.08);
    transform: translateX(8px); 
    text-shadow: 0 0 5px rgba(255,255,255,0.3);
}

.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    z-index: 2000;
    display: none;
    opacity: 0;
    transition: opacity 0.3s;
}

/* =========================================================
   INTEGRATED MAIN CONTENT & TOP BAR
   Sincronizado al 100% con --actual-sidebar-width
   ========================================================= */
#main-content {
    margin-left: var(--actual-sidebar-width);
    min-height: 100vh;
    transition: margin-left var(--transition-speed) var(--transition-curve), width var(--transition-speed) var(--transition-curve);
    background: var(--bg-main);
    padding-top: 90px; 
    padding-left: 45px;
    padding-right: 45px;
    position: relative;
    width: calc(100% - var(--actual-sidebar-width));
}

.top-bar {
    position: fixed;
    top: 0;
    right: 0;
    left: var(--actual-sidebar-width);
    height: 70px;
    background: {{ $modoOscuro ? 'rgba(0, 0, 0, 0.92)' : 'rgba(255, 255, 255, 0.98)' }};
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border-bottom: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.12)' : 'rgba(0,0,0,0.06)' }};
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2.5rem;
    z-index: 1000; 
    transition: left var(--transition-speed) var(--transition-curve), width var(--transition-speed) var(--transition-curve);
}

/* UI Elements */
.btn-sidebar-toggle {
    background: transparent;
    border: none;
    color: var(--text-main);
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    transition: background 0.2s;
}
.btn-sidebar-toggle:hover { background: rgba(0,0,0,0.05); }

/* Optimizaciones Mobile */
@media (max-width: 991px) {
    #sidebar {
        transform: translateX(-100%);
        width: 280px !important;
        --actual-sidebar-width: 0px !important;
    }
    #sidebar.show {
        transform: translateX(0);
    }
    #sidebar.show ~ .sidebar-overlay {
        display: block;
        opacity: 1;
    }
    #main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }
    .top-bar {
        left: 0 !important;
    }
}

/* =========================================================
   BOTÓN MÁGICO (ASISTENTE 360)
   ========================================================= */
#help-trigger {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--color-primario), #a855f7);
    color: white;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 10px 25px rgba(var(--color-primario-rgb), 0.4);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 2px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
}

#help-trigger:hover {
    transform: scale(1.1) rotate(15deg);
    box-shadow: 0 15px 35px rgba(var(--color-primario-rgb), 0.6);
}

@keyframes pulse-magic {
    0% { box-shadow: 0 0 0 0 rgba(var(--color-primario-rgb), 0.4); }
    70% { box-shadow: 0 0 0 15px rgba(var(--color-primario-rgb), 0); }
    100% { box-shadow: 0 0 0 0 rgba(var(--color-primario-rgb), 0); }
}

#help-trigger {
    animation: pulse-magic 2s infinite;
}

/* OFFCANVAS DE AYUDA PREMIUM */
.offcanvas-help {
    position: fixed;
    top: 20px;
    right: -450px;
    bottom: 20px;
    width: 400px;
    background: {{ $modoOscuro ? 'rgba(10, 12, 14, 0.95)' : 'rgba(255, 255, 255, 0.98)' }};
    backdrop-filter: blur(20px);
    z-index: 10000;
    border-radius: 25px 0 0 25px;
    box-shadow: -20px 0 50px rgba(0,0,0,0.3);
    transition: right 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(255,255,255,0.1);
}

.offcanvas-help.show { right: 0; }

</style>

@stack('styles')
</head>

<body class="{{ isset($posMode) ? 'no-sidebar' : '' }}">

<!-- SIDEBAR MODERNO -->
@if(!isset($posMode))
<div id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('empresa.dashboard') }}" class="sidebar-logo">
            <div class="bg-white p-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <img src="{{ $logo }}" alt="Logo" style="max-height: 100%;">
            </div>
            <span>{{ $empresa->nombre_comercial ?? 'MultiPOS' }}</span>
        </a>
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('empresa.dashboard') }}" class="nav-link-item {{ Route::is('empresa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
        </a>

        {{-- MÓDULO: ARTÍCULOS --}}
        <div class="nav-label text-info">Gestión de Productos</div>
        
        <a href="{{ route('empresa.products.index') }}" class="nav-link-item {{ Request::is('empresa/products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> <span>📑 MIS ARTÍCULOS</span>
        </a>

        <a href="{{ route('empresa.rubros.index') }}" class="nav-link-item {{ Request::is('empresa/rubros*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> <span>🏷️ RUBROS / CATEGORÍAS</span>
        </a>

        <a href="{{ (auth()->user()?->empresa?->slug) ? route('catalog.index', ['empresa' => auth()->user()->empresa->slug]) : '#' }}" target="_blank" class="nav-link-item">
            <i class="bi bi-shop"></i> <span>🛒 MI CATÁLOGO (Store)</span>
        </a>

        <a href="#sm_articulos" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-gear-wide-connected"></i> <span>Herramientas de Stock</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/stock*') || Request::is('empresa/faltantes*') ? 'show' : '' }}" id="sm_articulos">
            <a href="{{ route('empresa.stock.index') }}" class="submenu-item">🔄 Movimientos Stock</a>
            <a href="{{ route('empresa.stock.faltantes') }}" class="submenu-item fw-bold text-warning">⚠️ Reposición de Stock</a>
            <a href="{{ route('empresa.inventory_scan') }}" class="submenu-item fw-bold">📲 Escáner Móvil</a>
            <a href="{{ route('empresa.recipes.index') }}" class="submenu-item">🧪 Recetas de Fábrica</a>
        </div>

        {{-- MÓDULO: VENTAS --}}
        <div class="nav-label text-warning">Área de Ventas</div>
        <a href="{{ route('empresa.pos.index') }}" class="nav-link-item {{ Route::is('empresa.pos.index') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> <span>🏪 VENTAS (POS)</span>
        </a>
        <a href="#sm_ventas" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-receipt"></i> <span>Gestión Ventas</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/ventas*') || Request::is('empresa/presupuestos*') ? 'show' : '' }}" id="sm_ventas">
            <a href="{{ route('empresa.ventas.index') }}" class="submenu-item">📋 Historial Ventas</a>
            <a href="{{ route('empresa.ventas.manual') }}" class="submenu-item fw-bold">✍️ Venta Manual</a>
            <a href="{{ route('empresa.presupuestos.index') }}" class="submenu-item">📜 Presupuestos</a>
        </div>

        {{-- MÓDULO: LOGÍSTICA --}}
        <div class="nav-label text-white">Logística & Guarda</div>
        <a href="#sm_logistica" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-truck"></i> <span>Entregas (Remitos)</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/remitos*') || Request::is('empresa/logistica*') ? 'show' : '' }}" id="sm_logistica">
            <a href="{{ route('empresa.logistica.reporte') }}" class="submenu-item fw-bold">📦 Stock en Guarda</a>
            <a href="{{ route('empresa.remitos.index') }}" class="submenu-item">📋 Historial Remitos</a>
        </div>

        {{-- MÓDULO: GASTOS --}}
        <div class="nav-label text-danger">Área Financiera</div>
        <a href="{{ route('empresa.gastos.index') }}" class="nav-link-item {{ Request::is('empresa/gastos*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> <span>💸 GESTIÓN GASTOS</span>
        </a>

        {{-- MÓDULO: COMPRAS --}}
        <div class="nav-label text-danger">Área de Compras</div>
        <a href="#sm_compras" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-cart-check"></i> <span>Abastecimiento</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/compras*') ? 'show' : '' }}" id="sm_compras">
            <a href="{{ route('empresa.compras.create') }}" class="submenu-item fw-bold">🟢 Nueva Compra</a>
            <a href="{{ route('empresa.stock.faltantes') }}" class="submenu-item">📋 Plan de Reposición</a>
            <a href="{{ route('empresa.compras.index') }}" class="submenu-item">📋 Historial Compras</a>
        </div>

        {{-- MÓDULO: CLIENTES --}}
        <div class="nav-label text-primary">Área de Clientes</div>
        <a href="#sm_clientes" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-people"></i> <span>Cartera Clientes</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/clientes*') || Request::is('empresa/pagos*') ? 'show' : '' }}" id="sm_clientes">
            <a href="{{ route('empresa.clientes.index') }}" class="submenu-item">👥 Listado Clientes</a>
            <a href="{{ route('empresa.pagos.index') }}" class="submenu-item fw-bold">💰 Cta. Cte. y Recibos</a>
        </div>

        {{-- MÓDULO: PROVEEDORES --}}
        <div class="nav-label text-success">Área de Proveedores</div>
        <a href="#sm_proveedores" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-truck"></i> <span>Gestión Proveedores</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/proveedores*') ? 'show' : '' }}" id="sm_proveedores">
            <a href="{{ route('empresa.proveedores.index') }}" class="submenu-item">🚛 Mis Proveedores</a>
            <a href="{{ route('empresa.proveedores.index', ['has_debt' => 1]) }}" class="submenu-item fw-bold">🏦 Cuentas Corrientes</a>
        </div>

        {{-- MÓDULO: BANCO --}}
        <div class="nav-label">Área de Bancos</div>
        <a href="#sm_bancos" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-bank"></i> <span>Bancos & Billeteras</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/tesoreria*') ? 'show' : '' }}" id="sm_bancos">
            <a href="{{ route('empresa.tesoreria.index') }}" class="submenu-item fw-bold">🏦 Mis Cuentas / Billeteras</a>
            <a href="{{ route('empresa.tesoreria.cheques.index') }}" class="submenu-item">✍️ Cheques de Terceros</a>
            <a href="{{ route('empresa.tesoreria.chequeras.index') }}" class="submenu-item">📖 Chequeras Propias</a>
        </div>

        {{-- MÓDULO: REPORTES --}}
        <div class="nav-label">Centro de Inteligencia</div>
        <a href="#sm_reportes" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-bar-chart-line"></i> <span>Reportes & Estadísticas</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/reportes*') ? 'show' : '' }}" id="sm_reportes">
            <a href="{{ route('empresa.reportes.panel') }}" class="submenu-item fw-bold">📊 Dashboard Global</a>
            <a href="{{ route('empresa.reportes.caja_diaria') }}" class="submenu-item">💵 Caja Diaria / Auditoría</a>
            <a href="{{ route('empresa.gps.index') }}" class="submenu-item text-warning fw-bold"><i class="bi bi-geo-alt-fill me-1"></i> Utilidades GPS (Beta)</a>
            <a href="{{ route('empresa.clientes.index', ['has_debt' => 1]) }}" class="submenu-item">📉 Morosidad Clientes</a>
        </div>

        {{-- ADMINISTRACIÓN --}}
        <div class="nav-label">Administración</div>
        <a href="#sm_admin" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-person-badge"></i> <span>Recursos Humanos</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/usuarios*') || Request::is('empresa/personal*') ? 'show' : '' }}" id="sm_admin">
            <a href="{{ route('empresa.usuarios.index') }}" class="submenu-item">👥 Gestión Usuarios</a>
            <a href="{{ route('empresa.personal.rendimiento') }}" class="submenu-item">📊 Rendimiento Operativo</a>
            <a href="{{ route('empresa.personal.asistencia.qr') }}" class="submenu-item fw-bold">📲 Punto QR Asistencia</a>
        </div>

        <a href="{{ route('empresa.reportes.panel') }}" class="nav-link-item {{ Route::is('empresa.reportes.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> <span>Reportes Pro</span>
        </a>

        <a href="{{ route('empresa.backup.index') }}" class="nav-link-item {{ Route::is('empresa.backup.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill"></i> <span>Bóveda (Backup)</span>
        </a>

        <a href="{{ route('empresa.configuracion.index') }}" class="nav-link-item {{ Route::is('empresa.configuracion.*') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i> <span>Configuración App</span>
        </a>

        <div class="p-3 mt-4">
            <a href="{{ route('logout.get') }}" class="btn btn-outline-danger w-100 rounded-pill x-small">
                <i class="bi bi-power me-2"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
@endif

<!-- CONTENIDO PRINCIPAL -->
<div id="main-content">
    <header class="top-bar">
        <div class="d-flex align-items-center gap-3">
            @if(!isset($posMode))
                <button class="btn-sidebar-toggle" id="btnToggle">
                    <i class="bi bi-list"></i>
                </button>
            @else
                <a href="{{ route('empresa.dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3 border fw-bold">
                    <i class="bi bi-grid-fill me-1"></i> PANEL DE GESTIÓN
                </a>
            @endif
            <h5 class="mb-0 fw-bold d-none d-md-block" id="page_title">@yield('page_title', 'MultiPOS v2')</h5>
        </div>

        <div class="d-flex align-items-center gap-3">
            {{-- BOTONES TOP BAR --}}
            <a href="{{ route('empresa.novedades') }}" class="btn btn-light btn-sm rounded-pill px-3 border fw-bold text-warning d-none d-lg-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-fire"></i> NOVEDADES
            </a>

            @if(session('impersonator_id'))
                <a href="{{ route('owner.return-to-owner') }}" class="btn btn-warning btn-sm fw-bold border-2 rounded-pill px-3">
                    <i class="bi bi-arrow-left-circle me-1"></i> VOLVER A OWNER
                </a>
            @endif

            <div class="dropdown">
                <button class="btn d-flex align-items-center gap-2 p-1 rounded-pill" data-bs-toggle="dropdown">
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="text-start d-none d-md-block me-2">
                        <div class="small fw-bold lh-1">{{ $user->name }}</div>
                        <div class="x-small text-muted">@switch($user->role) @case('empresa') Admin @break @default Operador @endswitch</div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 p-2">
                    <li><a class="dropdown-item rounded-3" href="{{ route('password.edit') }}"><i class="bi bi-shield-lock me-2"></i> Seguridad</a></li>
                    <li><hr class="dropdown-divider opacity-10"></li>
                    <li><a class="dropdown-item rounded-3 text-danger fw-bold" href="{{ route('logout.get') }}"><i class="bi bi-power me-2"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main>
        @if(session('error')) <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">{{ session('error') }}</div> @endif
        @if(session('success')) <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">{{ session('success') }}</div> @endif
        @yield('content')
    </main>
</div>

{{-- BOTÓN MÁGICO DE AYUDA --}}
<div id="help-trigger" onclick="openHelp()"><i class="bi bi-magic"></i></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const body = document.body;
    const btnToggle = document.getElementById('btnToggle');
    const overlay = document.getElementById('sidebarOverlay');

    // Estado inicial persistente
    if(localStorage.getItem('sidebar-state') === 'collapsed' && window.innerWidth > 991) {
        body.classList.add('sidebar-collapsed');
    }

    function toggleSidebar() {
        if (window.innerWidth <= 991) {
            sidebar.classList.toggle('show');
        } else {
            body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar-state', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'full');
        }
    }

    if(btnToggle) btnToggle.addEventListener('click', toggleSidebar);
    if(overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

    function openHelp() {
        // Implementación de ayuda
    }
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>
