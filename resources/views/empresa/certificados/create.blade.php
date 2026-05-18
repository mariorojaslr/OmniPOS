@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">📜 Emitir Nuevo Certificado Oficial</h4>
            <p class="text-muted small mb-0">Rellene los datos médicos para generar la constancia oficial con código de autenticidad QR.</p>
        </div>
        <div>
            <a href="{{ route('empresa.certificados.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver al Listado
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden bg-white mb-5">
                <div class="card-body p-4">
                    <form action="{{ route('empresa.certificados.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 col-sm-12">
                                <label class="small text-muted fw-bold mb-1">Paciente *</label>
                                <select name="client_id" id="client_id" class="form-select border-slate shadow-none" required>
                                    <option value="">Seleccione el paciente...</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" data-name="{{ $patient->name }}" data-doc="{{ $patient->document ?? $patient->cuit_cuil }}">
                                            {{ $patient->name }} (Doc: {{ $patient->document ?? $patient->cuit_cuil ?? 'S/D' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="small text-muted fw-bold mb-1">Médico Firmante *</label>
                                <select name="user_id" class="form-select border-slate shadow-none" required>
                                    <option value="">Seleccione el profesional...</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            Dr. {{ $doctor->name }} (M.P. {{ $doctor->profesionalConfig->matricula ?? 'Pendiente' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 col-sm-12">
                                <label class="small text-muted fw-bold mb-1">Tipo de Certificado *</label>
                                <input type="text" name="tipo" id="tipo" class="form-control border-slate shadow-none" placeholder="Ej: Aptitud Psicofísica, Apto Deportivo, Licencia Laboral" required>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="small text-muted fw-bold mb-1">Vigencia hasta (Opcional)</label>
                                <input type="date" name="vigencia_hasta" class="form-control border-slate shadow-none">
                            </div>
                        </div>

                        <!-- Cargar Plantilla Inteligente -->
                        <div class="mb-4 p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                            <div class="x-small fw-bold text-success text-uppercase mb-2"><i class="bi bi-magic me-1"></i> Asistente de Plantillas Médicas Rápidas</div>
                            <div class="row">
                                <div class="col-12">
                                    <select id="templateSelector" onchange="loadTemplate()" class="form-select form-select-sm border-slate shadow-none">
                                        <option value="">-- Seleccionar una plantilla pre-definida --</option>
                                        <option value="psicofisico">Examen de Aptitud Psicofísica (Conductor / Licencia)</option>
                                        <option value="deportivo">Certificado de Apto Deportivo</option>
                                        <option value="reposo">Licencia Médica por Reposo Laboral / Escolar</option>
                                        <option value="clinico">Certificado Clínico General de Buena Salud</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="small text-muted fw-bold mb-1">Texto Clínico del Certificado *</label>
                            <textarea name="contenido" id="contenido" class="form-control border-slate shadow-none" rows="8" placeholder="Escriba aquí los detalles del examen médico, diagnóstico y aptitud..." required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold py-3 rounded-4 shadow-sm fs-5">
                                <i class="bi bi-qr-code-scan me-2"></i> EMITIR CERTIFICADO Y CONFIGURAR FIRMA QR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card bg-gradient-dark text-white rounded-4 border-0 shadow-lg p-4 mb-4" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="fs-1">🛡️</span>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">Validación Criptográfica QR</h5>
                        <small class="text-success fw-bold text-uppercase">Estándar Seguro MedPlus</small>
                    </div>
                </div>
                <p class="small text-muted mb-0">Cada certificado emitido genera una firma digital criptográfica de validación pública de forma única. Al ser impreso, un código QR seguro es insertado al pie de la hoja para que inspectores, escuelas o dependencias de tránsito verifiquen la autenticidad en tiempo real.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function loadTemplate() {
        const selector = document.getElementById('templateSelector');
        const selectedValue = selector.value;
        const contenidoField = document.getElementById('contenido');
        const tipoField = document.getElementById('tipo');

        const clientSelector = document.getElementById('client_id');
        const selectedClientOption = clientSelector.options[clientSelector.selectedIndex];
        
        let patientName = "[Nombre del Paciente]";
        let patientDoc = "[Documento]";
        
        if (selectedClientOption && selectedClientOption.value !== "") {
            patientName = selectedClientOption.getAttribute('data-name');
            patientDoc = selectedClientOption.getAttribute('data-doc') || "Sin Documento";
        }

        const templates = {
            psicofisico: {
                tipo: "Aptitud Psicofísica (Carnet de Conducir)",
                texto: `Por medio del presente, certifico que habiendo evaluado clínicamente al paciente ${patientName}, titular del documento ${patientDoc}, se constata que presenta aptitud física, auditiva, visual y psíquica normal para la conducción de vehículos automotores de categorías particulares, encontrándose APTO para tramitar/renovar su Licencia de Conducir.`
            },
            deportivo: {
                tipo: "Apto Deportivo Físico",
                texto: `Certifico que el paciente ${patientName}, DNI/Doc ${patientDoc}, ha sido sometido a un examen físico completo, incluyendo controles cardiológicos y respiratorios de rutina. No encontrándose contraindicación clínica al momento de la fecha, se otorga el APTO FÍSICO Y CARDIOVASCULAR para realizar actividades físicas recreativas, competitivas y de alto impacto.`
            },
            reposo: {
                tipo: "Licencia Médica por Reposo",
                texto: `Por la presente constancia, se prescribe reposo absoluto de actividades laborales y de esfuerzo físico al paciente ${patientName}, DNI/Doc ${patientDoc}, a partir del día de la fecha por un término de ___ días, debido a presentar cuadro clínico compatible con afección aguda de carácter temporario.`
            },
            clinico: {
                tipo: "Certificado de Salud General",
                texto: `Certifico por la presente que el paciente ${patientName}, DNI/Doc ${patientDoc}, goza al día de la fecha de buena salud física general, no constatándose afecciones infectocontagiosas ni limitaciones clínicas de relevancia al examen físico realizado.`
            }
        };

        if (selectedValue && templates[selectedValue]) {
            contenidoField.value = templates[selectedValue].texto;
            tipoField.value = templates[selectedValue].tipo;
        }
    }

    // Actualizar plantilla si ya seleccionaron paciente e interactúan con selector
    document.getElementById('client_id').addEventListener('change', function() {
        if (document.getElementById('templateSelector').value !== "") {
            loadTemplate();
        }
    });
</script>
@endsection
