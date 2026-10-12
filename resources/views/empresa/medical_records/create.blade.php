@extends('layouts.empresa')

@php
    $config = auth()->user()->empresa?->config;
    $modoOscuro = ($config?->theme === 'dark');
    $colorPrimario = $config?->color_primario ?? '#0d6efd';
@endphp

@section('content')
<style>
    .medical-bg {
        position: relative;
        padding: 40px 0;
    }
    .glass-card {
        background: {{ $modoOscuro ? 'rgba(11, 17, 32, 0.7)' : 'rgba(255, 255, 255, 0.75)' }};
        backdrop-filter: blur(15px);
        border: 1px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.06)' : 'rgba(255, 255, 255, 0.4)' }} !important;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, {{ $modoOscuro ? '0.4' : '0.04' }});
    }
    .form-label-premium {
        font-size: 0.75rem;
        font-weight: 800;
        color: {{ $modoOscuro ? '#94a3b8' : '#64748b' }};
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .form-control-premium {
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#ffffff' }} !important;
        border-radius: 14px !important;
        border: 1px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.12)' }} !important;
        padding: 12px 16px !important;
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }} !important;
        transition: all 0.3s;
    }
    .form-control-premium:focus {
        border-color: var(--color-primario, #0d6efd) !important;
        box-shadow: 0 0 15px {{ $colorPrimario }}30 !important;
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.04)' : '#ffffff' }} !important;
    }
    .form-control-premium option {
        background: {{ $modoOscuro ? '#0b1120' : '#ffffff' }};
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }};
    }
    .btn-save-medical {
        background: linear-gradient(135deg, var(--color-primario, #0d6efd), #3b82f6);
        border: none;
        border-radius: 15px;
        padding: 16px;
        font-weight: 800;
        transition: transform 0.3s;
    }
    .btn-save-medical:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
    }

    /* Estilo premium de Pestañas Nav Pills */
    .nav-pills .nav-link {
        color: {{ $modoOscuro ? '#94a3b8' : '#64748b' }};
        background: transparent;
        border-radius: 12px;
        transition: all 0.3s;
        border: 1px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)' }} !important;
    }
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, var(--color-primario, #0d6efd), #3b82f6) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        border: none !important;
    }
    .tab-content {
        background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.4)' : '#ffffff' }} !important;
        border: 1px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.06)' : 'rgba(0, 0, 0, 0.08)' }} !important;
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }} !important;
    }
    .table th {
        color: {{ $modoOscuro ? '#94a3b8' : '#64748b' }} !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }
    .table td {
        color: {{ $modoOscuro ? '#cbd5e1' : '#334155' }} !important;
    }
    .table input, .table select {
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#ffffff' }} !important;
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }} !important;
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.12)' }} !important;
        border-radius: 8px !important;
    }
</style>

