@extends('layouts.empresa')

@section('content')
<style>
    .medical-bg {
        position: relative;
        padding: 40px 0;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }
    .form-label-premium {
        font-size: 0.75rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .form-control-premium {
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        padding: 12px 16px;
        transition: all 0.3s;
    }
    .form-control-premium:focus {
        border-color: var(--color-primario, #0d6efd);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
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
        color: #64748b;
        background: transparent;
        border-radius: 12px;
        transition: all 0.3s;
    }
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, var(--color-primario, #0d6efd), #3b82f6) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }
</style>

<div class="container medical-bg">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-800 text-dark mb-1">Nueva Evolución Médica</h2>
                    <p class="text-muted">Registro oficial para la Historia Clínica del paciente.</p>
                </div>
                <a href="{{ route('empresa.medical_records.index') }}" class="btn btn-light rounded-pill px-4 fw-bold border">
                    <i class="bi bi-arrow-left me-2"></i> VOLVER
                </a>
            </div>

            <form action="{{ route('empresa.medical_records.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="glass-card p-4 p-md-5">
                            <div class="mb-4">
                                <label class="form-label-premium">Paciente / Afiliado</label>
                                <select name="client_id" class="form-select form-control-premium @error('client_id') is-invalid @enderror" required>
                                    <option value="">Seleccione un paciente...</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}" {{ $selectedPatientId == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->document ?? 'Sin DNI' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-premium">Especialidad</label>
                                    <input type="text" name="specialty" class="form-control form-control-premium" placeholder="Ej: Clínica General" value="{{ old('specialty') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-premium">Motivo de la Visita</label>
                                    <input type="text" name="reason_for_visit" class="form-control form-control-premium" placeholder="Ej: Control de rutina" value="{{ old('reason_for_visit') }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-premium">Diagnóstico y Evolución</label>
                                <textarea name="diagnosis" class="form-control form-control-premium" rows="6" placeholder="Describa el estado del paciente y hallazgos...">{{ old('diagnosis') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-premium">Tratamiento / Plan de Acción</label>
                                <textarea name="treatment" class="form-control form-control-premium" rows="3" placeholder="Indicaciones médicas generales o próximos pasos...">{{ old('treatment') }}</textarea>
                            </div>

                            <hr class="my-4">

                            <!-- Módulo de Pestañas de Carga Especializada Estructurada -->
                            <div class="mb-0">
                                <label class="form-label-premium mb-3"><i class="bi bi-braces-asterisk me-1 text-primary"></i> Data Especializada Estructurada</label>
                                
                                <ul class="nav nav-pills nav-justified gap-2 mb-4" id="specializedTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active fw-bold border py-2" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals-pane" type="button" role="tab"><i class="bi bi-heart-pulse-fill me-2"></i>Signos Vitales</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-bold border py-2" id="meds-tab" data-bs-toggle="tab" data-bs-target="#meds-pane" type="button" role="tab"><i class="bi bi-capsule me-2"></i>Receta / Meds</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-bold border py-2" id="labs-tab" data-bs-toggle="tab" data-bs-target="#labs-pane" type="button" role="tab"><i class="bi bi-clipboard2-pulse-fill me-2"></i>Laboratorio</button>
                                    </li>
                                </ul>

                                <div class="tab-content bg-white p-4 rounded-4 border" id="specializedTabsContent">
                                    <!-- PESTAÑA: SIGNOS VITALES -->
                                    <div class="tab-pane fade show active" id="vitals-pane" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Presión Arterial Sistólica (mmHg)</label>
                                                <input type="number" id="vital_sys" class="form-control form-control-premium" placeholder="Ej: 120" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Presión Arterial Diastólica (mmHg)</label>
                                                <input type="number" id="vital_dia" class="form-control form-control-premium" placeholder="Ej: 80" min="0">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold">Glucemia (mg/dL)</label>
                                                <input type="number" id="vital_glucose" class="form-control form-control-premium" placeholder="Ej: 95" min="0">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold">Frecuencia Cardíaca (lpm)</label>
                                                <input type="number" id="vital_heart" class="form-control form-control-premium" placeholder="Ej: 72" min="0">
                                            </div>
                                            <div class="col-md-4">
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
                                                    <tr class="small text-secondary fw-bold">
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
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Hemoglobina (g/dL)</label>
                                                <input type="number" step="0.1" id="lab_hemo" class="form-control form-control-premium" placeholder="Ej: 14.2">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Glóbulos Rojos (M/uL)</label>
                                                <input type="number" step="0.01" id="lab_red" class="form-control form-control-premium" placeholder="Ej: 4.8">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Glóbulos Blancos (/uL)</label>
                                                <input type="number" id="lab_white" class="form-control form-control-premium" placeholder="Ej: 6500">
                                            </div>
                                            <div class="col-md-6">
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

                    <div class="col-lg-4">
                        <div class="glass-card p-4 mb-4" style="background: rgba(248, 250, 252, 0.8);">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-paperclip me-2 text-primary"></i>Archivos y Estudios</h6>
                            <p class="x-small text-muted mb-3">Adjunte radiografías, informes externos o PDFs. Se guardarán en Bunny.net.</p>
                            <input type="file" name="attachments[]" class="form-control form-control-premium bg-white mb-2" multiple>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-white text-muted border x-small">PDF</span>
                                <span class="badge bg-white text-muted border x-small">JPG</span>
                                <span class="badge bg-white text-muted border x-small">PNG</span>
                            </div>
                        </div>

                        <div class="glass-card p-4 mb-4" style="background: rgba(248, 250, 252, 0.8);">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i>Notas Internas</h6>
                            <p class="x-small text-muted mb-3">Información confidencial para el equipo médico. No se imprime en informes para el paciente.</p>
                            <textarea name="internal_notes" class="form-control form-control-premium" rows="6" placeholder="Escriba notas privadas aquí...">{{ old('internal_notes') }}</textarea>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-save-medical shadow-lg">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> GUARDAR REGISTRO
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

        // Combinar en un único objeto estructurado
        const specializedData = {
            vitals: vitals,
            medications: medications,
            labs: labs
        };

        // Inyectar en el input oculto en formato string JSON
        document.getElementById('specialized_data').value = JSON.stringify(specializedData);
    });
</script>
@endpush
