<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'MultiPOS') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

        /* Fondo Animado Dark Elegante */
        .premium-bg {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: -1;
            background: radial-gradient(circle at 15% 50%, rgba(37, 99, 235, 0.1), transparent 25%),
                        radial-gradient(circle at 85% 30%, rgba(147, 51, 234, 0.1), transparent 25%);
            animation: bgShift 15s infinite alternate ease-in-out;
        }

        @keyframes bgShift {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
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
            z-index: 1050 !important;
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
    <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('empresa.dashboard') }}">
            <img src="{{ asset('images/logo_premium.png') }}" alt="Logo" style="height:40px; width:auto; border-radius: 10px;" class="me-2 shadow">
            MultiPOS Central
        </a>

        @if(auth()->user()->role === 'owner' && !request()->routeIs('owner.dashboard'))
            <a href="{{ route('owner.dashboard') }}" class="btn btn-sm text-white ms-3 px-3 shadow-none" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 20px;">
                <i class="me-1">⬅️</i> Volver al Owner Dashboard
            </a>
        @endif

        <div class="ms-auto d-flex align-items-center gap-3">

            @php $empresa = auth()->user()->empresa ?? null; @endphp

            @if($empresa)
                <div class="small text-end" style="color: #94a3b8;">
                    <div><span class="text-white">Empresa:</span> {{ $empresa->nombre_comercial }}</div>
                    <div><span class="text-white">Usuario:</span> {{ auth()->user()->name }}</div>
                </div>
            @endif

            <div class="dropdown">
                <button class="btn btn-profile dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="me-1">👤</i> {{ auth()->user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark-custom">
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

                    <li><hr></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item">
                                Cerrar sesión
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
<main class="container my-4">
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

</body>
</html>
