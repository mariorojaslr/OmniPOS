<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ config('app.name', 'MultiPOS') }}</title>
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
    $logo = ($config && $config->logo_url) ? $config->logo_url : asset('images/logo_premium.png');
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

/* SIDEBAR SLIM MEJORADO */
#sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 999999;
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
    overflow: visible !important;
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
    position: absolute;
    left: var(--sidebar-width);
    top: 50%;
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

/* CONTENT LAYOUT */
#main-content {
    padding-left: var(--sidebar-width);
    padding-top: calc(var(--navbar-height) + 20px);
    min-height: 100vh;
}

.top-bar {
    position: fixed;
    top: 0;
    right: 0;
    left: var(--sidebar-width);
    height: var(--navbar-height);
    background: {{ $modoOscuro ? 'rgba(2, 6, 23, 0.95)' : 'rgba(255, 255, 255, 0.98)' }};
    backdrop-filter: blur(15px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 999;
    border-bottom: 1px solid var(--sidebar-border);
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
</head>

<body>

@if(!isset($posMode))
<div id="sidebar">
    <div class="sidebar-header">
        <img src="{{ $logo }}" style="height: 45px; border-radius: 8px;" alt="Logo">
    </div>

    <div class="sidebar-nav">
        {{-- DASHBOARD --}}
        <a href="{{ route('empresa.dashboard') }}" class="nav-link-item {{ Route::is('empresa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2" style="color: var(--color-primario);"></i>
            <span class="nav-icon-label">Inicio</span>
        </a>

        {{-- STOCK (AGRUPADO) --}}
        <div class="nav-link-item">
            <i class="bi bi-box-seam" style="color: #00d2ff;"></i>
            <span class="nav-icon-label">Stock & Art.</span>
            <div class="floating-balloon">
                <h6>📦 Gestión de Stock</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.products.index') }}" class="submenu-link">📄 Mis Artículos</a>
                    <a href="{{ route('empresa.rubros.index') }}" class="submenu-link">🏷️ Rubros / Categorías</a>
                    <a href="{{ route('empresa.stock.index') }}" class="submenu-link">🔄 Movimientos Stock</a>
                    <a href="{{ route('empresa.stock.faltantes') }}" class="submenu-link">⚠️ Reposición</a>
                    <a href="{{ route('empresa.inventory_scan') }}" class="submenu-link">📸 Escáner Cámara</a>
                    <a href="{{ route('empresa.recipes.index') }}" class="submenu-link">🧪 Recetas Fábrica</a>
                </div>
            </div>
        </div>

        {{-- VENTAS (AGRUPADO) --}}
        <div class="nav-link-item">
            <i class="bi bi-shop" style="color: #ffc107;"></i>
            <span class="nav-icon-label">Ventas</span>
            <div class="floating-balloon">
                <h6>📑 Área de Ventas</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.pos.index') }}" class="submenu-link text-success">🚀 Punto de Venta (POS)</a>
                    <a href="{{ route('empresa.ventas.index') }}" class="submenu-link">📋 Historial Ventas</a>
                    <a href="{{ route('empresa.ventas.manual') }}" class="submenu-link">✍️ Venta Manual</a>
                    <a href="{{ route('empresa.presupuestos.index') }}" class="submenu-link">📜 Presupuestos</a>
                </div>
            </div>
        </div>

        {{-- ENTREGAS / LOGÍSTICA --}}
        <div class="nav-link-item">
            <i class="bi bi-truck" style="color: #fff;"></i>
            <span class="nav-icon-label">Logística</span>
            <div class="floating-balloon">
                <h6>🚚 Entregas & Guarda</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.logistica.reporte') }}" class="submenu-link">📦 Stock en Guarda</a>
                    <a href="{{ route('empresa.remitos.index') }}" class="submenu-link">📜 Historial Remitos</a>
                </div>
            </div>
        </div>

        {{-- ÁREA FINANCIERA (CAJAS + GASTOS + BANCOS) --}}
        <div class="nav-link-item">
            <i class="bi bi-bank" style="color: #4da3ff;"></i>
            <span class="nav-icon-label">Finanzas</span>
            <div class="floating-balloon">
                <h6>🏦 Caja & Finanzas</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.tesoreria.index') }}" class="submenu-link">🏦 Cuentas & Billeteras</a>
                    <a href="{{ route('empresa.tesoreria.cheques.index') }}" class="submenu-link">✍️ Cheques de Terceros</a>
                    <a href="{{ route('empresa.tesoreria.chequeras.index') }}" class="submenu-link">📖 Chequeras Propias</a>
                    <hr class="my-1 opacity-10">
                    <a href="{{ route('empresa.gastos.index') }}" class="submenu-link">💸 Gestión de Gastos</a>
                </div>
            </div>
        </div>

        {{-- ABASTECIMIENTO (COMPRAS) --}}
        <div class="nav-link-item">
            <i class="bi bi-cart3" style="color: #ff4d4d;"></i>
            <span class="nav-icon-label">Abasto</span>
            <div class="floating-balloon">
                <h6>🛒 Abastecimiento</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.compras.create') }}" class="submenu-link text-success">🟢 Nueva Compra</a>
                    <a href="{{ route('empresa.compras.index') }}" class="submenu-link">📑 Historial de Compras</a>
                    <a href="{{ route('empresa.stock.faltantes') }}" class="submenu-link">📋 Plan de Reposición</a>
                </div>
            </div>
        </div>

        {{-- ÁREA DE CLIENTES --}}
        <div class="nav-link-item">
            <i class="bi bi-people" style="color: #00d2ff;"></i>
            <span class="nav-icon-label">Clientes</span>
            <div class="floating-balloon">
                <h6>👥 Cartera de Clientes</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.clientes.index') }}" class="submenu-link">📄 Listado de Clientes</a>
                    <a href="{{ route('empresa.pagos.index') }}" class="submenu-link">💰 Cta. Cte. Clientes</a>
                    <a href="{{ route('empresa.pagos.index') }}" class="submenu-link">🧾 Recibos de Cobro</a>
                </div>
            </div>
        </div>

        {{-- ÁREA DE PROVEEDORES --}}
        <div class="nav-link-item">
            <i class="bi bi-truck-flatbed" style="color: #28a745;"></i>
            <span class="nav-icon-label">Proveedores</span>
            <div class="floating-balloon">
                <h6>🚛 Gestión Proveedores</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.proveedores.index') }}" class="submenu-link">🚚 Mis Proveedores</a>
                    <a href="{{ route('empresa.compras.index') }}" class="submenu-link">📑 Facturas de Compra</a>
                    <a href="{{ route('empresa.proveedores.index') }}" class="submenu-link">💳 Cta. Cte. Proveedores</a>
                    <a href="{{ route('empresa.proveedores.index') }}" class="submenu-link">🧾 Recibos de Pago</a>
                </div>
            </div>
        </div>

        {{-- REPORTES & BI --}}
        <div class="nav-link-item">
            <i class="bi bi-bar-chart-line" style="color: #adb5bd;"></i>
            <span class="nav-icon-label">Reportes</span>
            <div class="floating-balloon">
                <h6>📊 Inteligencia & BI</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.reportes.panel') }}" class="submenu-link">📈 Dashboard Global</a>
                    <a href="{{ route('empresa.reportes.caja_diaria') }}" class="submenu-link">💵 Auditoría de Caja</a>
                    <a href="{{ route('empresa.gps.index') }}" class="submenu-link">📍 GPS (Beta)</a>
                </div>
            </div>
        </div>

        {{-- SISTEMA & CONFIG --}}
        <div class="nav-link-item">
            <i class="bi bi-gear" style="color: #f1f5f9;"></i>
            <span class="nav-icon-label">Ajustes</span>
            <div class="floating-balloon">
                <h6>⚙️ Administración</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.configuracion.index') }}" class="submenu-link">🛠️ Configurar App</a>
                    <a href="{{ route('empresa.usuarios.index') }}" class="submenu-link">👥 Gestión Usuarios</a>
                    <a href="{{ route('empresa.personal.rendimiento') }}" class="submenu-link">📊 Rendimiento</a>
                    <a href="{{ route('empresa.backup.index') }}" class="submenu-link">🛡️ Bóveda Backups</a>
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
                <h4 class="mb-0 fw-bold d-none d-md-block">{{ $empresa->nombre_comercial ?? 'MultiPOS' }}</h4>
                {{-- NOVEDADES --}}
                <a href="{{ route('empresa.novedades') }}" class="btn btn-sm btn-light border rounded-pill px-3 ms-2 d-none d-lg-flex align-items-center gap-2">
                    <span class="pulse-dot"></span>
                    <span class="small fw-bold">Novedades v2.4</span>
                </a>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            {{-- BOTÓN REGRESAR A OWNER (MIMETIZACIÓN) --}}
            @if(session()->has('impersonator_id'))
                <a href="{{ route('owner.return-to-owner') }}" class="btn btn-warning btn-sm fw-bold px-3 shadow-sm d-flex align-items-center gap-2 animate__animated animate__pulse animate__infinite">
                    <i class="bi bi-person-badge-fill"></i>
                    VOLVER A MI PANEL
                </a>
            @endif

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
        #main-content { padding-left: 0 !important; }
        .top-bar { left: 0 !important; }
        @endif
    </style>

    <main class="container-fluid p-4">
        @yield('content')
    </main>
</div>

{{-- AYUDA MÁGICA --}}
<div id="help-trigger" onclick="openHelp()"><i class="bi bi-magic"></i></div>

{{-- ASISTENTE INTELIGENTE ARTI (VENTANA MAESTRA ARRSTRABLE Y REDIMENSIONABLE) --}}
<div id="helpPanel" class="arti-window-pro shadow-lg animate__animated animate__fadeInUp" style="display:none;">
    <div class="arti-header-pro d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-robot text-warning"></i>
            <h6 class="mb-0 fw-bold small text-uppercase tracking-wider">Cerebro de Arti</h6>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button class="btn-arti-tool" id="btnEditHelp" title="Editar Manual"><i class="bi bi-pencil-square"></i></button>
            <button class="btn-arti-tool" onclick="openHelp()" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    
    <div class="arti-body-pro" id="helpContentArea">
        {{-- Aquí se carga el contenido --}}
    </div>

    {{-- ZONA DE EDICIÓN --}}
    <div id="helpEditorArea" style="display:none;" class="p-3 bg-white text-dark rounded-bottom-4">
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
        Ruta operativa: <span class="text-white fw-bold">{{ Route::currentRouteName() }}</span>
    </div>
    <div class="ui-resizable-handle ui-resizable-se"></div>
</div>

<style>
    .arti-window-pro {
        position: fixed;
        right: 30px;
        top: 100px;
        width: 450px;
        height: 650px;
        min-width: 350px;
        min-height: 300px;
        background: rgba(10, 12, 14, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        z-index: 1000002;
        display: none;
        flex-direction: column;
        color: #fff;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    .arti-header-pro {
        padding: 18px 22px;
        background: rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        cursor: move;
        border-radius: 20px 20px 0 0;
    }

    .arti-body-pro {
        flex-grow: 1;
        overflow-y: auto;
        padding: 30px;
        line-height: 1.6;
        color: #e2e8f0;
    }

    .btn-arti-tool {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        cursor: pointer;
        padding: 0 8px;
        transition: all 0.2s;
    }
    .btn-arti-tool:hover { color: #fff; transform: scale(1.1); }

    .arti-footer-pro {
        padding: 10px 20px;
        text-align: center;
        border-top: 1px solid rgba(255,255,255,0.05);
        font-size: 10px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .manual-body h1, .manual-body h2, .manual-body h3 { color: var(--color-primario); margin-top: 1.2rem; }
    .manual-body img { max-width: 100%; border-radius: 12px; margin: 15px 0; border: 1px solid rgba(255,255,255,0.1); }
    
    .ui-resizable-se {
        width: 15px; height: 15px; background: linear-gradient(135deg, transparent 50%, rgba(255,255,255,0.3) 50%); bottom: 5px; right: 5px; cursor: nwse-resize;
    }
</style>

{{-- LIBRERIAS MASTER --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const currentRoute = '{{ Route::currentRouteName() }}';
    const userRole = "{{ auth()->user()->role }}";
    const isImpersonated = {{ session('impersonator_id') ? 'true' : 'false' }};

    $(function() {
        // HABILITAR ARRASTRE Y REDIMENSIONADO MULTI-EJE
        $("#helpPanel").draggable({ handle: ".arti-header-pro", containment: "window" })
                       .resizable({ minWidth: 350, minHeight: 300, handles: "all" });
    });

    function openHelp() {
        const panel = $("#helpPanel");
        if (panel.css('display') === 'flex') {
            panel.hide();
        } else {
            panel.css('display', 'flex');
            fetchHelpContent();
        }
    }

    function fetchHelpContent() {
        const area = $("#helpContentArea");
        area.html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted x-small">Consultando cerebro...</p></div>');
        $("#btnEditHelp").addClass('d-none');

        $.get(`/help/fetch?route=${currentRoute}`, function(res) {
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
                        <i class="bi bi-journal-x fs-1"></i>
                        <p class="mt-2 mb-0 fw-bold">Arti no tiene datos aquí aún.</p>
                        ${(userRole === 'owner' || isImpersonated) ? '<button class="btn btn-sm btn-primary rounded-pill px-4 mt-3 mt-1" onclick="activateEditor()">INICIAR MANUAL</button>' : ''}
                    </div>
                `);
            }
        });
    }

    function activateEditor() {
        $("#helpContentArea").hide();
        $("#helpEditorArea").show();
        const currentTitle = $("#helpContentArea h3").text() || "Guía de " + currentRoute;
        const currentContent = $("#helpContentArea .manual-body").html() || "";
        const currentVideo = ""; // Podríamos leerlo de un buffer si hiciese falta

        $("#editHelpTitle").val(currentTitle);
        $('#summernoteHelp').summernote({ 
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
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
        const title = $("#editHelpTitle").val();
        const content = $('#summernoteHelp').summernote('code');
        const video = $("#editHelpVideo").val();

        if(!title || !content) {
            Swal.fire({ icon: 'warning', title: 'Faltan datos', text: 'Arti necesita un título y contenido para guardar.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return;
        }

        $.post('/help/save', {
            _token: "{{ csrf_token() }}",
            route_name: currentRoute,
            title: title,
            content: content,
            video_url: video
        }, function(res) {
            if(res.success) {
                Swal.fire({ icon: 'success', title: '¡Memoria guardada!', text: 'Arti ha memorizado las instrucciones.', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
                cancelEditHelp();
                fetchHelpContent();
            }
        });
    }

    $("#btnEditHelp").on('click', activateEditor);
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>
