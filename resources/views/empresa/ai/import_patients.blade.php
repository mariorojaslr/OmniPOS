@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4" style="max-width: 1200px;">
    <!-- CABECERA -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('empresa.ai.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill me-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3 class="fw-extrabold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                    <i class="bi bi-file-earmark-spreadsheet-fill text-info me-2"></i>Carga de Pacientes con Inteligencia Artificial
                </h3>
            </div>
            <p class="text-muted small mb-0 mt-1">
                Sube la planilla Excel/CSV del software anterior. La IA mapeará automáticamente las columnas a nuestro formato.
            </p>
        </div>
    </div>

    <!-- PASO 1: CARGA DE ARCHIVO -->
    <div id="step-upload" class="card border-0 rounded-4 shadow-sm mb-4" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.7)' : '#ffffff' }};">
        <div class="card-body p-4 p-md-5 text-center">
            <div class="p-4 rounded-4 border-2 border-dashed mb-3" style="border: 2px dashed {{ $modoOscuro ? '#475569' : '#cbd5e1' }}; background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#f8fafc' }}; cursor: pointer;" onclick="document.getElementById('file_input').click()">
                <i class="bi bi-cloud-arrow-up text-primary fs-1 mb-2 d-block"></i>
                <h5 class="fw-bold mb-1 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">Arrastra o haz clic para subir el archivo de pacientes</h5>
                <p class="text-muted small mb-3">Compatible con Excel (.xlsx, .xls), archivos CSV y TXT delimitados.</p>
                <button type="button" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    <i class="bi bi-folder2-open me-1"></i> Seleccionar archivo del software anterior
                </button>
                <input type="file" id="file_input" class="d-none" accept=".xlsx,.xls,.csv,.txt" onchange="handleFileSelected(this)">
            </div>
            <div id="upload_spinner" class="d-none py-3">
                <div class="spinner-border text-primary me-2" role="status"></div>
                <span class="fw-bold text-primary">La IA está analizando las columnas y datos del archivo...</span>
            </div>
        </div>
    </div>

    <!-- PASO 2: MAPEO INTELIGENTE DETECTADO POR IA (OCULTO INICIALMENTE) -->
    <div id="step-mapping" class="card border-0 rounded-4 shadow-sm mb-4 d-none" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.7)' : '#ffffff' }};">
        <div class="card-header bg-transparent p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                    <i class="bi bi-magic text-warning me-2"></i>Mapeo Semántico Detectado por IA
                </h5>
                <small class="text-muted">Verifique o ajuste las columnas que la IA identificó para cada campo de nuestro sistema.</small>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill" id="badge_total_rows">
                0 pacientes detectados
            </span>
        </div>
        <div class="card-body p-4">
            <form id="form_mapping" onsubmit="executeImport(event)">
                <div class="row g-3 mb-4" id="mapping_fields_container">
                    <!-- Columnas inyectadas por JS -->
                </div>

                <!-- VISTA PREVIA DE LAS PRIMERAS FILAS -->
                <h6 class="fw-bold mb-2 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                    <i class="bi bi-table me-1 text-primary"></i> Vista Previa de Datos Detectados:
                </h6>
                <div class="table-responsive rounded-3 border mb-4">
                    <table class="table table-sm table-hover align-middle mb-0" id="preview_table">
                        <thead class="table-light">
                            <tr id="preview_headers"></tr>
                        </thead>
                        <tbody id="preview_body"></tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" onclick="resetUpload()">
                        <i class="bi bi-arrow-left me-1"></i> Subir otro archivo
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm" id="btn_confirm_import">
                        <i class="bi bi-check-circle-fill me-2"></i> Confirmar y Estructurar Pacientes en el Sistema
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- RESULTADO FINAL -->
    <div id="step-result" class="card border-0 rounded-4 shadow-sm mb-4 d-none text-center p-5" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.7)' : '#ffffff' }};">
        <div class="mb-3">
            <span class="fs-1 text-success">🎉</span>
        </div>
        <h3 class="fw-bold text-success mb-2">¡Migración Completada Exitosamente!</h3>
        <p class="text-muted mb-4" id="result_summary_text"></p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('empresa.clientes.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="bi bi-people-fill me-2"></i> Ver Cartera de Clientes / Pacientes
            </a>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" onclick="resetUpload()">
                <i class="bi bi-plus-lg me-1"></i> Importar otra planilla
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let uploadedHeaders = [];

    const systemFields = [
        { key: 'name', label: 'Nombre Completo del Paciente *', required: true, icon: 'bi-person' },
        { key: 'document', label: 'DNI / Documento / Cédula', required: false, icon: 'bi-card-text' },
        { key: 'phone', label: 'Teléfono / Móvil / WhatsApp', required: false, icon: 'bi-telephone' },
        { key: 'email', label: 'Correo Electrónico (Email)', required: false, icon: 'bi-envelope' },
        { key: 'address', label: 'Dirección / Domicilio', required: false, icon: 'bi-geo-alt' },
        { key: 'city', label: 'Ciudad / Localidad', required: false, icon: 'bi-building' },
        { key: 'affiliate_number', label: 'Nº Afiliado / Carnet / Prepaga', required: false, icon: 'bi-hash' },
        { key: 'workplace', label: 'Obra Social / Empresa / Cobertura', required: false, icon: 'bi-heart-pulse' }
    ];

    function handleFileSelected(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        document.getElementById('upload_spinner').classList.remove('d-none');

        fetch('{{ route("empresa.ai.analyze-patients") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('upload_spinner').classList.add('d-none');
            if (data.success) {
                uploadedHeaders = data.headers;
                renderMappingUI(data);
            } else {
                alert(data.message || 'Error al procesar el archivo');
            }
        })
        .catch(err => {
            document.getElementById('upload_spinner').classList.add('d-none');
            alert('Error en la comunicación con el servidor.');
        });
    }

    function renderMappingUI(data) {
        document.getElementById('step-upload').classList.add('d-none');
        document.getElementById('step-mapping').classList.remove('d-none');
        document.getElementById('badge_total_rows').textContent = `${data.total_rows} filas de pacientes listas`;

        const container = document.getElementById('mapping_fields_container');
        container.innerHTML = '';

        systemFields.forEach(field => {
            const detectedIdx = data.mapping[field.key];
            const col = document.createElement('div');
            col.className = 'col-md-6 col-lg-3';

            let options = `<option value="">-- Ignorar este campo --</option>`;
            data.headers.forEach((h, idx) => {
                const selected = (detectedIdx !== null && detectedIdx === idx) ? 'selected' : '';
                options += `<option value="${idx}" ${selected}>Columna: ${h}</option>`;
            });

            col.innerHTML = `
                <div class="p-3 rounded-3 border" style="background: rgba(14, 165, 233, 0.02);">
                    <label class="form-label x-small fw-bold text-uppercase text-muted d-flex align-items-center gap-1 mb-1">
                        <i class="bi ${field.icon} text-primary"></i> ${field.label}
                    </label>
                    <select class="form-select form-select-sm" name="mapping[${field.key}]" ${field.required ? 'required' : ''}>
                        ${options}
                    </select>
                </div>
            `;
            container.appendChild(col);
        });

        // Vista previa
        const theadTr = document.getElementById('preview_headers');
        theadTr.innerHTML = '';
        data.headers.forEach(h => {
            const th = document.createElement('th');
            th.textContent = h;
            th.className = 'x-small fw-bold';
            theadTr.appendChild(th);
        });

        const tbody = document.getElementById('preview_body');
        tbody.innerHTML = '';
        data.preview_rows.forEach(row => {
            const tr = document.createElement('tr');
            row.forEach(val => {
                const td = document.createElement('td');
                td.textContent = val;
                td.className = 'small text-truncate';
                td.style.maxWidth = '180px';
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
    }

    function executeImport(e) {
        e.preventDefault();
        const btn = document.getElementById('btn_confirm_import');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Estructurando pacientes con IA...`;

        const form = document.getElementById('form_mapping');
        const formData = new FormData(form);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("empresa.ai.execute-import-patients") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            if (data.success) {
                document.getElementById('step-mapping').classList.add('d-none');
                document.getElementById('step-result').classList.remove('d-none');
                document.getElementById('result_summary_text').innerHTML = `
                    Se han procesado correctamente <strong>${data.total} pacientes</strong>.<br>
                    <span class="text-success fw-bold">${data.created} nuevos pacientes creados</span> y 
                    <span class="text-primary fw-bold">${data.updated} actualizados</span> sin pérdida de datos.
                `;
            } else {
                alert(data.message || 'Error en la importación');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i> Confirmar y Estructurar Pacientes`;
            alert('Error al ejecutar la importación.');
        });
    }

    function resetUpload() {
        document.getElementById('file_input').value = '';
        document.getElementById('step-mapping').classList.add('d-none');
        document.getElementById('step-result').classList.add('d-none');
        document.getElementById('step-upload').classList.remove('d-none');
    }
</script>
@endpush
@endsection
