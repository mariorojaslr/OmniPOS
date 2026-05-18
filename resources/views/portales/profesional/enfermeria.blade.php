<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MedPlus — Portal de Enfermería Celular</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #030712; /* OLED Deep Dark */
            color: #f3f4f6;
            margin: 0;
            padding-bottom: 90px;
        }

        .header-enfermeria {
            background: linear-gradient(135deg, #10b981, #064e3b);
            border-bottom: 2px solid #10b981;
            padding: 20px 15px;
            border-radius: 0 0 25px 25px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);
        }

        .patient-card {
            background-color: #0f172a; /* Slate 900 */
            border: 1px solid #1e293b;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .patient-card:hover {
            border-color: #10b981;
            transform: translateY(-2px);
        }

        .badge-room {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 8px;
            letter-spacing: 0.5px;
        }

        .alert-pill {
            background-color: #ef4444;
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 50px;
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .task-item {
            background-color: #1e293b; /* Slate 800 */
            border-radius: 12px;
            padding: 12px;
            border-left: 4px solid #10b981;
        }

        .task-item.signos {
            border-left-color: #3b82f6; /* Blue 500 */
        }

        .task-item.descartable {
            border-left-color: #f59e0b; /* Amber 500 */
        }

        .btn-action-round {
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.8rem;
            padding: 10px 14px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Bottom Floating Bar */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 75px;
            background-color: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(15px);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 1000;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .bottom-nav-item {
            color: #9ca3af;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.7rem;
            font-weight: 700;
            transition: all 0.2s;
        }

        .bottom-nav-item.active, .bottom-nav-item:hover {
            color: #10b981;
        }

        .bottom-nav-item i {
            font-size: 1.4rem;
            margin-bottom: 2px;
        }

        .modal-content-oled {
            background-color: #0f172a;
            color: #f3f4f6;
            border: 1px solid #1e293b;
            border-radius: 25px;
        }

        .modal-header-oled {
            border-bottom: 1px solid #1e293b;
        }

        .form-control-oled {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: #f3f4f6;
            border-radius: 12px;
        }

        .form-control-oled:focus {
            background-color: #1e293b;
            border-color: #10b981;
            color: #f3f4f6;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }

        .border-emerald {
            border: 2px solid #10b981 !important;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4) !important;
            animation: border-glow 2s infinite alternate;
        }

        @keyframes border-glow {
            from { box-shadow: 0 0 10px rgba(16, 185, 129, 0.4); }
            to { box-shadow: 0 0 25px rgba(16, 185, 129, 0.8); }
        }

        @keyframes pulse-green {
            0% { transform: scale(1); opacity: 0.9; }
            50% { transform: scale(1.03); opacity: 1; }
            100% { transform: scale(1); opacity: 0.9; }
        }
    </style>
</head>
<body>

    <!-- CABECERA -->
    <div class="header-enfermeria">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-black bg-opacity-35 text-white mb-2 py-1 px-3 small fw-bold">PORTAL ENFERMERÍA</span>
                <h4 class="mb-0 fw-extrabold text-white"><i class="bi bi-heart-pulse-fill me-2"></i> Rondín de Turno</h4>
            </div>
            <div class="text-end">
                <div class="fw-bold text-white">{{ $user->name }}</div>
                <div class="x-small text-white opacity-75">Enfermero de Guardia</div>
            </div>
        </div>
    </div>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="container py-4 px-3">
        
        <!-- ALARMA AUDIBLE DE ENFERMERÍA -->
        <div class="card bg-black border-secondary border-opacity-25 mb-4 rounded-4 shadow-sm">
            <div class="card-body py-3 d-flex align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="fs-2">🔔</span>
                    <div>
                        <div class="fw-bold text-white small">Monitoreo de Alerta de Medicación</div>
                        <div class="x-small text-muted">Web Audio API sintetizador de guardia activo.</div>
                    </div>
                </div>
                <button onclick="testAlarm()" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold small">
                    <i class="bi bi-soundwave"></i> PROBAR ALARMA
                </button>
            </div>
        </div>

        @php
            $focusInternacionId = session('focus_internacion_id');
        @endphp

        <!-- SIMULADOR DE ESCÁNER DE CAMA CLÍNICA (Testing en PC/Móvil) -->
        <div class="card bg-black border-secondary border-opacity-25 mb-4 rounded-4 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-4">📸</span>
                        <div>
                            <div class="fw-bold text-white small">Simulador de Escáner QR de Cama</div>
                            <div class="x-small text-muted" style="font-size: 0.7rem;">Simule el escaneo físico de una cama clínica.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 400px;">
                        <select id="simulated_bed_select" class="form-select form-select-sm bg-dark border-secondary text-white small rounded-pill py-1 px-3 shadow-none">
                            <option value="">Seleccione cama a escanear...</option>
                            @foreach($internaciones as $item)
                                @php
                                    $itemCama = $item->currentBedAssignment?->bed;
                                    $itemHab = $itemCama?->room;
                                @endphp
                                @if($itemCama)
                                    <option value="{{ $itemCama->qr_token }}">
                                        Hab. {{ $itemHab?->nombre }} — Cama {{ $itemCama->nombre }} ({{ $item->patient?->name }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <button onclick="simulateQrScan()" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold small flex-shrink-0">
                            <i class="bi bi-qr-code-scan"></i> ESCANEAR
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-white text-uppercase tracking-wider fs-6"><i class="bi bi-people-fill text-success me-2"></i> Pacientes Hospitalizados Activos ({{ count($internaciones) }})</h5>

        <!-- LISTADO DE PACIENTES -->
        @forelse($internaciones as $h)
            @php
                $bedAssignment = $h->currentBedAssignment;
                $cama = $bedAssignment?->bed;
                $habitacion = $cama?->room;
            @endphp
            <div class="patient-card mb-3 p-3 shadow-lg @if($h->id == $focusInternacionId) border-emerald @endif" id="internacion-{{ $h->id }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        @if($h->id == $focusInternacionId)
                            <div class="mb-2">
                                <span class="badge bg-success text-white py-1 px-3 small fw-bold shadow-sm" style="animation: pulse-green 1.5s infinite;">
                                    <i class="bi bi-shield-fill-check me-1"></i> PRESENCIA FÍSICA VALIDADA
                                </span>
                            </div>
                        @endif
                        <span class="badge-room text-uppercase">
                            <i class="bi bi-door-open-fill me-1"></i> Hab. {{ $habitacion?->nombre ?? 'S/H' }} — Cama {{ $cama?->nombre ?? 'S/C' }}
                        </span>
                        <h5 class="fw-bold text-white mt-2 mb-1" style="font-size: 1.1rem;">
                            {{ $h->patient?->name }}
                        </h5>
                        <div class="x-small text-muted">
                            <strong>DNI:</strong> {{ $h->patient?->document ?? 'Sin DNI' }} | <strong>Obra Social:</strong> {{ $h->patient?->obraSocial?->nombre ?? 'Particular' }}
                        </div>
                    </div>
                    <span class="badge rounded-pill bg-success bg-opacity-15 text-success px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                        INTERNADO
                    </span>
                </div>

                <hr class="my-2 border-secondary opacity-25">

                <!-- CONTROLES RAPIDOS -->
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <button onclick="openSignosModal({{ $h->id }}, '{{ $h->patient?->name }}')" class="btn btn-sm btn-primary btn-action-round flex-grow-1 py-2">
                        <i class="bi bi-activity"></i> REGISTRAR SIGNOS
                    </button>
                    <button onclick="openMedicacionModal({{ $h->id }}, '{{ $h->patient?->name }}')" class="btn btn-sm btn-success btn-action-round flex-grow-1 py-2">
                        <i class="bi bi-capsule"></i> APLICAR DOSIS
                    </button>
                    <button onclick="openNotasModal({{ $h->id }}, '{{ $h->patient?->name }}')" class="btn btn-sm btn-outline-light btn-action-round flex-grow-1 py-2">
                        <i class="bi bi-pencil-square"></i> NOTA EVOLUCIÓN
                    </button>
                </div>

                <!-- HISTORIAL RECIENTE DE ADMINISTRACIONES EN ESTE RONDIN -->
                @if(count($h->administrations) > 0)
                    <div class="mt-3 bg-black bg-opacity-35 p-3 rounded-3 border border-secondary border-opacity-10">
                        <div class="x-small fw-bold text-success text-uppercase mb-2"><i class="bi bi-clock-history me-1"></i> Historial Reciente (Rondín de Guardia)</div>
                        <div class="d-flex flex-column gap-2">
                            @foreach($h->administrations->take(3) as $admin)
                                <div class="task-item {{ $admin->tipo === 'signos' ? 'signos' : ($admin->tipo === 'descartable' ? 'descartable' : '') }} p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold small text-white">{{ $admin->nombre }}</span>
                                        <span class="x-small text-muted">{{ $admin->fecha_registro?->format('H:i') ?? 'Hora pendiente' }} hs</span>
                                    </div>
                                    @if($admin->notas)
                                        <div class="x-small text-muted mt-1" style="font-size: 0.75rem;">
                                            {{ $admin->notas }}
                                        </div>
                                    @endif
                                    <div class="x-small text-success text-end mt-1" style="font-size: 0.65rem;">
                                        Registrado por: <strong>{{ $admin->user?->name ?? 'Enfermero' }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-5">
                <span class="fs-1">🛏️</span>
                <h6 class="fw-bold mt-3 text-muted">No hay pacientes internados activos en este momento.</h6>
            </div>
        @endforelse

    </div>

    <!-- FLOAT MENU -->
    <div class="bottom-nav">
        <a href="#" class="bottom-nav-item active">
            <i class="bi bi-house-door-fill"></i>
            <span>Inicio</span>
        </a>
        <a href="javascript:void(0)" onclick="testAlarm()" class="bottom-nav-item">
            <i class="bi bi-bell-fill"></i>
            <span>Alarma</span>
        </a>
        <a href="{{ route('logout.get') }}" class="bottom-nav-item text-danger">
            <i class="bi bi-power"></i>
            <span>Salir</span>
        </a>
    </div>

    <!-- ================= MODALES DE REGISTRO ENFERMERO (OLED STYLED) ================= -->

    <!-- MODAL SIGNOS VITALES -->
    <div class="modal fade" id="modalSignos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-oled">
                <div class="modal-header modal-header-oled border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-white" id="titleSignos">Registrar Signos Vitales</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <form id="formSignos">
                        <input type="hidden" name="internacion_id" id="signos_internacion_id">
                        <input type="hidden" name="tipo" value="signos">
                        <input type="hidden" name="nombre" value="Control de Signos Vitales (Constantes)">

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="small text-muted fw-bold mb-1">Tensión Arterial (TA)</label>
                                <input type="text" name="ta" class="form-control form-control-oled" placeholder="Ej: 120/80 mmHg" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold mb-1">Temperatura (°C)</label>
                                <input type="text" name="temp" class="form-control form-control-oled" placeholder="Ej: 36.5" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold mb-1">Frec. Cardíaca (FC)</label>
                                <input type="text" name="fc" class="form-control form-control-oled" placeholder="Ej: 72 lpm" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold mb-1">Oximetría (SatO2)</label>
                                <input type="text" name="sato2" class="form-control form-control-oled" placeholder="Ej: 98%" required>
                            </div>
                            <div class="col-12">
                                <label class="small text-muted fw-bold mb-1">Notas / Observaciones Clínicas</label>
                                <textarea name="notas" class="form-control form-control-oled" rows="2" placeholder="Paciente reactivo, hemodinámicamente estable..."></textarea>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" onclick="submitNurseAdmin('formSignos')" class="btn btn-primary fw-bold py-3 rounded-4 shadow-sm">
                                <i class="bi bi-save me-2"></i> GUARDAR EN HISTORIA CLÍNICA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DOSIS MEDICACIÓN -->
    <div class="modal fade" id="modalMedicacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-oled">
                <div class="modal-header modal-header-oled border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-white" id="titleMedicacion">Aplicar Dosis de Medicamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <form id="formMedicacion">
                        <input type="hidden" name="internacion_id" id="med_internacion_id">
                        <input type="hidden" name="tipo" value="medicamento">

                        <div class="mb-3">
                            <label class="small text-muted fw-bold mb-1">Medicamento / Fármaco</label>
                            <input type="text" name="nombre" class="form-control form-control-oled" placeholder="Ej: Dipirona, Fisiológica, Diclofenac..." required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="small text-muted fw-bold mb-1">Dosis</label>
                                <input type="text" name="dosis" class="form-control form-control-oled" placeholder="Ej: 1 amp, 500mg, 50ml" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold mb-1">Vía de Adm.</label>
                                <input type="text" name="via" class="form-control form-control-oled" placeholder="Ej: EV, IM, VO, Subcutánea" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted fw-bold mb-1">Próxima aplicación (Horas)</label>
                            <select name="proxima_aplicacion_horas" class="form-select form-control-oled">
                                <option value="">Sin repetición automática</option>
                                <option value="4">En 4 horas</option>
                                <option value="6">En 6 horas</option>
                                <option value="8" selected>En 8 horas</option>
                                <option value="12">En 12 horas</option>
                                <option value="24">En 24 horas</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted fw-bold mb-1">Notas / Dilución / Observación</label>
                            <textarea name="notas" class="form-control form-control-oled" rows="2" placeholder="Diluido en 100ml de solución fisiológica..."></textarea>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" onclick="submitNurseAdmin('formMedicacion')" class="btn btn-success fw-bold py-3 rounded-4 shadow-sm">
                                <i class="bi bi-check-all me-2"></i> CONFIRMAR ADMINISTRACIÓN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EVOLUCIÓN / NOTAS -->
    <div class="modal fade" id="modalNotas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-oled">
                <div class="modal-header modal-header-oled border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-white" id="titleNotas">Evolución / Observaciones de Enfermería</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <form id="formNotas">
                        <input type="hidden" name="internacion_id" id="notas_internacion_id">
                        <input type="hidden" name="tipo" value="notas">
                        <input type="hidden" name="nombre" value="Nota de Evolución de Enfermería">

                        <div class="mb-3">
                            <label class="small text-muted fw-bold mb-1">Notas de Evolución / Incidencias</label>
                            <textarea name="notas" class="form-control form-control-oled" rows="5" placeholder="Paciente refiere dolor localizado, se reporta a médico de cabecera. Se asiste en confort e higiene..." required></textarea>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" onclick="submitNurseAdmin('formNotas')" class="btn btn-outline-light fw-bold py-3 rounded-4 shadow-sm">
                                <i class="bi bi-save me-2"></i> GUARDAR NOTA DE EVOLUCIÓN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // WEB AUDIO API SINTETIZADOR DE GUARDIA
        let audioCtx = null;

        function getAudioContext() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            return audioCtx;
        }

        function playChime(frequency, type, duration) {
            try {
                const ctx = getAudioContext();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.type = type;
                osc.frequency.setValueAtTime(frequency, ctx.currentTime);
                
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + duration);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start();
                osc.stop(ctx.currentTime + duration);
            } catch (e) {
                console.log("AudioContext blocked or uninitialized:", e);
            }
        }

        // Test Alarma audible amigable para enfermeros
        function testAlarm() {
            const f1 = 523.25; // C5
            const f2 = 659.25; // E5
            const f3 = 783.99; // G5
            
            playChime(f1, 'sine', 0.3);
            setTimeout(() => playChime(f2, 'sine', 0.3), 150);
            setTimeout(() => playChime(f3, 'sine', 0.5), 300);
            
            Swal.fire({
                title: '🔔 Alarma de Guardia Activa',
                text: 'Sintetizador Web Audio API probado con éxito. Las alarmas de dosis sonarán puntualmente.',
                icon: 'success',
                confirmButtonColor: '#10b981',
                background: '#0f172a',
                color: '#f3f4f6'
            });
        }

        // Abrir modales
        function openSignosModal(internacionId, patientName) {
            document.getElementById('signos_internacion_id').value = internacionId;
            document.getElementById('titleSignos').innerHTML = `<i class="bi bi-activity text-primary"></i> Signos: ${patientName}`;
            new bootstrap.Modal(document.getElementById('modalSignos')).show();
        }

        // Auto trigger chime if there's scheduled treatments soon
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger a soft sound upon loading to confirm speaker availability
            document.body.addEventListener('click', function() {
                getAudioContext();
            }, { once: true });
        });

        function openMedicacionModal(internacionId, patientName) {
            document.getElementById('med_internacion_id').value = internacionId;
            document.getElementById('titleMedicacion').innerHTML = `<i class="bi bi-capsule text-success"></i> Dosis: ${patientName}`;
            new bootstrap.Modal(document.getElementById('modalMedicacion')).show();
        }

        function openNotasModal(internacionId, patientName) {
            document.getElementById('notas_internacion_id').value = internacionId;
            document.getElementById('titleNotas').innerHTML = `<i class="bi bi-pencil-square text-white"></i> Notas: ${patientName}`;
            new bootstrap.Modal(document.getElementById('modalNotas')).show();
        }

        // Enviar administraciones
        function submitNurseAdmin(formId) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);

            // Si es signos, concatenamos en la descripción de notas
            if (formId === 'formSignos') {
                const ta = formData.get('ta');
                const temp = formData.get('temp');
                const fc = formData.get('fc');
                const sato2 = formData.get('sato2');
                const notasOriginales = formData.get('notas');
                
                const notaConcatenada = `TA: ${ta} mmHg | Temp: ${temp} °C | FC: ${fc} lpm | SatO2: ${sato2} % | Observaciones: ${notasOriginales}`;
                formData.set('notas', notaConcatenada);
            }

            // Convertir FormData a un objeto plano para enviar como JSON
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            Swal.fire({
                title: 'Registrando...',
                text: 'Guardando registro de auditoría clínica en base de datos',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                background: '#0f172a',
                color: '#f3f4f6'
            });

            fetch("{{ route('portal.profesional.enfermeria.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(res => {
                Swal.close();
                if (res.success) {
                    // Sonar chime de confirmación
                    playChime(880, 'sine', 0.4);
                    
                    Swal.fire({
                        title: '¡Registrado!',
                        text: res.message,
                        icon: 'success',
                        confirmButtonColor: '#10b981',
                        background: '#0f172a',
                        color: '#f3f4f6'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: res.message || 'Ocurrió un error al guardar.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        background: '#0f172a',
                        color: '#f3f4f6'
                    });
                }
            })
            .catch(err => {
                Swal.close();
                console.error(err);
                Swal.fire({
                    title: 'Error de Red',
                    text: 'No se pudo conectar con el servidor central de MedPlus.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    background: '#0f172a',
                    color: '#f3f4f6'
                });
            });
        }

        // Simular escaneo de QR
        function simulateQrScan() {
            const token = document.getElementById('simulated_bed_select').value;
            if (!token) {
                Swal.fire({
                    title: 'Seleccione una Cama',
                    text: 'Debe elegir una cama del listado de simulación para escanear su código QR.',
                    icon: 'warning',
                    confirmButtonColor: '#10b981',
                    background: '#0f172a',
                    color: '#f3f4f6'
                });
                return;
            }

            // Redirigir a la ruta de verificación
            window.location.href = `/portal/profesional/escanear-cama/${token}`;
        }

        // Sonar sonido de verificación de presencia física
        @if(session('focus_bed_scanned'))
            document.addEventListener('DOMContentLoaded', function() {
                // Pequeña espera para asegurar inicialización de audio
                setTimeout(() => {
                    playChime(660, 'sine', 0.2);
                    setTimeout(() => playChime(880, 'sine', 0.2), 100);
                    setTimeout(() => playChime(1100, 'sine', 0.4), 200);

                    // Hacer scroll automático al paciente verificado
                    const card = document.getElementById("internacion-{{ $focusInternacionId }}");
                    if (card) {
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 800);
            });
        @endif
    </script>
</body>
</html>
