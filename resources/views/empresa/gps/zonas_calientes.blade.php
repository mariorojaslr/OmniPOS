@extends('layouts.empresa')

@section('page_title', 'Heatmap de Ventas')

@section('content')
<div class="container-fluid py-4">
    
    <div class="row mb-4 text-start">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark mb-1">Mapa de Calor (Inteligencia Comercial) 🚀</h1>
            <p class="text-secondary">Visualiza la densidad de facturación por zona geográfica.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('empresa.gps.index') }}" class="btn btn-outline-dark fw-bold rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> VOLVER AL MENÚ GPS
            </a>
        </div>
    </div>

    <div class="row g-4 text-start">
        <div class="col-md-9">
            <div class="glass-panel d-flex flex-column" style="padding: 0 !important; min-height: 600px;">
                <div class="p-4 border-bottom d-flex align-items-center justify-content-between bg-white bg-opacity-10" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-map-fill text-danger fs-4"></i>
                        <h5 class="mb-0 fw-bold">Análisis Térmico de Ventas</h5>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2 bg-light px-3 py-1 rounded-pill border">
                            <span class="x-small fw-bold text-muted uppercase">Intensidad</span>
                            <input type="range" id="intensityRange" min="0.1" max="1" step="0.1" value="0.5" class="form-range" style="width: 100px;">
                            <span id="intensityVal" class="badge bg-danger rounded-pill">0.5</span>
                        </div>
                        <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill x-small fw-bold">DATOS EN TIEMPO REAL</div>
                    </div>
                </div>
                <div class="flex-grow-1" style="position: relative; min-height: 500px; border-radius: 0 0 16px 16px; overflow: hidden;">
                    <div id="heatmap" style="height: 100%; width: 100%; position: absolute; top: 0; left: 0;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel h-100">
                <h5 class="fw-bold mb-4">Métricas de Zona</h5>
                <div class="mb-4">
                    <p class="text-secondary small mb-1 uppercase tracking-wider">Punto de Mayor Carga</p>
                    <h3 class="fw-bold text-danger" id="topPoint">Escanando...</h3>
                </div>
                <div class="mb-4">
                    <p class="text-secondary small mb-1 uppercase tracking-wider">Tickets en Pantalla</p>
                    <h4 class="fw-bold" id="ticketsCount">0</h4>
                </div>
                <hr class="opacity-10 my-4">
                <div class="p-3 bg-light rounded-4 border">
                    <p class="small text-muted mb-0">💡 <b>Tip</b>: Las zonas rojas muestran saturación de tickets, ideales para campañas de fidelización.</p>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://leaflet.github.io/Leaflet.heat/dist/leaflet-heat.js"></script>

<script>
    let map, heatLayer;

    function initMap() {
        if (map) return;
        
        map = L.map('heatmap').setView([-29.4124, -66.8566], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            attribution: '© OSM',
            maxZoom: 19
        }).addTo(map);

        // CASCADA DE REFRESCO NUCLEAR
        [100, 500, 1000, 1500, 3000].forEach(ms => {
            setTimeout(() => { 
                if(map) {
                    map.invalidateSize(true); 
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.TileLayer) layer.redraw();
                    });
                }
            }, ms);
        });

        fetch('{{ route("empresa.gps.heatmap_data") }}')
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const formattedData = data.map(d => [parseFloat(d.lat), parseFloat(d.lng), parseFloat(d.total)]);
                    heatLayer = L.heatLayer(formattedData, {radius: 25, blur: 15, max: 0.5}).addTo(map);
                    document.getElementById('ticketsCount').innerText = data.length;
                    document.getElementById('topPoint').innerText = "Capital Rioja";
                }
            });
    }

    document.getElementById('intensityRange').oninput = function() {
        document.getElementById('intensityVal').innerText = this.value;
        if (heatLayer) heatLayer.setOptions({max: parseFloat(this.value)});
    };
    
    window.addEventListener('load', () => {
        setTimeout(initMap, 200);
        
        const mapContainer = document.getElementById('heatmap');
        if (mapContainer) {
            new ResizeObserver(() => {
                if (map) {
                    map.invalidateSize();
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.TileLayer) layer.redraw();
                    });
                }
            }).observe(mapContainer);
        }
    });
</script>
@endpush
