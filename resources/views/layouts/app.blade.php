<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'MultiPOS') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- PREVENIR CACHÉ DESPUÉS DE LOGOUT --}}
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- FAVICON MULTIPOS -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    @yield('styles')

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #0f172a;
            color: #e2e8f0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* Fondo Animado Dark Elegante y Vibrante */
        .premium-bg {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: -1;
            background: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(147, 51, 234, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 1) 0px, transparent 100%);
            animation: bgShift 20s infinite alternate ease-in-out;
        }

        @keyframes bgShift {
            0% { background-position: 0% 0%; }
            100% { background-position: 100% 100%; }
        }

        /* Navbar Glassmorphism */
        .navbar-premium {
            background: rgba(15, 23, 42, 0.7) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: #f8fafc !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        /* Botón de Perfil Dropdown */
        .btn-profile {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #f8fafc;
            border-radius: 20px;
            padding: 6px 16px;
            transition: all 0.3s;
        }
        .btn-profile:hover, .btn-profile.show {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* Menú Dropdown Dark */
        .dropdown-menu-dark-custom {
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
            z-index: 9999 !important;
            max-height: none !important; /* Asegura que no se corte nada */
            overflow: visible !important;
        }
        .dropdown-menu-dark-custom .dropdown-item,
        .dropdown-menu-dark-custom .dropdown-item-text {
            color: #f8fafc !important;
            transition: all 0.2s;
        }
        .dropdown-menu-dark-custom .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }
        .dropdown-menu-dark-custom .dropdown-divider {
            border-top-color: rgba(255,255,255,0.1);
        }

        /* Glassmorphism Cards Utilities */
        .glass-card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            color: #f8fafc;
        }

        /* 🛡️ BLINDAJE ANTI-ICONOS GIGANTES DE LARAVEL */
        nav svg, .pagination svg {
            max-width: 1.25rem !important;
            max-height: 1.25rem !important;
            display: inline-block !important;
            vertical-align: middle;
        }
        
        /* 🌟 ANIMACIÓN PARA BOTÓN DE RETORNO */
        @keyframes pulse-yellow {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        .animate-pulse {
            animation: pulse-yellow 2s infinite;
        }

        /* Ocultar texto redundante de Laravel */
        nav[role="navigation"] .flex.hidden.sm\:flex-1,
        nav[role="navigation"] p.text-sm.text-gray-700 {
            display: none !important;
        }

        /* Estética de Paginación Dark Premium */
        .pagination .page-link {
            background-color: transparent !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #94a3b8 !important;
            border-radius: 8px !important;
            margin: 0 3px;
            padding: 8px 14px;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #60a5fa; /* Celeste atenuado sugerido */
            font-weight: 700;
            margin-bottom: 0.5rem;
            padding-left: 2px;
        }
        .pagination .active .page-link {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .pagination .page-link:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #fff !important;
        }
    </style>
</head>

<body>
    <div class="premium-bg"></div>

{{-- =========================================================
   NAVBAR
========================================================= --}}
<nav class="navbar navbar-expand-lg navbar-premium shadow-sm">
    <div class="{{ auth()->check() ? 'container-fluid px-4 px-md-5' : 'container' }}">

        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo_premium.png') }}" alt="Logo" style="height:40px; width:auto; border-radius: 10px;" class="me-2 shadow">
            MultiPOS Central
        </a>

        @if(auth()->user()?->role === 'owner' && !request()->routeIs('owner.dashboard'))
            <a href="{{ route('owner.dashboard') }}" class="btn btn-sm text-white ms-3 px-3 shadow-none" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 20px;">
                <i class="me-1">⬅️</i> Volver al Owner Dashboard
            </a>
        @endif

        <div class="ms-auto d-flex align-items-center gap-3">

            @php $empresa = auth()->user()?->empresa ?? null; @endphp

            @if(session('impersonator_id'))
                <a href="{{ route('owner.return-to-owner') }}" class="btn btn-warning fw-bold border-0 px-3 shadow animate-pulse" style="background: #f59e0b; color: #000 !important; border-radius: 12px; font-size: 0.85rem;">
                    🔙 VOLVER A MI SESIÓN (OWNER)
                </a>
            @endif

            @if($empresa)
                <div class="small text-end d-none d-md-block" style="color: #94a3b8;">
                    <div><span class="text-white">Empresa:</span> {{ $empresa->nombre_comercial }}</div>
                    <div><span class="text-white">Usuario:</span> {{ auth()->user()->name }}</div>
                </div>
            @endif

            {{-- BOTÓN ROJO DE CIERRE RÁPIDO (PEDIDO POR OWNER) --}}
            @if(auth()->user()->role === 'owner')
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger fw-bold border-0 px-3 shadow" style="background: #ef4444; color: white !important; border-radius: 12px; font-size: 0.85rem;">
                        🚪 CERRAR SESIÓN
                    </button>
                </form>
            @endif

            <div class="dropdown">
                <button class="btn btn-profile dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="me-1">👤</i> {{ auth()->user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark-custom">
                    
                    {{-- BOTÓN DE RETORNO A OWNER (SIEMPRE VISIBLE SI ESTA MIMETIZADO) --}}
                    @if(session('impersonator_id'))
                    <li>
                        <a class="dropdown-item fw-bold text-warning d-flex align-items-center" href="{{ route('owner.return-to-owner') }}">
                           <span class="me-2">🔙</span> VOLVER A MI CUENTA (OWNER)
                        </a>
                    </li>
                    <li><hr class="dropdown-divider opacity-20"></li>
                    @endif

                    <li>
                        <span class="dropdown-item-text">
                            {{ auth()->user()->role === 'owner' ? 'Propietario / Master' : (auth()->user()->role === 'empresa' ? 'Administrador' : 'Empleado / Cajero') }}
                        </span>
                    </li>

                    <li><hr></li>

                    <li>
                        <a class="dropdown-item" href="{{ route('password.edit') }}">
                            Cambiar contraseña
                        </a>
                    </li>

                    @if(auth()->user()->role === 'owner')
                    <li>
                        <a class="dropdown-item fw-bold text-primary" href="{{ route('owner.dashboard') }}">
                            Panel de Control Owner
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->role === 'empresa')
                    <li>
                        <a class="dropdown-item" href="{{ route('empresa.dashboard') }}">
                            Panel de la Empresa
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('empresa.configuracion.index') }}">
                            Configuración empresa
                        </a>
                    </li>
                    @endif

                    <li><hr class="dropdown-divider opacity-20"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-bold d-flex align-items-center">
                                <span class="me-2 text-danger">🚪</span> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

