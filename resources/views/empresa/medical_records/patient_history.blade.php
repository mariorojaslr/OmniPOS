@extends('layouts.empresa')

@section('content')
<!-- Cargar CDN de Chart.js y Bootstrap Icons -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .patient-header-card {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        border-radius: 24px;
        color: white;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }
    .quick-vitals-card {
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
    }
    .timeline-accordion .accordion-item {
        border: none;
        background: transparent;
        margin-bottom: 20px;
    }
    .timeline-accordion .accordion-button {
        border-radius: 16px !important;
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        padding: 20px;
        transition: all 0.3s;
    }
    .timeline-accordion .accordion-button:not(.collapsed) {
        color: var(--color-primario, #0d6efd);
        background: rgba(13, 110, 253, 0.03);
        border-color: rgba(13, 110, 253, 0.15);
        box-shadow: 0 8px 24px rgba(13, 110, 253, 0.06);
    }
    .timeline-accordion .accordion-collapse {
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-top: none;
        border-radius: 0 0 16px 16px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.02);
    }
    .timeline-accordion .accordion-button.collapsed {
        border-radius: 16px !important;
    }
    .badge-clinical {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
    }
    .vitals-indicator {
        font-size: 0.85rem;
        font-weight: 700;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
    }
    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }
</style>

