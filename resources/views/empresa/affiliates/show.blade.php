@extends('layouts.empresa')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-show { z-index: 1; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('empresa.affiliates.index') }}" class="text-muted text-decoration-none small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> VOLVER AL LISTADO
            </a>
            <h2 class="fw-bold mt-2 mb-0">Ficha del Paciente</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.medical_records.index', ['patient_id' => $affiliate->id]) }}" class="btn btn-outline-primary fw-bold rounded-pill">
                <i class="bi bi-file-medical me-2"></i> HISTORIA CLÍNICA
            </a>
            <a href="{{ route('empresa.affiliates.edit', $affiliate->id) }}" class="btn btn-light fw-bold rounded-pill border">
                <i class="bi bi-pencil me-2"></i> EDITAR
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Perfil Principal -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-4 text-center bg-light border-bottom">
                        <div class="mx-auto mb-3 rounded-circle border border-4 border-white shadow-sm overflow-hidden" style="width: 150px; height: 150px;">
                            @if($affiliate->photo_path)
                                @if(str_starts_with($affiliate->photo_path, 'local:'))
                                    <img src="{{ asset('storage/' . str_replace('local:', '', $affiliate->photo_path)) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @elseif(str_starts_with($affiliate->photo_path, 'bunny:'))
                                    <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ str_replace('bunny:', '', $affiliate->photo_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ $affiliate->photo_path }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            @else
                                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white fs-1 fw-bold">
                                    {{ substr($affiliate->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $affiliate->name }}</h4>
                        <span class="badge bg-{{ $affiliate->affiliate_status == 'active' ? 'success' : 'danger' }} rounded-pill px-3">
                            {{ strtoupper($affiliate->affiliate_status) }}
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold">DNI</span>
                            <span class="fw-bold">{{ $affiliate->document }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold">TELÉFONO</span>
                            <span class="fw-bold">{{ $affiliate->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold">EMAIL</span>
                            <span class="fw-bold small">{{ $affiliate->email ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-0">
                            <span class="text-muted small fw-bold d-block mb-1">DIRECCIÓN</span>
                            <span class="fw-bold small">{{ $affiliate->address ?? 'No registrada' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lugar de Trabajo -->
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 opacity-75 text-uppercase small" style="letter-spacing: 1px;">Información Laboral</h6>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-building fs-2 me-3"></i>
                        <div>
                            <div class="fw-bold fs-5">{{ $affiliate->workplace ?? 'No especificado' }}</div>
                            <div class="small opacity-75">Lugar de Trabajo / Entidad</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ubicación GPS (Mapa) -->
            @if($affiliate->lat && $affiliate->lng)
                <div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div id="map-show" style="height: 200px; width: 100%;"></div>
                        <div class="p-3">
                            <h6 class="fw-bold mb-1 text-dark">Ubicación GPS</h6>
                            <p class="text-muted extra-small mb-2">Coordenadas exactas de residencia.</p>
                            @if($affiliate->plus_code)
                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded-3 mb-2">
                                    <span class="small fw-bold text-muted">Plus Code:</span>
                                    <span class="badge bg-secondary font-monospace">{{ $affiliate->plus_code }}</span>
                                </div>
                            @endif
                            <div class="row g-2">
                                <div class="col-6 bg-light p-2 rounded-3 text-center">
                                    <small class="text-muted d-block extra-small">LATITUD</small>
                                    <strong class="small text-dark">{{ $affiliate->lat }}</strong>
                                </div>
                                <div class="col-6 bg-light p-2 rounded-3 text-center">
                                    <small class="text-muted d-block extra-small">LONGITUD</small>
                                    <strong class="small text-dark">{{ $affiliate->lng }}</strong>
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $affiliate->lat }},{{ $affiliate->lng }}" target="_blank" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-bold">
                                    <i class="bi bi-geo-alt me-1"></i> VER EN GOOGLE MAPS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Detalles del Plan y Cobertura -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Plan y Cobertura Médica</h5>
                    <div class="text-primary fw-800 fs-4">
                        ${{ number_format($affiliate->monthly_fee ?? ($affiliate->affiliatePlan->price ?? 0), 0, ',', '.') }}<small class="fs-6 text-muted">/mes</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Plan Contratado</label>
                            <div class="p-3 bg-light rounded-3">
                                <h5 class="fw-bold text-dark mb-1">{{ $affiliate->affiliatePlan->name ?? 'SIN PLAN' }}</h5>
                                <p class="text-muted small mb-0">{{ $affiliate->affiliatePlan->description ?? 'Sin descripción adicional.' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Obra Social / Mutual</label>
                            <div class="p-3 bg-light rounded-3">
                                <h5 class="fw-bold text-dark mb-1">{{ $affiliate->obraSocial->nombre ?? 'NINGUNA' }}</h5>
                                <p class="text-muted small mb-2">N° Afiliado: {{ $affiliate->affiliate_number ?? 'N/A' }}</p>
                                
                                @if($affiliate->additional_insurances && is_array($affiliate->additional_insurances) && count($affiliate->additional_insurances) > 0)
                                    <div class="mt-2 border-top pt-2">
                                        <small class="text-muted fw-bold d-block mb-2 text-uppercase">Coberturas Adicionales</small>
                                        @foreach($affiliate->additional_insurances as $osItem)
                                            @php
                                                $osObj = \App\Models\ObraSocial::find($osItem['obra_social_id'] ?? null);
                                            @endphp
                                            @if($osObj)
                                                <div class="d-flex justify-content-between align-items-center mb-1 border-bottom pb-1">
                                                    <span class="small fw-bold text-success" style="font-size: 0.85rem;"><i class="bi bi-shield-plus me-1"></i> {{ $osObj->nombre }}</span>
                                                    <span class="badge bg-dark rounded-pill">Cred: {{ $osItem['affiliate_number'] ?? 'N/A' }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small fw-bold text-uppercase mb-3 d-block">Prestaciones Incluidas en su Plan</label>
                            <div class="row g-3">
                                @forelse($affiliate->affiliatePlan->features ?? [] as $feature)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-2 border rounded-3">
                                            <i class="bi bi-check2-circle text-success me-2 fs-5"></i>
                                            <span class="small fw-bold text-dark">{{ $feature }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted small italic">Este plan no tiene prestaciones detalladas.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de Actividad Reciente -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0">Últimas Atenciones Médicas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 small fw-bold text-muted">FECHA</th>
                                    <th class="small fw-bold text-muted">ESPECIALIDAD</th>
                                    <th class="small fw-bold text-muted">MOTIVO</th>
                                    <th class="text-end pe-4 small fw-bold text-muted">ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($affiliate->medicalRecords->take(5) as $record)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $record->created_at->format('d/m/Y') }}</td>
                                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $record->specialty }}</span></td>
                                        <td class="small">{{ Str::limit($record->reason_for_visit, 50) }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('empresa.medical_records.show', $record->id) }}" class="btn btn-sm btn-light rounded-pill px-3">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">No registra atenciones previas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($affiliate->medicalRecords->count() > 5)
                    <div class="card-footer bg-white border-0 text-center py-3">
                        <a href="{{ route('empresa.medical_records.patient_history', $affiliate->id) }}" class="small fw-bold text-primary text-decoration-none">Ver historial completo</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($affiliate->lat && $affiliate->lng)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lat = parseFloat('{{ $affiliate->lat }}');
            const lng = parseFloat('{{ $affiliate->lng }}');
            
            const map = L.map('map-show', {
                dragging: false,
                touchZoom: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                zoomControl: false
            }).setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);
        });
    </script>
@endif
@endsection
