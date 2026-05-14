<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ config('app.name', 'OmniPOS') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@php
    $user = auth()->user();
    $empresa = $user->empresa ?? null;
    $config  = $empresa?->config ?? null;
    $colorPrimario = $config?->color_primary ?? '#0d6efd';
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
    $logo = ($config && $config->logo_url) ? $config->logo_url : asset('images/logo_omnipos.png');
@endphp

<style>
:root {
    --sidebar-width: 105px;
    --navbar-height: 70px;
    --color-primario: {{ $colorPrimario }};
    --sidebar-bg: #0b1120;
    --sidebar-border: rgba(255, 255, 255, 0.08);
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: {{ $modoOscuro ? '#020617' : '#f8fafc' }};
    color: {{ $modoOscuro ? '#f1f5f9' : '#334155' }};
    overflow-x: hidden;
}

/* FIX LEAFLET PUZZLE EFFECT caused by Bootstrap img max-width */
.leaflet-container img {
    max-width: none !important;
    max-height: none !important;
}

/* SIDEBAR SLIM MEJORADO */
#sidebar {
    width: 105px !important;
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050; /* Por debajo de modales pero por encima del contenido */
    border-right: 1px solid var(--sidebar-border);
    display: flex;
    flex-direction: column;
    overflow: visible !important;
}

.sidebar-header {
    height: var(--navbar-height);
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid var(--sidebar-border);
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    padding: 15px 0;
    overflow-y: auto !important;
    overflow-x: hidden !important;
}

/* Custom Scrollbar for sidebar */
.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}
.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
}

.nav-link-item {
    width: 100%;
    padding: 15px 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    position: relative;
    transition: all 0.2s ease;
    cursor: pointer;
    text-align: center;
}

.nav-link-item i { font-size: 1.6rem; margin-bottom: 6px; transition: transform 0.2s; }
.nav-icon-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; line-height: 1.1; }

.nav-link-item:hover {
    color: #fff;
    background: rgba(255,255,255,0.05);
}
.nav-link-item:hover i { transform: scale(1.1); }

/* GLOBO FLOTANTE POSICIÓN FIJA (FIX PARA EL SCROLL) */
.floating-balloon {
    position: fixed;
    left: var(--sidebar-width);
    /* top is set via Javascript on hover */
    transform: translateY(-50%) translateX(10px);
    background: #0f172a;
    border: 1px solid rgba(255,255,255,0.15);
    box-shadow: 20px 0 50px rgba(0,0,0,0.6);
    border-radius: 15px;
    padding: 20px;
    width: 260px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000000;
    pointer-events: none;
}

/* Puente invisible para no perder el hover al mover el mouse al globo */
.floating-balloon::before {
    content: '';
    position: absolute;
    left: -25px; /* Cubre el espacio vacío entre el sidebar y el globo */
    top: -20px;  /* Margen extra arriba para movimientos diagonales */
    bottom: -20px; /* Margen extra abajo para movimientos diagonales */
    width: 25px;
    background: transparent;
}

.nav-link-item:hover .floating-balloon {
    opacity: 1;
    visibility: visible;
    transform: translateY(-50%) translateX(5px);
    pointer-events: auto;
}

.floating-balloon h6 {
    color: var(--color-primario);
    font-size: 0.85rem;
    font-weight: 800;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding-bottom: 10px;
}

.submenu-list { display: flex; flex-direction: column; gap: 6px; }
.submenu-link {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    font-size: 0.85rem;
    padding: 10px 14px;
    border-radius: 10px;
    transition: all 0.2s;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
}
.submenu-link:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    padding-left: 20px;
}

/* ESTRUCTURA BASE — FUERZA BRUTA */
body {
    margin: 0;
    padding: 0;
    @if(!isset($posMode))
    padding-left: 105px !important;
    @endif
    overflow-x: hidden;
}

/* CONTENT LAYOUT */
#main-content {
    padding-top: 90px !important; /* Aumentado para evitar solapamiento con top-bar */
    min-height: 100vh;
    position: relative;
    display: flex;
    flex-direction: column;
    z-index: 1;
    width: 100%;
}

.top-bar {
    position: fixed;
    top: 0;
    right: 0;
    left: 105px !important;
    background: #ffffff;
    backdrop-filter: blur(15px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    height: var(--navbar-height);
    z-index: 1040;
    border-bottom: 3px solid var(--color-primario);
    box-sizing: border-box;
    width: calc(100% - 105px) !important;
}

.top-bar h4, .top-bar div, .top-bar i, .top-bar span {
    color: #1e293b;
}

/* BOTÓN DE AYUDA MÁGICA */
#help-trigger {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 55px;
    height: 55px;
    background: var(--color-primario);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    z-index: 1000001;
}

