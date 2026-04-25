@extends('layouts.empresa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .map-container { height: 600px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .supplier-item:hover { background: #f8fafc; cursor: pointer; }
    #main-content { padding-top: 80px !important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-2">

    <div class="row align-items-center mb-2">
        <div class="col-8">
            <h2 class="fw-bold text-dark mb-0 fs-4">Logística de Retiros 📦</h2>
            <p class="text-secondary small mb-0">Gestión de proveedores.</p>
        </div>
        <div class="col-4 d-flex flex-column align-items-end gap-1">
            <a href="{{ route('empresa.gps.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> VOLVER AL MENÚ GPS
            </a>
            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-2 py-1 rounded-pill" style="font-size: 0.65rem;">
                <i class="bi bi-geo-alt me-1"></i> LOGÍSTICA DE RETIROS
            </span>
        </div>
    </div>

    <div class="row g-4 text-start">
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-lg rounded-4 overflow-hidden h-100 border border-light">
                <div class="card-header bg-opacity-30 border-bottom p-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchSupplier" class="form-control bg-white border-0 text-dark fw-bold" placeholder="Buscar proveedor...">
                    </div>
                </div>
                <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                    <div id="suppliersList" class="list-group list-group-flush bg-transparent"></div>
                </div>
                <div class="card-footer bg-light border-0 p-3 text-center">
                    <span class="badge bg-white text-dark border fw-bold px-3 py-2 rounded-pill shadow-sm">
                        Puntos de Retiro: <span id="suppliersCount">0</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div id="supplierMap" class="map-container bg-white border position-relative">
                 <div id="loader" class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1000;">
                    <div class="spinner-border text-info" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, markers = [], allSuppliers = [];
    function initMap() {
        map = L.map('supplierMap').setView([-29.4124, -66.8566], 12); 
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' }).addTo(map);
        loadSuppliers();
    }
    function loadSuppliers() {
        fetch("{{ route('empresa.gps.proveedores_data') }}").then(r => r.json()).then(data => {
            allSuppliers = data;
            document.getElementById('suppliersCount').innerText = data.length;
            renderSuppliers(data);
        }).finally(() => document.getElementById('loader').style.display = 'none');
    }
    function renderSuppliers(suppliers) {
        const list = document.getElementById('suppliersList');
        list.innerHTML = '';
        markers.forEach(m => map.removeLayer(m));
        markers = [];
        const bounds = L.latLngBounds();
        if (suppliers.length === 0) { list.innerHTML = '<div class="p-4 text-center text-muted small">No hay proveedores con ubicación.</div>'; return; }
        suppliers.forEach(s => {
            const marker = L.marker([s.lat, s.lng]).addTo(map).bindPopup(`<b>${s.name}</b>`);
            markers.push(marker);
            bounds.extend([s.lat, s.lng]);
            const item = document.createElement('div');
            item.className = 'list-group-item bg-white text-dark border-light p-3 supplier-item shadow-sm mb-1 rounded-3 mx-2';
            item.innerHTML = `<div class="d-flex justify-content-between align-items-center"><div><div class="fw-bold text-dark small">${s.name}</div><div class="x-small text-muted fw-bold">📍 \${s.direccion || 'Ubicante'}</div></div><i class="bi bi-chevron-right text-primary"></i></div>`;
            item.onclick = () => { map.setView([s.lat, s.lng], 16); marker.openPopup(); };
            list.appendChild(item);
        });
        if (suppliers.length > 0) map.fitBounds(bounds, { padding: [50, 50] });
    }
    document.getElementById('searchSupplier').oninput = e => { renderSuppliers(allSuppliers.filter(s => s.name.toLowerCase().includes(e.target.value.toLowerCase()))); };
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
