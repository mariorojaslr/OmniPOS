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

<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.products.index') }}">Productos</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.clientes.index') }}">Clientes</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.proveedores.index') }}">Proveedores</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.compras.index') }}">Compras</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.stock.index') }}">Inventario</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('catalog.index', $empresa->id ?? 0) }}" target="_blank">
Catálogo
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.pos.index') }}">POS / Ventas</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{ route('empresa.reportes.panel') }}">Reportes</a>
</li>

</ul>



{{-- =========================================================
   USUARIO / PERFIL
   ========================================================= --}}

<ul class="navbar-nav">

<li class="nav-item dropdown">

<button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
{{ $user->name }} ({{ $roleName }})
</button>


<ul class="dropdown-menu dropdown-menu-end">


{{-- Cambiar contraseña --}}
<li>
<a class="dropdown-item" href="{{ url('/password') }}">
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
    <div class="alert alert-warning d-flex justify-content-between align-items-center mb-4 border border-warning shadow-sm">
        <div>
            <strong>Modo Mimetización:</strong> Estás navegando la plataforma como <b>{{ auth()->user()->name }}</b> de la empresa <b>{{ $empresa->nombre_comercial ?? '' }}</b>.
        </div>
        <a href="{{ route('impersonate.leave') }}" class="btn btn-sm btn-dark">
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


</body>
</html>