</style>
@yield('styles')
@stack('styles')
</head>

<body>

@if(!isset($posMode))
<div id="sidebar">
    <div class="sidebar-header">
        <img src="{{ $logo }}" style="height: 45px; border-radius: 8px;" alt="Logo">
    </div>

    <div class="sidebar-nav">
        {{-- INICIO --}}
        <a href="{{ route('empresa.dashboard') }}" class="nav-link-item {{ Route::is('empresa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span class="nav-icon-label">Inicio</span>
        </a>

        {{-- CATÁLOGO PÚBLICO --}}
        <a href="{{ route('catalog.index', $empresa->slug ?? $empresa->id) }}" target="_blank" class="nav-link-item" title="Ver mi tienda online">
            <i class="bi bi-shop-window" style="color: #ffc107;"></i>
            <span class="nav-icon-label text-center lh-sm mt-1">Mi Catálogo<br>(Store)</span>
        </a>

        {{-- ARTICULOS --}}
        <div class="nav-link-item">
            <i class="bi bi-tags" style="color: #00d2ff;"></i>
            <span class="nav-icon-label text-center">Artículos</span>
            <div class="floating-balloon">
                <h6>📦 GESTIÓN DE ARTÍCULOS</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.products.index') }}" class="submenu-link">📄 Mis Artículos</a>
                    <a href="{{ route('empresa.rubros.index') }}" class="submenu-link">🏷️ Rubros / Categorías</a>
                    <a href="{{ route('empresa.listados.articulos') }}" class="submenu-link text-success">🖨️ Catálogo / Imprimir</a>
                </div>
            </div>
        </div>

        {{-- STOCK --}}
        <div class="nav-link-item">
            <i class="bi bi-boxes" style="color: #20c997;"></i>
            <span class="nav-icon-label text-center">Stock</span>
            <div class="floating-balloon">
                <h6>📊 CONTROL DE STOCK</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.stock.index') }}" class="submenu-link">🔄 Movimientos Stock</a>
                    <a href="{{ route('empresa.stock.valuation') }}" class="submenu-link">💰 Valuación de Stock</a>
                    <a href="{{ route('empresa.stock.faltantes') }}" class="submenu-link text-warning">⚠️ Reposición</a>
                    <hr class="my-1 opacity-10">
                    <a href="{{ route('empresa.recipes.index') }}" class="submenu-link text-info">🧪 Recetas (BOM)</a>
                    <a href="{{ route('empresa.production_orders.index') }}" class="submenu-link text-info">🏭 Producción</a>
                </div>
            </div>
        </div>

        {{-- VENTAS --}}
        @if($config?->mod_ventas)
        <div class="nav-link-item">
            <i class="bi bi-shop" style="color: #ffc107;"></i>
            <span class="nav-icon-label">Ventas</span>
            <div class="floating-balloon">
                <h6>📑 ÁREA DE VENTAS</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.orders.index') }}" class="submenu-link text-info">🛒 Pedidos Catálogo</a>
                    <a href="{{ route('empresa.ventas.index') }}" class="submenu-link">📋 Historial Ventas</a>
                    <a href="{{ route('empresa.ventas.manual') }}" class="submenu-link">✍️ Venta Manual</a>
                    <a href="{{ route('empresa.presupuestos.index') }}" class="submenu-link">📜 Presupuestos</a>
                </div>
            </div>
        </div>
        @endif

        {{-- LOGÍSTICA --}}
        @if($config?->mod_logistica)
        <div onclick="window.location='{{ route('empresa.gps.index') }}'" class="nav-link-item" style="cursor: pointer;">
            <i class="bi bi-truck-flatbed" style="color: #fff;"></i>
            <span class="nav-icon-label">Logística</span>
            <div class="floating-balloon" onclick="event.stopPropagation()">
                <h6>🚚 GPS & ENTREGAS</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.gps.index') }}" class="submenu-link text-primary fw-bold">🗺️ Centro de Control GPS</a>
                    <a href="{{ route('empresa.gps.rutas') }}" class="submenu-link">🚛 Smart Delivery</a>
                    <a href="{{ route('empresa.gps.zonas_calientes') }}" class="submenu-link">🔥 Mapa de Calor</a>
                    <a href="{{ route('empresa.gps.retiros_inteligentes') }}" class="submenu-link">📦 Retiros CRM</a>
                    <hr class="my-1 opacity-10">
                    <a href="{{ route('empresa.logistica.reporte') }}" class="submenu-link">📦 Stock en Guarda</a>
                    <a href="{{ route('empresa.remitos.index') }}" class="submenu-link">📜 Historial Remitos</a>
                </div>
            </div>
        </div>
        @endif

        {{-- FINANZAS --}}
        @if($config?->mod_tesoreria)
        <div onclick="window.location='{{ route('empresa.tesoreria.index') }}'" class="nav-link-item" style="cursor: pointer;">
            <i class="bi bi-bank" style="color: #4da3ff;"></i>
            <span class="nav-icon-label">Finanzas</span>
            <div class="floating-balloon" onclick="event.stopPropagation()">
                <h6>🏦 CAJA & FINANZAS</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.tesoreria.index') }}" class="submenu-link">🏦 Cuentas & Billeteras</a>
                    <a href="{{ route('empresa.tesoreria.proyeccion') }}" class="submenu-link">📈 Proyección de Caja</a>
                    <a href="{{ route('empresa.tesoreria.cheques.index') }}" class="submenu-link">✍️ Cheques de Terceros</a>
                    <a href="{{ route('empresa.tesoreria.chequeras.index') }}" class="submenu-link">📖 Chequeras Propias</a>
                    <hr class="my-1 opacity-10">
                    <a href="{{ route('empresa.gastos.index') }}" class="submenu-link text-danger">💸 Gestión de Gastos</a>
                </div>
            </div>
        </div>
        @endif

        {{-- ABASTO --}}
        @if($config?->mod_compras)
        <div onclick="window.location='{{ route('empresa.compras.index') }}'" class="nav-link-item" style="cursor: pointer;">
            <i class="bi bi-cart-check" style="color: #ff4d4d;"></i>
            <span class="nav-icon-label">Abasto</span>
            <div class="floating-balloon" onclick="event.stopPropagation()">
                <h6>🛒 ABASTECIMIENTO</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.compras.create') }}" class="submenu-link text-success">🟢 Nueva Compra</a>
                    <a href="{{ route('empresa.compras.index') }}" class="submenu-link">📑 Historial Compras</a>
                    <a href="{{ route('empresa.stock.faltantes') }}" class="submenu-link text-warning">📋 Plan de Reposición</a>
                </div>
            </div>
        </div>
        @endif

        {{-- CLIENTES --}}
        <div onclick="window.location='{{ route('empresa.clientes.index') }}'" class="nav-link-item" style="cursor: pointer;">
            <i class="bi bi-people" style="color: #00d2ff;"></i>
            <span class="nav-icon-label">Clientes</span>
            <div class="floating-balloon" onclick="event.stopPropagation()">
                <h6>👥 CARTERA</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.clientes.index') }}" class="submenu-link">📄 Listado de Clientes</a>
                    <a href="{{ route('empresa.clientes.portal_list') }}" class="submenu-link">🌐 Portal del Cliente</a>
                    <a href="{{ route('empresa.pagos.index') }}" class="submenu-link">💰 Cta. Cte. Clientes</a>
                    <a href="{{ route('empresa.pagos.index') }}" class="submenu-link">🧾 Recibos de Cobro</a>
                    <a href="{{ route('empresa.listados.clientes') }}" class="submenu-link">📋 Padrones</a>
                </div>
            </div>
        </div>

        {{-- PROVEEDORES --}}
        <div onclick="window.location='{{ route('empresa.proveedores.index') }}'" class="nav-link-item" style="cursor: pointer;">
            <i class="bi bi-truck" style="color: #28a745;"></i>
            <span class="nav-icon-label text-center">Proveedores</span>
            <div class="floating-balloon" onclick="event.stopPropagation()">
                <h6>🚛 GESTIÓN PROVEEDORES</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.proveedores.index') }}" class="submenu-link">🚚 Mis Proveedores</a>
                    <a href="{{ route('empresa.proveedores.portal_list') }}" class="submenu-link">🌐 Portal de Proveedores</a>
                    <a href="{{ route('empresa.compras.index') }}" class="submenu-link">📑 Facturas de Compra</a>
                    <a href="{{ route('empresa.proveedores.index') }}" class="submenu-link">💳 Cta. Cte. Proveedores</a>
                    <a href="{{ route('empresa.recibos-proveedores.index') }}" class="submenu-link">🧾 Recibos de Pago</a>
                    @if($config?->mod_orden_pedido)
                        <hr class="my-1 opacity-10">
                        <a href="{{ route('empresa.ordenes-pedido.index') }}" class="submenu-link fw-bold text-success">📝 Órdenes de Pedido</a>
                    @endif
                </div>
            </div>
        </div>

        {{-- PERSONAL --}}
        <div class="nav-link-item">
            <i class="bi bi-person-badge" style="color: #ff8c00;"></i>
            <span class="nav-icon-label text-center">Personal</span>
            <div class="floating-balloon">
                <h6>👥 GESTIÓN DE EQUIPO</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.usuarios.index') }}" class="submenu-link">👥 Lista de Personal</a>
                    <a href="{{ route('empresa.personal.rendimiento') }}" class="submenu-link">📊 Rendimiento Operativo</a>
                    <a href="{{ route('empresa.personal.cajas.index') }}" class="submenu-link">💵 Auditoría de Cajas</a>
                    <a href="{{ route('empresa.personal.asistencia.qr') }}" class="submenu-link">📱 Fichaje QR</a>
                </div>
            </div>
        </div>

        {{-- TURNOS (MODULAR) --}}
        @if($config?->mod_turnos)
            <div onclick="window.location='{{ route('empresa.turnos.index') }}'" class="nav-link-item" style="cursor: pointer;">
                <i class="bi bi-calendar-event" style="color: #00d2ff;"></i>
                <span class="nav-icon-label">Turnos</span>
                <div class="floating-balloon">
                    <h6>📅 RESERVAS</h6>
                    <div class="submenu-list">
                        <a href="{{ route('empresa.turnos.index') }}" class="submenu-link">📋 Agenda de Turnos</a>
                        <a href="{{ route('empresa.turnos.create') }}" class="submenu-link">⚙️ Nuevo Turno</a>
                        
                        @if($config?->mod_hce)
                            <a href="{{ route('empresa.medical_records.index') }}" class="submenu-link text-info">🩺 Historia Clínica (HCE)</a>
                        @endif

                        @if($config?->mod_afiliados)
                            <a href="{{ route('empresa.affiliates.index') }}" class="submenu-link text-success">👥 Plan Afiliados</a>
                        @endif

                        <a href="{{ route('empresa.liquidaciones.index') }}" class="submenu-link">💰 Liquidaciones</a>
                        <a href="{{ route('empresa.liquidaciones.create') }}" class="submenu-link">⚡ Motor de Pago</a>
                        <hr class="my-1 border-white border-opacity-10">
                        <a href="{{ route('empresa.servicios.index') }}" class="submenu-link">💆 Servicios</a>
                    </div>
                </div>
            </div>
        @endif

        {{-- REPORTES --}}
        <div onclick="window.location='{{ route('empresa.reportes.panel') }}'" class="nav-link-item" style="cursor: pointer;">
            <i class="bi bi-bar-chart-line" style="color: #adb5bd;"></i>
            <span class="nav-icon-label">Reportes</span>
            <div class="floating-balloon" onclick="event.stopPropagation()">
                <h6>📊 INTELIGENCIA</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.reportes.panel') }}" class="submenu-link">📈 Dashboard Global</a>
                    <a href="{{ route('empresa.reportes.caja_diaria') }}" class="submenu-link">💵 Auditoría de Caja</a>
                    <a href="{{ route('empresa.reportes.vendedores') }}" class="submenu-link">👨‍💼 Ventas por Vendedor</a>
                    <a href="{{ route('empresa.reportes.rentabilidad') }}" class="submenu-link">💎 Rentabilidad</a>
                    <hr class="my-1 opacity-10">
                    <a href="{{ route('empresa.gps.index') }}" class="submenu-link">📍 GPS (Beta)</a>
                </div>
            </div>
        </div>

        {{-- AJUSTES --}}
        <div onclick="window.location='{{ route('empresa.configuracion.index') }}'" class="nav-link-item" style="cursor: pointer;">
            <i class="bi bi-gear" style="color: #f1f5f9;"></i>
            <span class="nav-icon-label">Ajustes</span>
            <div class="floating-balloon" onclick="event.stopPropagation()">
                <h6>⚙️ SISTEMA</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.configuracion.index') }}" class="submenu-link">🛠️ Configurar App</a>
                    @if($config?->mod_unidades_medida)
                        <a href="{{ route('empresa.units.index') }}" class="submenu-link">⚖️ Unidades de Medida</a>
                    @endif
                    <a href="{{ route('empresa.backup.index') }}" class="submenu-link">🛡️ Bóveda de Backups</a>
                    <a href="{{ route('empresa.suscripcion.index') }}" class="submenu-link text-primary">⭐ Mi Suscripción</a>
                </div>
            </div>
        </div>

        <div class="mt-auto px-2 pb-3">
             <a href="{{ route('logout.get') }}" class="text-danger d-flex flex-column align-items-center text-decoration-none py-2" style="border: 1px solid rgba(220,53,69,0.3); border-radius: 12px;">
                <i class="bi bi-power fs-4"></i>
                <span class="nav-icon-label x-small">SALIR</span>
            </a>
        </div>
    </div>