<div class="container-fluid py-4">
    <!-- Botón Volver y Cabecera -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 mb-1">Dossier Clínico Histórico</h1>
            <p class="text-muted mb-0">Evolución, diagnóstico y constantes vitales del paciente.</p>
        </div>
        <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-light rounded-pill px-4 fw-bold border">
            <i class="bi bi-arrow-left me-2"></i> VOLVER
        </a>
    </div>

    <!-- 1. Cabecera del Paciente -->
    <div class="patient-header-card mb-4">
        <div class="row align-items-center g-4">
            <div class="col-md-auto text-center">
                @if($client->photo_path)
                    @if(str_starts_with($client->photo_path, 'local:'))
                        <img src="{{ asset('storage/' . str_replace('local:', '', $client->photo_path)) }}" class="rounded-circle border border-white border-4 shadow-lg" style="width: 120px; height: 120px; object-fit: cover;">
                    @elseif(str_starts_with($client->photo_path, 'bunny:'))
                        <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ str_replace('bunny:', '', $client->photo_path) }}" class="rounded-circle border border-white border-4 shadow-lg" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ $client->photo_path }}" class="rounded-circle border border-white border-4 shadow-lg" style="width: 120px; height: 120px; object-fit: cover;">
                    @endif
                @else
                    <div class="rounded-circle border border-white border-4 shadow-lg bg-light text-dark d-flex align-items-center justify-content-center fw-bold fs-1 mx-auto" style="width: 120px; height: 120px;">
                        {{ substr($client->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="col-md">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <h2 class="fw-bold mb-0 me-2">{{ $client->name }}</h2>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill px-3 py-1">Afiliado Activo</span>
                    @if($client->obraSocial)
                        <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 rounded-pill px-3 py-1">🏥 {{ $client->obraSocial->nombre }}</span>
                    @endif
                </div>
                <div class="row g-3 small text-white text-opacity-75">
                    <div class="col-sm-6 col-md-4">
                        <i class="bi bi-card-text me-2"></i><strong>DNI:</strong> {{ $client->document ?? 'N/A' }}
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <i class="bi bi-envelope me-2"></i><strong>Email:</strong> {{ $client->email ?? 'N/A' }}
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <i class="bi bi-telephone me-2"></i><strong>Teléfono:</strong> {{ $client->phone ?? 'N/A' }}
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <i class="bi bi-building me-2"></i><strong>Trabajo:</strong> {{ $client->workplace ?? 'N/A' }}
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <i class="bi bi-hash me-2"></i><strong>Nro Afiliado OS:</strong> {{ $client->affiliate_number ?? 'N/A' }}
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <i class="bi bi-wallet2 me-2"></i><strong>Cuota Mensual:</strong> ${{ number_format($client->monthly_fee ?? 0, 2) }}
                    </div>
                </div>
            </div>
            <div class="col-md-auto text-end">
                <a href="{{ route('empresa.medical_records.create', ['patient_id' => $client->id]) }}" class="btn btn-primary px-4 py-3 fw-bold rounded-pill shadow-lg border-0" style="background: linear-gradient(135deg, #3b82f6, #0d6efd);">
                    <i class="bi bi-plus-lg me-2"></i> NUEVA CONSULTA
                </a>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- 2. Gráfico e Historial Clínico (Columna Izquierda 8/12) -->
        <div class="col-lg-8">
            <!-- Bloque de Gráficos -->
            <div class="glass-card p-4 mb-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up text-primary me-2"></i>Curva de Evolución Clínica</h5>
                        <p class="x-small text-muted mb-0">Monitoreo histórico de constantes vitales en consulta.</p>
                    </div>
                    <!-- Selector de Variable a Mostrar -->
                    <select class="form-select form-select-sm rounded-pill border" style="width: auto;" id="chartSelector" onchange="updateChartType()">
                        <option value="all">Todas las Variables</option>
                        <option value="bp">Presión Arterial (Sys/Dia)</option>
                        <option value="glucose">Glucemia (mg/dL)</option>
                        <option value="weight">Peso Corporal (kg)</option>
                    </select>
                </div>
                
                <div class="chart-container">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>

            <!-- Bloque de Acordeón Cronológico -->
            <h4 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-secondary me-2"></i>Cronología de Evoluciones Clínicas</h4>

            <!-- Filtro de Especialidad Premium -->
            <div class="mb-4 p-3 rounded-4 bg-white border border-opacity-10 d-flex flex-wrap align-items-center gap-3">
                <span class="fw-bold text-muted small uppercase text-spacing"><i class="bi bi-funnel-fill me-1 text-primary"></i> Filtrar por Especialidad:</span>
                <div class="d-flex flex-wrap gap-2" id="specialtyFilterTags">
                    <button class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold specialty-filter-btn" data-filter="all">Todas</button>
                    @php
                        $uniqueSpecialties = $records->pluck('specialty')->unique()->filter();
                    @endphp
                    @foreach($uniqueSpecialties as $spec)
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-bold specialty-filter-btn" data-filter="{{ strtolower(trim($spec)) }}">{{ $spec }}</button>
                    @endforeach
                </div>
            </div>
            
            <div class="accordion timeline-accordion" id="accordionHistory">
                @forelse($records as $index => $rec)
                    <!-- Extraer variables estructuradas -->
                    @php
                        $specData = $rec->specialized_data ?? [];
                        $vitals = $specData['vitals'] ?? [];
                        $meds = $specData['medications'] ?? [];
                        $labs = $specData['labs'] ?? [];
                    @endphp
                    <div class="accordion-item medical-record-card" data-specialty="{{ strtolower(trim($rec->specialty ?? 'General')) }}">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="tab" data-bs-target="#collapse{{ $rec->id }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-100 pe-3 gap-2">
                                    <div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill me-2 px-3 py-1">
                                            {{ $rec->specialty ?? 'General' }}
                                        </span>
                                        <strong class="text-dark">{{ $rec->created_at->format('d/m/Y') }}</strong>
                                        <span class="text-muted small ms-2">({{ $rec->created_at->format('H:i') }} Hs)</span>
                                    </div>
                                    <div class="text-muted small fw-semibold">
                                        Atendido por: <span class="text-dark font-semibold">Dr. {{ $rec->doctor->name }}</span>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $rec->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionHistory">
                            <div class="accordion-body p-4 bg-white">
                                <!-- Diagnóstico Principal -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-text-fill text-muted me-2"></i>Diagnóstico y Evolución</h6>
                                    <p class="text-secondary mb-0" style="white-space: pre-wrap; font-size: 0.95rem;">{{ $rec->diagnosis ?? 'Sin diagnóstico registrado.' }}</p>
                                </div>

                                <!-- Tratamiento -->
                                @if($rec->treatment)
                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-clipboard2-check text-muted me-2"></i>Tratamiento e Indicaciones</h6>
                                    <p class="text-secondary mb-0" style="white-space: pre-wrap; font-size: 0.95rem;">{{ $rec->treatment }}</p>
                                </div>
                                @endif

                                <!-- Fila de Data Estructurada (Mediciones y Recetas) -->
                                <div class="row g-4 mb-4">
                                    <!-- Signos Vitales de esta evolución -->
                                    @if(!empty($vitals['sys']) || !empty($vitals['dia']) || !empty($vitals['glucose']) || !empty($vitals['heart']) || !empty($vitals['weight']))
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-4 border">
                                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-activity text-primary me-2"></i>Signos Vitales en Consulta</h6>
                                            <div class="row g-2">
                                                @if(!empty($vitals['sys']) && !empty($vitals['dia']))
                                                <div class="col-6">
                                                    <div class="bg-white p-2 rounded-3 border small">
                                                        <span class="text-muted d-block">P. Arterial</span>
                                                        <strong>{{ $vitals['sys'] }}/{{ $vitals['dia'] }} <span class="x-small text-muted font-normal">mmHg</span></strong>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(!empty($vitals['glucose']))
                                                <div class="col-6">
                                                    <div class="bg-white p-2 rounded-3 border small">
                                                        <span class="text-muted d-block">Glucemia</span>
                                                        <strong>{{ $vitals['glucose'] }} <span class="x-small text-muted font-normal">mg/dL</span></strong>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(!empty($vitals['heart']))
                                                <div class="col-6">
                                                    <div class="bg-white p-2 rounded-3 border small">
                                                        <span class="text-muted d-block">Frec. Cardíaca</span>
                                                        <strong>{{ $vitals['heart'] }} <span class="x-small text-muted font-normal">lpm</span></strong>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(!empty($vitals['weight']))
                                                <div class="col-6">
                                                    <div class="bg-white p-2 rounded-3 border small">
                                                        <span class="text-muted d-block">Peso Corporal</span>
                                                        <strong>{{ $vitals['weight'] }} <span class="x-small text-muted font-normal">kg</span></strong>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Receta Médica -->
                                    @if(count($meds) > 0)
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-4 border">
                                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-capsule-pill text-success me-2"></i>Medicamentos Recetados</h6>
                                            <div class="d-flex flex-column gap-2">
                                                @foreach($meds as $m)
                                                <div class="bg-white p-2 rounded-3 border small">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <strong class="text-dark">{{ $m['name'] }}</strong>
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary x-small py-1 rounded-pill">{{ $m['route'] ?? 'Oral' }}</span>
                                                    </div>
                                                    <div class="text-muted x-small">
                                                        Dosis: {{ $m['dose'] ?? '—' }} | Frecuencia: {{ $m['frequency'] ?? '—' }}
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Laboratorios e Informes Clínicos -->
                                @if(!empty($labs['hemo']) || !empty($labs['red']) || !empty($labs['white']) || !empty($labs['chol']))
                                <div class="p-3 bg-light rounded-4 border mb-4">
                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-clipboard2-pulse text-info me-2"></i>Resultados de Laboratorio</h6>
                                    <div class="row g-3">
                                        @if(!empty($labs['hemo']))
                                        <div class="col-sm-6 col-md-3">
                                            <div class="bg-white p-2 rounded-3 border small">
                                                <span class="text-muted d-block x-small">Hemoglobina</span>
                                                <strong>{{ $labs['hemo'] }} <span class="x-small text-muted">g/dL</span></strong>
                                                <span class="d-block x-small text-opacity-50 text-secondary" style="font-size: 0.65rem;">Ref: 12-16 g/dL</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if(!empty($labs['red']))
                                        <div class="col-sm-6 col-md-3">
                                            <div class="bg-white p-2 rounded-3 border small">
                                                <span class="text-muted d-block x-small">Glóbulos Rojos</span>
                                                <strong>{{ $labs['red'] }} <span class="x-small text-muted">M/uL</span></strong>
                                                <span class="d-block x-small text-opacity-50 text-secondary" style="font-size: 0.65rem;">Ref: 4.2-5.4 M/uL</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if(!empty($labs['white']))
                                        <div class="col-sm-6 col-md-3">
                                            <div class="bg-white p-2 rounded-3 border small">
                                                <span class="text-muted d-block x-small">Glóbulos Blancos</span>
                                                <strong>{{ $labs['white'] }} <span class="x-small text-muted">/uL</span></strong>
                                                <span class="d-block x-small text-opacity-50 text-secondary" style="font-size: 0.65rem;">Ref: 4k-10k /uL</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if(!empty($labs['chol']))
                                        <div class="col-sm-6 col-md-3">
                                            <div class="bg-white p-2 rounded-3 border small">
                                                <span class="text-muted d-block x-small">Colesterol Total</span>
                                                <strong class="{{ $labs['chol'] > 200 ? 'text-danger' : '' }}">{{ $labs['chol'] }} <span class="x-small text-muted">mg/dL</span></strong>
                                                <span class="d-block x-small text-opacity-50 text-secondary" style="font-size: 0.65rem;">Ref: < 200 mg/dL</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <!-- Adjuntos e Imágenes médicas -->
                                @if($rec->attachments->count() > 0)
                                <div class="mb-0">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-paperclip text-muted me-2"></i>Archivos y Estudios Adjuntos</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($rec->attachments as $att)
                                            @php
                                                $path = str_replace('bunny:', '', $att->file_path);
                                                $url = config('filesystems.disks.bunny_storage.url') . '/' . $path;
                                            @endphp
                                            <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-white border px-3 rounded-pill d-inline-flex align-items-center shadow-sm text-secondary small fw-bold">
                                                <i class="bi bi-file-earmark-medical me-2 text-primary"></i>Ver Estudio Clinico ({{ $att->file_type ?? 'PDF' }})
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($rec->internal_notes)
                                <div class="mt-4 p-3 rounded-4 border bg-light bg-opacity-50">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-lock-fill text-primary me-2"></i>Notas Médicas Internas (Confidencial)</h6>
                                    <p class="text-muted small mb-0">{{ $rec->internal_notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 glass-card bg-white">
                        <i class="bi bi-journal-medical fs-1 text-muted opacity-25 d-block mb-3"></i>
                        <h5 class="text-muted fw-bold">Sin evoluciones registradas</h5>
                        <p class="text-muted small mb-0">Aún no se han guardado visitas médicas para este paciente.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 3. Formulario de Carga Rápida (Columna Derecha 4/12) -->
        <div class="col-lg-4">
            <div class="glass-card p-4 quick-vitals-card bg-white position-sticky" style="top: 24px;">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Control de Constantes Rápido</h5>
                <p class="small text-muted mb-4">Ingresa signos vitales directos para sumarlos instantáneamente a la curva evolutiva del paciente.</p>

                <form action="{{ route('empresa.medical_records.store') }}" method="POST" id="formQuickVitals">
                    @csrf
                    <!-- Campos por defecto para que funcione nativamente en el store actual -->
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <input type="hidden" name="specialty" value="Signos Vitales">
                    <input type="hidden" name="reason_for_visit" value="Control Rápido">
                    <input type="hidden" name="diagnosis" value="Control rápido de constantes vitales en consultorio.">
                    <input type="hidden" name="specialized_data" id="quick_specialized_data">

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Presión Arterial Sistólica (mmHg)</label>
                        <input type="number" id="quick_sys" class="form-control form-control-premium" placeholder="Ej: 120" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Presión Arterial Diastólica (mmHg)</label>
                        <input type="number" id="quick_dia" class="form-control form-control-premium" placeholder="Ej: 80" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Glucemia (mg/dL)</label>
                        <input type="number" id="quick_glucose" class="form-control form-control-premium" placeholder="Ej: 95" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Frecuencia Cardíaca (lpm)</label>
                        <input type="number" id="quick_heart" class="form-control form-control-premium" placeholder="Ej: 72" min="0">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Peso Corporal (kg)</label>
                        <input type="number" step="0.1" id="quick_weight" class="form-control form-control-premium" placeholder="Ej: 75.5" min="0">
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-3 fw-bold rounded-pill shadow d-flex align-items-center justify-content-center text-dark">
                        <i class="bi bi-save me-2"></i> REGISTRAR CONSTANTES
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Generar JSON al enviar constantes rápidas
    document.getElementById('formQuickVitals').addEventListener('submit', function(e) {
        const vitals = {
            sys: document.getElementById('quick_sys').value,
            dia: document.getElementById('quick_dia').value,
            glucose: document.getElementById('quick_glucose').value,
            heart: document.getElementById('quick_heart').value,
            weight: document.getElementById('quick_weight').value
        };

        const specializedData = {
            vitals: vitals,
            medications: [],
            labs: {}
        };

        document.getElementById('quick_specialized_data').value = JSON.stringify(specializedData);
    });

    // -------------------------------------------------------------
    // GESTIÓN DEL GRÁFICO EVOLUTIVO DE CONSTANTES (CHART.JS)
    // -------------------------------------------------------------
    
    // Parsear datos de evoluciones históricas desde Laravel a Javascript
    const historicalData = [];
    
    @foreach($records->reverse() as $rec)
        @php
            $specData = $rec->specialized_data ?? [];
            $vitals = $specData['vitals'] ?? [];
        @endphp
        
        @if(!empty($vitals['sys']) || !empty($vitals['dia']) || !empty($vitals['glucose']) || !empty($vitals['weight']))
            historicalData.push({
                date: "{{ $rec->created_at->format('d/m/Y') }}",
                sys: parseFloat("{{ $vitals['sys'] ?? 0 }}") || null,
                dia: parseFloat("{{ $vitals['dia'] ?? 0 }}") || null,
                glucose: parseFloat("{{ $vitals['glucose'] ?? 0 }}") || null,
                weight: parseFloat("{{ $vitals['weight'] ?? 0 }}") || null
            });
        @endif
    @endforeach

    const labels = historicalData.map(d => d.date);
    let chartInstance = null;

    function renderChart(type = 'all') {
        const ctx = document.getElementById('evolutionChart').getContext('2d');
        
        if (chartInstance) {
            chartInstance.destroy();
        }

        const datasets = [];

        if (type === 'all' || type === 'bp') {
            datasets.push({
                label: 'Tensión Sistólica (mmHg)',
                data: historicalData.map(d => d.sys),
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5,
                fill: true
            }, {
                label: 'Tensión Diastólica (mmHg)',
                data: historicalData.map(d => d.dia),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5,
                fill: true
            });
        }

        if (type === 'all' || type === 'glucose') {
            datasets.push({
                label: 'Glucemia (mg/dL)',
                data: historicalData.map(d => d.glucose),
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5,
                fill: true
            });
        }

        if (type === 'all' || type === 'weight') {
            datasets.push({
                label: 'Peso (kg)',
                data: historicalData.map(d => d.weight),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5,
                fill: true
            });
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 11,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        padding: 12,
                        cornerRadius: 10,
                        backgroundColor: '#1e293b'
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.03)'
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.03)'
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    }

    function updateChartType() {
        const value = document.getElementById('chartSelector').value;
        renderChart(value);
    }

    // Inicializar gráfico al cargar la página e instalar el filtro de especialidades
    document.addEventListener('DOMContentLoaded', function() {
        if (historicalData.length > 0) {
            renderChart('all');
        } else {
            // Mostrar mensaje alternativo en el canvas si no hay datos
            const ctx = document.getElementById('evolutionChart').getContext('2d');
            ctx.font = '14px Outfit, Inter, sans-serif';
            ctx.fillStyle = '#64748b';
            ctx.textAlign = 'center';
            ctx.fillText('Aún no hay mediciones cargadas para trazar gráficos.', ctx.canvas.width / 2, ctx.canvas.height / 2);
        }

        // Filtrado Reactivo de Especialidades
        document.querySelectorAll('.specialty-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Actualizar clases activas de los botones de filtro
                document.querySelectorAll('.specialty-filter-btn').forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.add('btn-primary');
                this.classList.remove('btn-outline-secondary');

                const filterValue = this.getAttribute('data-filter');
                
                // Filtrar las tarjetas de evolución
                let countVisible = 0;
                document.querySelectorAll('.medical-record-card').forEach(card => {
                    const cardSpecialty = card.getAttribute('data-specialty');
                    if (filterValue === 'all' || cardSpecialty === filterValue) {
                        card.style.display = 'block';
                        countVisible++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Si no hay registros visibles, mostrar un mensaje de estado vacío
                const emptyState = document.getElementById('filterEmptyState');
                if (countVisible === 0) {
                    if (!emptyState) {
                        const container = document.getElementById('accordionHistory');
                        const div = document.createElement('div');
                        div.id = 'filterEmptyState';
                        div.className = 'text-center py-5 glass-card bg-white border rounded-4';
                        div.innerHTML = `
                            <i class="bi bi-folder-x fs-1 text-muted opacity-25 d-block mb-3"></i>
                            <h5 class="text-muted fw-bold">Sin evoluciones para esta especialidad</h5>
                            <p class="text-muted small mb-0">No se encontraron atenciones registradas bajo la especialidad elegida.</p>
                        `;
                        container.appendChild(div);
                    } else {
                        emptyState.style.display = 'block';
                    }
                } else {
                    if (emptyState) {
                        emptyState.style.display = 'none';
                    }
                }
            });
        });
    });
</script>
@endpush
