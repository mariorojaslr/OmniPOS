@extends('layouts.empresa')

@section('content')

<div class="container py-3">

    <h3 class="mb-3">Editar Proveedor</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('empresa.proveedores.update',$supplier->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" name="document" class="form-control" value="{{ $supplier->document }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ $supplier->direccion }}">
                </div>

                <div class="row gx-3 text-start">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Latitud (GPS)</label>
                        <input type="text" name="lat" class="form-control" value="{{ $supplier->lat }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Longitud (GPS)</label>
                        <input type="text" name="lng" class="form-control" value="{{ $supplier->lng }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-primary">PLUS CODE 🌐</label>
                        <input type="text" name="plus_code" id="plus_code" class="form-control" value="{{ $supplier->plus_code }}" placeholder="8GV2+M9">
                    </div>

                    {{-- MAPA INTERACTIVO --}}
                    <div class="col-12 mt-2 mb-4">
                        <div class="card border shadow-sm rounded-3 overflow-hidden">
                            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                <span class="small fw-bold text-muted text-uppercase tracking-wider"><i class="fas fa-map-marked-alt me-1"></i> Verificación Geográfica</span>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 fw-bold" onclick="geocodeAddress()" style="font-size: 0.7rem;">
                                    <i class="fas fa-search-location"></i> UBICAR POR DIRECCIÓN
                                </button>
                            </div>
                            <div id="map" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="active" value="1" class="form-check-input"
                           {{ $supplier->active ? 'checked' : '' }}>
                    <label class="form-check-label">Activo</label>
                </div>

                <button class="btn btn-success">Actualizar</button>
                <a href="{{ route('empresa.proveedores.index') }}" class="btn btn-secondary">Volver</a>

            </form>

        </div>
    </div>

</div>

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endsection

@section('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, marker;
    const latInput = document.querySelector('input[name="lat"]');
    const lngInput = document.querySelector('input[name="lng"]');
    const addressInput = document.querySelector('input[name="direccion"]');
    const plusCodeInput = document.getElementById('plus_code');

    function initMap() {
        let defaultLat = parseFloat(latInput.value) || -34.6037; 
        let defaultLng = parseFloat(lngInput.value) || -58.3816;
        let zoom = (latInput.value && lngInput.value) ? 16 : 13;

        map = L.map('map').setView([defaultLat, defaultLng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function(event) {
            let position = marker.getLatLng();
            updateInputs(position.lat, position.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });
    }

    function updateInputs(lat, lng) {
        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);
    }

    function geocodeAddress() {
        const address = addressInput.value;
        if (!address) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    
                    map.setView([lat, lon], 17);
                    marker.setLatLng([lat, lon]);
                    updateInputs(lat, lon);
                } else {
                    alert("No se encontró la dirección. Intenta ser más específico.");
                }
            })
            .catch(error => {
                console.error('Error geocoding:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection
@endsection
