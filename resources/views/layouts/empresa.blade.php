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

{{-- ASISTENTE INTELIGENTE ARTI (VENTANA FLOTANTE) --}}
<div id="arti-window" class="arti-window shadow-lg animate__animated animate__fadeInUp d-none">
    <div id="arti-header" class="arti-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <span class="arti-status-dot"></span>
            <span class="fw-800 small tracking-wider text-uppercase">Cerebro de Arti</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="arti-control-btn edit-btn d-none" onclick="enterEditMode()" title="Editar Manual"><i class="bi bi-pencil-square"></i></button>
            <button class="arti-control-btn close-btn" onclick="closeArti()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    
    <div class="arti-body">
        <div id="arti-loader" class="text-center py-5">
            <div class="spinner-border spinner-border-sm text-primary"></div>
            <div class="mt-2 x-small opacity-50">Sincronizando...</div>
        </div>

        <div id="arti-view-mode">
            <div id="arti-empty" class="text-center py-4 d-none">
                <i class="bi bi-cpu fs-2 opacity-20"></i>
                <h6 class="mt-2 fw-bold">Arti está listo para aprender</h6>
                <p class="x-small opacity-50">No hay instrucciones cargadas para esta ruta.</p>
                <button class="btn btn-primary btn-xs rounded-pill px-3 mt-2" onclick="enterEditMode()">CREAR MANUAL</button>
            </div>

            <div id="arti-display" class="d-none">
                <h4 id="arti-title" class="fw-bold text-primary mb-3"></h4>
                <div id="arti-content" class="arti-content-scroll small"></div>
                <div id="arti-video" class="mt-3 d-none"></div>
            </div>
        </div>

        <div id="arti-edit-mode" class="d-none">
            <input type="text" id="edit-arti-title" class="form-control mb-2 bg-dark text-white border-secondary small" placeholder="Título del manual...">
            <textarea id="edit-arti-content"></textarea>
            <input type="text" id="edit-arti-video" class="form-control mt-2 mb-2 bg-dark text-white border-secondary x-small" placeholder="URL Video (Opcional)...">
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-success btn-sm w-100 fw-bold" onclick="saveArti()">GUARDAR</button>
                <button class="btn btn-outline-light btn-sm" onclick="exitEditMode()">CANCELAR</button>
            </div>
        </div>
    </div>
    <div class="arti-resize-handle"></div>
</div>

<style>
    .arti-window {
        position: fixed;
        bottom: 90px;
        right: 30px;
        width: 400px;
        min-width: 300px;
        height: 500px;
        min-height: 250px;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        z-index: 1000002;
        display: flex;
        flex-direction: column;
        color: #fff;
    }

    .arti-header {
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        cursor: move;
        border-radius: 20px 20px 0 0;
    }

    .arti-status-dot {
        width: 8px;
        height: 8px;
        background: var(--color-primario);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--color-primario);
    }

    .arti-control-btn {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.5);
        font-size: 1.1rem;
        transition: all 0.2s;
        padding: 0 5px;
    }
    .arti-control-btn:hover { color: #fff; transform: scale(1.1); }

    .arti-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .arti-content-scroll {
        max-height: 100%;
        line-height: 1.6;
        opacity: 0.9;
    }

    .arti-resize-handle {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        cursor: nwse-resize;
        background: linear-gradient(135deg, transparent 50%, rgba(255,255,255,0.2) 50%);
        border-bottom-right-radius: 20px;
    }

    /* Override Summernote para dark mode */
    .note-editor.note-frame { border: 1px solid rgba(255,255,255,0.1) !important; background: #fff; color: #333; }
    
    .tracking-wider { letter-spacing: 1.5px; }
    .x-small { font-size: 0.75rem; }
    .btn-xs { padding: 4px 10px; font-size: 0.77rem; }
</style>

{{-- LIBRERIAS PARA ARRASTRAR --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    const currentRoute = "{{ Route::currentRouteName() }}";
    const userRole = "{{ auth()->user()->role }}";
    const isImpersonated = {{ session('impersonator_id') ? 'true' : 'false' }};

    $(function() {
        // Inicializar arrastre y redimensionado
        $("#arti-window").draggable({ handle: "#arti-header", containment: "window" });
        $("#arti-window").resizable({
            handles: { 'se': '.arti-resize-handle' },
            minWidth: 300, minHeight: 250,
            containment: "window"
        });
    });

    function openHelp() {
        const win = $("#arti-window");
        if(!win.hasClass('d-none')) { closeArti(); return; }
        
        win.removeClass('d-none').addClass('animate__fadeInUp');
        $("#arti-loader").show();
        $("#arti-view-mode, #arti-edit-mode").hide();
        $(".edit-btn").addClass('d-none');

        $.get("{{ route('help.fetch') }}", { route: currentRoute }, function(res){
            $("#arti-loader").hide();
            $("#arti-view-mode").show();
            
            if(userRole === 'owner' || isImpersonated) $(".edit-btn").removeClass('d-none');

            if(res.success && res.data) {
                $("#arti-empty").addClass('d-none');
                $("#arti-display").removeClass('d-none');
                $("#arti-title").text(res.data.title);
                $("#arti-content").html(res.data.content);
                
                if(res.data.video_url) {
                    let embed = res.data.video_url.replace("watch?v=", "embed/");
                    $("#arti-video").html(`<iframe width="100%" height="200" src="${embed}" frameborder="0" allowfullscreen></iframe>`).removeClass('d-none');
                } else {
                    $("#arti-video").addClass('d-none');
                }
            } else {
                $("#arti-display").addClass('d-none');
                $("#arti-empty").removeClass('d-none');
            }
        });
    }

    function closeArti() {
        $("#arti-window").addClass('d-none');
    }

    function enterEditMode() {
        $("#arti-view-mode").hide();
        $("#arti-edit-mode").show();
        $("#edit-arti-title").val($("#arti-title").text() || "Manual de " + currentRoute);
        $("#edit-arti-content").summernote({ height: 250 });
        $("#edit-arti-content").summernote('code', $("#arti-content").html());
        $("#edit-arti-video").val("");
    }

    function exitEditMode() {
        $("#arti-edit-mode").hide();
        $("#arti-view-mode").show();
    }

    function saveArti() {
        const data = {
            _token: "{{ csrf_token() }}",
            route_name: currentRoute,
            title: $("#edit-arti-title").val(),
            content: $("#edit-arti-content").summernote('code'),
            video_url: $("#edit-arti-video").val()
        };
        $.post("{{ route('help.save') }}", data, function(res){
            if(res.success) { exitEditMode(); openHelp(); }
        });
    }
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>
