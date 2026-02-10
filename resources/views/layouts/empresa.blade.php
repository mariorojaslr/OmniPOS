<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'MultiPOS') }}</title>

    {{-- Responsive --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Estilos específicos --}}
    @stack('styles')
</head>

<body class="bg-light">

{{-- ======================================================
    NAVBAR · EMPRESA / USUARIO
====================================================== --}}
<nav class="navbar navbar-expand-lg shadow-sm border-bottom
@if(auth()->user()->role === 'usuario')
    navbar-dark bg-primary
@else
    navbar-light bg-white
@endif
">

    <div class="container-fluid px-4">

        {{-- LOGO --}}
        <a class="navbar-brand fw-bold
        @if(auth()->user()->role !== 'usuario') text-dark @else text-white @endif"
           href="{{ auth()->user()->role === 'usuario'
                    ? route('empresa.usuario.dashboard')
                    : route('empresa.dashboard') }}">
            MultiPOS
        </a>

        {{-- BOTÓN MOBILE --}}
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#empresaNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="empresaNavbar">

            {{-- ======================
                MENÚ IZQUIERDO
            ====================== --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a class="nav-link
                    @if(auth()->user()->role !== 'usuario') text-dark @else text-white @endif"
                       href="{{ auth()->user()->role === 'usuario'
                                ? route('empresa.usuario.dashboard')
                                : route('empresa.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                {{-- PRODUCTOS --}}
                <li class="nav-item">
                    <a class="nav-link
                    @if(auth()->user()->role !== 'usuario') text-dark @else text-white @endif"
                       href="{{ route('empresa.products.index') }}">
                        Productos
                    </a>
                </li>

                {{-- CATÁLOGO --}}
                <li class="nav-item">
                    <a class="nav-link
                    @if(auth()->user()->role !== 'usuario') text-dark @else text-white @endif"
                       href="{{ route('empresa.catalogo.index') }}">
                        Catálogo
                    </a>
                </li>

                {{-- POS --}}
                <li class="nav-item">
                    <a class="nav-link fw-semibold
                    @if(auth()->user()->role !== 'usuario') text-dark @else text-white @endif"
                       href="{{ route('empresa.pos.index') }}">
                        POS
                    </a>
                </li>

            </ul>

            {{-- ======================
                USUARIO / SEGURIDAD
            ====================== --}}
            <div class="dropdown">

                <button class="btn
                @if(auth()->user()->role === 'usuario')
                    btn-light
                @else
                    btn-outline-secondary
                @endif
                dropdown-toggle"
                        data-bs-toggle="dropdown">
                    {{ auth()->user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    {{-- PANEL --}}
                    @if(auth()->user()->role === 'usuario')
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('empresa.usuario.dashboard') }}">
                                Mi panel usuario
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('empresa.dashboard') }}">
                                Panel empresa
                            </a>
                        </li>

                        {{-- ⚙️ CONFIGURACIÓN (CORREGIDO) --}}
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('empresa.configuracion.index') }}">
                                ⚙️ Configuración
                            </a>
                        </li>
                    @endif

                    <li><hr class="dropdown-divider"></li>

                    {{-- CAMBIAR CONTRASEÑA --}}
                    <li>
                        <a class="dropdown-item"
                           href="{{ route('password.edit') }}">
                            Cambiar contraseña
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    {{-- LOGOUT --}}
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>
            </div>

        </div>
    </div>
</nav>

{{-- ======================================================
    CONTENIDO
====================================================== --}}
<main class="container-fluid px-4 py-4">
    @yield('content')
</main>

{{-- Bootstrap --}}
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

{{-- Scripts --}}
@stack('scripts')

</body>
</html>
