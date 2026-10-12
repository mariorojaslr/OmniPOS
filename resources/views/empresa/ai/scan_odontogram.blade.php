@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4" style="max-width: 1300px;">
    <!-- CABECERA -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('empresa.ai.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill me-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3 class="fw-extrabold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                    <i class="bi bi-camera-fill text-warning me-2"></i>Escaneo de Odontogramas Antiguos Manuales
                </h3>
            </div>
            <p class="text-muted small mb-0 mt-1">
                Digitaliza fichas en papel u odontogramas impresos con IA. El sistema reconoce la simbología y reconstruye el odontograma digital interactivo automáticamente.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <!-- COLUMNA IZQUIERDA: CAPTURA / ESCANEO DE LA FICHA EN PAPEL -->
        <div class="col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm mb-4" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.7)' : '#ffffff' }};">
                <div class="card-header bg-transparent p-4 border-bottom">
                    <h5 class="fw-bold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                        <i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>1. Captura de Ficha / Foto
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="form_scan_image" onsubmit="processImageWithAI(event)">
                        <!-- SELECCIÓN DE PACIENTE -->
                        <div class="mb-3">
                            <label class="form-label x-small fw-bold text-uppercase text-muted">Paciente a Asignar *</label>
                            <select id="scan_patient_id" class="form-select form-control-premium" required>
                                <option value="">Seleccione el paciente...</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (Doc: {{ $p->document ?? 'S/D' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SELECCIÓN DE ODONTÓLOGO QUE VALIDA -->
                        <div class="mb-3">
                            <label class="form-label x-small fw-bold text-uppercase text-muted">Profesional que Valida *</label>
                            <select id="scan_doctor_id" class="form-select form-control-premium" required>
                                <option value="">Seleccione el profesional...</option>
                                @foreach($doctors as $d)
                                    <option value="{{ $d->id }}" {{ $d->id === auth()->id() ? 'selected' : '' }}>
                                        {{ $d->name }} ({{ $d->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ÁREA DE SUBIDA / CÁMARA -->
                        <div class="mb-3">
                            <label class="form-label x-small fw-bold text-uppercase text-muted">Imagen del Odontograma Manual *</label>
                            <div class="p-4 text-center rounded-4 border-2 border-dashed" style="border: 2px dashed {{ $modoOscuro ? '#475569' : '#cbd5e1' }}; cursor: pointer; background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#f8fafc' }};" onclick="document.getElementById('scan_image_input').click()">
                                <i class="bi bi-camera fs-1 text-warning d-block mb-2"></i>
                                <span class="fw-bold d-block text-dark {{ $modoOscuro ? 'text-white' : '' }}">Tomar foto o subir escaneo</span>
                                <small class="text-muted d-block">Soporta JPG, PNG, escáner de alta resolución o PDF</small>
                                <input type="file" id="scan_image_input" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
                        </div>

                        <!-- PREVIEW DE LA IMAGEN CARGADA -->
                        <div id="image_preview_box" class="mb-3 d-none text-center">
                            <img id="image_preview" src="" alt="Ficha escaneada" class="img-fluid rounded-3 border shadow-sm" style="max-height: 250px;">
                        </div>

                        <!-- OBSERVACIONES ADICIONALES -->
                        <div class="mb-4">
                            <label class="form-label x-small fw-bold text-uppercase text-muted">Notas Clínicas Adicionales</label>
                            <textarea id="scan_notes" class="form-control" rows="2" placeholder="Ej: Ficha manual traída por el paciente de su clínica anterior año 2024..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning text-dark w-100 rounded-pill py-3 fw-bold shadow-sm" id="btn_process_ai">
                            <i class="bi bi-cpu-fill me-2"></i> Procesar con Visión Artificial IA
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: RESULTADO DIGITALIZADO & ODONTOGRAMA GENERADO -->
        <div class="col-lg-7">
            <div class="card border-0 rounded-4 shadow-sm h-100" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.7)' : '#ffffff' }};">
                <div class="card-header bg-transparent p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                            <i class="bi bi-stars text-warning me-2"></i>2. Odontograma Digital Reconstruido
                        </h5>
                        <small class="text-muted">La IA interpretará los símbolos y los proyectará en nuestro odontograma interactivo oficial.</small>
                    </div>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold" id="badge_status_ai">
                        Esperando imagen...
                    </span>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <!-- SPINNER DE ANÁLISIS IA -->
                    <div id="ai_analyzing_box" class="d-none text-center my-auto py-5">
                        <div class="spinner-grow text-warning mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                        <h5 class="fw-bold text-warning mb-1">Analizando trazos, cruces y cuadrantes con IA...</h5>
                        <p class="text-muted small">Reconociendo piezas dentales, obturaciones en resina, coronas y ausencias.</p>
                    </div>

                    <!-- ESTADO INICIAL VACÍO -->
                    <div id="ai_empty_box" class="text-center my-auto py-5">
                        <span class="fs-1 text-muted opacity-50 d-block mb-3">🦷</span>
                        <h6 class="fw-bold text-muted mb-1">Sin digitalización en curso</h6>
                        <p class="text-secondary small mb-0">Seleccione un paciente y suba la foto del odontograma físico para comenzar.</p>
                    </div>

                    <!-- RESULTADO DEL ODONTOGRAMA CARGADO -->
                    <div id="ai_result_box" class="d-none flex-grow-1">
                        <div class="alert alert-success d-flex align-items-center justify-content-between py-2 px-3 rounded-3 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                                <span class="small fw-bold" id="ai_findings_summary"></span>
                            </div>
                        </div>

                        <!-- COMPONENTE ODONTOGRAMA INTERACTIVO (PERMITE AL ODONTÓLOGO AJUSTAR CUALQUIER DETALLE) -->
                        @include('empresa.medical_records.components.odontograma', [
                            'readonly' => false,
                            'initialData' => [],
                            'containerId' => 'odontograma_scanned'
                        ])

                        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i> Puedes hacer clic en cualquier diente para corregir o agregar detalles antes de guardar.
                            </span>
                            <button type="button" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow-sm" id="btn_save_scanned" onclick="saveScannedOdontogram()">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar e Integrar en la Historia del Paciente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let scannedDetectedData = {};
    let scannedImageUrl = null;

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image_preview').src = e.target.result;
                document.getElementById('image_preview_box').classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function processImageWithAI(e) {
        e.preventDefault();
        const input = document.getElementById('scan_image_input');
        if (!input.files || !input.files[0]) {
            alert('Por favor selecciona o toma una foto del odontograma a escanear.');
            return;
        }

        const patientId = document.getElementById('scan_patient_id').value;
        const notes = document.getElementById('scan_notes').value;

        const formData = new FormData();
        formData.append('image', input.files[0]);
        formData.append('patient_id', patientId);
        formData.append('notes', notes);
        formData.append('_token', '{{ csrf_token() }}');

        const btn = document.getElementById('btn_process_ai');
        btn.disabled = true;

        document.getElementById('ai_empty_box').classList.add('d-none');
        document.getElementById('ai_result_box').classList.add('d-none');
        document.getElementById('ai_analyzing_box').classList.remove('d-none');
        document.getElementById('badge_status_ai').className = 'badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold';
        document.getElementById('badge_status_ai').textContent = 'Procesando con Visión Artificial...';

        fetch('{{ route("empresa.ai.process-odontogram-image") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            document.getElementById('ai_analyzing_box').classList.add('d-none');

            if (data.success) {
                scannedDetectedData = data.detected_data;
                scannedImageUrl = data.image_url;

                document.getElementById('ai_result_box').classList.remove('d-none');
                document.getElementById('badge_status_ai').className = 'badge bg-success px-3 py-2 rounded-pill fw-bold';
                document.getElementById('badge_status_ai').textContent = 'Digitalización Completada';
                document.getElementById('ai_findings_summary').innerHTML = `<strong>${data.findings_count} hallazgos reconocidos:</strong> ${data.summary}`;

                // Inyectar en el componente de odontograma interactivo
                if (typeof window.cargarOdontogramaData === 'function') {
                    window.cargarOdontogramaData('odontograma_scanned', scannedDetectedData);
                }
            } else {
                alert(data.message || 'Error al procesar la imagen con IA');
                document.getElementById('ai_empty_box').classList.remove('d-none');
            }
        })
        .catch(err => {
            btn.disabled = false;
            document.getElementById('ai_analyzing_box').classList.add('d-none');
            document.getElementById('ai_empty_box').classList.remove('d-none');
            alert('Error al comunicarse con el motor de visión.');
        });
    }

    function saveScannedOdontogram() {
        const patientId = document.getElementById('scan_patient_id').value;
        const doctorId = document.getElementById('scan_doctor_id').value;
        const notes = document.getElementById('scan_notes').value;

        if (!patientId || !doctorId) {
            alert('Por favor asegúrate de seleccionar el paciente y el profesional.');
            return;
        }

        // Obtener el estado actual del odontograma (por si el médico ajustó alguna pieza)
        let finalData = scannedDetectedData;
        const inputHidden = document.getElementById('odontograma_scanned_data');
        if (inputHidden && inputHidden.value) {
            try {
                finalData = JSON.parse(inputHidden.value);
            } catch(e) {}
        }

        const btn = document.getElementById('btn_save_scanned');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Guardando en HCE...`;

        fetch('{{ route("empresa.ai.save-scanned-odontogram") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                client_id: patientId,
                doctor_id: doctorId,
                specialized_data: finalData,
                clinical_notes: notes,
                scan_image_url: scannedImageUrl
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            if (data.success) {
                alert('¡Éxito! Odontograma digitalizado e integrado a la historia clínica del paciente.');
                window.location.href = data.patient_history_url;
            } else {
                alert(data.message || 'Error al guardar el odontograma.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar e Integrar en la Historia del Paciente`;
            alert('Error al guardar el registro en el servidor.');
        });
    }
</script>
@endpush
@endsection
