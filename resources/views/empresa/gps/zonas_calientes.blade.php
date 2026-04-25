@extends('layouts.empresa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .heatmap-container { height: 600px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    #main-content { padding-top: 80px !important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-2">
    
    <div class="row align-items-center mb-2">
        <div class="col-md-8 text-start">
            <h2 class="fw-bold text-dark mb-0 fs-4">Zonas Calientes de Venta 🔥</h2>
            <div class="d-flex align-items-center gap-2 mt-1">
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 px-2 py-1 small fw-bold" style="font-size: 0.65rem;">
                    ANÁLISIS COMERCIAL
                </span>
                <p class="text-secondary small mb-0">Densidad de facturación en tiempo real.</p>
            </div>
        </div>
        <div class="col-md-4 d-flex justify-content-end align-items-center">
            <a href="{{ route('empresa.gps.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> VOLVER AL MENÚ GPS
            </a>
        </div>
    </div>

    <div class="row g-4 text-start">
        <div class="col-md-9">
            <div id="heatmap" class="heatmap-container bg-white border position-relative">
                <div id="loader" class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1000;">
                    <div class="spinner-border text-danger" role="status"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-white shadow-lg rounded-4 overflow-hidden border border-light">
                <div class="card-body p-4 text-dark text-start">
                    <h6 class="fw-bold mb-4 text-uppercase tracking-wider small opacity-75">Leyenda Térmica</h6>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div style="width: 14px; height: 14px; background: red; border-radius: 3px;"></div>
                        <div class="small fw-bold">Alta Densidad</div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div style="width: 14px; height: 14px; background: orange; border-radius: 3px;"></div>
                        <div class="small fw-bold">Media Densidad</div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width: 14px; height: 14px; background: blue; border-radius: 3px;"></div>
                        <div class="small fw-bold">Baja Densidad</div>
                    </div>
                    <hr class="opacity-10 my-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <label class="form-label text-muted fw-bold x-small text-uppercase">Radio</label>
                            <span id="radiusVal" class="badge bg-light text-dark border" style="font-size: 0.6rem;">25px</span>
                        </div>
                        <input type="range" class="form-range" id="heatRadius" min="5" max="50" value="25">
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <label class="form-label text-muted fw-bold x-small text-uppercase">Intensidad</label>
                            <span id="intensityVal" class="badge bg-light text-dark border" style="font-size: 0.6rem;">0.5</span>
                        </div>
                        <input type="range" class="form-range" id="heatIntensity" min="0.1" max="1" step="0.1" value="0.5">
                    </div>
                    <button onclick="loadHeatmapData()" class="btn btn-primary w-100 mt-2 py-3 fw-bold rounded-pill shadow-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i> REFRESCAR
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
<script>
    let map, heatLayer;
    function initMap() {
        map = L.map('heatmap').setView([-29.4124, -66.8566], 11); 
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' }).addTo(map);
        
        // BLINDAJE ANTI-BUCLE: Observación con freno de seguridad
        let resizeTimer;
        const resizeObserver = new ResizeObserver(() => {
            cancelAnimationFrame(resizeTimer);
            resizeTimer = requestAnimationFrame(() => {
                if (map) map.invalidateSize();
            });
        });
        resizeObserver.observe(document.getElementById('heatmap'));
        
        loadHeatmapData();
    }
    function loadHeatmapData() {
        document.getElementById('loader').style.display = 'block';
        fetch("{{ route('empresa.gps.heatmap_data') }}").then(r => r.json()).then(data => {
            const points = data.map(p => [parseFloat(p.lat), parseFloat(p.lng), parseFloat(p.total) / 1000]);
            if (heatLayer) map.removeLayer(heatLayer);
            heatLayer = L.heatLayer(points, {
                radius: parseInt(document.getElementById('heatRadius').value),
                blur: 15, maxZoom: 17, max: 1.0,
                gradient: {0.4: 'blue', 0.65: 'lime', 1: 'red'}
            }).addTo(map);
            if (points.length > 0) map.fitBounds(L.latLngBounds(points.map(p => [p[0], p[1]])));
        }).finally(() => document.getElementById('loader').style.display = 'none');
    }
    document.getElementById('heatRadius').oninput = function() { document.getElementById('radiusVal').innerText = this.value + 'px'; loadHeatmapData(); };
    document.getElementById('heatIntensity').oninput = function() {
        document.getElementById('intensityVal').innerText = this.value;
        if (heatLayer) heatLayer.setOptions({max: parseFloat(this.value)});
    };
    document.addEventListener('DOMContentLoaded', () => {
        initMap();
        setTimeout(() => {
            if(map) map.invalidateSize();
        }, 500);
    });

    window.addEventListener('resize', () => {
        if(map) map.invalidateSize();
    });
</script>
@endpush
