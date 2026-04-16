@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA PREMIUM --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold mb-1 text-white" style="letter-spacing: -1px;">Utilidades GPS <span class="badge bg-warning text-dark fs-6 ms-2" style="vertical-align: middle;">BETA PRO</span></h1>
            <p class="text-muted opacity-75">Optimización logística y geolocalización avanzada para tu flota.</p>
        </div>
        <div>
            <button onclick="openHelp('gps_hub')" class="btn btn-outline-warning border-2 fw-bold rounded-pill px-4">
                <i class="bi bi-magic me-2"></i> ¿CÓMO USAR ESTO?
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
                        <i class="bi bi-signpost-split-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Smart Route (IDEAL)</h4>
                    <p class="text-muted small mb-4">Calculá el recorrido perfecto para tus visitas del día. Ahorrá combustible y tiempo optimizando cada kilómetro.</p>
                    
                    <a href="{{ route('empresa.gps.rutas') }}" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-sm">
                        CONFIGURAR RUTA 🚀
                    </a>
                </div>
                <div class="bg-primary bg-opacity-5 py-2 px-4 border-top border-white border-opacity-10">
                    <small class="text-primary fw-bold">Optimización con Plus Code</small>
                </div>
            </div>
        </div>

        {{-- CARD: CLUSTER DE CLIENTES (PRÓXIMAMENTE) --}}
        <div class="col-md-4">
            <div class="card bg-dark border-0 shadow-lg h-100 opacity-50" style="border-radius: 24px;">
                <div class="card-body p-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-geo-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Zonas Calientes</h4>
                    <p class="text-muted small mb-4">Visualizá tus ventas por zonas geográficas. Identificá dónde tenés más impacto y dónde faltan visitas.</p>
                    
                    <button class="btn btn-secondary w-100 py-3 fw-bold rounded-4" disabled>
                        PRÓXIMAMENTE
                    </button>
                </div>
            </div>
        </div>

        {{-- CARD: LOGÍSTICA DE CARGA --}}
        <div class="col-md-4">
            <div class="card bg-dark border-0 shadow-lg h-100 opacity-50" style="border-radius: 24px;">
                <div class="card-body p-4">
                    <div class="bg-info bg-opacity-10 text-info rounded-4 d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-truck fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-3">Retiros Inteligentes</h4>
                    <p class="text-muted small mb-4">Consolidá tus retiros de proveedores. El sistema te avisa qué proveedores están cerca de tu ruta actual.</p>
                    
                    <button class="btn btn-secondary w-100 py-3 fw-bold rounded-4" disabled>
                        PRÓXIMAMENTE
                    </button>
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
    body {
        background-color: #0b0b0b !important;
    }
</style>
@endsection
