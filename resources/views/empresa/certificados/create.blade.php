@extends('layouts.empresa')

@php
    $modoOscuro = ($config?->theme === 'dark');
@endphp

@section('content')
<style>
    .premium-form-card {
        background: {{ $modoOscuro ? 'rgba(11, 17, 32, 0.8)' : '#ffffff' }};
        backdrop-filter: blur(15px);
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.08)' }} !important;
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, {{ $modoOscuro ? '0.4' : '0.06' }});
        transition: all 0.3s ease;
    }
    
    .premium-input {
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#f8fafc' }} !important;
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.12)' }} !important;
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }} !important;
        border-radius: 14px !important;
        padding: 13px 18px !important;
        font-size: 0.95rem !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    
    .premium-input:focus {
        border-color: var(--color-primario) !important;
        box-shadow: 0 0 15px {{ $colorPrimario }}35 !important;
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.04)' : '#ffffff' }} !important;
    }
    
    .premium-input::placeholder {
        color: {{ $modoOscuro ? 'rgba(255,255,255,0.25)' : 'rgba(0,0,0,0.35)' }} !important;
    }
    
    .premium-input option {
        background: {{ $modoOscuro ? '#0b1120' : '#ffffff' }};
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }};
    }
    
    .form-label-premium {
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: {{ $modoOscuro ? '#94a3b8' : '#64748b' }};
        margin-bottom: 6px;
    }

    .asistente-box {
        background: linear-gradient(135deg, {{ $colorPrimario }}10, rgba(255,255,255,0.01));
        border: 1px solid {{ $colorPrimario }}25;
        border-radius: 18px;
    }

    .info-card-premium {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 15px 35px rgba(0,0,0,0.35);
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1 fw-extrabold" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};">📜 Emitir Nuevo Certificado Oficial</h4>
            <p class="small mb-0 {{ $modoOscuro ? 'text-muted' : 'text-secondary' }}">Rellene los datos médicos para generar la constancia oficial con código de autenticidad QR.</p>
        </div>
        <div>
            <a href="{{ route('empresa.certificados.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver al Listado
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Formulario principal -->
        <div class="col-lg-8 col-md-12">
            <div class="card premium-form-card mb-5">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('empresa.certificados.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-sm-12">
                                <label class="form-label-premium">Paciente *</label>
                                <select name="client_id" id="client_id" class="form-select premium-input shadow-none" required>
                                    <option value="">Seleccione el paciente...</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" data-name="{{ $patient->name }}" data-doc="{{ $patient->document ?? $patient->cuit_cuil }}">
                                            {{ $patient->name }} (Doc: {{ $patient->document ?? $patient->cuit_cuil ?? 'S/D' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="form-label-premium">Médico Firmante *</label>
                                <select name="user_id" class="form-select premium-input shadow-none" required>
                                    <option value="">Seleccione el profesional...</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            Dr. {{ $doctor->name }} (M.P. {{ $doctor->profesionalConfig->matricula ?? 'Pendiente' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4 col-sm-12">
                                <label class="form-label-premium">Tipo de Certificado *</label>
                                <input type="text" name="tipo" id="tipo" class="form-control premium-input shadow-none" placeholder="Ej: Aptitud Psicofísica, Apto Deportivo" required>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label class="form-label-premium">Convenio B2B Asociado (Opcional)</label>
                                <select name="b2b_convention_id" id="b2b_convention_id" class="form-select premium-input shadow-none" onchange="onConventionChange()">
                                    <option value="">Ninguno / Particular</option>
                                    @foreach($conventions as $conv)
                                        @php
                                            $names = \App\Models\Servicio::whereIn('id', (array) $conv->servicios_requeridos)->pluck('nombre')->toArray();
                                            $namesStr = implode(', ', $names);
                                        @endphp
                                        <option value="{{ $conv->id }}" data-practices="{{ $namesStr }}">
                                            {{ $conv->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label class="form-label-premium">Vigencia hasta (Opcional)</label>
                                <input type="date" name="vigencia_hasta" class="form-control premium-input shadow-none">
                            </div>
                        </div>

                        <!-- Alerta dinámica de prácticas requeridas por convenio B2B -->
                        <div id="convention_practices_alert" class="mb-4 p-3 bg-opacity-10 border border-info rounded-3 text-info d-none" style="background-color: rgba(59, 130, 246, 0.05); border-color: rgba(59, 130, 246, 0.3) !important;">
                            <div class="small fw-bold"><i class="bi bi-info-circle-fill me-1"></i> Circuito de Prácticas Requeridas para este Convenio:</div>
                            <div id="convention_practices_list" class="mt-1 small font-monospace"></div>
                        </div>

                        <!-- Cargar Plantilla Inteligente -->
                        <div class="mb-4 p-4 asistente-box">
                            <div class="x-small fw-bold text-success text-uppercase mb-2"><i class="bi bi-magic me-1"></i> Asistente de Plantillas Médicas Rápidas</div>
                            <div class="row">
                                <div class="col-12">
                                    <select id="templateSelector" onchange="loadTemplate()" class="form-select premium-input shadow-none" style="padding: 10px 15px !important;">
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
                            <label class="form-label-premium">Texto Clínico del Certificado *</label>
                            <textarea name="contenido" id="contenido" class="form-control premium-input shadow-none" rows="7" placeholder="Escriba aquí los detalles del examen médico, diagnóstico y aptitud..." required></textarea>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg py-3 rounded-pill fw-bold text-white shadow-sm border-0" style="background: linear-gradient(135deg, var(--color-primario), #10b981);">
                                <i class="bi bi-qr-code-scan me-2"></i> EMITIR CERTIFICADO Y COMPILACIÓN QR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tarjeta informativa derecha -->
        <div class="col-lg-4 col-md-12">
            <div class="card info-card-premium text-white rounded-4 border-0 p-4 mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="fs-1">🛡️</span>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">Validación QR</h5>
                        <small class="text-success fw-bold text-uppercase">Estándar Seguro MedPlus</small>
                    </div>
                </div>
                <p class="small text-white opacity-75 mb-0" style="line-height: 1.6;">
                    Cada certificado emitido genera una firma digital criptográfica de validación pública de forma única. 
                    Al ser impreso, un código QR seguro es insertado al pie de la hoja para que inspectores, escuelas o dependencias de tránsito verifiquen la autenticidad en tiempo real.
                </p>
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

    function onConventionChange() {
        const conventionSelector = document.getElementById('b2b_convention_id');
        const selectedOpt = conventionSelector.options[conventionSelector.selectedIndex];
        const alertBox = document.getElementById('convention_practices_alert');
        const practicesList = document.getElementById('convention_practices_list');

        if (selectedOpt && selectedOpt.value !== "") {
            const practices = selectedOpt.getAttribute('data-practices');
            if (practices && practices.trim() !== "") {
                practicesList.innerHTML = `<i class="bi bi-patch-check-fill text-info me-1"></i> ${practices}`;
                alertBox.classList.remove('d-none');
            } else {
                alertBox.classList.add('d-none');
            }

            // Pre-cargar la plantilla de psicofísico si el convenio es de licencias/conducir
            const optText = selectedOpt.text.toLowerCase();
            if (optText.includes('licencia') || optText.includes('conducir') || optText.includes('tránsito')) {
                document.getElementById('templateSelector').value = 'psicofisico';
                loadTemplate();
            }
        } else {
            alertBox.classList.add('d-none');
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
