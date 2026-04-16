@extends('layouts.empresa')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endsection

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA --}}
    <div class="mb-5">
        <a href="{{ route('empresa.gps.index') }}" class="text-decoration-none text-muted small mb-3 d-block">
            <i class="bi bi-arrow-left me-1"></i> Volver a Utilidades GPS
        </a>
        <h2 class="fw-bold mb-1" style="color: #1e293b;">Smart Route: Recorrido Ideal 🚚</h2>
        <p class="text-muted fw-500">Seleccioná los puntos de visita y optimizá tu jornada.</p>
    </div>

    <div class="row g-4">
        
        {{-- CONFIGURACIÓN --}}
        <div class="col-md-5">
            <div class="card bg-dark border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white bg-opacity-5 border-0 p-4">
                    <h5 class="mb-0 text-white fw-bold">1. Selección de Paradas</h5>
                </div>
                <div class="card-body p-4 text-white">
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase">Agregar parada (Cliente / Proveedor)</label>
                        <div class="input-group">
                            <input type="text" id="searchEntity" class="form-control bg-black border-white border-opacity-10 text-white" placeholder="Escribí para buscar...">
                            <button class="btn btn-primary" type="button"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>

                    <div id="routeList" class="mb-4">
                        {{-- Ejemplo de parada --}}
                        <div class="alert bg-white bg-opacity-5 border-0 d-flex justify-content-between align-items-center mb-2 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-primary rounded-circle p-2">1</span>
                                <div>
                                    <div class="fw-bold fs-6">Distribuidora Norte</div>
                                    <div class="x-small text-muted">📍 8GV2+M9 (Plus Code)</div>
                                </div>
                            </div>
                            <button class="btn btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>

                    <button class="btn btn-warning w-100 py-3 fw-bold rounded-4 shadow-lg mb-3">
                        <i class="bi bi-cpu me-2"></i> CALCULAR RUTA ÓPTIMA
                    </button>
                </div>
            </div>
        </div>

        {{-- RESULTADO Y MAPA --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100" style="background: #f8fafc;">
                <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-map me-2 text-primary"></i> Visor de Recorrido Logístico</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 rounded-pill">Visualización en tiempo real</span>
                </div>
                <div id="routeMap" style="height: 500px; width: 100%; background: #e2e8f0; position: relative;">
                    {{-- El mapa se inicializará aquí --}}
                    <div id="map-placeholder" class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1000; pointer-events: none;">
                        <div class="spinner-grow text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                        <h6 class="fw-bold text-dark opacity-75">Configurá las paradas para trazar el mapa</h6>
                        <p class="text-muted small">El recorrido optimizado aparecerá aquí</p>
                    </div>
                </div>
                
                {{-- INFO DE RUTA --}}
                <div id="routeInfo" class="p-4 bg-white border-top d-none">
                    <div class="row text-center mb-4">
                        <div class="col-4 border-end">
                            <div class="small text-muted text-uppercase">Distancia</div>
                            <div class="fw-bold fs-5 text-primary" id="totalDistance">-- km</div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="small text-muted text-uppercase">Tiempo Est.</div>
                            <div class="fw-bold fs-5 text-primary" id="totalTime">-- min</div>
                        </div>
                        <div class="col-4">
                            <div class="small text-muted text-uppercase">Paradas</div>
                            <div class="fw-bold fs-5 text-primary" id="totalStops">0</div>
                        </div>
                    </div>
                    <button class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-sm">
                        <i class="bi bi-whatsapp me-2"></i> ENVIAR RUTA OPTIMIZADA AL CHOFER
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>

<style>
    .form-control:focus {
        background-color: #000 !important;
        border-color: var(--color-primario) !important;
        box-shadow: none;
    }
</style>
@section('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map;
    function initRouteMap() {
        // Inicializar el mapa en una ubicación neutra o la de la empresa
        map = L.map('routeMap').setView([-34.6037, -58.3816], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Ocultar el placeholder cuando el mapa cargue (simulado)
        setTimeout(() => {
            document.getElementById('map-placeholder').style.display = 'none';
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', initRouteMap);
</script>
@endsection
@endsection
