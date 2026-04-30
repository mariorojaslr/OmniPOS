@extends('layouts.empresa')

@section('page_title', 'Logística de Retiros')

@section('content')
<div class="container-fluid py-4">
    
    <div class="row mb-4 text-start">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark mb-1">Retiros Proveedor (Logística de Abasto) 🚛</h1>
            <p class="text-secondary">Ubicación estratégica de proveedores para optimización de fletes.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('empresa.gps.index') }}" class="btn btn-outline-dark fw-bold rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> VOLVER AL MENÚ GPS
            </a>
        </div>
    </div>

    <div class="glass-panel d-flex flex-column" style="padding: 0 !important; min-height: 600px;">
        <div class="p-4 border-bottom d-flex align-items-center justify-content-between bg-white bg-opacity-10" style="border-radius: 16px 16px 0 0;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt-fill text-info fs-4"></i>
                <h5 class="mb-0 fw-bold">Mapa de Proveedores</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchSupplier" class="form-control border-start-0" placeholder="Buscar proveedor...">
                </div>
                <div class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill x-small fw-bold">LOGÍSTICA DE ABASTO</div>
            </div>
        </div>
        <div class="flex-grow-1" style="position: relative; min-height: 500px; border-radius: 0 0 16px 16px; overflow: hidden;">
            <div id="supplierMap" style="height: 100%; width: 100%; position: absolute; top: 0; left: 0;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, markers = [], allSuppliers = [];

    function initMap() {
        if (map) return;

        map = L.map('supplierMap').setView([-29.4124, -66.8566], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            attribution: '© OSM',
            maxZoom: 19
        }).addTo(map);
        
        // CASCADA DE REFRESCO NUCLEAR
        [100, 500, 1000, 1500, 3000].forEach(ms => {
            setTimeout(() => { if(map) map.invalidateSize(true); }, ms);
        });

        fetch('{{ route("empresa.gps.proveedores_data") }}')
            .then(res => res.json())
            .then(data => {
                allSuppliers = data;
                renderSuppliers(allSuppliers);
            });
    }

    function renderSuppliers(suppliers) {
        markers.forEach(m => map.removeLayer(m));
        markers = [];
        const bounds = [];

        suppliers.forEach(s => {
            if (s.lat && s.lng) {
                const marker = L.marker([parseFloat(s.lat), parseFloat(s.lng)])
                    .bindPopup(`<b>${s.name}</b><br>${s.direccion || 'Sin dirección'}`)
                    .addTo(map);
                markers.push(marker);
                bounds.push([parseFloat(s.lat), parseFloat(s.lng)]);
            }
        });

        if (suppliers.length > 0 && bounds.length > 0) map.fitBounds(bounds, { padding: [50, 50] });
    }

    document.getElementById('searchSupplier').oninput = e => { 
        renderSuppliers(allSuppliers.filter(s => s.name.toLowerCase().includes(e.target.value.toLowerCase()))); 
    };

    window.addEventListener('load', () => {
        setTimeout(initMap, 200);
        
        const mapContainer = document.getElementById('supplierMap');
        new ResizeObserver(() => {
            if (map) {
                map.invalidateSize();
                setTimeout(() => map.invalidateSize(), 300);
            }
        }).observe(mapContainer);
    });
</script>
@endpush
