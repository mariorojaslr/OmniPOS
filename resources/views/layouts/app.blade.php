<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'MultiPOS') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @yield('styles')
</head>

<body class="bg-light">

{{-- =========================================================
   NAVBAR
========================================================= --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ route('empresa.dashboard') }}">
            MultiPOS
        </a>

        <div class="ms-auto d-flex align-items-center gap-3">

            @php $empresa = auth()->user()->empresa ?? null; @endphp

            @if($empresa)
                <div class="small text-muted text-end">
                    <div><strong>Empresa:</strong> {{ $empresa->nombre_comercial }}</div>
                    <div><strong>Usuario:</strong> {{ auth()->user()->name }}</div>
                </div>
            @endif

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    {{ auth()->user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text text-muted">{{ auth()->user()->role }}</span></li>
                    <li><hr></li>

                    <li>
                        <a class="dropdown-item" href="{{ route('password.edit') }}">
                            Cambiar contraseña
                        </a>
                    </li>

                    @if(auth()->user()->role === 'empresa')
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
                            <button class="dropdown-item">Cerrar sesión</button>
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
   ✔ Modal centrado
   ✔ Una vez por día
   ✔ SIN LABEL FIJO
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
