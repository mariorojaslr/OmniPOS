<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>{{ config('app.name', 'MultiPOS') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

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
}

body{
    background:#f4f6f9;
}

/* =========================================================
   MODO OSCURO
   ========================================================= */

@if($modoOscuro)

body{
    background:#0f1115 !important;
    color:#e6edf3 !important;
}

.navbar{
    background: rgba(11, 26, 43, 0.7) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom:1px solid rgba(31, 45, 61, 0.5);
    position: relative;
    z-index: 1050;
}

.navbar .nav-link,
.navbar .navbar-brand{
    color:#e6edf3 !important;
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

.table thead{
    background:#161b22;
}

.table tbody tr{
    border-bottom:1px solid #1f2d3d;
}

input, select, textarea{
    background:#0f1115 !important;
    color:#e6edf3 !important;
    border:1px solid #2c3642 !important;
}

.btn-light{
    background:#161b22;
    color:#e6edf3;
}

.dropdown-menu{
    background:#161b22;
    border:1px solid #2c3642;
}

.dropdown-item{
    color:#e6edf3;
}

.dropdown-item:hover{
    background:#1f2d3d;
}

@endif


/* =========================================================
   BOTONES
   ========================================================= */

.btn-primary{
    background:var(--color-primario)!important;
    border-color:var(--color-primario)!important;
}


/* =========================================================
   MENSAJES FLASH
   ========================================================= */

.flash-message{
    padding:6px 10px;
    border-radius:6px;
    font-size:13px;
    margin-bottom:10px;
    width:fit-content;
}

.flash-success{
    background:#e6f7ee;
    color:#1e7e34;
    border:1px solid #b7e4c7;
}

.flash-error{
    background:#fdeaea;
    color:#a71d2a;
    border:1px solid #f5c2c7;
}


/* =========================================================
   CONTENEDOR PRINCIPAL
   ========================================================= */

.main-fluid{
    width:100%;
    padding:20px;
}

/* =========================================================
   GLASSMORPHISM BASE (MODO CLARO)
   ========================================================= */

@if(!$modoOscuro)
.navbar {
    background: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
    position: relative;
    z-index: 1050;
}

.card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.1);
}
@endif

/* Cuidar la visual de los botones primary con el color de la empresa */
.btn-primary {
    transition: all 0.3s ease !important;
    background: var(--color-primario) !important;
    border-color: var(--color-primario) !important;
}
.btn-primary:hover {
    filter: brightness(1.15) !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
}

</style>

</head>



<body>


{{-- =========================================================
   NAVBAR PRINCIPAL
   ========================================================= --}}

<nav class="navbar navbar-expand-lg">

<div class="container-fluid">


<a class="navbar-brand fw-bold d-flex align-items-center"
   href="{{ route('empresa.dashboard') }}">

<img src="{{ $logo }}" style="height:34px;margin-right:8px;">

{{ $empresa->nombre_comercial ?? 'MultiPOS' }}

</a>


<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarMain">
<span class="navbar-toggler-icon"></span>
</button>


<div class="collapse navbar-collapse" id="navbarMain">


{{-- =========================================================
   MENÚ PRINCIPAL
   ========================================================= --}}

<ul class="navbar-nav me-auto">

<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.dashboard') }}">Panel</a>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        Productos
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('empresa.products.index') }}">Listado / Alta</a></li>
        <li><a class="dropdown-item" href="{{ route('empresa.rubros.index') }}">Gestionar Rubros</a></li>
        <li><a class="dropdown-item" href="{{ route('empresa.labels.index') }}">🏷️ Imprimir etiquetas</a></li>
        <li><a class="dropdown-item fw-bold text-primary" href="{{ route('empresa.inventory_scan') }}">📦 Escanear Inventario (QR)</a></li>
        <li><a class="dropdown-item fw-bold text-warning" href="{{ route('empresa.stock.faltantes') }}">🤖 Centro de Reposición</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('empresa.products.bulk-price-update') }}">Actualización de Precios</a></li>
    </ul>
</li>


<li class="nav-item">
    <a class="nav-link" href="{{ route('empresa.stock.index') }}">📦 Inventario</a>
</li>


