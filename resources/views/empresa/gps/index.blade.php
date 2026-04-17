@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA PREMIUM --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="display-5 fw-bold mb-1" style="color: #000; letter-spacing: -1.5px;">Centro de Inteligencia GPS 🗺️</h1>
            <p class="text-secondary fs-5 fw-500">Gestioná tus repartos, analizá tus ventas y optimizá tus recorridos.</p>
        </div>
        <div>
            <button onclick="openHelp('gps_hub')" class="btn btn-warning fw-bold rounded-pill px-5 py-2 shadow-sm border-0">
                <i class="bi bi-question-circle-fill me-2"></i> GUÍA DE USO
            </button>
        </div>
    </div>

    {{-- GRID DE APLICACIONES --}}
    <div class="row g-4">
        
        {{-- CARD: SMART ROUTE --}}
        <div class="col-md-4">
            <div class="card bg-dark border-0 shadow-lg h-100 overflow-hidden" style="border-radius: 24px; transition: transform 0.3s ease;">
                <div class="card-body p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-cart-check-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Repartos de Catálogo</h4>
                    <p class="text-muted small mb-4">La logística para tus clientes. Cargá tus pedidos pendientes, organizá el orden de entrega y generá la nómina para el chofer.</p>
                    
                    <a href="{{ route('empresa.gps.rutas') }}" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-sm">
                        GESTIONAR REPARTO 🚚
                    </a>
                </div>
            </div>
        </div>

        {{-- CARD: CLUSTER DE CLIENTES --}}
        <div class="col-md-4">
            <div class="card bg-dark border-0 shadow-lg h-100 overflow-hidden" style="border-radius: 24px; transition: transform 0.3s ease;">
                <div class="card-body p-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-geo-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Zonas Calientes</h4>
                    <p class="text-muted small mb-4">Visualizá tus ventas por zonas geográficas. Identificá dónde tenés más impacto y dónde faltan visitas.</p>
                    
                    <a href="{{ route('empresa.gps.zonas_calientes') }}" class="btn btn-success w-100 py-3 fw-bold rounded-4 shadow-sm">
                        VER HEATMAP 🔥
                    </a>
                </div>
            </div>
        </div>

        {{-- CARD: LOGÍSTICA DE CARGA --}}
        <div class="col-md-4">
            <div class="card bg-dark border-0 shadow-lg h-100 overflow-hidden" style="border-radius: 24px; transition: transform 0.3s ease;">
                <div class="card-body p-4">
                    <div class="bg-info bg-opacity-10 text-info rounded-4 d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-truck fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Retiros Inteligentes</h4>
                    <p class="text-muted small mb-4">Consolidá tus retiros de proveedores. El sistema te avisa qué proveedores están cerca de tu ruta actual.</p>
                    
                    <a href="{{ route('empresa.gps.retiros_inteligentes') }}" class="btn btn-info w-100 py-3 fw-bold rounded-4 shadow-sm">
                        GESTIONAR RETIROS 📦
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

<style>
    .card:hover {
        transform: translateY(-10px);
        background: #1a1a1a !important;
    }
</style>
@endsection
