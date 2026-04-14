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
    --sidebar-width: 270px;
    --sidebar-collapsed-width: 80px;
    --navbar-height: 70px;
    --color-primario: {{ $colorPrimario }};
    --color-secundario: {{ $colorSecundario }};
    --color-primario-rgb: {{ implode(',', $rgb) }};
    
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

* { font-family: 'Plus Jakarta Sans', sans-serif; }

body { background: var(--bg-main); color: var(--text-main); overflow-x: hidden; }

/* =========================================================
   SIDEBAR "SPACE COMMAND"
   ========================================================= */
#sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: var(--sidebar-bg);
    z-index: 2000; /* Asegurar que esté sobre el contenido */
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    box-shadow: 15px 0 35px rgba(0,0,0,0.3);
}

#sidebar.collapsed { width: var(--sidebar-collapsed-width); }

.sidebar-header {
    min-height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 15px;
    border-bottom: 1px solid var(--sidebar-border);
    transition: all 0.3s;
}

#sidebar.collapsed .sidebar-header {
    min-height: var(--navbar-height);
    padding: 10px;
}

.sidebar-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 8px;
    text-decoration: none;
    color: white;
}

.sidebar-logo img { 
    max-height: 48px; 
    max-width: 100%;
    object-fit: contain;
    filter: drop-shadow(0 0 10px rgba(var(--color-primario-rgb), 0.3));
    transition: 0.3s;
}

#sidebar.collapsed .sidebar-logo img { max-height: 35px; }

.sidebar-logo span { 
    font-weight: 800; 
    font-size: 0.8rem; 
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #e2e8f0;
    text-align: center;
    max-width: 140px;
}

#sidebar.collapsed .sidebar-logo span { display: none; }

.sidebar-nav { flex-grow: 1; padding: 25px 0; overflow-y: auto; scrollbar-width: none; }

.nav-label {
    padding: 22px 25px 8px 25px;
    font-size: 0.72rem;
    font-weight: 900;
    color: #00d2ff; /* Azul Espacial Vibrante */
    text-transform: uppercase;
    letter-spacing: 2.5px;
    display: block;
    position: relative;
    margin: 0 20px 12px 20px;
    text-shadow: 0 0 12px rgba(0, 210, 255, 0.6);
}

.nav-label::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, #00d2ff, transparent);
    box-shadow: 0 0 8px rgba(0, 210, 255, 0.8);
}

#sidebar.collapsed .nav-label { visibility: hidden; }

.nav-link-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 600;
    transition: all 0.2s;
    margin: 4px 15px;
    border-radius: 12px;
}

.nav-link-item:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #00f2ff;
    transform: translateX(5px);
}

.nav-link-item.active {
    background: linear-gradient(90deg, var(--color-primario), #0fbcf9);
    color: white;
    box-shadow: 0 5px 15px rgba(var(--color-primario-rgb), 0.4);
}

.nav-link-item i { width: 28px; font-size: 1.25rem; margin-right: 12px; }

/* =========================================================
   FIX OVERLAP & MAIN CONTENT
   ========================================================= */
#main-content {
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    transition: margin 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    z-index: 10;
}
#main-content.expanded { margin-left: var(--sidebar-collapsed-width); }

