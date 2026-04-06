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
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

@yield('styles')
@stack('styles')

{{-- =========================================================
   CONFIGURACIÓN DE EMPRESA
   ========================================================= --}}
@php

$user = auth()->user();
$empresa = $user->empresa ?? null;
$config  = $empresa?->config ?? null;

// Buscamos la asistencia activa para el usuario logueado (Cajeros o Empleados)
$asistenciaActiva = \App\Models\Asistencia::where('user_id', $user?->id)
    ->whereNull('salida')
    ->latest()
    ->first();

$colorPrimario   = $config?->color_primary   ?? '#0d6efd';
$colorSecundario = $config?->color_secondary ?? '#6c757d';

$role = $user->role ?? 'usuario';

$roleName = match($role) {
    'owner' => 'Propietario',
    default => ucfirst($role),
};

$modoOscuro = ($config?->theme ?? 'light') === 'dark';

$logo = ($config && $config->logo_url) ? $config->logo_url : asset('images/logo_premium.png');
@endphp

<style>

:root{
    --color-primario: {{ $colorPrimario }};
    --color-secundario: {{ $colorSecundario }};
    @php
        // Extraer RGB para transparencias
        $rgb = [0,0,0];
        if (preg_match('/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/i', $colorPrimario, $matches)) {
            $rgb = [hexdec($matches[1]), hexdec($matches[2]), hexdec($matches[3])];
        }
    @endphp
    --color-primario-rgb: {{ implode(',', $rgb) }};
}

body{
    background:#f4f6f9;
}

@if($modoOscuro)
body{
    background:#0f1115 !important;
    color:#e6edf3 !important;
}