</div>
@endif


<div id="main-content">
    <div class="top-bar" style="{{ isset($posMode) ? 'left: 0;' : '' }}">
        <div class="d-flex align-items-center gap-3">
            @if(isset($posMode))
                <a href="{{ route('empresa.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-grid-fill"></i> PANEL DE GESTIÓN
                </a>
                <h4 class="mb-0 fw-bold ms-2">📦 Terminal de Ventas (POS)</h4>
            @else
                <h4 class="mb-0 fw-bold d-none d-md-block">{{ $empresa->nombre_comercial ?? 'OmniPOS' }}</h4>
                {{-- Novedades con Contraste --}}
                <a href="{{ route('empresa.novedades') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 ms-2 d-none d-lg-flex align-items-center gap-2 fw-bold" style="font-size: 0.7rem; border-color: var(--color-primario)40; color: var(--color-primario);">
                    <span class="d-flex align-items-center justify-content-center bg-primary rounded-circle" style="width: 8px; height: 8px; background-color: var(--color-primario) !important;"></span>
                    Novedades v2.4
                </a>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            @if(!isset($posMode))
                <a href="{{ route('empresa.pos.index') }}" class="btn btn-success btn-sm fw-bold px-3 shadow-sm d-none d-md-flex align-items-center gap-2" title="Abrir Punto de Venta">
                    <i class="bi bi-pc-display"></i>
                    PUNTO DE VENTA (POS)
                </a>
            @endif

            {{-- BOTÓN REGRESAR A OWNER (MIMETIZACIÓN) --}}
            @if(session()->has('impersonator_id'))
                <a href="{{ route('owner.return-to-owner') }}" class="btn btn-warning btn-sm fw-bold px-3 shadow-sm d-flex align-items-center gap-2 animate__animated animate__pulse animate__infinite">
                    <i class="bi bi-person-badge-fill"></i>
                    VOLVER A MI PANEL
                </a>
            @endif

            {{-- NOTIFICACIONES DINÁMICAS --}}
            @php
                $totalNotif = ($lowStockCount ?? 0) + 1; // +1 por el backup (siempre presente como recordatorio)
            @endphp
            <div class="dropdown">
                <div class="position-relative cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5 text-dark opacity-75"></i>
                    @if($totalNotif > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white shadow-sm" style="font-size: 0.65rem; padding: 0.3em 0.5em;">
                            {{ $totalNotif }}
                        </span>
                    @endif
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-0 mt-3 overflow-hidden bg-black border border-secondary border-opacity-25" style="min-width: 320px;">
                    <li class="bg-dark bg-opacity-50 p-3 border-bottom border-secondary border-opacity-25">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold small text-uppercase text-white tracking-wider">Centro de Alertas</h6>
                            <span class="badge bg-primary text-white rounded-pill px-3" style="font-size: 0.6rem;">{{ $totalNotif }} ACTIVAS</span>
                        </div>
                    </li>
                    <li class="p-1">
                        {{-- ALERTA: STOCK BAJO --}}
                        @if(($lowStockCount ?? 0) > 0)
                            <a class="dropdown-item rounded-3 p-3 d-flex gap-3 align-items-center border-bottom border-secondary border-opacity-10 hover-bright" href="{{ route('empresa.stock.faltantes') }}">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="height: 40px; width: 40px; flex-shrink: 0;">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold small text-white">Stock Crítico</div>
                                    <div class="x-small text-white opacity-75">Hay <strong>{{ $lowStockCount }} artículos</strong> por debajo del mínimo.</div>
                                </div>
                                <i class="bi bi-chevron-right text-white opacity-25"></i>
                            </a>
                        @endif

                        {{-- ALERTA: BACKUP (ESTÁTICA PERO ÚTIL) --}}
                        <a class="dropdown-item rounded-3 p-3 d-flex gap-3 align-items-center hover-bright" href="{{ route('empresa.backup.index') }}">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="height: 40px; width: 40px; flex-shrink: 0;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small text-white">Bóveda de Seguridad</div>
                                <div class="x-small text-white opacity-75">Tu información está resguardada. Haz un backup manual aquí.</div>
                            </div>
                            <i class="bi bi-chevron-right text-white opacity-25"></i>
                        </a>
                    </li>
                    <li class="bg-dark bg-opacity-50 p-2 text-center border-top border-secondary border-opacity-25">
                        <a href="{{ route('empresa.dashboard') }}" class="x-small fw-bold text-white text-decoration-none opacity-75 hover-opacity-100">CERRAR PANEL</a>
                    </li>
                </ul>
            </div>

            <div class="dropdown">
                <div class="d-flex align-items-center gap-3 cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                    <div class="text-end d-none d-md-block">
                        <div class="small fw-bold lh-1 text-dark">{{ $user->name }}</div>
                        <div class="x-small opacity-50">{{ $user->email }}</div>
                    </div>
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:42px; height:42px; font-size: 1.2rem; border: 3px solid rgba(255,255,255,0.8);">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2" style="min-width: 220px;">
                    <li><h6 class="dropdown-header small text-uppercase fw-800 opacity-50">Mi Usuario</h6></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="#"><i class="bi bi-person me-2"></i> Mi Perfil</a></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="#"><i class="bi bi-bell me-2"></i> Mis Notificaciones</a></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('password.edit') }}"><i class="bi bi-key me-2"></i> Cambiar Contraseña</a></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('empresa.configuracion.index') }}"><i class="bi bi-gear me-2"></i> Ajustes App</a></li>
                    @if($user->role === 'owner')
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item rounded-3 py-2 text-primary fw-bold" href="{{ route('owner.dashboard') }}"><i class="bi bi-shield-check me-2"></i> Panel Owner</a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item rounded-3 py-2 text-danger fw-bold" href="{{ route('logout.get') }}">
                            <i class="bi bi-power me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: var(--color-primario);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 rgba(var(--color-primario-rgb), 0.4);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
            100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
        }
        .cursor-pointer { cursor: pointer; }

        @if(isset($posMode))
        #main-content { width: 100% !important; }
        .top-bar { left: 0 !important; width: 100% !important; }
        @endif
    </style>

    {{-- AREA DE TRABAJO (CONTENIDO REAL) --}}
    <main class="flex-grow-1" style="padding: 20px 30px 20px 30px;">
        @yield('content')
    </main>
