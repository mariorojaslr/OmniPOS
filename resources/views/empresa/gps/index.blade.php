@extends('layouts.empresa')

@section('page_title', 'Centro de Comando Logístico GPS')

@section('content')
<div class="container-fluid py-4">
    
    <div class="row mb-5 text-start">
        <div class="col-12">
            <h1 class="fw-bold text-dark mb-1">Centro de Comando Logístico GPS 🛰️</h1>
            <p class="text-secondary">Optimización de rutas y análisis geográfico en tiempo real.</p>
        </div>
    </div>

    <div class="row g-4 text-start">
        <!-- SMART ROUTE -->
        <div class="col-md-4">
            <a href="{{ route('empresa.gps.rutas') }}" class="text-decoration-none h-100">
                <div class="glass-panel">
                    <i class="bi bi-truck glass-icon text-primary"></i>
                    <h5 class="stat-label text-primary">Operativa de Campo</h5>
                    <h3 class="fw-bold mb-3 text-dark">Smart Delivery Route</h3>
                    <p class="text-secondary small mb-0">
                        Armado inteligente de hojas de ruta basado en pedidos empaquetados. Visualiza la mejor secuencia de entrega.
                    </p>
                    <div class="mt-4">
                        <span class="btn btn-sm btn-glass px-4">INGRESAR 🚀</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- ZONAS CALIENTES -->
        <div class="col-md-4">
            <a href="{{ route('empresa.gps.zonas_calientes') }}" class="text-decoration-none h-100">
                <div class="glass-panel">
                    <i class="bi bi-fire glass-icon text-danger"></i>
                    <h5 class="stat-label text-danger">Inteligencia Comercial</h5>
                    <h3 class="fw-bold mb-3 text-dark">Heatmap de Ventas</h3>
                    <p class="text-secondary small mb-0">
                        Analiza dónde vendes más. Mapa de calor basado en la facturación histórica por zona geográfica.
                    </p>
                    <div class="mt-4">
                        <span class="btn btn-sm btn-glass px-4">VER MAPA 🔥</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- RETIROS INTELIGENTES -->
        <div class="col-md-4">
            <a href="{{ route('empresa.gps.retiros_inteligentes') }}" class="text-decoration-none h-100">
                <div class="glass-panel">
                    <i class="bi bi-box-seam glass-icon text-info"></i>
                    <h5 class="stat-label text-info">Logística de Abasto</h5>
                    <h3 class="fw-bold mb-3 text-dark">Retiros CRM</h3>
                    <p class="text-secondary small mb-0">
                        Visualiza la ubicación de tus proveedores y planifica retiros de mercadería de forma eficiente.
                    </p>
                    <div class="mt-4">
                        <span class="btn btn-sm btn-glass px-4">GESTIONAR 📦</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
/* =========================================================
   GLASSMORPHISM PREMIUM - GPS DASHBOARD
========================================================= */

.glass-panel {
    background: rgba(var(--bs-body-bg-rgb), 0.65);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(128, 128, 128, 0.15);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.glass-panel:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 55px -10px rgba(0,0,0,0.15);
}

.glass-icon {
    position: absolute;
    top: -10px;
    right: -10px;
    font-size: 6rem;
    opacity: 0.2;
    transform: rotate(-10deg);
}

.stat-label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.btn-glass {
    background: rgba(255,255,255, 0.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(128, 128, 128, 0.2);
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
    color: inherit;
}
</style>
@endsection