<li class="nav-item">
<a class="nav-link fw-bold text-primary" href="{{ route('empresa.pos.index') }}">🛒 PUNTO DE VENTA (POS)</a>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        Ventas
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('empresa.ventas.index') }}">📋 Historial / Listado</a></li>
        <li><a class="dropdown-item" href="{{ route('empresa.clientes.index') }}">👥 Clientes</a></li>
        <li><a class="dropdown-item" href="{{ route('empresa.orders.index') }}">🛒 Pedidos Online</a></li>
        <li><a class="dropdown-item fw-bold text-dark" href="{{ route('empresa.logistica.reporte') }}">📦 Reporte de Guarda (Global)</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('empresa.ventas.manual') }}">✍️ Venta Manual</a></li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        Compras
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('empresa.compras.index') }}">🛒 Historial de Compras</a></li>
        <li><a class="dropdown-item" href="{{ route('empresa.proveedores.index') }}">🚛 Proveedores</a></li>
    </ul>
</li>



<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.reportes.panel') }}">Reportes</a>
</li>


<li class="nav-item">
<a class="nav-link fw-bold text-success" href="{{ route('catalog.index', $empresa->id) }}" target="_blank">
    🌐 Mi Catálogo
</a>
</li>

<li class="nav-item">
<a class="nav-link text-warning fw-bold" href="{{ route('empresa.novedades') }}">
🔥 Novedades
</a>
</li>

</ul>



{{-- =========================================================
   USUARIO / PERFIL / ASISTENCIA
   ========================================================= --}}

<ul class="navbar-nav">

    {{-- 🔔 BOTONES DE ASISTENCIA / TURNO (Sugeridos por Rol) --}}
    @if(!$user->isOwner())
    <li class="nav-item me-3">
        @if(!$asistenciaActiva)
            @if($user->esCajero())
                {{-- Cajero: Necesita abrir caja --}}
                <button class="btn btn-outline-success btn-sm fw-bold px-3 py-1 border-2" data-bs-toggle="modal" data-bs-target="#modalCheckIn">
                    🔔 INICIAR TURNO
                </button>
            @else
                {{-- Empleado General: Solo asistencia --}}
                <form action="{{ route('empresa.personal.checkin') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="vuelto_inicial" value="0">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-3 py-1 shadow-sm">
                        📲 MARCAR ENTRADA
                    </button>
                </form>
            @endif
        @else
            @if($user->esCajero())
                {{-- Cajero: Necesita cerrar caja y arquear --}}
                <button class="btn btn-danger btn-sm fw-bold px-3 py-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCheckOut">
                    🛑 FINALIZAR TURNO
                </button>
            @else
                {{-- Empleado General: Solo salida --}}
                <form action="{{ route('empresa.personal.checkout') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="vuelto_final" value="0">
                    <button type="submit" class="btn btn-outline-danger btn-sm fw-bold px-3 py-1 bg-white">
                        🛑 MARCAR SALIDA
                    </button>
                </form>
            @endif
        @endif
    </li>
    @endif


<li class="nav-item dropdown">

<button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
{{ $user->name }} ({{ $roleName }})
</button>



<ul class="dropdown-menu dropdown-menu-end">


{{-- Cambiar contraseña --}}
<li>
<a class="dropdown-item" href="{{ route('password.edit') }}">
    Cambiar contraseña
</a>
</li>


{{-- Configuración empresa --}}
@if(method_exists($user,'isEmpresa') && $user->isEmpresa())

<li>
<a class="dropdown-item" href="{{ route('empresa.configuracion.index') }}">
Configuración empresa
</a>
</li>

<li>
<a class="dropdown-item" href="{{ route('empresa.soporte.index') }}">
🎧 Soporte (Tickets)
</a>
</li>

@endif


<li><hr></li>


{{-- Logout --}}
<li>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button class="dropdown-item">Cerrar sesión</button>
</form>

</li>

</ul>

</li>

</ul>


</div>
</div>

</nav>



{{-- =========================================================
   CONTENIDO
   ========================================================= --}}

<main class="main-fluid">

