@extends('layouts.empresa')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { z-index: 1; min-height: 250px; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('empresa.affiliates.index') }}" class="text-muted text-decoration-none small fw-bold">
            <i class="bi bi-arrow-left me-1"></i> VOLVER A LISTADO
        </a>
        <h2 class="fw-bold mt-2 text-dark">Editar Afiliado: {{ $affiliate->name }}</h2>
    <!-- Alertas premium de errores de validación (español) -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-2 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> ¡Atención! Verifica los siguientes campos:</h5>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li class="small fw-semibold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('empresa.affiliates.update', $affiliate->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Información Personal</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Nombre Completo</label>
                                <input type="text" name="name" class="form-control" value="{{ $affiliate->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">DNI / Documento</label>
                                <input type="text" name="document" class="form-control" value="{{ $affiliate->document }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $affiliate->email }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Teléfono</label>
                                <input type="text" name="phone" class="form-control" value="{{ $affiliate->phone }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Lugar de Trabajo</label>
                                @php
                                    $wpExists = in_array($affiliate->workplace, $workplaces->toArray());
                                    $hasWorkplace = !empty($affiliate->workplace);
                                @endphp
                                <div class="input-group {{ ($hasWorkplace && !$wpExists) ? 'd-none' : '' }}" id="workplace-select-container">
                                    <select id="workplace-select" class="form-select">
                                        <option value="">Selecciona un lugar de trabajo existente...</option>
                                        @foreach($workplaces as $wp)
                                            <option value="{{ $wp }}" {{ $affiliate->workplace === $wp ? 'selected' : '' }}>{{ $wp }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-success fw-bold" onclick="toggleNewWorkplace(true)" title="Agregar nuevo lugar de trabajo"><i class="bi bi-plus-lg"></i> Nuevo</button>
                                </div>
                                <div class="input-group {{ ($hasWorkplace && !$wpExists) ? '' : 'd-none' }}" id="workplace-input-container">
                                    <input type="text" id="workplace-text" class="form-control" placeholder="Escribe el nuevo lugar de trabajo (Ej: Fábrica Hilados)..." value="{{ !$wpExists ? $affiliate->workplace : '' }}">
                                    <button type="button" class="btn btn-outline-secondary fw-bold" onclick="toggleNewWorkplace(false)" title="Volver a la lista"><i class="bi bi-arrow-left-right"></i> Listado</button>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Dirección</label>
                                <div class="input-group">
                                    <input type="text" name="address" id="address_input" class="form-control" value="{{ $affiliate->address }}" placeholder="Calle, número, depto...">
                                    <button type="button" class="btn btn-outline-secondary" onclick="buscarDireccionEnMapa()">Buscar en Mapa</button>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Plus Code</label>
                                <input type="text" name="plus_code" id="plus_code" class="form-control" placeholder="Ej: 85GP+42" value="{{ $affiliate->plus_code }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Latitud (GPS)</label>
                                <input type="text" name="lat" id="lat" class="form-control" placeholder="Ej: -29.41" value="{{ $affiliate->lat }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Longitud (GPS)</label>
                                <input type="text" name="lng" id="lng" class="form-control" placeholder="Ej: -66.85" value="{{ $affiliate->lng }}">
                            </div>

                            <div class="col-12">
                                <div id="map" class="rounded-4 border shadow-sm" style="height: 250px; width: 100%;"></div>
                                <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i> Puedes hacer clic en el mapa para ajustar la chincheta GPS exacta.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-4 text-dark text-uppercase small tracking-wider">Foto de Perfil</h5>
                        
                        <!-- Contenedor interactivo: superpone el input nativo invisible para máxima compatibilidad -->
                        <div class="position-relative mx-auto mb-3 shadow-sm rounded-circle" style="width: 150px; height: 150px;">
                            <div id="photo-preview" class="w-100 h-100 bg-light rounded-circle d-flex align-items-center justify-content-center overflow-hidden border" style="border: 3px solid #16a34a !important;">
                                @if($affiliate->photo_path)
                                    @if(str_starts_with($affiliate->photo_path, 'local:'))
                                        <img src="{{ asset('storage/' . str_replace('local:', '', $affiliate->photo_path)) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @elseif(str_starts_with($affiliate->photo_path, 'bunny:'))
                                        <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ str_replace('bunny:', '', $affiliate->photo_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ $affiliate->photo_path }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                @else
                                    <i class="bi bi-camera fs-1 text-success opacity-75"></i>
                                @endif
                            </div>
                            <!-- Input de tipo file 100% nativo invisible que cubre todo el círculo de previsualización -->
                            <input type="file" name="photo" id="photo-input" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer; z-index: 10;" accept="image/*">
                        </div>
                        
                        <!-- Botón visual que también activa la selección nativa por respaldo de JavaScript -->
                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-4 fw-bold shadow-sm" onclick="document.getElementById('photo-input').click()">CAMBIAR FOTO</button>
                        <p class="text-muted extra-small mt-2 mb-0">Toca el círculo o el botón para cambiar la foto del paciente.</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Plan y Cobertura</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Plan Med Plus</label>
                            <select name="affiliate_plan_id" class="form-select">
                                <option value="">Sin Plan asignado</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ $affiliate->affiliate_plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Obra Social (Principal)</label>
                            <select name="obra_social_id" class="form-select">
                                <option value="">Ninguna</option>
                                @foreach($obrasSociales as $os)
                                    <option value="{{ $os->id }}" {{ $affiliate->obra_social_id == $os->id ? 'selected' : '' }}>{{ $os->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">N° Afiliado Obra Social Principal</label>
                            <input type="text" name="affiliate_number" class="form-control" value="{{ $affiliate->affiliate_number }}">
                        </div>

                        <!-- Obras Sociales Adicionales -->
                        <div class="mb-3 mt-4 border-top pt-3">
                            <label class="form-label text-muted small fw-bold d-flex justify-content-between align-items-center">
                                Prepaga / Obras Sociales Adicionales
                                <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 fw-bold" style="font-size: 0.75rem;" onclick="addAdditionalOS()"><i class="bi bi-plus-lg"></i> Agregar</button>
                            </label>
                            <div id="additional-os-container" class="mt-2">
                                <!-- Contenedor dinámico -->
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Monto Cuota Personalizado</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="monthly_fee" class="form-control" value="{{ $affiliate->monthly_fee }}">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted small fw-bold">Estado</label>
                            <select name="affiliate_status" class="form-select">
                                <option value="active" {{ $affiliate->affiliate_status == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ $affiliate->affiliate_status == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <option value="overdue" {{ $affiliate->affiliate_status == 'overdue' ? 'selected' : '' }}>Moroso</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-success px-5 py-3 fw-bold rounded-pill shadow">GUARDAR CAMBIOS</button>
            </div>
        </div>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // 1. Detección de Archivo de Foto
    document.getElementById('photo-input').onchange = evt => {
        const [file] = evt.target.files
        if (file) {
            document.getElementById('photo-preview').innerHTML = `<img src="${URL.createObjectURL(file)}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">`;
        }
    }

    // 2. Control de Obras Sociales Adicionales (Máximo 3)
    let osIndex = 0;
    function addAdditionalOS(selectedId = '', numberVal = '') {
        if (document.querySelectorAll('.additional-os-row').length >= 3) {
            alert('Puedes agregar hasta un máximo de 3 obras sociales/prepagas adicionales.');
            return;
        }
        const container = document.getElementById('additional-os-container');
        const row = document.createElement('div');
        row.className = 'additional-os-row d-flex gap-2 mb-2 align-items-center';
        row.id = `os-row-${osIndex}`;
        
        let options = '<option value="">Selecciona prepaga...</option>';
        @foreach($obrasSociales as $os)
            options += `<option value="{{ $os->id }}" ${selectedId == '{{ $os->id }}' ? 'selected' : ''}>{{ $os->nombre }}</option>`;
        @endforeach

        row.innerHTML = `
            <select name="additional_os[]" class="form-select form-select-sm" style="flex: 2;" required>
                ${options}
            </select>
            <input type="text" name="additional_number[]" class="form-control form-control-sm" style="flex: 2;" placeholder="N° Credencial" value="${numberVal}" required>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('os-row-${osIndex}').remove()"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(row);
        osIndex++;
    }

    // 3. Mapa Leaflet GPS & Geocodificación
    let map;
    let marker;

    document.addEventListener('DOMContentLoaded', () => {
        // Cargar Obras Sociales Adicionales preexistentes
        @if($affiliate->additional_insurances && is_array($affiliate->additional_insurances))
            @foreach($affiliate->additional_insurances as $item)
                addAdditionalOS('{{ $item['obra_social_id'] ?? '' }}', '{{ $item['affiliate_number'] ?? '' }}');
            @endforeach
        @endif

        // Configuración inicial del mapa
        const initLat = parseFloat(document.getElementById('lat').value) || -29.4124;
        const initLng = parseFloat(document.getElementById('lng').value) || -66.8569;
        
        map = L.map('map').setView([initLat, initLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            updateCoordsFields(position.lat, position.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoordsFields(e.latlng.lat, e.latlng.lng);
        });

        // Configuración inicial de nombres de inputs de Workplace
        const selectEl = document.getElementById('workplace-select');
        const textEl = document.getElementById('workplace-text');
        @if($hasWorkplace && !$wpExists)
            textEl.setAttribute('name', 'workplace');
            selectEl.removeAttribute('name');
        @else
            selectEl.setAttribute('name', 'workplace');
            textEl.removeAttribute('name');
        @endif
    });

    function toggleNewWorkplace(isNew) {
        const selectContainer = document.getElementById('workplace-select-container');
        const inputContainer = document.getElementById('workplace-input-container');
        const selectEl = document.getElementById('workplace-select');
        const textEl = document.getElementById('workplace-text');
        
        if (isNew) {
            selectContainer.classList.add('d-none');
            inputContainer.classList.remove('d-none');
            textEl.setAttribute('name', 'workplace');
            selectEl.removeAttribute('name');
            textEl.focus();
        } else {
            selectContainer.classList.remove('d-none');
            inputContainer.classList.add('d-none');
            selectEl.setAttribute('name', 'workplace');
            textEl.removeAttribute('name');
        }
    }

    function updateCoordsFields(lat, lng) {
        document.getElementById('lat').value = lat.toFixed(6);
        document.getElementById('lng').value = lng.toFixed(6);
    }

    async function buscarDireccionEnMapa() {
        const address = document.getElementById('address_input').value;
        if (!address) {
            alert('Por favor escribe una dirección para buscar.');
            return;
        }

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
            const data = await response.json();
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                map.setView([lat, lon], 16);
                marker.setLatLng([lat, lon]);
                updateCoordsFields(lat, lon);
            } else {
                alert('No se pudo encontrar la dirección en el mapa. Intenta buscarla moviendo la chincheta manualmente.');
            }
        } catch (error) {
            console.error('Error buscando dirección:', error);
        }
    }
</script>
@endsection