.navbar{
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1050;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.navbar .nav-link,
.navbar .navbar-brand,
.navbar .nav-link i,
.navbar .dropdown-toggle {
    color: #313131 !important;
    font-weight: 600 !important;
}

.card{
    background: rgba(22, 27, 34, 0.75) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border:1px solid rgba(44, 54, 66, 0.5);
    color:#e6edf3 !important;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
}

.table{
    background:#0f1115;
    color:#e6edf3;
}
@endif

/* =========================================================
   BOTONES
   ========================================================= */
.btn-primary{
    background:var(--color-primario)!important;
    border-color:var(--color-primario)!important;
}

.main-fluid{
    width:100%;
    padding:20px;
}

@if(!$modoOscuro)
.navbar {
    background: rgba(255, 255, 255, 0.95) !important;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}
.navbar .nav-link, .navbar .navbar-brand { color: #313131 !important; }
.card {
    background: #ffffff;
}
@endif

.btn-primary {
    transition: all 0.3s ease !important;
    background: var(--color-primario) !important;
    border-color: var(--color-primario) !important;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('empresa.dashboard') }}">
            <img src="{{ $logo }}" style="height:34px;margin-right:8px;">
            {{ $empresa->nombre_comercial ?? 'MultiPOS' }}
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('empresa.dashboard') }}">Panel</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-box-seam me-1"></i> Productos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('empresa.products.index') }}">Listado / Alta</a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.rubros.index') }}">Gestionar Rubros</a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.products.bulk-price-update') }}">Act. de Precios</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item fw-bold text-primary" href="{{ route('empresa.inventory_scan') }}"><i class="bi bi-qr-code-scan me-2"></i> ESCÁNER MÓVIL</a></li>
                        <li><a class="dropdown-item fw-bold" href="{{ route('empresa.labels.index') }}"><i class="bi bi-printer me-2"></i> IMPRIMIR ETIQUETAS</a></li>
                        <li><a class="dropdown-item fw-bold text-dark border-top mt-2 pt-2" href="{{ route('empresa.recipes.index') }}"><i class="bi bi-mortarboard me-2"></i> RECETAS / PRODUCCIÓN</a></li>
                        <li><a class="dropdown-item text-warning" href="{{ route('empresa.stock.faltantes') }}"><i class="bi bi-exclamation-triangle me-2"></i> CENTRO DE REPOSICIÓN</a></li>
                        <li><a class="dropdown-item fw-bold text-success border-top mt-2 pt-2" href="{{ route('empresa.stock.valuation') }}"><i class="bi bi-currency-dollar me-2"></i> VALORIZACIÓN (CAPITAL)</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('empresa.stock.index') }}">
                        <i class="bi bi-database-fill-gear"></i> Inventario
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-bold text-primary" href="{{ route('empresa.pos.index') }}">🛒 VENTAS (POS)</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Ventas</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('empresa.ventas.index') }}">📋 Historial / Listado</a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.clientes.index') }}">👥 Clientes</a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.presupuestos.index') }}">📜 Presupuestos</a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.orders.index') }}">🛒 Pedidos Online</a></li>
                        <li><a class="dropdown-item fw-bold text-dark" href="{{ route('empresa.logistica.reporte') }}">📦 Reporte de Guarda</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.ventas.manual') }}">✍️ Venta Manual</a></li>
                    </ul>
                </li>

                {{-- COMPRAS (ABASTECIMIENTO) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-cart-check"></i> Compras
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li><a class="dropdown-item fw-bold" href="{{ route('empresa.compras.create') }}"><i class="bi bi-plus-circle me-2 text-success"></i> Nueva Compra</a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.compras.index') }}"><i class="bi bi-list-ul me-2"></i> Historial de Compras</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.proveedores.index') }}"><i class="bi bi-truck me-2"></i> Proveedores</a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.stock.faltantes') }}"><i class="bi bi-exclamation-triangle me-2 text-warning"></i> Reposición / Faltantes</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.labels.index') }}"><i class="bi bi-qr-code me-2"></i> Etiquetas & Barcodes</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-bold text-info" href="#" role="button" data-bs-toggle="dropdown">👤 Personal</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('empresa.usuarios.index') }}">👥 Gestión de Usuarios</a></li>
                        <li><a class="dropdown-item fw-bold" href="{{ route('empresa.personal.rendimiento') }}">📊 Rendimiento Operativo</a></li>
                        @if(auth()->user()->role === 'empresa')
                            <li><a class="dropdown-item fw-bold text-danger" href="{{ route('empresa.personal.cajas.index') }}">🕵️ Auditoría de Cajas</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item fw-bold text-primary" href="{{ route('empresa.personal.asistencia.qr') }}">📲 PUNTO DE FICHAJE (QR)</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle font-weight-bold text-danger" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-wallet2 me-1"></i> Gastos
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0">
                        <li><a class="dropdown-item fw-bold" href="{{ route('empresa.gastos.index') }}">📋 Listado / Auditoría</a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.gastos_categorias.index') }}">🏷️ Gestionar Categorías</a></li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        @if(auth()->user()->can_register_expenses || auth()->user()->role === 'empresa')
                            <li><a class="dropdown-item text-warning fw-bold" href="{{ route('empresa.gastos.quick') }}"><i class="bi bi-phone me-1"></i> Registro de Gasto Rápido</a></li>
                        @endif
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('empresa.reportes.panel') }}">Reportes</a>
                </li>

                <li class="nav-item">
                    @php
                        $targetEmpresa = auth()->user()->empresa;
                        $catalogParam = $targetEmpresa?->slug ?: $targetEmpresa?->id;
                    @endphp
                    <a class="nav-link fw-bold text-success" href="{{ $targetEmpresa ? route('catalog.index', $catalogParam) : '#' }}" target="_blank">🌐 Catálogo</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="{{ route('empresa.novedades') }}">🔥 Novedades</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                {{-- Solo CAJEROS ven el botón de turno --}}
                @if(auth()->user()->role === 'usuario' && auth()->user()->sub_role === 'cajero')
                    <li class="nav-item">
                        @if(!$asistenciaActiva)
                            <button class="btn btn-outline-success fw-bold px-3 py-1 border-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalCheckIn" style="font-size: 0.8rem;">
                                🔔 INGRESO
                            </button>
                        @else
                            <button class="btn btn-outline-danger fw-bold px-3 py-1 border-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalCheckOut" style="font-size: 0.8rem;">
                                🛑 EGRESO
                            </button>
                        @endif
                    </li>
                @endif

                {{-- BOTÓN TÁCTICO DE RETORNO A OWNER --}}
                @if(session('impersonator_id'))
                    <li class="nav-item">
                        <a href="{{ route('owner.return-to-owner') }}" 
                           class="btn btn-warning fw-bold d-flex align-items-center justify-content-center shadow-sm"
                           style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; border: 1px solid rgba(0,0,0,0.1);"
                           title="Volver a mi sesión (OWNER)">
                           O
                        </a>
                    </li>
                @endif

                {{-- PERFIL DE USUARIO --}}
                <li class="nav-item dropdown">
                    <button class="btn dropdown-toggle shadow-sm d-flex align-items-center gap-2 {{ $modoOscuro ? 'btn-dark border-secondary' : 'btn-light border text-dark' }}" 
                            data-bs-toggle="dropdown" 
                            style="border-radius:30px; padding: 4px 14px;">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span class="d-none d-md-inline fw-bold small">
                            {{ $user->name }}
                        </span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg mt-2 border-0" style="border-radius: 12px; min-width: 210px;">
                        @if(session('impersonator_id'))
                        <li>
                            <a class="dropdown-item fw-bold text-warning" href="{{ route('owner.return-to-owner') }}" style="background: rgba(245, 158, 11, 0.1);">
                                ⬅️ Volver a mi sesión (OWNER)
                            </a>
                        </li>
                        <li><hr class="dropdown-divider opacity-10"></li>
                        @endif

                        <li><div class="dropdown-header text-uppercase small fw-bold text-muted">Mi Cuenta</div></li>
                        <li><a class="dropdown-item" href="{{ route('password.edit') }}">
                            <i class="bi bi-shield-lock me-2"></i> Seguridad
                        </a></li>
                        
                        @if($user->role === 'empresa')
                        <li><a class="dropdown-item fw-bold text-primary bg-primary bg-opacity-10" href="{{ route('empresa.suscripcion.index') }}">
                            <i class="bi bi-star-fill text-warning me-2"></i> Mi Suscripción
                        </a></li>
                        <li><a class="dropdown-item fw-bold text-success" href="{{ route('empresa.backup.index') }}">
                            <i class="bi bi-shield-shaded me-2"></i> Bóveda de Resguardo
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('empresa.configuracion.index') }}">
                            <i class="bi bi-gear me-2"></i> Configuración
                        </a></li>
                        @endif

                        <li><hr class="dropdown-divider opacity-10"></li>
                        <li>
                            <a class="dropdown-item text-danger fw-bold" href="{{ route('logout.get') }}">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container-fluid px-4 px-md-5 my-4">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