{{-- =========================================================
   CONTENIDO
========================================================= --}}
<main class="{{ auth()->check() ? 'container-fluid px-4 px-md-5' : 'container' }}" style="margin-top: 2rem; margin-bottom: 4rem; position: relative; z-index: 5;">
    @yield('content')
</main>

{{-- =========================================================
   MODAL REAL · CAMBIO PASSWORD
========================================================= --}}
@auth
@if(auth()->user()->must_change_password)

<div class="modal fade" id="forcePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-body text-center p-4">

                <h5 class="fw-bold mb-2">🔐 Seguridad requerida</h5>

                <p class="text-muted mb-4">
                    Debés cambiar tu contraseña para continuar usando el sistema.
                </p>

                <div class="d-flex gap-2 justify-content-center">

                    <a href="{{ route('password.edit') }}" class="btn btn-primary">
                        Cambiar contraseña
                    </a>

                    <button class="btn btn-outline-secondary" id="recordarBtn">
                        Recordar luego
                    </button>

                </div>

            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const hoy = new Date().toISOString().slice(0,10);
    const visto = localStorage.getItem('force_password_seen');

    if (visto !== hoy) {
        setTimeout(() => {
            let modal = new bootstrap.Modal(document.getElementById('forcePasswordModal'));
            modal.show();
        }, 800);
    }

    document.getElementById('recordarBtn')?.addEventListener('click', function () {
        localStorage.setItem('force_password_seen', hoy);
        bootstrap.Modal.getInstance(document.getElementById('forcePasswordModal')).hide();
    });

});
</script>

@endif
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
@stack('scripts')