</div> {{-- cierre #main-content --}}



{{-- ASISTENTE INTELIGENTE MULTIPOS (VENTANA MAESTRA AL INICIO PARA EVITAR DESPLAZAMIENTOS) --}}
<div id="helpPanel" class="multipos-window-pro shadow-lg" style="display:none; position:fixed; right:30px; top:90px; z-index:1000005;">
    <div class="multipos-header-pro d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-robot text-white"></i>
            <h6 class="mb-0 fw-bold small text-uppercase tracking-wider text-white">Centro de Ayuda MultiPOS</h6>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button class="btn-multipos-tool" id="btnEditHelp" title="Editar Manual"><i class="bi bi-pencil-square"></i></button>
            <button class="btn-multipos-tool" onclick="openHelp()" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    
    <div class="multipos-body-pro" id="helpContentArea">
        {{-- Contenido --}}
    </div>

    {{-- ZONA DE EDICIÓN --}}
    <div id="helpEditorArea" style="display:none;" class="p-3 bg-white text-dark">
        <div class="mb-2">
            <label class="x-small fw-bold text-muted text-uppercase mb-1">Título del Manual</label>
            <input type="text" id="editHelpTitle" class="form-control form-control-sm border-secondary fw-bold" placeholder="Ej: Guía de Ventas...">
        </div>
        <div id="summernoteHelp"></div>
        <div class="mt-3">
            <label class="x-small fw-bold text-muted text-uppercase mb-1">URL de Video (Youtube)</label>
            <input type="text" id="editHelpVideo" class="form-control form-control-sm border-secondary x-small" placeholder="https://www.youtube.com/watch?v=...">
        </div>
        <div class="d-flex justify-content-end gap-2 mt-3 pt-2 border-top">
            <button class="btn btn-sm btn-light border fw-bold" onclick="cancelEditHelp()">CANCELAR</button>
            <button class="btn btn-sm btn-primary fw-bold px-4" onclick="saveHelpContent()">GUARDAR CAMBIOS</button>
        </div>
    </div>

    <div class="arti-footer-pro">
        Ruta operativa: <span class="fw-bold" style="color: var(--color-primario);">{{ Route::currentRouteName() }}</span>
    </div>
</div>

{{-- BOTÓN MÁGICO DE AYUDA --}}
<div id="help-trigger-fixed" onclick="openHelp()" style="cursor:pointer;"><i class="bi bi-magic"></i></div>

<style>
    #help-trigger-fixed {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--color-primario), #333);
        color: white;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        cursor: pointer;
        z-index: 1000001;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }
    #help-trigger-fixed:hover {
        transform: scale(1.1) rotate(15deg);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }

    .multipos-window-pro {
        position: fixed;
        right: 30px;
        top: 90px;
        width: 450px;
        height: 650px;
        min-width: 300px;
        min-height: 200px;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        z-index: 1000005;
        display: none;
        flex-direction: column;
        color: #333;
        box-shadow: 0 25px 80px rgba(0,0,0,0.15);
    }

    .multipos-header-pro {
        padding: 15px 22px;
        background: var(--color-primario);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        cursor: move;
        border-radius: 20px 20px 0 0;
        color: #fff;
    }

    /* TIRADORES DE REDIMENSIONADO */
    .ui-resizable-handle { 
        z-index: 1000006 !important; 
        opacity: 0.5;
        transition: opacity 0.2s;
        background: transparent !important;
    }
    .ui-resizable-handle:hover { opacity: 1; }
    
    .ui-resizable-e { width: 10px !important; right: -5px !important; cursor: ew-resize !important; }
    .ui-resizable-s { height: 10px !important; bottom: -5px !important; cursor: ns-resize !important; }

    .btn-multipos-tool {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.8);
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0 8px;
        transition: all 0.2s;
    }
    .btn-multipos-tool:hover { color: #fff; transform: scale(1.2); }

    .multipos-body-pro {
        flex-grow: 1;
        overflow-y: auto;
        padding: 25px;
        line-height: 1.6;
        color: #475569;
    }

    .multipos-footer-pro {
        padding: 12px 25px;
        background: #f8fafc;
        font-size: 0.75rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 0 0 20px 20px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #helpEditorArea {
        border-radius: 0 0 20px 20px;
        flex-grow: 1;
        overflow-y: auto;
        background: #f8fafc !important;
        color: #000 !important;
    }

    .arti-footer-pro {
        padding: 10px 20px;
        text-align: center;
        border-top: 1px solid rgba(0,0,0,0.05);
        font-size: 10px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .manual-body h1, .manual-body h2, .manual-body h3, .manual-body h4 { color: var(--color-primario) !important; margin-top: 1.2rem; font-weight: 800; }
    .manual-body img { max-width: 100%; border-radius: 12px; margin: 15px 0; border: 1px solid rgba(0,0,0,0.1); }
</style>

{{-- LIBRERIAS MASTER --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* FORZAR VISIBILIDAD Y AGARRE DE REDIMENSIONADO */
    .ui-resizable-handle { 
        z-index: 1000006 !important; 
        background: transparent !important;
    }
    .ui-resizable-e { width: 10px !important; right: -5px !important; }
    .ui-resizable-s { height: 10px !important; bottom: -5px !important; }
    .ui-resizable-w { width: 10px !important; left: -5px !important; }
    .ui-resizable-n { height: 10px !important; top: -5px !important; }
    .ui-resizable-se { 
        width: 20px !important; height: 20px !important; 
        right: 0 !important; bottom: 0 !important; 
        background: linear-gradient(135deg, transparent 50%, var(--color-primario) 50%) !important;
        opacity: 0.5;
        border-bottom-right-radius: 18px;
    }
    .ui-resizable-se:hover { opacity: 1; }
</style>

<script>
    const userRole = "{{ auth()->user()->role }}";
    const isImpersonated = {{ session('impersonator_id') ? 'true' : 'false' }};

    $(function() {
        // HABILITAR ARRASTRE Y REDIMENSIONADO TOTAL (LIBRE)
        $("#helpPanel").draggable({ 
            handle: ".multipos-header-pro", 
            containment: "window",
            scroll: false 
        }).resizable({ 
            minWidth: 350, 
            minHeight: 250, 
            handles: "all",
            resize: function(event, ui) {
                const editor = $('#summernoteHelp');
                if (editor.next('.note-editor').length) {
                    const newHeight = ui.size.height - 280;
                    editor.summernote('height', newHeight > 100 ? newHeight : 100);
                }
            }
        });
    });

    // Variable global para rastrear el contexto (Ruta + Pestaña si existe)
    let currentContext = '{{ Route::currentRouteName() }}';

    function updateContext() {
        const route = '{{ Route::currentRouteName() }}';
        // Detectar si hay una pestaña activa de Bootstrap
        const activeTab = document.querySelector('.nav-link.active[data-bs-toggle="tab"]');
        const tabId = activeTab ? activeTab.getAttribute('data-bs-target').replace('#', '') : null;
        
        currentContext = tabId ? `${route}__${tabId}` : route;
        return currentContext;
    }

    function openHelp() {
        const panel = $("#helpPanel");
        if (panel.is(':visible')) {
            panel.fadeOut(200);
        } else {
            panel.fadeIn(200).css('display', 'flex');
            fetchHelpContent();
        }
    }

    function fetchHelpContent() {
        const context = updateContext();
        const area = $("#helpContentArea");
        area.html('<div class="text-center py-5"><div class="spinner-border text-primary spinner-border-sm"></div><p class="mt-2 text-muted x-small">MultiPOS está pensando...</p></div>');
        $("#btnEditHelp").addClass('d-none');

        $.get(`/help/fetch?route=${context}`, function(res) {
            if(res.success && res.data) {
                if(userRole === 'owner' || isImpersonated) $("#btnEditHelp").removeClass('d-none');
                
                let videoHtml = "";
                if(res.data.video_url) {
                    let embed = res.data.video_url.replace("watch?v=", "embed/");
                    videoHtml = `<div class="mt-4 border rounded-4 overflow-hidden shadow-sm"><iframe width="100%" height="220" src="${embed}" frameborder="0" allowfullscreen></iframe></div>`;
                }

                area.html(`
                    <h3 class="fw-bold mb-3" style="color:var(--color-primario);">${res.data.title}</h3>
                    <div class="manual-body small">${res.data.content}</div>
                    ${videoHtml}
                `);
            } else {
                area.html(`
                    <div class="text-center py-5 opacity-40">
                        <i class="bi bi-robot fs-1"></i>
                        <p class="mt-2 mb-0 fw-bold">No hay instrucciones registradas para esta sección.</p>
                        ${(userRole === 'owner' || isImpersonated) ? '<button class="btn btn-sm btn-primary rounded-pill px-4 mt-3" onclick="activateEditor()">REGISTRAR MANUAL</button>' : ''}
                    </div>
                `);
            }
        });
    }

    function activateEditor() {
        const context = updateContext();
        $("#helpContentArea").hide();
        $("#helpEditorArea").show();
        const currentTitle = $("#helpContentArea h3").text() || "Manual de " + context;
        const currentContent = $("#helpContentArea .manual-body").html() || "";
        const panelHeight = $("#helpPanel").height();

        $("#editHelpTitle").val(currentTitle);
        $('#summernoteHelp').summernote({ 
            height: panelHeight - 350, 
            lang: 'es-ES',
            placeholder: 'Escribe aquí las instrucciones maestras...',
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernoteHelp').summernote('code', currentContent);
    }

    function cancelEditHelp() {
        $("#helpContentArea").show();
        $("#helpEditorArea").hide();
        $('#summernoteHelp').summernote('destroy');
    }

    function saveHelpContent() {
        const context = updateContext();
        const title = $("#editHelpTitle").val();
        const content = $('#summernoteHelp').summernote('code');
        const video = $("#editHelpVideo").val();

        if(!title || !content) {
            Swal.fire({ icon: 'warning', title: 'Arti necesita datos', text: 'Por favor completa título y contenido.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return;
        }

        $.post('/help/save', {
            _token: "{{ csrf_token() }}",
            route_name: context,
            title: title,
            content: content,
            video_url: video
        }, function(res) {
            if(res.success) {
                Swal.fire({ icon: 'success', title: '¡Manual Guardado!', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
                cancelEditHelp();
                fetchHelpContent();
            }
        });
    }

    $("#btnEditHelp").on('click', activateEditor);

    // Listener para cambios de pestaña: si el panel de ayuda está abierto, se actualiza el contenido
    $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function (e) {
        if ($("#helpPanel").is(':visible')) {
            fetchHelpContent();
        }
    });

    // JS para posicionar los globos flotantes
    document.querySelectorAll('.nav-link-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            const balloon = this.querySelector('.floating-balloon');
            if(balloon) {
                const rect = this.getBoundingClientRect();
                balloon.style.top = (rect.top + (rect.height / 2)) + 'px';
            }
        });
    });
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>
