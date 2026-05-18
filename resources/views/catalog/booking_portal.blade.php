@extends('layouts.guest')

@php
    $config = $empresa->config ?? null;
    $primary   = '#2563eb'; // Azul Clínico Premium
    $secondary = '#10b981'; // Verde Éxito
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
@endphp

@section('content')
<style>
    :root {
        --bg-color: #080c14;
        --card-bg: #0f1626;
        --border-color: rgba(255, 255, 255, 0.08);
        --text-white: #ffffff;
        --text-secondary: #94a3b8;
        --clinical-blue: #2563eb;
        --clinical-green: #10b981;
    }

    body, html {
        background-color: var(--bg-color) !important;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: var(--text-white);
        margin: 0;
        padding: 0;
    }

    .glass-nav {
        background: rgba(15, 22, 38, 0.85);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid var(--border-color);
        padding: 18px 40px;
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
        box-shadow: 0 20px 45px rgba(0,0,0,0.4);
        overflow: hidden;
    }

    .card-header-booking {
        background: linear-gradient(135deg, var(--clinical-blue), #0b0f19);
        padding: 35px 30px;
        border-bottom: 1px solid var(--border-color);
    }

    /* FILTRO DE ESPECIALIDADES HORIZONTAL */
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .esp-badge {
        font-size: 0.95rem;
        font-weight: 700;
        padding: 10px 22px;
        border-radius: 100px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.03);
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .esp-badge.active {
        background: var(--clinical-blue) !important;
        color: white !important;
        border-color: var(--clinical-blue) !important;
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
    }

    /* TARJETAS DE DOCTORES (GRID SELECTOR) */
    .doctor-card {
        background: rgba(255, 255, 255, 0.02);
        border: 2px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .doctor-card:hover {
        border-color: rgba(37, 99, 235, 0.5);
        background: rgba(255,255,255,0.04);
        transform: translateY(-3px);
    }

    .doctor-card.selected {
        border-color: var(--clinical-blue) !important;
        background: rgba(37, 99, 235, 0.08) !important;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.15);
    }

    .doctor-avatar-circle {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        margin: 0 auto 12px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--clinical-blue), #4f46e5);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .doctor-card.selected .doctor-avatar-circle {
        background: linear-gradient(135deg, var(--clinical-green), var(--clinical-blue));
        border-color: var(--text-white);
    }

    /* TARJETAS DE SERVICIOS */
    .service-card {
        background: rgba(255, 255, 255, 0.02);
        border: 2px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .service-card:hover {
        border-color: rgba(16, 185, 129, 0.4);
        background: rgba(255,255,255,0.04);
    }

    .service-card.selected {
        border-color: var(--clinical-green) !important;
        background: rgba(16, 185, 129, 0.08) !important;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
    }

    /* GRILLA DE SLOTS HORARIOS */
    .slot-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 10px;
    }

    .slot-button {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.12);
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
        background: rgba(37, 99, 235, 0.1);
    }

    .slot-button.active {
        background: var(--clinical-blue) !important;
        color: white !important;
        border-color: var(--clinical-blue) !important;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .policy-alert {
        background-color: rgba(239, 68, 68, 0.08);
        border-left: 5px solid #ef4444;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 30px;
    }

    /* INPUTS ESTILO OLED */
    .form-control-oled {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 12px 16px !important;
        transition: all 0.3s ease;
    }

    .form-control-oled:focus {
        border-color: var(--clinical-blue) !important;
        box-shadow: 0 0 10px rgba(37, 99, 235, 0.2) !important;
    }

    .form-control-oled::placeholder {
        color: rgba(255, 255, 255, 0.35) !important;
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
        ● Reserva Online
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
            <span class="fs-1">⚠️</span>
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
            <h3 class="mb-2 fw-extrabold text-white text-uppercase tracking-wide"><i class="bi bi-calendar-plus me-2 text-warning"></i> Reservar Turno Clínico</h3>
            <p class="x-small mb-0 text-white opacity-75 fs-6">Seleccione su profesional, especialidad y horario de atención de forma rápida y segura.</p>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('catalog.booking.confirm', $empresa->slug) }}" method="POST">
                @csrf

                <!-- SECCIÓN 1: DATOS PERSONALES -->
                <h5 class="fw-bold text-white mb-3 text-uppercase tracking-wider fs-6 border-bottom border-secondary border-opacity-25 pb-2">
                    <span class="text-primary me-2">1.</span> Datos del Paciente
                </h5>
                <div class="row g-3 mb-5">
                    <div class="col-md-6 col-sm-12">
                        <label class="small text-secondary fw-bold mb-1">Nombre Completo *</label>
                        <input type="text" name="client_name" class="form-control form-control-oled shadow-none" placeholder="Ej: Lionel Messi" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="small text-secondary fw-bold mb-1">DNI / Documento *</label>
                        <input type="text" name="client_document" class="form-control form-control-oled shadow-none" placeholder="Ej: 38442991" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="small text-secondary fw-bold mb-1">Correo Electrónico *</label>
                        <input type="email" name="client_email" class="form-control form-control-oled shadow-none" placeholder="messi@correo.com" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="small text-secondary fw-bold mb-1">Teléfono Móvil (WhatsApp) *</label>
                        <input type="text" name="client_phone" class="form-control form-control-oled shadow-none" placeholder="Ej: 1162452291" required>
                    </div>
                </div>

                <!-- SECCIÓN 2: ESPECIALIDADES Y PROFESIONALES (INTERACTIVO SIN DROPDOWNS) -->
                <h5 class="fw-bold text-white mb-3 text-uppercase tracking-wider fs-6 border-bottom border-secondary border-opacity-25 pb-2">
                    <span class="text-success me-2">2.</span> Seleccione su Especialista
                </h5>

                <!-- Filtro de Especialidades Horizontal -->
                <div class="d-flex gap-2 overflow-x-auto pb-3 mb-3 scrollbar-none">
                    <div class="esp-badge active" onclick="filterSpecialty('all', this)">🔍 Todas las Especialidades</div>
                    @foreach($especialidades as $esp)
                        <div class="esp-badge" onclick="filterSpecialty('{{ $esp }}', this)">🩺 {{ $esp }}</div>
                    @endforeach
                </div>

                <!-- Grilla de Tarjetas de Doctores -->
                <input type="hidden" name="user_id" id="selected_doctor_id" required>
                <div class="row g-3 mb-5">
                    @foreach($doctors as $doctor)
                        @php
                            $doctorSpecialties = $doctor->profesionalConfig && is_array($doctor->profesionalConfig->especialidades) 
                                ? $doctor->profesionalConfig->especialidades 
                                : ['Médico Clínico'];
                            $mainSpecialty = $doctorSpecialties[0] ?? 'Médico Clínico';
                            $specialtiesCsv = implode(',', $doctorSpecialties);
                        @endphp
                        <div class="col-6 col-md-4 doctor-card-container" data-specialties="{{ $specialtiesCsv }}">
                            <div class="doctor-card h-100" onclick="selectDoctor(this, '{{ $doctor->id }}')">
                                <div class="doctor-avatar-circle">
                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                </div>
                                <h6 class="fw-bold text-white mb-1" style="font-size: 1.05rem;">Dr. {{ $doctor->name }}</h6>
                                <span class="badge bg-primary bg-opacity-25 text-primary rounded-pill px-3 py-1 font-monospace mt-1" style="font-size: 0.75rem;">
                                    {{ $mainSpecialty }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- SECCIÓN 3: SERVICIOS Y CONSULTAS (INTERACTIVO SIN DROPDOWNS) -->
                <h5 class="fw-bold text-white mb-3 text-uppercase tracking-wider fs-6 border-bottom border-secondary border-opacity-25 pb-2">
                    <span class="text-info me-2">3.</span> Tipo de Práctica o Consulta
                </h5>
                <input type="hidden" name="servicio_id" id="selected_servicio_id" required>
                <div class="row g-3 mb-5">
                    @foreach($services as $serv)
                        <div class="col-md-6 col-sm-12">
                            <div class="service-card d-flex align-items-center justify-content-between" onclick="selectService(this, '{{ $serv->id }}')">
                                <div class="text-start">
                                    <h6 class="fw-bold text-white mb-1" style="font-size: 1.05rem;">{{ $serv->nombre }}</h6>
                                    <span class="x-small text-secondary font-monospace" style="font-size: 0.8rem;">Categoría: {{ $serv->categoria }}</span>
                                </div>
                                <div class="badge bg-success bg-opacity-25 text-success rounded-pill px-3 py-1 fw-bold fs-6">
                                    ✓
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- SECCIÓN 4: FECHA Y SLOTS -->
                <h5 class="fw-bold text-white mb-3 text-uppercase tracking-wider fs-6 border-bottom border-secondary border-opacity-25 pb-2">
                    <span class="text-warning me-2">4.</span> Horario de Atención
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-sm-12">
                        <label class="small text-secondary fw-bold mb-1">Fecha de Consulta *</label>
                        <input type="date" name="fecha" class="form-control form-control-oled shadow-none" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="small text-secondary fw-bold mb-2">Slots Horarios Disponibles *</label>
                        <input type="hidden" name="hora" id="selected_hora" required>
                        
                        <div class="slot-grid">
                            @php
                                $slots = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];
                            @endphp
                            @foreach($slots as $s)
                                <div class="slot-button" onclick="selectSlot(this, '{{ $s }}')">{{ $s }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- BOTÓN ACCIÓN -->
                <div class="d-grid mt-5 pt-3">
                    @if($onlinePrice > 0)
                        <button type="submit" class="btn btn-warning btn-lg py-3 rounded-pill fw-bold text-dark fs-5 shadow" style="letter-spacing: 0.5px;">
                            <i class="bi bi-credit-card me-2"></i> CONFIRMAR Y PAGAR RESERVA ($ {{ number_format($onlinePrice, 2, ',', '.') }})
                        </button>
                    @else
                        <button type="submit" class="btn btn-warning btn-lg py-3 rounded-pill fw-bold text-dark fs-5 shadow" style="letter-spacing: 0.5px;">
                            <i class="bi bi-calendar-check me-2"></i> CONFIRMAR Y AGENDAR TURNO
                        </button>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // Filtrar doctores por especialidad
    function filterSpecialty(specialty, element) {
        document.querySelectorAll('.esp-badge').forEach(b => {
            b.classList.remove('active');
        });
        element.classList.add('active');

        document.querySelectorAll('.doctor-card-container').forEach(card => {
            const specialties = card.getAttribute('data-specialties').split(',');
            if (specialty === 'all' || specialties.includes(specialty)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Seleccionar Doctor
    function selectDoctor(element, doctorId) {
        document.querySelectorAll('.doctor-card').forEach(c => {
            c.classList.remove('selected');
        });
        element.classList.add('selected');
        document.getElementById('selected_doctor_id').value = doctorId;
    }

    // Seleccionar Servicio
    function selectService(element, serviceId) {
        document.querySelectorAll('.service-card').forEach(c => {
            c.classList.remove('selected');
        });
        element.classList.add('selected');
        document.getElementById('selected_servicio_id').value = serviceId;
    }

    // Seleccionar Horario
    function selectSlot(element, time) {
        document.querySelectorAll('.slot-button').forEach(btn => {
            btn.classList.remove('active');
        });
        element.classList.add('active');
        document.getElementById('selected_hora').value = time;
    }
</script>
@endsection
