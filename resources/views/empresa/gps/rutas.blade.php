@extends('layouts.empresa')

@section('page_title', 'Rutas Inteligentes GPS')

@section('content')
<div class="container-fluid py-4">
    
    <div class="row mb-4 text-start">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark mb-1">Smart Route: Recorrido de Reparto 🚚</h1>
            <p class="text-secondary">Gestión logística integral.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('empresa.gps.index') }}" class="btn btn-outline-dark fw-bold rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> VOLVER AL MENÚ GPS
            </a>
        </div>
    </div>

    <div class="row g-4 text-start">
        <div class="col-md-5">
            <div class="glass-panel">
                <h5 class="fw-bold mb-4">1. SELECCIÓN DE PARADAS</h5>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted uppercase">AGREGAR PARADA MANUAL (CLIENTE / PROV)</label>
                    <div class="input-group">
                        <input type="text" id="searchEntity" class="form-control border-end-0" placeholder="Escribí para buscar...">
                        <button class="btn btn-primary border-start-0" onclick="addManualStop()"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>

                <div class="mb-4">
                    <button onclick="loadPendingDeliveries()" class="btn btn-info w-100 fw-bold rounded-pill text-white py-2 shadow-sm">
                        <i class="bi bi-cart-plus me-2"></i> CARGAR PEDIDOS PENDIENTES
                    </button>
                </div>

                <div class="stop-list-container mb-4" style="min-height: 200px;">
                    <div id="stopList" class="d-flex flex-column gap-2">
                        <div class="text-center py-5 text-muted opacity-50">
                            <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                            <p class="small mt-2">No hay paradas cargadas</p>
                        </div>
                    </div>
                </div>

                <div id="manifestInfo" class="d-none mb-4 p-3 bg-light rounded-4 border border-info border-opacity-50">
                    <h6 class="text-info fw-bold small mb-2"><i class="bi bi-file-earmark-text me-2 text-info"></i> Hoja de Ruta</h6>
                    <div id="manifestSummary" class="small text-muted mb-3">
                        Incluye <span class="text-dark fw-bold" id="manifestOrdersCount">0</span> entregas.
                    </div>
                    <button onclick="printManifest()" class="btn btn-sm btn-info w-100 fw-bold rounded-pill text-dark">
                        <i class="bi bi-printer me-1"></i> IMPRIMIR NÓMINA CHOFER
                    </button>
                </div>

                <button class="btn btn-warning w-100 py-3 fw-bold rounded-pill shadow-lg mb-1 text-dark btn-calculate">
                    <i class="bi bi-cpu me-2"></i> CALCULAR RUTA ÓPTIMA
                </button>
            </div>
        </div>

        <div class="col-md-7">
            <div class="row g-2 mb-3" id="routeStatsQuick"></div>
            <div class="glass-panel h-100 d-flex flex-column" style="padding: 0 !important;">
                <div class="p-4 border-bottom d-flex align-items-center justify-content-between bg-white bg-opacity-10" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-map text-primary fs-4"></i>
                        <h5 class="mb-0 fw-bold">Visor Logístico</h5>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill x-small fw-bold">PROVINCIA DETECTADA</span>
                </div>
                <div class="flex-grow-1" style="position: relative; min-height: 500px; border-radius: 0 0 16px 16px; overflow: hidden;">
                    <div id="routeMap" style="height: 100%; width: 100%; position: absolute; top: 0; left: 0;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script>
    let map, stops = [], markers = [], routingControl = null;

    function initMap() {
        if (map) return; // Evitar doble inicialización
        
        map = L.map('routeMap').setView([-29.4124, -66.8566], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            attribution: '© OSM',
            maxZoom: 19
        }).addTo(map);

        // CASCADA DE REFRESCO NUCLEAR PARA EVITAR EL "EFECTO ROMPECABEZAS"
        [100, 500, 1000, 2000, 3000].forEach(ms => {
            setTimeout(() => { if(map) map.invalidateSize(true); }, ms);
        });
    }

    function loadPendingDeliveries() {
        fetch('{{ route("empresa.gps.pedidos_pendientes") }}')
            .then(res => res.json())
            .then(data => {
                stops = data.map(s => ({ ...s, active: true }));
                renderStops();
            });
    }

    function toggleStop(idx) {
        stops[idx].active = !stops[idx].active;
        renderStops();
    }

    function removeStop(idx) {
        if(confirm('¿Seguro que deseas eliminar esta parada?')) {
            stops.splice(idx, 1);
            renderStops();
        }
    }

    function renderStops() {
        const list = document.getElementById('stopList');
        list.innerHTML = '';
        markers.forEach(m => map.removeLayer(m));
        if(routingControl) map.removeControl(routingControl);
        markers = [];

        stops.forEach((s, idx) => {
            const isActive = s.active;
            const isStrange = s.address.toLowerCase().includes('santa fe') || 
                              s.address.toLowerCase().includes('cordoba') || 
                              s.address.toLowerCase().includes('buenos aires');

            const item = document.createElement('div');
            item.className = `p-3 mb-2 border rounded-4 d-flex align-items-center justify-content-between shadow-sm transition-all ${isActive ? 'bg-white' : 'bg-light opacity-50'}`;
            if(isStrange && isActive) item.style.borderLeft = '4px solid #ff4d4d';

            item.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <span class="badge ${isActive ? 'bg-dark' : 'bg-secondary'} rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                        ${idx+1}
                    </span>
                    <div>
                        <h6 class="mb-0 small fw-bold ${isActive ? 'text-dark' : 'text-muted'}">${s.client_name}</h6>
                        <p class="mb-0 x-small text-muted">${s.address}</p>
                        ${isStrange ? '<span class="badge bg-danger bg-opacity-10 text-danger x-small mt-1" style="font-size: 10px;">OTRA PROVINCIA</span>' : ''}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" ${isActive ? 'checked' : ''} onchange="toggleStop(${idx})">
                    </div>
                    <button class="btn btn-link text-danger p-0 ms-1" onclick="removeStop(${idx})">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            `;
            list.appendChild(item);

            if(isActive) {
                const marker = L.marker([s.lat, s.lng]).addTo(map).bindPopup(`<b>${s.client_name}</b>`);
                markers.push(marker);
            }
        });

        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.2));
        }
        
        setTimeout(() => map.invalidateSize(), 300);
    }

    function calculateOptimalRoute() {
        const activeStops = stops.filter(s => s.active);
        if (activeStops.length < 2) {
            alert("Necesitas al menos 2 paradas activas para calcular una ruta.");
            return;
        }

        if (routingControl) map.removeControl(routingControl);

        const waypoints = activeStops.map(s => L.latLng(s.lat, s.lng));

        routingControl = L.Routing.control({
            waypoints: waypoints,
            routeWhileDragging: false,
            addWaypoints: false,
            show: false, // Ocultamos el panel de texto para que no ensucie
            language: 'es',
            createMarker: function() { return null; }
        }).addTo(map);

        document.getElementById('manifestInfo').classList.remove('d-none');
        document.getElementById('manifestOrdersCount').innerText = activeStops.length;
        
        setTimeout(() => map.invalidateSize(), 500);
    }

    // Vinculamos el botón
    document.querySelector('.btn-calculate').addEventListener('click', calculateOptimalRoute);

    // Inicializar con Doble Seguro y Cascada de Redibujado Profundo
    window.addEventListener('load', () => {
        setTimeout(initMap, 200);

        [500, 1000, 2500].forEach(ms => {
            setTimeout(() => { 
                if(map) {
                    map.invalidateSize();
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.TileLayer) layer.redraw();
                    });
                }
            }, ms);
        });
    });

    // Observador de Redimensionamiento
    const mapContainer = document.getElementById('routeMap');
    if (mapContainer) {
        new ResizeObserver(() => {
            if(map) {
                map.invalidateSize();
                map.eachLayer(function(layer) {
                    if (layer instanceof L.TileLayer) layer.redraw();
                });
            }
        }).observe(mapContainer);
    }
</script>
@endpush
