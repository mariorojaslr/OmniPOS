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
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 border border-light transition-hover">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 mb-4" style="width: fit-content;">
                        <i class="bi bi-truck fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Smart Delivery Route</h3>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        Armado inteligente de hojas de ruta basado en pedidos empaquetados. Visualiza en el mapa la mejor secuencia de entrega.
                    </p>
                    <a href="{{ route('empresa.gps.rutas') }}" class="btn btn-primary w-100 py-3 fw-bold rounded-pill">
                        GESTIONAR RUTAS 🚀
                    </a>
                </div>
            </div>
        </div>

        <!-- ZONAS CALIENTES -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 border border-light transition-hover">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-4 mb-4" style="width: fit-content;">
                        <i class="bi bi-fire fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Zonas Calientes (Heatmap)</h3>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        Analiza dónde vendes más. Mapa de calor basado en la facturación histórica por zona geográfica.
                    </p>
                    <a href="{{ route('empresa.gps.zonas_calientes') }}" class="btn btn-danger w-100 py-3 fw-bold rounded-pill">
                        VER HEATMAP 🔥
                    </a>
                </div>
            </div>
        </div>

        <!-- RETIROS INTELIGENTES -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 border border-light transition-hover">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-4 mb-4" style="width: fit-content;">
                        <i class="bi bi-box-seam fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Retiros Inteligentes CRM</h3>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        Visualiza la ubicación de tus proveedores y planifica retiros de mercadería de forma eficiente.
                    </p>
                    <a href="{{ route('empresa.gps.retiros_inteligentes') }}" class="btn btn-info w-100 py-3 fw-bold rounded-pill text-white">
                        GESTIONAR RETIROS 📦
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover { transition: all 0.3s ease; }
    .transition-hover:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important; }
</style>
@endsection
