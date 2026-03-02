<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ config('app.name', 'MultiPOS') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" href="{{ asset('images/favicon.png') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

@yield('styles')
@stack('styles')

@php
    $empresa = auth()->user()->empresa ?? null;
    $config  = $empresa?->configuracion ?? null;

    $colorPrimario   = $config?->color_primary   ?? '#0d6efd';
    $colorSecundario = $config?->color_secondary ?? '#6c757d';

    $role = auth()->user()->role ?? 'usuario';
    $roleName = match($role) {
        'owner' => 'Propietario',
        default => ucfirst($role),
    };

    $modoOscuro = ($config?->theme ?? 'light') === 'dark';

    $logo = (!empty($config?->logo))
        ? asset('storage/' . $config->logo)
        : asset('images/logo-multipos.png');
@endphp

<style>
:root{
    --color-primario: {{ $colorPrimario }};
    --color-secundario: {{ $colorSecundario }};
}

body{
    background:#f4f6f9;
}

/* ================== MODO OSCURO ================== */
@if($modoOscuro)
body{
    background:#0f1115 !important;
    color:#e6edf3 !important;
}
.navbar{
    background:#0b1a2b !important;
    border-bottom:1px solid #1f2d3d;
}
.navbar .nav-link,
.navbar .navbar-brand{
    color:#e6edf3 !important;
}
.card{
    background:#161b22 !important;
    border:1px solid #2c3642;
    color:#e6edf3 !important;
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

/* ================== BOTONES ================== */
.btn-primary{
    background:var(--color-primario)!important;
    border-color:var(--color-primario)!important;
}

/* ================== MENSAJES ================== */
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

/* ================== CONTENEDOR ================== */
.main-fluid{
    width:100%;
    padding:20px;
}
</style>
</head>

<body>

<!-- ================== NAVBAR ================== -->
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

<ul class="navbar-nav me-auto">

<li class="nav-item"><a class="nav-link" href="{{ route('empresa.dashboard') }}">Panel</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('empresa.products.index') }}">Productos</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('empresa.clientes.index') }}">Clientes</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('empresa.proveedores.index') }}">Proveedores</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('empresa.compras.index') }}">Compras</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('empresa.stock.index') }}">Inventario</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('catalog.index', auth()->user()->empresa_id) }}" target="_blank">Catálogo</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('empresa.pos.index') }}">POS / Ventas</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('empresa.reportes.panel') }}">Reportes</a></li>

</ul>

<ul class="navbar-nav">
<li class="nav-item dropdown">
<button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
{{ auth()->user()->name }} ({{ $roleName }})
</button>

<ul class="dropdown-menu dropdown-menu-end">

<li><a class="dropdown-item" href="{{ route('password.edit') }}">Cambiar contraseña</a></li>

@if(auth()->user()->isEmpresa())
<li><a class="dropdown-item" href="{{ route('empresa.configuracion.index') }}">Configuración empresa</a></li>
@endif

<li><hr></li>

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

<!-- ================== CONTENIDO ================== -->
<main class="main-fluid">

{{-- 🔴 ERROR (NO SE BORRA) --}}
@if(session('error'))
<div class="flash-message flash-error">
✖ {{ session('error') }}
</div>
@endif

{{-- 🟢 ÉXITO (SE BORRA SOLO) --}}
@if(session('success'))
<div id="flashSuccess" class="flash-message flash-success">
✔ {{ session('success') }}
</div>
@endif

@yield('content')

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- SOLO EL MENSAJE VERDE SE OCULTA --}}
<script>
setTimeout(function(){
    let msg = document.getElementById('flashSuccess');
    if(msg){
        msg.style.transition = "opacity 0.6s ease";
        msg.style.opacity = "0";
        setTimeout(()=>msg.remove(),600);
    }
}, 2500);
</script>

@yield('scripts')
@stack('scripts')

</body>
</html>
