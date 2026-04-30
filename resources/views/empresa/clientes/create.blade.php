@extends('layouts.empresa')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 350px; border-radius: 15px; border: 1px solid rgba(0,0,0,0.1); }
</style>
@endsection


@section('content')

<div class="container-fluid py-3">

    {{-- CABECERA --}}
    <div class="mb-3">
        <h2 class="fw-bold mb-0">Nuevo Cliente</h2>
        <small class="text-muted">Alta de cliente en el sistema</small>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ route('empresa.clientes.store') }}">
                @csrf

                <div class="row g-3">

                    {{-- NOMBRE --}}
                    <div class="col-md-6">
                        <label class="form-label">Nombre / Razón social *</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               required
                               value="{{ old('name') }}">
                    </div>

                    {{-- DOCUMENTO / LUPA --}}
                    <div class="col-md-3">
                        <label class="form-label">Documento / CUIT</label>
                        <div class="input-group">
                            <input type="text"
                                   name="document"
                                   id="documentInput"
                                   class="form-control"
                                   value="{{ old('document') }}"
                                   placeholder="CUIT sin guiones">
                            <button class="btn btn-primary" type="button" id="btnSearchCuit" title="Buscar en AFIP">
                                <span class="spinner-border spinner-border-sm d-none" id="searchSpinner"></span>
                                🚀
                            </button>
                        </div>
                    </div>

                    {{-- TELEFONO --}}
                    <div class="col-md-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone') }}">
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}">
                    </div>

                    {{-- CONDICION IVA --}}
                    <div class="col-md-3">
                        <label class="form-label">Condición fiscal *</label>
                        <select name="tax_condition" id="taxConditionSelect" class="form-select" required>
                            <option value="consumidor_final">Consumidor Final</option>
                            <option value="responsable_inscripto">Responsable Inscripto</option>
                            <option value="monotributo">Monotributo</option>
                            <option value="exento">Exento</option>
                        </select>
                    </div>

                    {{-- DIRECCION --}}
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="address" id="addressInput" class="form-control" value="{{ old('address') }}">
                    </div>

                    {{-- GPS --}}
                    <div class="col-md-2">
                        <label class="form-label">Latitud (GPS)</label>
                        <input type="text" name="lat" class="form-control" placeholder="-34.123..." value="{{ old('lat') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Longitud (GPS)</label>
                        <input type="text" name="lng" class="form-control" placeholder="-58.456..." value="{{ old('lng') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-primary fw-bold text-uppercase" style="font-size: 0.75rem;">Plus Code 🌐</label>
                        <input type="text" name="plus_code" class="form-control" placeholder="8GV2+M9" value="{{ old('plus_code') }}">
                        <div class="x-small text-muted mt-1">Copiá de Google Maps</div>
                    </div>

                    {{-- TIPO DE CLIENTE --}}
                    <div class="col-md-3">
                        <label class="form-label">Tipo de cliente *</label>
                        <select name="type" class="form-select" required>
                            <option value="consumidor_final">Consumidor Final</option>
                            <option value="minorista" selected>Minorista (Normal)</option>
                            <option value="mayorista">Mayorista</option>
                            <option value="revendedor">Revendedor</option>
                            <option value="amigo">Amigo / VIP</option>
                        </select>
                    </div>

                    {{-- LIMITE CREDITO --}}
                    <div class="col-md-3">
                        <label class="form-label">Límite crédito</label>
                        <input type="number"
                               step="0.01"
                               name="credit_limit"
                               class="form-control"
                               value="{{ old('credit_limit',0) }}">
                    </div>

                    {{-- MAPA --}}
                    <div class="col-12 mt-4">
                        <label class="form-label fw-bold"><i class="bi bi-map me-2"></i>Ubicar en el Mapa</label>
                        <div id="map"></div>
                        <small class="text-muted">Haz clic en el mapa para ajustar la ubicación exacta del cliente.</small>
                    </div>

                </div>

                {{-- BOTONES --}}
                <div class="mt-4 d-flex gap-2">

                    <button type="submit" class="btn btn-success">
                        Guardar cliente
                    </button>

                    <a href="{{ route('empresa.clientes.index') }}"
                       class="btn btn-secondary">
                       Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
document.getElementById('btnSearchCuit').addEventListener('click', function() {
    let cuit = document.getElementById('documentInput').value.trim();
    if(!cuit) return alert("Ingresa un CUIT primero");

    const btn = this;
    const spinner = document.getElementById('searchSpinner');
    
    btn.disabled = true;
    spinner.classList.remove('d-none');

    // Usar la ruta de búsqueda de CUIT (Padrón A10 habilitado)
    fetch(`{{ route('empresa.tax.search_cuit') }}?cuit=${cuit}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                // 1. Nombre / Razón Social
                document.querySelector('input[name="name"]').value = res.data.nombre;
                
                // 2. Dirección Completa
                const loc = res.data.localidad ? (', ' + res.data.localidad) : '';
                document.getElementById('addressInput').value = res.data.direccion + loc;
                
                // 3. Mapear Condición Fiscal
                const cond = res.data.condicion_iva.toLowerCase();
                const taxSelect = document.getElementById('taxConditionSelect');
                const typeSelect = document.querySelector('select[name="type"]');
                
                if(cond.includes('inscripto')) {
                    taxSelect.value = 'responsable_inscripto';
                    typeSelect.value = 'mayorista'; // Sugerir mayorista para RI
                } else if(cond.includes('monotributo')) {
                    taxSelect.value = 'monotributo';
                    typeSelect.value = 'minorista';
                } else if(cond.includes('exento')) {
                    taxSelect.value = 'exento';
                } else {
                    taxSelect.value = 'consumidor_final';
                }

                alert("✅ Datos cargados correctamente desde ARCA.");

            } else {
                alert("❌ Error AFIP: " + res.error);
            }
        })
        .catch(err => alert("⚠️ Error técnico: " + err))
        .finally(() => {
            btn.disabled = false;
            spinner.classList.add('d-none');
        });
});
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inicializar Mapa
    var default_lat = -34.6037;
    var default_lng = -58.3816;
    
    var map = L.map('map').setView([default_lat, default_lng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([default_lat, default_lng], {draggable: true}).addTo(map);

    // Al mover el marcador manualmente
    marker.on('dragend', function(event) {
        var position = marker.getLatLng();
        updateCoords(position.lat, position.lng);
    });

    // Al hacer clic en el mapa
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
    });

    function updateCoords(lat, lng) {
        document.querySelector('input[name="lat"]').value = lat.toFixed(7);
        document.querySelector('input[name="lng"]').value = lng.toFixed(7);
    }

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
</script>
@endsection