<!-- CENTER OF KNOWLEDGE (AYUDA CONTEXTUAL) -->
<style>
    #help-trigger {
        position: fixed;
        bottom: 25px;
        left: 25px;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #3b82f6;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        cursor: pointer;
        z-index: 9999;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid rgba(255,255,255,0.2);
    }
    #help-trigger:hover { transform: scale(1.1) rotate(5deg); box-shadow: 0 15px 35px rgba(59, 130, 246, 0.6); }

    .offcanvas-help { 
        width: 480px !important; 
        background: rgba(15, 23, 42, 0.95) !important; 
        backdrop-filter: blur(20px); 
        color: #f8fafc;
        border-left: 1px solid rgba(255,255,255,0.1);
    }
    .help-content img { max-width: 100%; border-radius: 12px; margin: 15px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
</style>

<div id="help-trigger" onclick="openHelp()" title="Ayuda sobre esta página">
    <i class="bi bi-question-lg"></i>
</div>

<div class="offcanvas offcanvas-end offcanvas-help" tabindex="-1" id="offcanvasHelp">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title fw-bold">Manual de Operaciones</h5>
        <button type="button" class="btn-close btn-close-white" onclick="closeHelp()"></button>
    </div>
    <div class="offcanvas-body">
        <div id="help-loading" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Buscando instrucciones...</p>
        </div>

        <div id="help-view-mode">
            <div id="help-empty" style="display:none;" class="text-center py-5">
                <i class="bi bi-journal-x fs-1 text-muted"></i>
                <h5 class="mt-3">Sin instrucciones aún</h5>
                <p class="text-muted small">Esta página todavía no tiene contenido de ayuda asignado.</p>
                <button class="btn btn-primary btn-sm mt-3" onclick="enterEditMode()">Crear Ayuda</button>
            </div>

            <div id="help-display" style="display:none;">
                <h3 id="help-title" class="fw-bold mb-3"></h3>
                <div id="help-body" class="help-content mb-4"></div>
                <button class="btn btn-outline-primary w-100 fw-bold" onclick="enterEditMode()">Editar Manual</button>
            </div>
        </div>

        <div id="help-edit-mode" style="display:none;">
            <div class="mb-3">
                <label class="form-label fw-bold">Título</label>
                <input type="text" id="edit-help-title" class="form-control bg-dark text-white border-secondary">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Contenido</label>
                <textarea id="edit-help-content" class="form-control"></textarea>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-success w-100 fw-bold" onclick="saveHelp()">GUARDAR</button>
                <button class="btn btn-light" onclick="exitEditMode()">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    const helpModal = new bootstrap.Offcanvas(document.getElementById('offcanvasHelp'));
    const currentRoute = "{{ Route::currentRouteName() }}";

    function openHelp() {
        helpModal.show();
        $("#help-loading").show();
        $("#help-view-mode, #help-edit-mode").hide();

        $.get("{{ route('help.fetch') }}", { route: currentRoute }, function(res){
            $("#help-loading").hide();
            $("#help-view-mode").show();
            if(res.success && res.data) {
                $("#help-empty").hide(); $("#help-display").show();
                $("#help-title").text(res.data.title);
                $("#help-body").html(res.data.content);
            } else {
                $("#help-display").hide(); $("#help-empty").show();
            }
        });
    }

    function closeHelp() { helpModal.hide(); }

    function enterEditMode() {
        $("#help-view-mode").hide(); $("#help-edit-mode").show();
        $("#edit-help-title").val($("#help-title").text());
        $("#edit-help-content").summernote({ height: 300 });
        $("#edit-help-content").summernote('code', $("#help-body").html());
    }

    function exitEditMode() { 
        $("#help-edit-mode").hide(); 
        $("#help-view-mode").show(); 
    }

    function saveHelp() {
        const data = {
            _token: "{{ csrf_token() }}",
            route_name: currentRoute,
            title: $("#edit-help-title").val(),
            content: $("#edit-help-content").summernote('code')
        };
        $.post("{{ route('help.save') }}", data, function(res){
            if(res.success) { exitEditMode(); openHelp(); }
        });
    }
</script>

</body>
</html>
