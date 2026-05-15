@extends('layouts.empresa')

@section('content')

<div class="container py-4">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h3 class="fw-bold mb-4">Editar cliente</h3>

            <form method="POST" action="{{ route('empresa.clientes.update', $cliente->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $cliente->name) }}"
                               required>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $cliente->email) }}">
                    </div>

                    {{-- Teléfono --}}
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $cliente->phone) }}">
                    </div>

                    {{-- Documento --}}
                    <div class="col-md-6">
                        <label class="form-label">Documento</label>
                        <input type="text"
                               name="document"
                               class="form-control"
                               value="{{ old('document', $cliente->document) }}">
                    </div>

                    {{-- Condición --}}
                    <div class="col-md-3">
                        <label class="form-label">Condición Fiscal</label>
                        <select name="tax_condition" class="form-select">
                            <option value="consumidor_final" {{ $cliente->tax_condition=='consumidor_final'?'selected':'' }}>Consumidor Final</option>
                            <option value="responsable_inscripto" {{ $cliente->tax_condition=='responsable_inscripto'?'selected':'' }}>Responsable Inscripto</option>
                            <option value="monotributo" {{ $cliente->tax_condition=='monotributo'?'selected':'' }}>Monotributo</option>
                            <option value="exento" {{ $cliente->tax_condition=='exento'?'selected':'' }}>Exento</option>
                        </select>
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Cliente</label>
                        <select name="type" class="form-select">
                            <option value="consumidor_final" {{ $cliente->type=='consumidor_final'?'selected':'' }}>Consumidor Final</option>
                            <option value="minorista" {{ $cliente->type=='minorista'?'selected':'' }}>Minorista</option>
                            <option value="mayorista" {{ $cliente->type=='mayorista'?'selected':'' }}>Mayorista</option>
                            <option value="revendedor" {{ $cliente->type=='revendedor'?'selected':'' }}>Revendedor</option>
                            <option value="amigo" {{ $cliente->type=='amigo'?'selected':'' }}>Amigo / VIP</option>
                        </select>
                    </div>

                    {{-- Dirección --}}
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $cliente->address) }}">
                    </div>

                    {{-- GPS --}}
                    <div class="col-md-2">
                        <label class="form-label">Latitud (GPS)</label>
                        <input type="text" name="lat" class="form-control" value="{{ old('lat', $cliente->lat) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Longitud (GPS)</label>
                        <input type="text" name="lng" class="form-control" value="{{ old('lng', $cliente->lng) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-primary fw-bold text-uppercase" style="font-size: 0.75rem;">Plus Code 🌐</label>
                        <input type="text" name="plus_code" id="plus_code" class="form-control" value="{{ old('plus_code', $cliente->plus_code) }}" placeholder="8GV2+M9">
                    </div>

                    {{-- MAPA INTERACTIVO --}}
                    <div class="col-12 mt-3">
                        <div class="card border shadow-sm rounded-3 overflow-hidden">
                            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                <span class="small fw-bold text-muted text-uppercase tracking-wider"><i class="fas fa-map-marked-alt me-1"></i> Verificación Geográfica</span>
                                <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 fw-bold" onclick="geocodeAddress()" style="font-size: 0.7rem;">
                                    <i class="fas fa-search-location"></i> UBICAR POR DIRECCIÓN
                                </button>
                            </div>
                            <div id="map" style="height: 300px; width: 100%;"></div>
                            <div class="card-footer py-2 bg-white">
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Puedes arrastrar el marcador para ajustar la ubicación exacta.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Límite crédito --}}
                    <div class="col-md-6">
                        <label class="form-label">Límite de crédito</label>
                        <input type="number"
                               step="0.01"
                               name="credit_limit"
                               class="form-control"
                               value="{{ old('credit_limit', $cliente->credit_limit) }}">
                    </div>

                    {{-- Activo --}}
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="active" class="form-select">
                            <option value="1" {{ $cliente->active?'selected':'' }}>Activo</option>
                            <option value="0" {{ !$cliente->active?'selected':'' }}>Inactivo</option>
                        </select>
                    </div>

                    {{-- SECCIÓN PLAN MED PLUS / AFILIADOS --}}
                    <div class="col-12 mt-4">
                        <div class="card border-primary border-opacity-25 shadow-sm rounded-3" style="background: rgba(13, 110, 253, 0.02);">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-heart-pulse-fill text-primary fs-4 me-2"></i>
                                    <h5 class="fw-bold mb-0 text-primary">Plan Med Plus / Afiliación</h5>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" name="is_affiliate" id="is_affiliate" {{ $cliente->is_affiliate ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_affiliate">¿Es Afiliado?</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Nro de Afiliado</label>
                                        <input type="text" name="affiliate_number" class="form-control" value="{{ old('affiliate_number', $cliente->affiliate_number) }}" placeholder="Ej: 102938475">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Afiliado desde</label>
                                        <input type="date" name="affiliate_since" class="form-control" value="{{ old('affiliate_since', $cliente->affiliate_since ? $cliente->affiliate_since->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Cuota Mensual ($)</label>
                                        <input type="number" step="0.01" name="monthly_fee" class="form-control" value="{{ old('monthly_fee', $cliente->monthly_fee) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estado de Afiliación</label>
                                        <select name="affiliate_status" class="form-select">
                                            <option value="active" {{ $cliente->affiliate_status == 'active' ? 'selected' : '' }}>Activo</option>
                                            <option value="inactive" {{ $cliente->affiliate_status == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                            <option value="overdue" {{ $cliente->affiliate_status == 'overdue' ? 'selected' : '' }}>Deuda Pendiente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('empresa.clientes.index') }}" class="btn btn-outline-secondary">
                        Volver
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .btn-xs { padding: 0.1rem 0.4rem; font-size: 0.75rem; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, marker;
    const latInput = document.querySelector('input[name="lat"]');
    const lngInput = document.querySelector('input[name="lng"]');
    const addressInput = document.querySelector('input[name="address"]');
    const plusCodeInput = document.getElementById('plus_code');

    function initMap() {
        let defaultLat = parseFloat(latInput.value) || -34.6037; // Buenos Aires por defecto
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

        // CASCADA DE REFRESCO NUCLEAR (Evita teselas movidas por animaciones CSS o colapsos)
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
    }

    function updateInputs(lat, lng) {
        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);
        // Aquí podríamos disparar una api para traer el plus-code real si tuviéramos un helper de php
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
                console.error('Error in geocoding:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endpush
@endsection
