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

    {{-- Estilos específicos de cada vista --}}
    @stack('styles')
</head>

<body class="bg-light">

{{-- ======================================================
    NAVBAR · EMPRESA
====================================================== --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid px-4">

        {{-- Logo --}}
        <a class="navbar-brand fw-bold" href="{{ route('empresa.dashboard') }}">
            MultiPOS
        </a>

        {{-- Botón mobile --}}
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

                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('empresa.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('empresa.products.index') }}">
                        Productos
                    </a>
                </li>

                {{-- CATÁLOGO --}}
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('empresa.catalogo.index') }}">
                        Catálogo
                    </a>
                </li>

                {{-- POS --}}
                <li class="nav-item">
                    <a class="nav-link fw-semibold"
                       href="{{ route('empresa.pos.index') }}">
                        POS
                    </a>
                </li>

            </ul>

            {{-- ======================
                USUARIO
            ====================== --}}
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle"
                        data-bs-toggle="dropdown">
                    {{ auth()->user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item-text text-muted">
                            Empresa
                        </span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

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

{{-- ======================================================
    CONTENIDO PRINCIPAL
====================================================== --}}
<main class="container-fluid px-4 py-4">
    @yield('content')
</main>

{{-- Bootstrap --}}
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

{{-- Scripts específicos de cada vista --}}
@stack('scripts')

</body>
</html>