<div class="container medical-bg">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
                <div>
                    <h2 class="fw-extrabold mb-1" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};">🩺 Nueva Evolución Médica</h2>
                    <p class="small mb-0 {{ $modoOscuro ? 'text-muted' : 'text-secondary' }}">Registro oficial estructurado para la Historia Clínica del paciente.</p>
                </div>
                <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> VOLVER AL LISTADO
                </a>
            </div>

            <form action="{{ route('empresa.medical_records.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8 col-md-12">
                        <div class="glass-card p-4 p-md-5">
                            <div class="mb-4">
                                <label class="form-label-premium">Paciente / Afiliado *</label>
                                <select name="client_id" class="form-select form-control-premium @error('client_id') is-invalid @enderror" required>
                                    <option value="">Seleccione un paciente...</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}" {{ $selectedPatientId == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} (Doc: {{ $p->document ?? 'Sin DNI' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row mb-4 g-3">
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label-premium">Especialidad</label>
                                    <input type="text" name="specialty" class="form-control form-control-premium" placeholder="Ej: Clínica General" value="{{ old('specialty') }}">
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label-premium">Motivo de la Visita</label>
                                    <input type="text" name="reason_for_visit" class="form-control form-control-premium" placeholder="Ej: Control de rutina" value="{{ old('reason_for_visit') }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-premium">Diagnóstico y Evolución *</label>
                                <textarea name="diagnosis" class="form-control form-control-premium" rows="6" placeholder="Describa el estado del paciente y hallazgos..." required>{{ old('diagnosis') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-premium">Tratamiento / Plan de Acción</label>
                                <textarea name="treatment" class="form-control form-control-premium" rows="3" placeholder="Indicaciones médicas generales o próximos pasos...">{{ old('treatment') }}</textarea>
                            </div>

                            <div class="mb-4 p-4 rounded-4 border border-primary border-opacity-10 shadow-sm" style="background: {{ $modoOscuro ? 'rgba(13, 110, 253, 0.03)' : 'rgba(13, 110, 253, 0.01)' }};">
                                <label class="form-label-premium text-primary"><i class="bi bi-tags-fill me-1"></i> Campos Dinámicos & Tags HCE</label>
                                <p class="x-small text-muted mb-3">Agrega etiquetas personalizadas con valores clave para adaptar la consulta (ej. Odontología, Kinesiología, Oftalmología).</p>
                                
                                <div class="row g-2 mb-3">
                                    <div class="col-md-5 col-sm-12">
                                        <input type="text" id="dyn_key" class="form-control form-control-premium py-2" placeholder="Propiedad (Ej: Piezas Dentales, Dolor)">
                                    </div>
                                    <div class="col-md-5 col-sm-12">
                                        <input type="text" id="dyn_value" class="form-control form-control-premium py-2" placeholder="Valor (Ej: 14 y 15, Lumbar, 8/10)">
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <button type="button" class="btn btn-primary w-100 py-2 fw-bold" onclick="addDynamicField()" style="border-radius: 12px; height: 48px;">
                                            <i class="bi bi-plus-lg fs-5"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2" id="dynamicFieldsContainer">
                                    <!-- Aquí se inyectarán las cápsulas de tags -->
                                </div>
                            </div>

                            <hr class="my-4" style="opacity: 0.15;">

                            <!-- Módulo de Pestañas de Carga Especializada Estructurada -->
                            @php
                                $esOdonto = $config?->isOdontologica() || $config?->hasOdontograma();
                            @endphp
                            <div class="mb-0">
                                <label class="form-label-premium mb-3"><i class="bi bi-braces-asterisk me-1 text-primary"></i> Data Especializada Estructurada</label>
                                
                                <ul class="nav nav-pills nav-justified gap-2 mb-4" id="specializedTabs" role="tablist">
                                    @if($esOdonto)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active fw-bold border py-2" id="odonto-tab" data-bs-toggle="tab" data-bs-target="#odonto-pane" type="button" role="tab">
                                                <span class="fs-6 me-1">🦷</span> Odontograma Clínico
                                            </button>
                                        </li>
                                    @endif
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ !$esOdonto ? 'active' : '' }} fw-bold border py-2" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals-pane" type="button" role="tab"><i class="bi bi-heart-pulse-fill me-2"></i>Signos Vitales</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-bold border py-2" id="meds-tab" data-bs-toggle="tab" data-bs-target="#meds-pane" type="button" role="tab"><i class="bi bi-capsule me-2"></i>Receta / Meds</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-bold border py-2" id="labs-tab" data-bs-toggle="tab" data-bs-target="#labs-pane" type="button" role="tab"><i class="bi bi-clipboard2-pulse-fill me-2"></i>Laboratorio</button>
                                    </li>
                                </ul>

                                <div class="tab-content p-3 p-md-4 rounded-4 border" id="specializedTabsContent">
                                    @if($esOdonto)
                                        <!-- PESTAÑA: ODONTOGRAMA CLÍNICO -->
                                        <div class="tab-pane fade show active" id="odonto-pane" role="tabpanel">
                                            @include('empresa.medical_records.components.odontograma', ['readonly' => false, 'initialData' => []])
                                        </div>
                                    @endif

                                    <!-- PESTAÑA: SIGNOS VITALES -->
                                    <div class="tab-pane fade {{ !$esOdonto ? 'show active' : '' }}" id="vitals-pane" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Presión Arterial Sistólica (mmHg)</label>
                                                <input type="number" id="vital_sys" class="form-control form-control-premium" placeholder="Ej: 120" min="0">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Presión Arterial Diastólica (mmHg)</label>
                                                <input type="number" id="vital_dia" class="form-control form-control-premium" placeholder="Ej: 80" min="0">
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Glucemia (mg/dL)</label>
                                                <input type="number" id="vital_glucose" class="form-control form-control-premium" placeholder="Ej: 95" min="0">
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Frecuencia Cardíaca (lpm)</label>
                                                <input type="number" id="vital_heart" class="form-control form-control-premium" placeholder="Ej: 72" min="0">
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Peso Corporal (kg)</label>
                                                <input type="number" step="0.1" id="vital_weight" class="form-control form-control-premium" placeholder="Ej: 75.5" min="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PESTAÑA: RECETA / MEDICAMENTOS -->
                                    <div class="tab-pane fade" id="meds-pane" role="tabpanel">
                                        <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1 text-primary"></i>Agrega las prescripciones correspondientes para esta consulta.</p>
                                        <div class="table-responsive">
                                            <table class="table align-middle" id="tableMeds">
                                                <thead>
                                                    <tr class="small text-secondary fw-bold border-bottom">
                                                        <th>Medicamento</th>
                                                        <th>Dosis</th>
                                                        <th>Frecuencia</th>
                                                        <th>Vía de Administración</th>
                                                        <th style="width: 50px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="medsRows">
                                                    <tr class="med-row">
                                                        <td><input type="text" class="form-control form-control-sm med-name" placeholder="Ej: Paracetamol 500mg"></td>
                                                        <td><input type="text" class="form-control form-control-sm med-dose" placeholder="Ej: 1 comp"></td>
                                                        <td><input type="text" class="form-control form-control-sm med-freq" placeholder="Ej: cada 8 hs"></td>
                                                        <td>
                                                            <select class="form-select form-select-sm med-route">
                                                                <option value="Oral">Vía Oral</option>
                                                                <option value="Sublingual">Vía Sublingual</option>
                                                                <option value="Intravenosa">Vía Intravenosa</option>
                                                                <option value="Intramuscular">Vía Intramuscular</option>
                                                                <option value="Cutánea">Vía Tópica/Cutánea</option>
                                                                <option value="Inhalatoria">Vía Inhalatoria</option>
                                                            </select>
                                                        </td>
                                                        <td><button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeMedRow(this)"><i class="bi bi-trash"></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill mt-2" onclick="addMedRow()">
                                            <i class="bi bi-plus-lg me-1"></i> Agregar Fila
                                        </button>
                                    </div>

                                    <!-- PESTAÑA: LABORATORIO -->
                                    <div class="tab-pane fade" id="labs-pane" role="tabpanel">
                                        <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1 text-primary"></i>Ingresa valores críticos de análisis sanguíneo si aplica.</p>
                                        <div class="row g-3">
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Hemoglobina (g/dL)</label>
                                                <input type="number" step="0.1" id="lab_hemo" class="form-control form-control-premium" placeholder="Ej: 14.2">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Glóbulos Rojos (M/uL)</label>
                                                <input type="number" step="0.01" id="lab_red" class="form-control form-control-premium" placeholder="Ej: 4.8">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Glóbulos Blancos (/uL)</label>
                                                <input type="number" id="lab_white" class="form-control form-control-premium" placeholder="Ej: 6500">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label text-muted small fw-bold">Colesterol Total (mg/dL)</label>
                                                <input type="number" id="lab_chol" class="form-control form-control-premium" placeholder="Ej: 185">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Entrada oculta que recibirá la serialización JSON del formulario antes de submit -->
                                <input type="hidden" name="specialized_data" id="specialized_data">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="glass-card p-4 mb-4" style="background: {{ $modoOscuro ? 'rgba(30, 41, 59, 0.3)' : 'rgba(248, 250, 252, 0.8)' }};">
                            <h6 class="fw-bold mb-3" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};"><i class="bi bi-paperclip me-2 text-primary"></i>Archivos y Estudios</h6>
                            <p class="x-small text-muted mb-3">Adjunte radiografías, informes externos o PDFs. Se guardarán de forma segura.</p>
                            <input type="file" name="attachments[]" class="form-control form-control-premium bg-white mb-2" multiple>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-white text-muted border x-small">PDF</span>
                                <span class="badge bg-white text-muted border x-small">JPG</span>
                                <span class="badge bg-white text-muted border x-small">PNG</span>
                            </div>
                        </div>

                        <div class="glass-card p-4 mb-4" style="background: {{ $modoOscuro ? 'rgba(30, 41, 59, 0.3)' : 'rgba(248, 250, 252, 0.8)' }};">
                            <h6 class="fw-bold mb-3" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};"><i class="bi bi-shield-lock me-2 text-primary"></i>Notas Internas Privadas</h6>
                            <p class="x-small text-muted mb-3">Información confidencial para el equipo médico. No se imprime en informes para el paciente.</p>
                            <textarea name="internal_notes" class="form-control form-control-premium" rows="6" placeholder="Escriba notas privadas aquí...">{{ old('internal_notes') }}</textarea>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-save-medical shadow-lg text-white">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> GUARDAR REGISTRO DE HCE
                            </button>
                            <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-link text-muted text-decoration-none text-center small">Descartar cambios</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addMedRow() {
        const tbody = document.getElementById('medsRows');
        const tr = document.createElement('tr');
        tr.className = 'med-row';
        tr.innerHTML = `
            <td><input type="text" class="form-control form-control-sm med-name" placeholder="Ej: Paracetamol 500mg"></td>
            <td><input type="text" class="form-control form-control-sm med-dose" placeholder="Ej: 1 comp"></td>
            <td><input type="text" class="form-control form-control-sm med-freq" placeholder="Ej: cada 8 hs"></td>
            <td>
                <select class="form-select form-select-sm med-route">
                    <option value="Oral">Vía Oral</option>
                    <option value="Sublingual">Vía Sublingual</option>
                    <option value="Intravenosa">Vía Intravenosa</option>
                    <option value="Intramuscular">Vía Intramuscular</option>
                    <option value="Cutánea">Vía Tópica/Cutánea</option>
                    <option value="Inhalatoria">Vía Inhalatoria</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeMedRow(this)"><i class="bi bi-trash"></i></button></td>
        `;
        tbody.appendChild(tr);
    }

    function removeMedRow(button) {
        const rows = document.querySelectorAll('.med-row');
        if (rows.length > 1) {
            button.closest('tr').remove();
        } else {
            // Limpiar valores de la única fila restante si es la última
            const row = button.closest('tr');
            row.querySelector('.med-name').value = '';
            row.querySelector('.med-dose').value = '';
            row.querySelector('.med-freq').value = '';
        }
    }

    // Función para agregar Campos Dinámicos / Tags
    function addDynamicField() {
        const keyInput = document.getElementById('dyn_key');
        const valInput = document.getElementById('dyn_value');
        if (!keyInput || !valInput) return;

        const key = keyInput.value.trim();
        const val = valInput.value.trim();

        if (!key || !val) {
            alert('Por favor, ingresa tanto la propiedad como el detalle del campo.');
            return;
        }

        const container = document.getElementById('dynamicFieldsContainer');
        
        // Crear elemento badge
        const badge = document.createElement('span');
        badge.className = 'badge bg-primary text-white d-flex align-items-center gap-2 px-3 py-2 dynamic-field-badge rounded-pill shadow-sm';
        badge.setAttribute('data-key', key);
        badge.setAttribute('data-value', val);
        badge.style.fontSize = '0.85rem';
        badge.style.border = '1px solid rgba(255,255,255,0.1)';
        badge.innerHTML = `
            <strong>${key}:</strong> ${val}
            <i class="bi bi-x-circle-fill text-white-50 ms-1 fs-6" style="cursor:pointer;" onclick="this.parentElement.remove()" title="Eliminar"></i>
        `;

        container.appendChild(badge);

        // Limpiar inputs
        keyInput.value = '';
        valInput.value = '';
        keyInput.focus();
    }

    // Interceptar el envío del formulario para serializar la data estructurada
    document.querySelector('form').addEventListener('submit', function(e) {
        // Signos Vitales
        const vitals = {
            sys: document.getElementById('vital_sys').value,
            dia: document.getElementById('vital_dia').value,
            glucose: document.getElementById('vital_glucose').value,
            heart: document.getElementById('vital_heart').value,
            weight: document.getElementById('vital_weight').value
        };

        // Recetas / Medicamentos
        const medications = [];
        document.querySelectorAll('.med-row').forEach(row => {
            const name = row.querySelector('.med-name').value.trim();
            if (name) {
                medications.push({
                    name: name,
                    dose: row.querySelector('.med-dose').value.trim(),
                    frequency: row.querySelector('.med-freq').value.trim(),
                    route: row.querySelector('.med-route').value
                });
            }
        });

        // Laboratorio
        const labs = {
            hemo: document.getElementById('lab_hemo').value,
            red: document.getElementById('lab_red').value,
            white: document.getElementById('lab_white').value,
            chol: document.getElementById('lab_chol').value
        };

        // Obtener Campos Dinámicos / Tags si el contenedor existe
        const dynamicFields = {};
        document.querySelectorAll('.dynamic-field-badge').forEach(badge => {
            const key = badge.getAttribute('data-key');
            const val = badge.getAttribute('data-value');
            if (key && val) {
                dynamicFields[key] = val;
            }
        });

        // Combinar en un único objeto estructurado
        let odontogramaData = null;
        const odontoInput = document.querySelector('input[name="odontograma_data"]');
        if (odontoInput && odontoInput.value) {
            try {
                odontogramaData = JSON.parse(odontoInput.value);
            } catch(e) {
                odontogramaData = null;
            }
        }

        const specializedData = {
            vitals: vitals,
            medications: medications,
            labs: labs,
            dynamic_fields: dynamicFields,
            odontograma: odontogramaData
        };

        // Inyectar en el input oculto en formato string JSON
        document.getElementById('specialized_data').value = JSON.stringify(specializedData);
    });
</script>
@endpush