@stack('scripts')

{{-- MODALES DE ASISTENCIA --}}
<div class="modal fade" id="modalCheckIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title fw-bold">🟢 INICIAR TURNO DE CAJA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.personal.checkin') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Ingrese con cuánto efectivo inicial recibe la caja para comenzar a operar.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fondo de Caja (Vuelto) 💵</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="vuelto_inicial" class="form-control form-control-lg fw-bold" step="0.01" value="0.00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="submit" class="btn btn-success px-4 fw-bold">CONFIRMAR Y EMPEZAR 🚀</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCheckOut" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title fw-bold">🛑 CERRAR JORNADA Y CAJA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.personal.checkout') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <h2 class="fw-bold mb-4">¿Finalizar turno ahora?</h2>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Efectivo Físico en Caja 💰</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="vuelto_final" class="form-control form-control-lg fw-bold" step="0.01" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="submit" class="btn btn-danger px-4 fw-bold">FINALIZAR JORNADA 🛑</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('{{ asset('sw.js') }}')
                .then(reg => console.log('Service Worker registrado', reg))
                .catch(err => console.log('Error al registrar Service Worker', err));
        });
    }

    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        // Mostrar botón de instalación si existe en la página
        const installBtn = document.getElementById('installAppBtn');
        if (installBtn) {
            installBtn.style.display = 'block';
        }
    });

    function installApp() {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('Usuario aceptó la instalación');
                }
                deferredPrompt = null;
            });
        }
    }
</script>

</body>
</html>
