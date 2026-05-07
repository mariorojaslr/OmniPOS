<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ config('app.name', 'MultiPOS') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@php
    $user = auth()->user();
    $empresa = $user?->empresa ?? null;
    $config  = $empresa?->configuracion ?? null;
    $colorPrimario = $config?->color_primary ?? '#0d6efd';
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
    $logo = ($config && $config->logo_url) ? $config->logo_url : asset('images/logo_premium.png');
@endphp

<style>
:root {
    --sidebar-width: 105px;
    --navbar-height: 70px;
    --color-primario: {{ $colorPrimario }};
    --sidebar-bg: #0b1120;
    --sidebar-border: rgba(255, 255, 255, 0.08);
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: {{ $modoOscuro ? '#020617' : '#f8fafc' }};
    color: {{ $modoOscuro ? '#f1f5f9' : '#334155' }};
    overflow-x: hidden;
}

#sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    border-right: 1px solid var(--sidebar-border);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    height: var(--navbar-height);
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid var(--sidebar-border);
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    padding: 15px 0;
    overflow-y: auto;
    overflow-x: hidden;
}

.nav-link-item {
    width: 100%;
    padding: 15px 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    position: relative;
    transition: all 0.2s ease;
    cursor: pointer;
    text-align: center;
}

.nav-link-item i { font-size: 1.6rem; margin-bottom: 6px; }
.nav-icon-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; line-height: 1.1; }

.nav-link-item:hover {
    color: #fff;
    background: rgba(255,255,255,0.05);
}

.floating-balloon {
    position: fixed;
    left: var(--sidebar-width);
    transform: translateY(-50%) translateX(10px);
    background: #0f172a;
    border: 1px solid rgba(255,255,255,0.15);
    box-shadow: 20px 0 50px rgba(0,0,0,0.6);
    border-radius: 15px;
    padding: 20px;
    width: 260px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000000;
    pointer-events: none;
}

.nav-link-item:hover .floating-balloon {
    opacity: 1;
    visibility: visible;
    transform: translateY(-50%) translateX(5px);
    pointer-events: auto;
}

.submenu-list { display: flex; flex-direction: column; gap: 6px; }
.submenu-link {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    font-size: 0.85rem;
    padding: 10px 14px;
    border-radius: 10px;
    transition: all 0.2s;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
}
.submenu-link:hover { background: rgba(255,255,255,0.1); color: #fff; }

#main-content {
    padding-left: var(--sidebar-width);
    padding-top: var(--navbar-height); /* Eliminamos el +20px aquí para controlar mejor los banners */
    min-height: 100vh;
    position: relative;
    z-index: 1;
}

.top-bar {
    position: fixed;
    top: 0;
    right: 0;
    left: var(--sidebar-width);
    height: var(--navbar-height);
    background: {{ $modoOscuro ? 'rgba(2, 6, 23, 0.95)' : 'rgba(255, 255, 255, 0.98)' }};
    backdrop-filter: blur(15px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 1040; /* Justo debajo del sidebar */
    border-bottom: 1px solid var(--sidebar-border);
}

/* Añadimos un espaciador para el contenido interno */
.content-wrapper {
    padding: 25px;
}
</style>
@yield('styles')
</head>

<body>

<div id="sidebar">
    <div class="sidebar-header">
        <img src="{{ $logo }}" style="height: 45px; border-radius: 8px;" alt="Logo">
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('empresa.dashboard') }}" class="nav-link-item">
            <i class="bi bi-speedometer2"></i>
            <span class="nav-icon-label">Inicio</span>
        </a>

        {{-- ARTICULOS --}}
        <div class="nav-link-item">
            <i class="bi bi-tags" style="color: #00d2ff;"></i>
            <span class="nav-icon-label">Artículos</span>
            <div class="floating-balloon">
                <h6>📦 GESTIÓN</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.products.index') }}" class="submenu-link">📄 Mis Artículos</a>
                    <a href="{{ route('empresa.rubros.index') }}" class="submenu-link">🏷️ Rubros</a>
                </div>
            </div>
        </div>

        {{-- VENTAS --}}
        <div class="nav-link-item">
            <i class="bi bi-shop" style="color: #ffc107;"></i>
            <span class="nav-icon-label">Ventas</span>
            <div class="floating-balloon">
                <h6>📑 VENTAS</h6>
                <div class="submenu-list">
                    <a href="{{ route('empresa.ventas.index') }}" class="submenu-link">📋 Historial</a>
                    <a href="{{ route('empresa.pos.index') }}" class="submenu-link">🛒 POS</a>
                </div>
            </div>
        </div>

        <div class="mt-auto px-2 pb-3 text-center">
             <a href="{{ route('logout.get') }}" class="btn btn-outline-danger btn-sm w-100">SALIR</a>
        </div>
    </div>
</div>

<div id="main-content">
    <div class="top-bar">
        <h4 class="mb-0 fw-bold">{{ $empresa?->nombre_comercial ?? 'MultiPOS' }}</h4>
        <div class="d-flex align-items-center gap-3">
            <span class="small fw-bold">{{ $user?->name }}</span>
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:35px; height:35px;">
                {{ substr($user?->name ?? 'U', 0, 1) }}
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