.top-bar {
    height: var(--navbar-height);
    background: rgba(255,255,255, 0.9);
    backdrop-filter: blur(12px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 40px;
    position: sticky;
    top: 0;
    z-index: 1001; /* Sobre el contenido pero bajo el sidebar moviéndose */
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

@if($modoOscuro)
.top-bar {
    background: rgba(0,0,0, 0.9);
    border-bottom-color: rgba(255,255,255,0.05);
}
@endif

.btn-sidebar-toggle {
    background: transparent;
    border: none;
    color: var(--text-main);
    font-size: 1.5rem;
    cursor: pointer;
}

</style>

@yield('styles')
@stack('styles')
</head>

<body>

<!-- SIDEBAR MODERNO -->
<div id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('empresa.dashboard') }}" class="sidebar-logo">
            <img src="{{ $logo }}" alt="Logo">
            <span>{{ $empresa->nombre_comercial ?? 'MultiPOS' }}</span>
        </a>
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('empresa.dashboard') }}" class="nav-link-item {{ Route::is('empresa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> <span>Dashboard</span>
        </a>

        {{-- VENTAS Y CRM --}}
        <div class="nav-label">Ventas & Clientes</div>
        <a href="{{ route('empresa.pos.index') }}" class="nav-link-item {{ Route::is('empresa.pos.index') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> <span>🏪 VENTAS (POS)</span>
        </a>
        
        <a href="#sm_ventas" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-receipt"></i> <span>Gestión Ventas</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/ventas*') || Request::is('empresa/presupuestos*') ? 'show' : '' }}" id="sm_ventas">
            <a href="{{ route('empresa.ventas.index') }}" class="submenu-item">📋 Ver Historial</a>
            <a href="{{ route('empresa.ventas.manual') }}" class="submenu-item text-primary fw-bold">✍️ Venta Manual</a>
            <a href="{{ route('empresa.presupuestos.index') }}" class="submenu-item">📜 Presupuestos</a>
            <a href="{{ route('empresa.orders.index') }}" class="submenu-item">📦 Pedidos Online</a>
            <a href="{{ route('empresa.logistica.reporte') }}" class="submenu-item text-warning">🚚 Reporte Guarda</a>
        </div>

        <a href="#sm_clientes" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-people"></i> <span>Cartera Clientes</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/clientes*') || Request::is('empresa/pagos*') ? 'show' : '' }}" id="sm_clientes">
            <a href="{{ route('empresa.clientes.index') }}" class="submenu-item">👥 Listado Clientes</a>
            <a href="{{ route('empresa.pagos.index') }}" class="submenu-item text-success fw-bold">💰 CTA. CTE. / COBROS</a>
        </div>

        {{-- COMPRAS Y FINANZAS --}}
        <div class="nav-label">Compras & Bancos</div>
        <a href="#sm_compras" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-bag-check"></i> <span>Abastecimiento</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/compras*') ? 'show' : '' }}" id="sm_compras">
            <a href="{{ route('empresa.compras.create') }}" class="submenu-item text-success fw-bold">🟢 Nueva Compra</a>
            <a href="{{ route('empresa.compras.index') }}" class="submenu-item">📋 Historial</a>
            <a href="{{ route('empresa.proveedores.index') }}" class="submenu-item">🚛 Proveedores</a>
        </div>

        <a href="#sm_tesoreria" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-bank"></i> <span>Finanzas & Tesorería</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/tesoreria*') ? 'show' : '' }}" id="sm_tesoreria">
            <a href="{{ route('empresa.tesoreria.index') }}" class="submenu-item text-warning fw-bold">📊 PANEL DE TESORERÍA</a>
            <a href="{{ route('empresa.tesoreria.index', ['filter' => 'banco']) }}" class="submenu-item text-info"><i class="bi bi-university me-1"></i> Mis Bancos</a>
            <a href="{{ route('empresa.tesoreria.index', ['filter' => 'billetera']) }}" class="submenu-item text-primary"><i class="bi bi-phone me-1"></i> Billeteras Virtuales</a>
            <a href="{{ route('empresa.tesoreria.cheques.index') }}" class="submenu-item">✍️ Cartera de Cheques</a>
            <a href="{{ route('empresa.tesoreria.chequeras.index') }}" class="submenu-item">📖 Chequeras Propias</a>
            <a href="{{ route('empresa.tesoreria.proyeccion') }}" class="submenu-item">📈 Flujo / Cashflow</a>
        </div>

        {{-- INVENTARIO Y PRODUCCIÓN --}}
        <div class="nav-label">Producción & Stock</div>
        <a href="#sm_stock" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-box-seam"></i> <span>Control Stock</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/products*') || Request::is('empresa/stock*') ? 'show' : '' }}" id="sm_stock">
            <a href="{{ route('empresa.products.index') }}" class="submenu-item">🔍 Listado Maestro</a>
            <a href="{{ route('empresa.stock.index') }}" class="submenu-item">📦 Movimientos Stock</a>
            <a href="{{ route('empresa.inventory_scan') }}" class="submenu-item text-primary fw-bold">📲 ESCÁNER MÓVIL</a>
            <a href="{{ route('empresa.stock.faltantes') }}" class="submenu-item text-danger">⚠️ Reposición</a>
            <a href="{{ route('empresa.stock.valuation') }}" class="submenu-item text-success">💲 Valorización</a>
            <a href="{{ route('empresa.labels.index') }}" class="submenu-item">🖨️ Etiquetas</a>
        </div>

        <a href="#sm_fabrica" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-gear"></i> <span>Fábrica/Producción</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/recipes*') || Request::is('empresa/production_orders*') ? 'show' : '' }}" id="sm_fabrica">
            <a href="{{ route('empresa.recipes.index') }}" class="submenu-item">🧪 Recetas (Fórmulas)</a>
            <a href="{{ route('empresa.production_orders.index') }}" class="submenu-item text-success">⚙️ Órdenes de Prod.</a>
            <a href="{{ route('empresa.units.index') }}" class="submenu-item">📏 Unidades Medida</a>
            <a href="{{ route('empresa.rubros.index') }}" class="submenu-item">🏷️ Rubros</a>
        </div>

        {{-- ADMINISTRACIÓN --}}
        <div class="nav-label">Administración</div>
        <a href="#sm_admin" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-person-badge"></i> <span>Recursos Humanos</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/usuarios*') || Request::is('empresa/personal*') ? 'show' : '' }}" id="sm_admin">
            <a href="{{ route('empresa.usuarios.index') }}" class="submenu-item">👥 Gestión Usuarios</a>
            <a href="{{ route('empresa.personal.rendimiento') }}" class="submenu-item">📊 Rendimiento</a>
            <a href="{{ route('empresa.personal.cajas.index') }}" class="submenu-item text-danger">🕵️ Auditoría Cajas</a>
            <a href="{{ route('empresa.personal.asistencia.qr') }}" class="submenu-item text-primary fw-bold">📲 PUNTO QR</a>
        </div>

        <a href="#sm_gastos" class="nav-link-item submenu-toggle" data-bs-toggle="collapse">
            <i class="bi bi-wallet2"></i> <span>Gastos & Auditoría</span>
        </a>
        <div class="collapse submenu-collapse {{ Request::is('empresa/gastos*') ? 'show' : '' }}" id="sm_gastos">
            <a href="{{ route('empresa.gastos.index') }}" class="submenu-item">📋 Ver Gastos</a>
            <a href="{{ route('empresa.gastos_categorias.index') }}" class="submenu-item">🏷️ Categorías</a>
            <a href="{{ route('empresa.gastos.quick') }}" class="submenu-item text-warning fw-bold">📱 Registro Rápido</a>
        </div>

        <a href="{{ route('empresa.reportes.panel') }}" class="nav-link-item {{ Route::is('empresa.reportes.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> <span>Reportes Pro</span>
        </a>

        <div class="nav-label">Sistema</div>
        <a href="{{ route('empresa.novedades') }}" class="nav-link-item text-warning">
            <i class="bi bi-fire"></i> <span>🔥 Novedades</span>
        </a>
        <a href="{{ route('empresa.soporte.index') }}" class="nav-link-item text-info">
            <i class="bi bi-headset"></i> <span>Soporte Técnico</span>
        </a>
        <a href="{{ route('empresa.configuracion.index') }}" class="nav-link-item">
            <i class="bi bi-gear-fill"></i> <span>Configuración</span>
        </a>
    </div>

    <div class="p-4 mt-auto">
        <a href="{{ route('logout.get') }}" class="nav-link-item text-danger p-0 m-0 border-0 bg-transparent">
            <i class="bi bi-box-arrow-right"></i> <span>Salir del Sistema</span>
        </a>
    </div>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div id="main-content">
    <header class="top-bar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-sidebar-toggle" id="btnToggle">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0 fw-bold d-none d-md-block" id="page_title">@yield('page_title', 'MultiPOS v2')</h5>
        </div>

        <div class="d-flex align-items-center gap-3">
            {{-- BOTÓN TÁCTICO OWNER --}}
            @if(session('impersonator_id'))
                <a href="{{ route('owner.return-to-owner') }}" class="btn btn-warning btn-sm fw-bold border-2 rounded-pill px-3">
                    <i class="bi bi-arrow-left-circle me-1"></i> VOLVER A OWNER
                </a>
            @endif

            {{-- ASISTENCIA RÁPIDA (CAJEROS) --}}
            @if(auth()->user()->role === 'usuario' && auth()->user()->sub_role === 'cajero')
                @if(!$asistenciaActiva)
                    <button class="btn btn-outline-success btn-sm fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCheckIn">🔔 INGRESO</button>
                @else
                    <button class="btn btn-outline-danger btn-sm fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCheckOut">🛑 EGRESO</button>
                @endif
            @endif

            {{-- PERFIL --}}
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
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 p-2" style="border-radius: 12px;">
                    <li><a class="dropdown-item rounded-3" href="{{ route('password.edit') }}"><i class="bi bi-shield-lock me-2"></i> Seguridad</a></li>
                    @if($user->role === 'empresa')
                        <li><a class="dropdown-item rounded-3" href="{{ route('empresa.suscripcion.index') }}"><i class="bi bi-star me-2"></i> Mi Plan</a></li>
                        <li><a class="dropdown-item rounded-3 text-success" href="{{ route('empresa.backup.index') }}"><i class="bi bi-safe me-2"></i> Backups</a></li>
                    @endif
                    <li><hr class="dropdown-divider opacity-10"></li>
                    <li><a class="dropdown-item rounded-3 text-danger fw-bold" href="{{ route('logout.get') }}"><i class="bi bi-power me-2"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="p-4">
        @if(session('error')) <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">{{ session('error') }}</div> @endif
        @if(session('success')) <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">{{ session('success') }}</div> @endif

        @yield('content')
    </main>
</div>

{{-- BOTÓN MÁGICO DE AYUDA (WAND) --}}
<div id="help-trigger" onclick="openHelp()" title="Manual dinámico">
    <i class="bi bi-magic"></i>
</div>

<div class="offcanvas-help" id="offcanvasHelp">
    <div class="help-resize-handle" id="helpResize"></div>
    <div class="help-drag-handle d-flex justify-content-between align-items-center" id="helpDrag">
        <div class="d-flex flex-column">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-1 fw-bold" style="width: fit-content; font-size: 0.6rem; letter-spacing: 1px; text-transform: uppercase;">Knowledge Center</span>
            <h5 class="help-header-gradient">Panel de Ayuda</h5>
        </div>
        <button type="button" class="btn-close shadow-none" onclick="closeHelp()"></button>
    </div>
    <div class="help-body-scroll"><div id="help-loading" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div><div id="help-view-mode"><div id="help-display"></div></div></div>
</div>

{{-- MODALES ASISTENCIA --}}
@if(auth()->user()->role === 'usuario' && auth()->user()->sub_role === 'cajero')
<div class="modal fade" id="modalCheckIn" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg"><div class="modal-header bg-success text-white"><h5>🟢 INICIAR TURNO</h5></div><form action="{{ route('empresa.personal.checkin') }}" method="POST">@csrf<div class="modal-body p-4"><label>Fondo de Caja inicial 💵</label><input type="number" name="vuelto_inicial" class="form-control" step="0.01" value="0.00" required></div><div class="modal-footer"><button type="submit" class="btn btn-success">EMPEZAR TURNO</button></div></form></div></div>
</div>
<div class="modal fade" id="modalCheckOut" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg"><div class="modal-header bg-danger text-white"><h5>🛑 CERRAR TURNO</h5></div><form action="{{ route('empresa.personal.checkout') }}" method="POST">@csrf<div class="modal-body p-4"><label>Efectivo final en caja 💰</label><input type="number" name="vuelto_final" class="form-control" step="0.01" required></div><div class="modal-footer"><button type="submit" class="btn btn-danger">CERRAR TURNO</button></div></form></div></div>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const btnToggle = document.getElementById('btnToggle');

    if(localStorage.getItem('sidebar-state') === 'collapsed') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
    }

    btnToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        localStorage.setItem('sidebar-state', sidebar.classList.contains('collapsed') ? 'collapsed' : 'full');
    });
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>
