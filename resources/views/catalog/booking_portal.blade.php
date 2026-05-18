@extends('layouts.guest')

@php
    $config = $empresa->config ?? null;
    $primary   = '#3b82f6'; // Azul Clínico Premium por defecto
    $secondary = '#10b981'; // Verde Éxito por defecto
    if ($config) {
        $primary = $config->color_primary ?? '#3b82f6';
        $secondary = $config->color_secondary ?? '#10b981';
    }
    $logo      = $config ? $config->logo_url : asset('images/logo_omnipos.png');
    $onlinePrice = (float) ($empresa->config_pasarelas['online_booking_price'] ?? 0);
    $toleranceActive = !empty($empresa->config_pasarelas['online_booking_tolerance']);

    // Extraer especialidades únicas de todos los profesionales activos
    $especialidades = [];
    foreach ($doctors as $doctor) {
        if ($doctor->profesionalConfig && is_array($doctor->profesionalConfig->especialidades)) {
            foreach ($doctor->profesionalConfig->especialidades as $esp) {
                if (!empty($esp)) {
                    $especialidades[] = trim($esp);
                }
            }
        }
    }
    $especialidades = array_unique($especialidades);
    if (empty($especialidades)) {
        $especialidades = ['Clínica Médica', 'Pediatría', 'Cardiología', 'Odontología'];
    }

    $specialtyIcons = [
        'cardiologia' => ['icon' => '🫀', 'desc' => 'Salud cardíaca y cardiovascular', 'color' => '#ef4444'],
        'pediatria' => ['icon' => '👶', 'desc' => 'Cuidado médico de niños y bebés', 'color' => '#10b981'],
        'odontologia' => ['icon' => '🦷', 'desc' => 'Salud bucal, implantes y ortodoncia', 'color' => '#3b82f6'],
        'oftalmologia' => ['icon' => '👁️', 'desc' => 'Salud visual y cirugías oculares', 'color' => '#6366f1'],
        'ginecologia' => ['icon' => '🤰', 'desc' => 'Cuidado femenino y obstetricia', 'color' => '#ec4899'],
        'clinica medica' => ['icon' => '🩺', 'desc' => 'Medicina general y preventivo', 'color' => '#f59e0b'],
        'traumatologia' => ['icon' => '🦴', 'desc' => 'Tratamiento óseo y muscular', 'color' => '#8b5cf6'],
        'nutricion' => ['icon' => '🍎', 'desc' => 'Nutrición y planes dietarios', 'color' => '#10b981'],
        'dermatologia' => ['icon' => '🧴', 'desc' => 'Cuidado y patologías de la piel', 'color' => '#14b8a6'],
        'neurologia' => ['icon' => '🧠', 'desc' => 'Sistema nervioso y cerebro', 'color' => '#a855f7'],
    ];
@endphp

