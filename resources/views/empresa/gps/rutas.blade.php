@extends('layouts.empresa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #routeMap { height: 500px; max-height: 500px; width: 100%; border-radius: 0 0 20px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; }
    #main-content { padding-top: 80px !important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-2">
    
    <div class="row align-items-center mb-2">
        <div class="col-8">
            <h2 class="fw-bold text-dark mb-0 fs-4">Smart Route: Recorrido de Reparto 🚚</h2>
            <p class="text-secondary small mb-0">Gestión logística integral.</p>
        </div>
        <div class="col-4 d-flex flex-column align-items-end gap-1">
            <a href="{{ route('empresa.gps.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> VOLVER AL MENÚ GPS
            </a>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2 py-1 rounded-pill" style="font-size: 0.65rem;">
                <i class="bi bi-cpu me-1"></i> LOGÍSTICA INTELIGENTE
            </span>
        </div>
    </div>

    <div class="row g-4 text-start">
        <div class="col-md-5">
            <div class="card bg-white border-0 shadow-lg rounded-4 border border-light overflow-hidden">
                <div class="card-header bg-light bg-opacity-50 border-bottom p-3">
                    <h6 class="mb-0 text-dark fw-bold small text-uppercase font-monospace tracking-wide">1. Selección de Paradas</h6>
                </div>
                <div class="card-body p-4 text-dark">
                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold x-small text-uppercase mb-1">Agregar Parada Manual (Cliente / Prov)</label>
                        <div class="input-group mb-2 shadow-sm">
                            <input type="text" id="searchEntity" class="form-control bg-light border-0 text-dark fw-bold" placeholder="Escribí para buscar...">
                            <button class="btn btn-primary" type="button"><i class="bi bi-plus-lg"></i></button>
                        </div>
                        <button onclick="loadPendingOrders()" id="btnLoadPending" class="btn btn-info w-100 rounded-pill fw-bold text-dark mt-1 shadow-sm">
                            <i class="bi bi-cart-check-fill me-1"></i> CARGAR PEDIDOS PENDIENTES
                        </button>
                    </div>

                    <div id="routeList" class="mb-4" style="max-height: 400px; overflow-y: auto;">
                        <div class="text-center py-5 text-muted opacity-50">
                            <i class="bi bi-geo-alt fs-1"></i>
                            <p class="small mt-2">No hay paradas cargadas</p>
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
        </div>

        <div class="col-md-7">
            <div class="row g-2 mb-3" id="routeStatsQuick"></div>
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden border">
                <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-map me-2 text-primary"></i> Visor Logístico</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 rounded-pill x-small fw-bold">PROVINCIA DETECTADA</span>
                </div>
                <div id="routeMap" style="height: 500px; max-height: 500px; overflow: hidden;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, stops = [], markers = [];

    function initMap() {
        // FOCO EN LA RIOJA POR DEFECTO (-29.4124, -66.8566)
        map = L.map('routeMap').setView([-29.4124, -66.8566], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' }).addTo(map);
    }

    function loadPendingOrders() {
        const btn = document.getElementById('btnLoadPending');
        const path = "{{ route('empresa.gps.pedidos_pendientes') }}";
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Buscando...';

        fetch(path).then(res => res.json()).then(data => {
            if (data.length > 0) {
                data.forEach(order => {
                    if (!stops.find(s => s.id === order.id && s.type === 'pedido')) {
                        stops.push({ ...order, name: order.client_name });
                    }
                });
                renderStops();
            } else { alert("No se encontraron pedidos de delivery listos."); }
        }).finally(() => { 
            btn.disabled = false; 
            btn.innerHTML = '<i class="bi bi-cart-check-fill me-1"></i> CARGAR PEDIDOS PENDIENTES';
        });
    }

    function renderStops() {
        const list = document.getElementById('routeList');
        const statsQuick = document.getElementById('routeStatsQuick');
        list.innerHTML = '';
        markers.forEach(m => map.removeLayer(m));
        markers = [];
        let ordersCount = 0, totalItems = 0, bounds = L.latLngBounds();

        stops.forEach((s, idx) => {
            const isOrder = s.type === 'pedido';
            if (isOrder) {
                ordersCount++;
                const matches = s.items_list?.match(/(\d+)x/g);
                if (matches) matches.forEach(m => totalItems += parseInt(m));
            }
            const marker = L.marker([parseFloat(s.lat), parseFloat(s.lng)], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style='background-color:${isOrder ? "#0dcaf0" : "#6c757d"}; width:28px; height:28px; border-radius:50%; border:2px solid white; display:flex; align-items:center; justify-content:center; color:white; font-size:11px; font-weight:bold;'>${idx+1}</div>`,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14]
                })
            }).addTo(map).bindPopup(`<b>${s.name}</b>`);
            markers.push(marker);
            bounds.extend([parseFloat(s.lat), parseFloat(s.lng)]);

            const item = document.createElement('div');
            item.className = `p-3 rounded-4 mb-2 border ${isOrder ? 'bg-info bg-opacity-5 border-info' : 'bg-light border-light'} d-flex justify-content-between align-items-center shadow-sm`;
            item.innerHTML = `
                <div class="d-flex align-items-center gap-2 text-dark text-start">
                    <span class="badge ${isOrder ? 'bg-info' : 'bg-secondary'} rounded-circle" style="width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; font-size:10px;">${idx+1}</span>
                    <div style="line-height:1.2;">
                        <div class="fw-bold small">${s.name}</div>
                        <div class="text-muted fw-bold" style="font-size:0.65rem;">📍 ${s.address || 'Localizado'}</div>
                        ${isOrder ? `<div class='text-info fw-bold mt-1 text-uppercase' style='font-size:0.6rem;'><i class="bi bi-box-seam me-1"></i> ${s.items_list}</div>` : ''}
                    </div>
                </div>
                <button class="btn btn-link text-danger p-0" onclick="removeStop(${idx})"><i class="bi bi-trash small"></i></button>
            `;
            list.appendChild(item);
        });

        statsQuick.innerHTML = `
            <div class="col-6 text-dark"><div class="card border-0 bg-white border border-light rounded-4 shadow-sm p-2 text-center"><div class="fw-bold text-info lh-1 fs-5">${ordersCount}</div><div class="text-muted x-small text-uppercase mt-1" style="font-size:0.55rem;">Pedidos</div></div></div>
            <div class="col-6 text-dark"><div class="card border-0 bg-white border border-light rounded-4 shadow-sm p-2 text-center"><div class="fw-bold text-warning lh-1 fs-5">${totalItems}</div><div class="text-muted x-small text-uppercase mt-1" style="font-size:0.55rem;">Bultos</div></div></div>
        `;
        if (stops.length > 0) map.fitBounds(bounds, { padding: [50, 50] });
    }

    function removeStop(idx) { stops.splice(idx, 1); renderStops(); }

    document.querySelector('.btn-calculate').onclick = function() {
        if (stops.length < 2) return alert('Se necesitan al menos 2 paradas.');
        L.polyline(stops.map(s => [parseFloat(s.lat), parseFloat(s.lng)]), {color: '#0dcaf0', weight: 4, dashArray: '5, 10'}).addTo(map);
    };

    const searchInput = document.getElementById('searchEntity');
    const resultsContainer = document.createElement('div');
    resultsContainer.className = 'list-group position-absolute w-100 shadow-lg';
    resultsContainer.style.zIndex = '1000';
    searchInput.parentNode.style.position = 'relative';
    searchInput.parentNode.appendChild(resultsContainer);

    searchInput.addEventListener('input', function() {
        if (this.value.length < 2) return resultsContainer.innerHTML = '';
        fetch("{{ route('empresa.gps.search') }}?q=" + this.value).then(r => r.json()).then(data => {
            resultsContainer.innerHTML = '';
            data.forEach(item => {
                const btn = document.createElement('button');
                btn.className = 'list-group-item list-group-item-action bg-white text-dark small border-light';
                btn.innerHTML = `<strong>${item.name}</strong> <span class="badge bg-secondary ms-1">${item.type}</span>`;
                btn.onclick = () => { stops.push(item); renderStops(); resultsContainer.innerHTML = ''; searchInput.value = ''; };
                resultsContainer.appendChild(btn);
            });
        });
    });

    // Inicializar DESPUÉS de que TODO esté cargado (CSS, fuentes, layout Bootstrap)
    window.addEventListener('load', () => {
        initMap();
        // Un recálculo de seguridad por si el sidebar animó su entrada
        setTimeout(() => { if(map) map.invalidateSize(); }, 300);
    });

    window.addEventListener('resize', () => {
        if(map) map.invalidateSize();
    });
</script>
@endpush
