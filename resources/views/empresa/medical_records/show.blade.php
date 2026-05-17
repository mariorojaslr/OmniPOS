@extends('layouts.empresa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0D6EFD;">Detalle de Atención</h2>
            <p class="text-muted">ID Registro: #{{ $medical_record->id }} | {{ $medical_record->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary px-3 rounded-pill" onclick="window.print()">
                <i class="bi bi-printer me-2"></i> IMPRIMIR
            </button>
            <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-outline-secondary px-3 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i> VOLVER
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-start mb-5 border-bottom pb-4">
                        <div>
                            <h4 class="fw-bold text-primary mb-1">{{ $medical_record->patient->name }}</h4>
                            <p class="text-muted mb-0">DNI: {{ $medical_record->patient->documento ?? 'N/A' }} | Edad: {{ $medical_record->patient->age ?? 'N/A' }} años</p>
                        </div>
                        <div class="text-end">
                            <h6 class="fw-bold mb-1">Especialidad</h6>
                            <span class="badge bg-primary px-3 py-2">{{ $medical_record->specialty ?? 'General' }}</span>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h6 class="text-muted fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;">Motivo de Consulta</h6>
                        <p class="fs-5">{{ $medical_record->reason_for_visit ?: 'No especificado' }}</p>
                    </div>

                    <div class="mb-5">
                        <h6 class="text-muted fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;">Diagnóstico / Evolución</h6>
                        <div class="bg-light p-4 rounded-4" style="white-space: pre-line;">
                            {{ $medical_record->diagnosis ?: 'Sin diagnóstico registrado.' }}
                        </div>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-muted fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;">Tratamiento / Indicaciones</h6>
                        <div class="bg-primary bg-opacity-5 p-4 rounded-4 border-start border-primary border-4" style="white-space: pre-line;">
                            {{ $medical_record->treatment ?: 'Sin indicaciones registradas.' }}
                        </div>
                    </div>

                    @php
                        $specData = $medical_record->specialized_data;
                        $config = auth()->user()->empresa->config;
                    @endphp

                    @if($specData)
                        <div class="row g-4 mt-5 pt-4 border-top">
                            {{-- SIGNOS VITALES --}}
                            @if(!empty($specData['vitals']) && array_filter($specData['vitals']))
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 rounded-4 p-4">
                                        <h6 class="text-danger fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;"><i class="bi bi-heart-pulse-fill me-2"></i>Signos Vitales</h6>
                                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                                            @if(!empty($specData['vitals']['sys']) || !empty($specData['vitals']['dia']))
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-muted">Presión Arterial:</span>
                                                    <strong class="text-dark">{{ $specData['vitals']['sys'] ?? 'N/A' }}/{{ $specData['vitals']['dia'] ?? 'N/A' }} mmHg</strong>
                                                </li>
                                            @endif
                                            @if(!empty($specData['vitals']['glucose']))
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-muted">Glucemia:</span>
                                                    <strong class="text-dark">{{ $specData['vitals']['glucose'] }} mg/dL</strong>
                                                </li>
                                            @endif
                                            @if(!empty($specData['vitals']['heart']))
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-muted">Frecuencia Cardíaca:</span>
                                                    <strong class="text-dark">{{ $specData['vitals']['heart'] }} lpm</strong>
                                                </li>
                                            @endif
                                            @if(!empty($specData['vitals']['weight']))
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-muted">Peso Corporal:</span>
                                                    <strong class="text-dark">{{ $specData['vitals']['weight'] }} kg</strong>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            {{-- LABORATORIO --}}
                            @if(!empty($specData['labs']) && array_filter($specData['labs']))
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 rounded-4 p-4">
                                        <h6 class="text-info fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;"><i class="bi bi-clipboard2-pulse-fill me-2"></i>Resultados Laboratorio</h6>
                                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                                            @if(!empty($specData['labs']['hemo']))
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-muted">Hemoglobina:</span>
                                                    <strong class="text-dark">{{ $specData['labs']['hemo'] }} g/dL</strong>
                                                </li>
                                            @endif
                                            @if(!empty($specData['labs']['red']))
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-muted">Glóbulos Rojos:</span>
                                                    <strong class="text-dark">{{ $specData['labs']['red'] }} M/uL</strong>
                                                </li>
                                            @endif
                                            @if(!empty($specData['labs']['white']))
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-muted">Glóbulos Blancos:</span>
                                                    <strong class="text-dark">{{ $specData['labs']['white'] }} /uL</strong>
                                                </li>
                                            @endif
                                            @if(!empty($specData['labs']['chol']))
                                                <li class="d-flex justify-content-between">
                                                    <span class="text-muted">Colesterol Total:</span>
                                                    <strong class="text-dark">{{ $specData['labs']['chol'] }} mg/dL</strong>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            {{-- MEDICAMENTOS --}}
                            @if(!empty($specData['medications']))
                                <div class="col-12 mt-4">
                                    <div class="card bg-light border-0 rounded-4 p-4">
                                        <h6 class="text-primary fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;"><i class="bi bi-capsule me-2"></i>Medicamentos Prescritos</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless align-middle mb-0 small">
                                                <thead>
                                                    <tr class="text-muted border-bottom">
                                                        <th class="py-2">Medicamento</th>
                                                        <th class="py-2">Dosis</th>
                                                        <th class="py-2">Frecuencia</th>
                                                        <th class="py-2">Vía</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($specData['medications'] as $med)
                                                        <tr>
                                                            <td class="fw-bold text-dark py-2">{{ $med['name'] }}</td>
                                                            <td class="text-secondary py-2">{{ $med['dose'] ?? 'N/A' }}</td>
                                                            <td class="text-secondary py-2">{{ $med['frequency'] ?? 'N/A' }}</td>
                                                            <td class="py-2"><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $med['route'] ?? 'Oral' }}</span></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- CAMPOS DINÁMICOS Y TAGS HCE --}}
                            @if(!empty($specData['dynamic_fields']))
                                <div class="col-12 mt-4">
                                    <div class="card border border-primary border-opacity-10 rounded-4 p-4" style="background: rgba(13, 110, 253, 0.01);">
                                        <h6 class="text-primary fw-bold text-uppercase small mb-3" style="letter-spacing: 1px;"><i class="bi bi-tags-fill me-2"></i>Atributos Especializados (Tags Dinámicos)</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($specData['dynamic_fields'] as $key => $val)
                                                <span class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.1);">
                                                    <strong>{{ $key }}:</strong> {{ $val }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Información del Médico</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px;">
                            {{ substr($medical_record->doctor->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $medical_record->doctor->name }}</div>
                            <div class="text-muted small">M.N. / M.P. Registrada</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Estudios y Adjuntos</h6>
                </div>
                <div class="card-body">
                    @forelse($medical_record->attachments as $attachment)
                        <div class="d-flex align-items-center mb-3 p-2 bg-light rounded-3">
                            <div class="me-3">
                                @if(in_array($attachment->file_type, ['jpg', 'png', 'jpeg']))
                                    <i class="bi bi-file-earmark-image text-primary fs-3"></i>
                                @else
                                    <i class="bi bi-file-earmark-pdf text-danger fs-3"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="text-dark fw-bold small text-truncate">{{ $attachment->file_name }}</div>
                                <div class="text-muted x-small">{{ number_format($attachment->file_size / 1024, 2) }} KB</div>
                            </div>
                            <a href="{{ config('filesystems.disks.bunny_storage.url') }}/{{ $attachment->file_path }}" target="_blank" class="btn btn-sm btn-link text-primary">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted small text-center mb-0">Sin archivos adjuntos.</p>
                    @endforelse
                </div>
            </div>

            @if($medical_record->internal_notes)
            <div class="card shadow-sm border-0 mb-4 bg-warning bg-opacity-10 border-warning" style="border-radius: 16px;">
                <div class="card-body">
                    <h6 class="fw-bold text-warning-emphasis mb-2"><i class="bi bi-shield-lock me-2"></i> Notas Internas</h6>
                    <p class="small mb-0 text-warning-emphasis opacity-75">{{ $medical_record->internal_notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, #sidebar, .top-bar, #help-trigger-fixed { display: none !important; }
    #main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
    .card { border: none !important; box-shadow: none !important; }
    .container-fluid { width: 100% !important; }
}
</style>
@endsection