{{-- MODO IMPERSONATE ACTIVO --}}
@if(session()->has('impersonate_by'))
    <div class="alert alert-warning shadow-lg" style="position: fixed; bottom: 25px; left: 25px; z-index: 9999; max-width: 300px; padding: 15px; font-size: 0.85rem; border-radius: 12px; border: 1px solid #e0a800;">
        <div class="mb-2" style="line-height: 1.4;">
            <strong>Modo Mimetización:</strong><br>
            Estás como <b>{{ auth()->user()->name }}</b> <br>
            ({{ $empresa->nombre_comercial ?? '' }})
        </div>
        <a href="{{ route('impersonate.leave') }}" class="btn btn-sm btn-dark w-100" style="font-weight: 600;">
            Volver a mi cuenta (Owner)
        </a>
    </div>
@endif

{{-- ERROR --}}
@if(session('error'))

<div class="flash-message flash-error">
✖ {{ session('error') }}
</div>

@endif



{{-- SUCCESS --}}
@if(session('success'))

<div id="flashSuccess" class="flash-message flash-success">
✔ {{ session('success') }}
</div>

@endif


@yield('content')

</main>



<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



{{-- =========================================================
   AUTO OCULTAR MENSAJES SUCCESS
   ========================================================= --}}

<script>

setTimeout(function(){

    let msg = document.getElementById('flashSuccess');

    if(msg){

        msg.style.transition = "opacity 0.6s ease";
        msg.style.opacity = "0";

        setTimeout(()=>msg.remove(),600);

    }

},2500);

</script>



@yield('scripts')
@stack('scripts')


{{-- =========================================================
   MODALES DE ASISTENCIA (PILAR 2)
   ========================================================= --}}

{{-- MODAL INICIAR TURNO --}}
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
                    <div class="mb-0">
                        <label class="form-label fw-bold small">Observaciones (Opcional)</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Ej: Recibido de la mañana..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold">CONFIRMAR Y EMPEZAR 🚀</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL FINALIZAR TURNO --}}
<div class="modal fade" id="modalCheckOut" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title fw-bold">🛑 CERRAR JORNADA Y CAJA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            @php
                // Cálculo detallado para el Arqueo (Modal)
                $ventasEfectivo = 0;
                $egresosTurno   = 0;
                $saldoInicial   = $asistenciaActiva->vuelto_inicial ?? 0;

                if($asistenciaActiva){
                    $ventasEfectivo = \App\Models\Venta::where('user_id', auth()->id())
                        ->where('created_at', '>=', $asistenciaActiva->entrada)
                        ->where('metodo_pago', 'efectivo')
                        ->sum('total_con_iva');

                    $egresosTurno = \App\Models\Expense::where('user_id', auth()->id())
                        ->where('created_at', '>=', $asistenciaActiva->entrada)
                        ->sum('amount');
                }
                $esperado = ($saldoInicial + $ventasEfectivo) - $egresosTurno;
            @endphp
            <form action="{{ route('empresa.personal.checkout') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    
                    <div class="bg-light rounded-3 p-3 mb-4 text-center border">
                        <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Cálculo de Efectivo Esperado</small>
                        <h2 class="fw-bold text-dark mb-0">$ {{ number_format($esperado, 2, ',', '.') }}</h2>
                        <hr class="my-2 border-secondary opacity-25">
                        <div class="row g-2 small text-muted">
                            <div class="col-4 border-end">
                                <span class="d-block">Inicial</span>
                                <span class="fw-bold text-dark">$ {{ number_format($saldoInicial, 0) }}</span>
                            </div>
                            <div class="col-4 border-end">
                                <span class="d-block">Ventas (Efect.)</span>
                                <span class="fw-bold text-success">+ $ {{ number_format($ventasEfectivo, 0) }}</span>
                            </div>
                            <div class="col-4">
                                <span class="d-block">Gastos</span>
                                <span class="fw-bold text-danger">- $ {{ number_format($egresosTurno, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Efectivo Físico en Caja 💰</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="vuelto_final" class="form-control form-control-lg fw-bold border-danger text-danger" step="0.01" placeholder="0.00" required>
                        </div>
                        <small class="text-muted mt-2 d-inline-block">
                            <i class="bi bi-info-circle me-1"></i> Cuente el dinero físico. La diferencia se guardará automáticamente como faltante o sobrante.
                        </small>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small">Observaciones del Cierre</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Ej: Se pagó al proveedor X con dinero de caja..."></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Seguir trabajando</button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm">FINALIZAR JORNADA 🛑</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

