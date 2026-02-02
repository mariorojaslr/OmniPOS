<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'MultiPOS') }}</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<!-- ===========================
     NAVBAR SUPERIOR
=========================== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand fw-bold" href="{{ route('empresa.dashboard') }}">
            MultiPOS
        </a>

        <!-- BOTÓN MOBILE -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            <!-- ===========================
                 MENÚ IZQUIERDO
            =========================== -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                @auth

                    {{-- ===========================
                         USUARIO EMPRESA
                    =========================== --}}
                    @if(auth()->user()->isEmpresa())

                        <!-- Dashboard Empresa -->
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('empresa.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <!-- Productos -->
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('empresa.products.index') }}">
                                Productos
                            </a>
                        </li>

                        <!-- Catálogo (ACTIVO) -->
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('empresa.catalogo.index') }}">
                                Catálogo
                            </a>
                        </li>

                        <!-- POS (ACTIVO) -->
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('empresa.pos.index') }}">
                                POS
                            </a>
                        </li>

                    @endif

                    {{-- ===========================
                         USUARIO OWNER
                    =========================== --}}
                    @if(auth()->user()->isOwner())

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('owner.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('owner.empresas.index') }}">
                                Empresas
                            </a>
                        </li>

                    @endif

                @endauth
            </ul>

            <!-- ===========================
                 MENÚ USUARIO (DERECHA)
            =========================== -->
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">

                        <button class="btn btn-light dropdown-toggle"
                                data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <!-- Tipo de usuario -->
                            <li>
                                <span class="dropdown-item-text text-muted">
                                    {{ auth()->user()->isOwner() ? 'Owner' : 'Empresa' }}
                                </span>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <!-- Logout -->
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </li>
                @endauth
            </ul>

        </div>
    </div>
</nav>

<!-- ===========================
     CONTENIDO PRINCIPAL
=========================== -->
<main class="container my-5">
    @yield('content')
</main>

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