@section('content')
<style>
    :root {
        --bg-color: #030712;
        --card-bg: #0b0f19;
        --border-color: rgba(255, 255, 255, 0.08);
        --text-white: #ffffff;
        --text-secondary: #94a3b8;
        --clinical-blue: {{ $primary }};
        --clinical-green: {{ $secondary }};
    }

    body, html {
        background-color: var(--bg-color) !important;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: var(--text-white);
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .glass-nav {
        background: rgba(11, 15, 25, 0.85);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid var(--border-color);
        padding: 15px 40px;
        position: sticky;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .brand-id {
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .booking-container {
        max-width: 850px;
        margin: 30px auto 100px auto;
        padding: 0 20px;
    }

    .card-booking {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.65);
        overflow: hidden;
    }

    .card-header-booking {
        background: linear-gradient(135deg, var(--clinical-blue), #030712);
        padding: 30px;
        border-bottom: 1px solid var(--border-color);
    }

    /* INDICADORES DE PASOS */
    .step-dot {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #111827;
        border: 2px solid var(--border-color);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .step-dot.active {
        background: var(--clinical-blue);
        color: white;
        border-color: var(--clinical-blue);
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
    }

    .step-dot.completed {
        background: var(--clinical-green);
        color: white;
        border-color: var(--clinical-green);
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
    }

    /* CARD DE ESPECIALIDADES */
    .specialty-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .specialty-card:hover {
        border-color: var(--clinical-blue);
        background: rgba(255, 255, 255, 0.04);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.08);
    }

    .specialty-icon-circle {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }

    /* CARD DE MEDICOS */
    .premium-doctor-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .premium-doctor-card:hover {
        border-color: var(--clinical-blue);
        background: rgba(255, 255, 255, 0.04);
        transform: translateY(-2px);
    }

    .doctor-avatar-circle {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        background: linear-gradient(135deg, var(--clinical-blue), #4f46e5);
        color: white;
        border: 2px solid rgba(255,255,255,0.1);
        flex-shrink: 0;
    }

    /* CARD DE SERVICIOS */
    .premium-service-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .premium-service-card:hover {
        border-color: var(--clinical-green);
        background: rgba(255,255,255,0.04);
    }

    /* PILAS DE FECHAS RAPIDAS */
    .quick-day-pill {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-color);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .quick-day-pill:hover {
        border-color: var(--clinical-blue);
        background: rgba(255,255,255,0.05);
    }

    .quick-day-pill.active {
        background: var(--clinical-blue) !important;
        border-color: var(--clinical-blue) !important;
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.35);
    }

    /* GRIDS DE HORAS */
    .slot-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(88px, 1fr));
        gap: 10px;
    }

    .slot-button {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 5px;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-secondary);
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .slot-button:hover {
        border-color: var(--clinical-blue);
        color: white;
        background: rgba(59, 130, 246, 0.1);
    }

    .slot-button.active {
        background: var(--clinical-blue) !important;
        color: white !important;
        border-color: var(--clinical-blue) !important;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35);
    }

    /* INPUTS OLED */
    .form-control-oled {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid var(--border-color) !important;
        color: white !important;
        border-radius: 14px !important;
        padding: 14px 16px !important;
        transition: all 0.3s ease;
    }

    .form-control-oled:focus {
        border-color: var(--clinical-blue) !important;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.2) !important;
    }

    .form-control-oled::placeholder {
        color: rgba(255, 255, 255, 0.3) !important;
    }

    /* WIZARD TRANSICIONES ANIMADAS */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .wizard-step {
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .policy-alert {
        background-color: rgba(239, 68, 68, 0.08);
        border-left: 5px solid #ef4444;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 25px;
    }

    .gateway-pill {
        background: rgba(255,255,255,0.02);
        border: 1px solid var(--border-color);
        transition: all 0.25s ease;
    }

    .gateway-pill.active {
        border-color: var(--clinical-blue) !important;
        background: rgba(59, 130, 246, 0.08) !important;
    }

    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    @media (max-width: 768px) {
        .glass-nav {
            padding: 15px 20px;
        }
        .booking-container {
            margin: 15px auto 80px auto;
        }
        .slot-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
</style>

<div class="glass-nav">
    <div class="brand-id d-flex align-items-center gap-3">
        @if($logo) <img src="{{ $logo }}" height="38" class="rounded shadow-sm"> @endif
        <span class="text-white">{{ $empresa->nombre_comercial }}</span>
    </div>
    <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3 py-2 fw-bold font-monospace border border-success border-opacity-25 d-none d-sm-inline-block">
        ● Agenda Online Activa
    </span>
</div>

<div class="booking-container">
    
    {{-- ALERTAS DE ÉXITO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg rounded-4 p-4 mb-4 border-0" style="background: rgba(16, 185, 129, 0.15);" role="alert">
            <h5 class="fw-bold text-success"><i class="bi bi-calendar2-check-fill me-2"></i> ¡Turno Reservado con Éxito!</h5>
            <p class="mb-0 text-white opacity-90 small">{{ session('success') }}</p>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ALERTA DE POLITICA DE TOLERANCIA ESTRICTA --}}
    @if($toleranceActive)
        <div class="policy-alert shadow-sm d-flex gap-3 align-items-center">
            <span class="fs-2">⚠️</span>
            <div>
                <h6 class="fw-bold text-danger mb-1">Política Estricta de 15 Minutos de Tolerancia</h6>
                <p class="x-small text-white opacity-75 mb-0">
                    Le recordamos que la clínica implementa una tolerancia máxima de 15 minutos. Superado dicho plazo, perderá prioridad en el orden del día de guardia y será reubicado al final de la cola del profesional para no demorar la agenda programada.
                </p>
            </div>
        </div>
    @endif

    <!-- TARJETA RESERVAR -->
    <div class="card-booking">
        <div class="card-header-booking">
            <h3 class="mb-2 fw-extrabold text-white text-uppercase tracking-wide"><i class="bi bi-calendar-plus me-2 text-warning"></i> Agendar Turno Clínico</h3>
            <p class="x-small mb-0 text-white opacity-75 fs-6">Complete su autogestión en simples pasos. Rápido, seguro e instantáneo.</p>
        </div>

        <div class="card-body p-4 p-md-5">
            
            <!-- INDICADOR DE PROGRESO GLOBAL -->
            <div class="step-indicator-container mb-5">
                <div class="d-flex justify-content-between position-relative px-2">
                    <div class="progress position-absolute top-50 start-0 end-0 translate-y-middle bg-dark" style="height: 3px; z-index: 1;">
                        <div class="progress-bar bg-primary" id="wizard-progress" role="progressbar" style="width: 0%; transition: width 0.4s ease;"></div>
                    </div>
                    <div class="step-dot active" data-step="1" title="Especialidad" style="z-index: 2;">1</div>
                    <div class="step-dot" data-step="2" title="Profesional" style="z-index: 2;">2</div>
                    <div class="step-dot" data-step="3" title="Consulta" style="z-index: 2;">3</div>
                    <div class="step-dot" data-step="4" title="Agenda" style="z-index: 2;">4</div>
                    <div class="step-dot" data-step="5" title="Datos" style="z-index: 2;">5</div>
                    <div class="step-dot" data-step="6" title="Confirmación" style="z-index: 2;">6</div>
                </div>
                <div class="d-flex justify-content-between mt-2 px-1 text-center x-small text-muted fw-bold">
                    <span class="step-label text-primary">Especialidad</span>
                    <span class="step-label">Médico</span>
                    <span class="step-label">Práctica</span>
                    <span class="step-label">Agenda</span>
                    <span class="step-label">Paciente</span>
                    <span class="step-label">Confirmación</span>
                </div>
            </div>

            <!-- FORMULARIO WRAPPER -->
            <form action="{{ route('catalog.booking.confirm', $empresa->slug) }}" method="POST" id="wizard-form">
                @csrf

                <!-- STEP 1: ESPECIALIDAD -->
                <div id="step-1" class="wizard-step">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase x-small">Paso 1 de 6</span>
                        <h4 class="fw-bold text-white mt-2 mb-1">¿Qué Especialidad Necesita?</h4>
                        <p class="text-muted small">Seleccione la especialidad para el profesional que le atenderá.</p>
                    </div>
                    
                    <div class="row g-3">
                        @foreach($especialidades as $esp)
                            @php
                                $norm = strtolower(str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $esp));
                                $meta = $specialtyIcons[$norm] ?? ['icon' => '🏥', 'desc' => 'Práctica y consulta general', 'color' => '#3b82f6'];
                            @endphp
                            <div class="col-md-6 col-sm-12">
                                <div class="specialty-card h-100 p-4 d-flex align-items-center gap-3" onclick="nextStepSpecialty('{{ $esp }}')">
                                    <div class="specialty-icon-circle" style="background: {{ $meta['color'] }}15; color: {{ $meta['color'] }};">
                                        {{ $meta['icon'] }}
                                    </div>
                                    <div class="text-start">
                                        <h5 class="fw-bold text-white mb-1" style="font-size: 1.15rem;">{{ $esp }}</h5>
                                        <p class="x-small text-muted mb-0">{{ $meta['desc'] }}</p>
                                    </div>
                                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- STEP 2: PROFESIONAL -->
                <div id="step-2" class="wizard-step d-none">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase x-small">Paso 2 de 6</span>
                        <h4 class="fw-bold text-white mt-2 mb-1">Seleccione su Profesional</h4>
                        <p class="text-muted small">Especialistas disponibles para: <strong id="selected-specialty-title" class="text-primary"></strong></p>
                    </div>
                    
                    <input type="hidden" name="user_id" id="selected_doctor_id" required>
                    
                    <div class="row g-3" id="doctors-wizard-grid">
                        @foreach($doctors as $doctor)
                            @php
                                $doctorSpecialties = $doctor->profesionalConfig && is_array($doctor->profesionalConfig->especialidades) 
                                    ? $doctor->profesionalConfig->especialidades 
                                    : ['Clínica Médica'];
                                $mainSpecialty = $doctorSpecialties[0] ?? 'Clínica Médica';
                                $specialtiesCsv = implode(',', $doctorSpecialties);
                            @endphp
                            <div class="col-md-6 col-sm-12 doctor-wizard-card-container" data-specialties="{{ $specialtiesCsv }}">
                                <div class="premium-doctor-card p-4 d-flex align-items-center gap-3" onclick="nextStepDoctor('{{ $doctor->id }}', 'Dr. {{ $doctor->name }}', '{{ $mainSpecialty }}')">
                                    <div class="doctor-avatar-circle">
                                        {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                    </div>
                                    <div class="text-start">
                                        <h5 class="fw-bold text-white mb-1" style="font-size: 1.1rem;">Dr. {{ $doctor->name }}</h5>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary bg-opacity-25 text-primary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                                {{ $mainSpecialty }}
                                            </span>
                                            <span class="x-small text-muted"><i class="bi bi-star-fill text-warning"></i> 4.9 (Médico Staff)</span>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-start mt-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevStep(1)"><i class="bi bi-arrow-left me-1"></i> Volver</button>
                    </div>
                </div>

                <!-- STEP 3: CONSULTA -->
                <div id="step-3" class="wizard-step d-none">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase x-small">Paso 3 de 6</span>
                        <h4 class="fw-bold text-white mt-2 mb-1">Tipo de Práctica o Consulta</h4>
                        <p class="text-muted small">Seleccione la consulta o práctica médica requerida.</p>
                    </div>
                    
                    <input type="hidden" name="servicio_id" id="selected_servicio_id" required>
                    
                    <div class="row g-3">
                        @foreach($services as $serv)
                            <div class="col-md-6 col-sm-12">
                                <div class="premium-service-card p-4 d-flex align-items-center justify-content-between" onclick="nextStepService('{{ $serv->id }}', '{{ $serv->nombre }}', '{{ $serv->categoria }}')">
                                    <div class="text-start">
                                        <h5 class="fw-bold text-white mb-1" style="font-size: 1.1rem;">{{ $serv->nombre }}</h5>
                                        <p class="x-small text-muted mb-0">Categoría: <strong class="text-success">{{ $serv->categoria }}</strong></p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($onlinePrice > 0)
                                            <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3 py-1 font-monospace" style="font-size: 0.8rem;">
                                                $ {{ number_format($onlinePrice, 2, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-25 text-white rounded-pill px-3 py-1 font-monospace" style="font-size: 0.8rem;">
                                                Bonificado
                                            </span>
                                        @endif
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-start mt-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevStep(2)"><i class="bi bi-arrow-left me-1"></i> Volver</button>
                    </div>
                </div>

                <!-- STEP 4: AGENDA Y HORARIO -->
                <div id="step-4" class="wizard-step d-none">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase x-small">Paso 4 de 6</span>
                        <h4 class="fw-bold text-white mt-2 mb-1">Fecha y Horario del Turno</h4>
                        <p class="text-muted small">Seleccione una fecha disponible y el bloque horario que mejor se adapte.</p>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-5 col-sm-12">
                            <label class="small text-secondary fw-bold mb-2">1. Seleccione la Fecha *</label>
                            <div class="calendar-wrapper p-3 rounded-4 border border-secondary border-opacity-10 bg-dark bg-opacity-40">
                                <input type="date" name="fecha" id="wizard_fecha" class="form-control form-control-oled shadow-none" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" onchange="updateSelectedDateLabel(this.value)" required>
                                
                                <!-- Quick Date Selector -->
                                <div class="quick-days-container mt-3 d-flex flex-wrap gap-2">
                                    @for($i=0; $i<6; $i++)
                                        @php
                                            $d = Carbon::now()->addDays($i);
                                            $dayName = $d->locale('es')->minDayName;
                                            $dayNum = $d->format('d');
                                            $fullDate = $d->format('Y-m-d');
                                        @endphp
                                        <div class="quick-day-pill p-2 text-center rounded-3 cursor-pointer flex-grow-1 {{ $i === 0 ? 'active' : '' }}" onclick="selectQuickDate('{{ $fullDate }}', this)">
                                            <div class="text-uppercase x-small text-muted" style="font-size: 0.65rem;">{{ $dayName }}</div>
                                            <div class="fw-extrabold fs-5 text-white">{{ $dayNum }}</div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12">
                            <label class="small text-secondary fw-bold mb-2">2. Slots Horarios Disponibles *</label>
                            <input type="hidden" name="hora" id="selected_hora" required>
                            
                            <div class="slot-grid">
                                @php
                                    $slots = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];
                                @endphp
                                @foreach($slots as $s)
                                    <div class="slot-button" onclick="nextStepSchedule('{{ $s }}', this)">{{ $s }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevStep(3)"><i class="bi bi-arrow-left me-1"></i> Volver</button>
                    </div>
                </div>

                <!-- STEP 5: DATOS DEL PACIENTE -->
                <div id="step-5" class="wizard-step d-none">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase x-small">Paso 5 de 6</span>
                        <h4 class="fw-bold text-white mt-2 mb-1">Datos de Contacto del Paciente</h4>
                        <p class="text-muted small">Complete sus datos para agendar la cita y enviarle el comprobante.</p>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label class="small text-secondary fw-bold mb-1">Nombre Completo *</label>
                            <input type="text" name="client_name" id="wizard_client_name" class="form-control form-control-oled shadow-none" placeholder="Ej: Lionel Messi" required>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="small text-secondary fw-bold mb-1">DNI / Documento de Identidad *</label>
                            <input type="text" name="client_document" id="wizard_client_document" class="form-control form-control-oled shadow-none" placeholder="Ej: 38442991" required>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="small text-secondary fw-bold mb-1">Correo Electrónico *</label>
                            <input type="email" name="client_email" id="wizard_client_email" class="form-control form-control-oled shadow-none" placeholder="messi@correo.com" required>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="small text-secondary fw-bold mb-1">Teléfono Móvil (WhatsApp) *</label>
                            <input type="text" name="client_phone" id="wizard_client_phone" class="form-control form-control-oled shadow-none" placeholder="Ej: 1162452291" required>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-5">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevStep(4)"><i class="bi bi-arrow-left me-1"></i> Volver</button>
                        <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold" onclick="validateAndGoToStep6()">Siguiente <i class="bi bi-arrow-right ms-1"></i></button>
                    </div>
                </div>

                <!-- STEP 6: CONFIRMACIÓN Y PAGO -->
                <div id="step-6" class="wizard-step d-none">
                    <div class="text-center mb-4">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold text-uppercase x-small">Paso 6 de 6</span>
                        <h4 class="fw-bold text-white mt-2 mb-1">Resumen e Inicio de Reserva</h4>
                        <p class="text-muted small">Verifique sus datos y seleccione el método para finalizar la reserva.</p>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Tarjeta de Resumen -->
                        <div class="col-md-6 col-sm-12">
                            <div class="summary-card p-4 rounded-4 border border-secondary border-opacity-10 h-100 bg-dark bg-opacity-40">
                                <h5 class="fw-bold text-white mb-3 border-bottom border-secondary border-opacity-25 pb-2"><i class="bi bi-receipt me-2 text-primary"></i> Detalle del Turno</h5>
                                
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-secondary small">Paciente:</span>
                                        <span class="text-white fw-bold small" id="summary-patient"></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-secondary small">Documento:</span>
                                        <span class="text-white fw-bold small" id="summary-document"></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-secondary small">Especialidad:</span>
                                        <span class="badge bg-primary bg-opacity-15 text-primary small" id="summary-specialty"></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-secondary small">Profesional:</span>
                                        <span class="text-white fw-bold small" id="summary-doctor"></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-secondary small">Práctica / Servicio:</span>
                                        <span class="text-white fw-bold small" id="summary-service"></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-secondary small">Fecha y Hora:</span>
                                        <span class="text-success fw-bold small" id="summary-schedule"></span>
                                    </div>
                                    
                                    <hr class="border-secondary border-opacity-50 my-2">
                                    
                                    <div class="d-flex justify-content-between align-items-center pt-2">
                                        <span class="text-white fw-bold">Importe de Coseguro:</span>
                                        @if($onlinePrice > 0)
                                            <span class="text-success fw-extrabold fs-4">$ {{ number_format($onlinePrice, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-info fw-extrabold fs-4">Bonificado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Método de Pago / Simulación -->
                        <div class="col-md-6 col-sm-12">
                            <div class="payment-method-card p-4 rounded-4 border border-secondary border-opacity-10 h-100 bg-dark bg-opacity-40">
                                <h5 class="fw-bold text-white mb-3 border-bottom border-secondary border-opacity-25 pb-2"><i class="bi bi-shield-check me-2 text-success"></i> Confirmación Segura</h5>
                                
                                @if($onlinePrice > 0)
                                    <p class="x-small text-muted mb-4">Esta clínica cuenta con coseguro previo obligatorio. A los fines demostrativos de su presentación de mañana, simularemos el procesamiento seguro de su tarjeta.</p>
                                    
                                    <div class="gateway-selector d-flex flex-column gap-2 mb-4">
                                        <div class="gateway-pill p-3 rounded-3 border active d-flex align-items-center gap-3 cursor-pointer">
                                            <span class="fs-4">💳</span>
                                            <div class="text-start">
                                                <div class="small fw-bold text-white">Tarjeta de Crédito / Débito</div>
                                                <div class="x-small text-muted">Simulación instantánea con acreditación inmediata</div>
                                            </div>
                                            <span class="badge bg-success text-white rounded-pill ms-auto">✓</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-warning btn-lg py-3 rounded-pill fw-bold text-dark fs-5 shadow" onclick="triggerCheckoutSimulation()">
                                            <i class="bi bi-credit-card-2-front me-2"></i> CONFIRMAR Y SIMULAR PAGO
                                        </button>
                                    </div>
                                @else
                                    <p class="x-small text-muted mb-4">La clínica ofrece reserva directa online sin cobro previo. Al hacer clic a continuación, se procesará su turno de inmediato y se registrará en el panel de recepción de guardia.</p>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-warning btn-lg py-3 rounded-pill fw-bold text-dark fs-5 shadow">
                                            <i class="bi bi-calendar2-check-fill me-2"></i> CONFIRMAR Y AGENDAR TURNO
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-start mt-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevStep(5)"><i class="bi bi-arrow-left me-1"></i> Volver</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 6;
    
    // Almacenamiento temporal de datos seleccionados para el resumen
    let selectedSpecialty = "";
    let selectedDoctorName = "";
    let selectedDoctorSpecialty = "";
    let selectedServiceName = "";
    let selectedServiceCategory = "";
    let selectedDate = "{{ date('Y-m-d') }}";
    let selectedTime = "";

    function showStep(step) {
        document.querySelectorAll('.wizard-step').forEach(s => s.classList.add('d-none'));
        document.getElementById(`step-${step}`).classList.remove('d-none');
        
        // Actualizar indicadores
        document.querySelectorAll('.step-dot').forEach((dot, idx) => {
            if (idx + 1 < step) {
                dot.classList.add('completed');
                dot.classList.remove('active');
            } else if (idx + 1 === step) {
                dot.classList.remove('completed');
                dot.classList.add('active');
            } else {
                dot.classList.remove('completed');
                dot.classList.remove('active');
            }
        });
        
        // Actualizar barra de progreso
        const progressPct = ((step - 1) / (totalSteps - 1)) * 100;
        document.getElementById('wizard-progress').style.width = `${progressPct}%`;
        
        // Actualizar etiquetas
        document.querySelectorAll('.step-label').forEach((lbl, idx) => {
            if (idx + 1 === step) {
                lbl.classList.add('text-primary');
            } else {
                lbl.classList.remove('text-primary');
            }
        });

        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prevStep(step) {
        showStep(step);
    }

    function nextStepSpecialty(specialty) {
        selectedSpecialty = specialty;
        document.getElementById('selected-specialty-title').innerText = specialty;
        
        // Filtrar doctores
        document.querySelectorAll('.doctor-wizard-card-container').forEach(card => {
            const specialties = card.getAttribute('data-specialties').split(',');
            if (specialties.includes(specialty)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        showStep(2);
    }

    function nextStepDoctor(doctorId, doctorName, specialty) {
        document.getElementById('selected_doctor_id').value = doctorId;
        selectedDoctorName = doctorName;
        selectedDoctorSpecialty = specialty;
        
        showStep(3);
    }

    function nextStepService(serviceId, serviceName, category) {
        document.getElementById('selected_servicio_id').value = serviceId;
        selectedServiceName = serviceName;
        selectedServiceCategory = category;
        
        showStep(4);
    }

    function selectQuickDate(dateString, element) {
        document.querySelectorAll('.quick-day-pill').forEach(p => p.classList.remove('active'));
        element.classList.add('active');
        document.getElementById('wizard_fecha').value = dateString;
        selectedDate = dateString;
    }

    function updateSelectedDateLabel(dateVal) {
        document.querySelectorAll('.quick-day-pill').forEach(p => p.classList.remove('active'));
        selectedDate = dateVal;
        
        const pills = document.querySelectorAll('.quick-day-pill');
        for (let pill of pills) {
            if (pill.getAttribute('onclick').includes(dateVal)) {
                pill.classList.add('active');
                break;
            }
        }
    }

    function nextStepSchedule(timeSlot, element) {
        document.querySelectorAll('.slot-button').forEach(b => b.classList.remove('active'));
        element.classList.add('active');
        document.getElementById('selected_hora').value = timeSlot;
        selectedTime = timeSlot;
        
        const dateVal = document.getElementById('wizard_fecha').value;
        if (!dateVal) {
            Swal.fire({
                icon: 'warning',
                title: 'Por favor seleccione una fecha',
                text: 'Debe seleccionar una fecha antes de elegir su horario.',
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#2563eb'
            });
            return;
        }
        selectedDate = dateVal;
        
        showStep(5);
    }

    function validateAndGoToStep6() {
        const name = document.getElementById('wizard_client_name').value.trim();
        const documentVal = document.getElementById('wizard_client_document').value.trim();
        const email = document.getElementById('wizard_client_email').value.trim();
        const phone = document.getElementById('wizard_client_phone').value.trim();
        
        if (!name || !documentVal || !email || !phone) {
            Swal.fire({
                icon: 'error',
                title: 'Campos requeridos',
                text: 'Por favor complete todos los datos del paciente para continuar.',
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#2563eb'
            });
            return;
        }

        // Renderizar resumen en el Paso 6
        document.getElementById('summary-patient').innerText = name;
        document.getElementById('summary-document').innerText = documentVal;
        document.getElementById('summary-specialty').innerText = selectedSpecialty;
        document.getElementById('summary-doctor').innerText = selectedDoctorName;
        document.getElementById('summary-service').innerText = selectedServiceName;
        
        // Formatear fecha
        const dateParts = selectedDate.split('-');
        const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
        document.getElementById('summary-schedule').innerText = `${formattedDate} a las ${selectedTime} hs`;
        
        showStep(6);
    }

    function triggerCheckoutSimulation() {
        Swal.fire({
            title: '💳 Conectando con Pasarela...',
            html: 'Iniciando portal seguro de pago. Por favor espere.<br><div class="progress mt-3 bg-dark" style="height: 6px;"><div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 100%"></div></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            background: '#0f172a',
            color: '#fff',
            timer: 1800,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            document.getElementById('wizard-form').submit();
        });
    }
</script>
@endsection
