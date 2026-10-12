@php
    $isCatalog = true;
    $config = $empresa->config;
    $primary = $config?->color_primary ?? '#3b82f6';
    $secondary = $config?->color_secondary ?? '#10b981';
    $logo = $config ? $config->logo_url : asset('images/logo_omnipos.png');
    $esOdonto = $empresa->isOdontologica();
    $espacioLabel = $empresa->getEspacioLabel();

    $estado = $turno->estado_cola ?? 'pendiente';
    $estadoLabels = [
        'pendiente' => ['label' => 'Turno Agendado', 'desc' => 'Te esperamos el día de tu cita.', 'color' => '#3b82f6', 'icon' => '📅'],
        'sala_espera' => ['label' => 'En Sala de Espera', 'desc' => 'Tu arribo fue registrado. En breve el profesional te llamará.', 'color' => '#10b981', 'icon' => '🛋️'],
        'llamado' => ['label' => '¡Te Estamos Llamando!', 'desc' => 'Por favor, ingresá al ' . $espacioLabel . ' (' . ($turno->user?->consultorio ?? 'Asignado') . ').', 'color' => '#f59e0b', 'icon' => '🔊'],
        'en_consulta' => ['label' => 'En Consulta / Atención', 'desc' => 'Estás siendo atendido en este momento.', 'color' => '#8b5cf6', 'icon' => '🩺'],
        'finalizado' => ['label' => 'Atención Finalizada', 'desc' => '¡Gracias por confiar en nosotros! Esperamos que tu experiencia haya sido excelente.', 'color' => '#64748b', 'icon' => '✅'],
    ];

    $estadoActual = $estadoLabels[$estado] ?? $estadoLabels['pendiente'];

    // Teléfono de WhatsApp de la clínica
    $clinicPhone = preg_replace('/[^0-9]/', '', $empresa->telefono ?? '');
    if (strlen($clinicPhone) === 10) $clinicPhone = '549' . $clinicPhone;
    $wspHelpLink = !empty($clinicPhone) ? 'https://api.whatsapp.com/send?phone=' . $clinicPhone . '&text=' . urlencode("Hola {$empresa->nombre_comercial}, me comunico por mi turno #{$turno->id}.") : '#';
@endphp

@extends('layouts.guest')

@section('content')
<style>
    :root {
        --bg-oled: #030712;
        --card-oled: #0b0f19;
        --border-oled: rgba(255, 255, 255, 0.08);
        --primary-accent: {{ $primary }};
        --secondary-accent: {{ $secondary }};
    }

    body {
        background-color: var(--bg-oled) !important;
        color: #f8fafc;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        min-height: 100vh;
    }

    .tracking-card {
        background: var(--card-oled);
        border: 1px solid var(--border-oled);
        border-radius: 28px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(20px);
    }

    .status-beacon {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 9999px;
        font-weight: 800;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .animate-pulse-beacon {
        animation: pulseBeacon 1.5s infinite;
    }

    @keyframes pulseBeacon {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.85; box-shadow: 0 0 25px {{ $estadoActual['color'] }}; }
    }

    .info-tile {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-oled);
        border-radius: 20px;
        padding: 18px;
    }

    .btn-wsp {
        background: #25d366;
        color: #ffffff;
        font-weight: 800;
        border: none;
        border-radius: 9999px;
        padding: 14px 24px;
        transition: all 0.3s;
    }
    .btn-wsp:hover {
        background: #20bd5a;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
    }
</style>

<div class="container py-4 py-md-5" style="max-width: 650px;">
    {{-- LOGO Y NOMBRE DE LA CLÍNICA --}}
    <div class="text-center mb-4">
        <a href="{{ route('catalog.booking', $empresa->slug) }}" class="text-decoration-none">
            <img src="{{ $logo }}" alt="{{ $empresa->nombre_comercial }}" style="max-height: 55px;" class="mb-2">
            <h4 class="fw-extrabold text-white mb-0">{{ $empresa->nombre_comercial }}</h4>
            <span class="badge bg-primary bg-opacity-25 text-primary rounded-pill px-3 py-1 font-monospace x-small">
                {{ $esOdonto ? '🦷 Centro Odontológico Especializado' : '🏥 Portal del Paciente' }}
            </span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 text-success rounded-4 mb-4 text-center">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- TARJETA PRINCIPAL DE SEGUIMIENTO EN VIVO --}}
    <div class="tracking-card p-4 p-md-5 mb-4">
        {{-- ESTADO EN VIVO --}}
        <div class="text-center mb-4 pb-3 border-bottom border-white border-opacity-10">
            <div class="status-beacon {{ $estado === 'llamado' ? 'animate-pulse-beacon' : '' }} mb-3" style="background: {{ $estadoActual['color'] }}20; color: {{ $estadoActual['color'] }}; border: 1px solid {{ $estadoActual['color'] }}40;">
                <span class="fs-5">{{ $estadoActual['icon'] }}</span>
                <span>{{ $estadoActual['label'] }}</span>
            </div>

            <h5 class="text-white fw-bold mb-1">{{ $estadoActual['desc'] }}</h5>
            <small class="text-muted">Actualización en tiempo real — Podés dejar esta pantalla abierta en tu teléfono.</small>
        </div>

        {{-- DATOS DEL TURNO --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="info-tile h-100">
                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Fecha y Horario</small>
                    <div class="fw-bold text-white fs-6">
                        📅 {{ \Carbon\Carbon::parse($turno->fecha)->locale('es')->isoFormat('D [de] MMMM') }}
                    </div>
                    <div class="fw-extrabold text-primary fs-5 mt-1 font-monospace">
                        ⏰ {{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }} hs
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="info-tile h-100">
                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">{{ $espacioLabel }}</small>
                    <div class="fw-bold text-warning fs-6">
                        📍 {{ $turno->user?->consultorio ?? 'Por asignar' }}
                    </div>
                    <small class="text-muted d-block mt-1">Sala de atención</small>
                </div>
            </div>

            <div class="col-12">
                <div class="info-tile">
                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Profesional Asignado</small>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-25 text-primary d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px;">
                            {{ substr($turno->user?->name ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <h6 class="text-white fw-bold mb-0">Dr/a. {{ $turno->user?->name ?? 'Médico Staff' }}</h6>
                            <span class="x-small text-muted">{{ $turno->servicio?->nombre ?? ($esOdonto ? 'Consulta Odontológica' : 'Consulta Médica') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="info-tile">
                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Paciente</small>
                    <div class="fw-bold text-white">{{ $turno->cliente?->name ?? $turno->cliente_nombre_manual }}</div>
                    <small class="text-muted">DNI: {{ $turno->cliente?->document ?? 'Registrado' }} | Tel: {{ $turno->cliente?->phone ?? 'Sin teléfono' }}</small>
                </div>
            </div>
        </div>

        {{-- BOTÓN DE CONFIRMACIÓN DE ASISTENCIA --}}
        @if($turno->estado !== 'confirmado')
            <form action="{{ route('turno.confirmar-asistencia', $turno->id) }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="btn btn-warning btn-lg w-100 py-3 rounded-pill fw-bold text-dark shadow">
                    <i class="bi bi-check2-circle me-2"></i> CONFIRMAR MI ASISTENCIA AHORA
                </button>
            </form>
        @else
            <div class="p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-25 text-center text-success mb-4 fw-bold">
                <i class="bi bi-check-circle-fill me-2"></i> ¡Turno confirmado! Tu lugar está reservado en la agenda.
            </div>
        @endif

        {{-- RECOMENDACIONES PREVIAS --}}
        <div class="p-3 rounded-4 bg-white bg-opacity-05 border border-white border-opacity-10 mb-4">
            <h6 class="fw-bold text-white mb-2" style="font-size: 0.85rem;"><i class="bi bi-lightbulb-fill text-warning me-2"></i>Pautas para tu llegada:</h6>
            <ul class="text-muted x-small mb-0 ps-3">
                <li>Presentate 10 minutos antes en recepción con tu documento de identidad.</li>
                @if($esOdonto)
                    <li>Se sugiere haber realizado higiene dental previa a la consulta.</li>
                    <li>Si disponés de radiografías panorámicas o estudios previos, podés traerlos.</li>
                @else
                    <li>Traé con vos tus estudios médicos anteriores o carnet de obra social.</li>
                @endif
            </ul>
        </div>

        {{-- BOTÓN DE WHATSAPP CON LA CLÍNICA --}}
        <div class="d-grid gap-2">
            @if(!empty($clinicPhone))
                <a href="{{ $wspHelpLink }}" target="_blank" class="btn btn-wsp text-center">
                    <i class="bi bi-whatsapp me-2 fs-5"></i> CONTACTAR A LA CLÍNICA POR WHATSAPP
                </a>
            @endif
            <a href="{{ route('catalog.booking', $empresa->slug) }}" class="btn btn-outline-secondary rounded-pill py-2 text-muted text-decoration-none small">
                Reservar otro turno o volver
            </a>
        </div>
    </div>

    {{-- FOOTER DISCRETO --}}
    <div class="text-center text-muted x-small">
        <p class="mb-1">© {{ date('Y') }} {{ $empresa->nombre_comercial }} — Sistema de Salud Cloud</p>
        <p class="text-white text-opacity-25 mb-0">ID de Referencia: #{{ $turno->id }}</p>
    </div>
</div>

<script>
    // Recargar estado automáticamente cada 30 segundos si está esperando o en cola
    const estadoActual = '{{ $estado }}';
    if (estadoActual === 'sala_espera' || estadoActual === 'llamado') {
        setTimeout(function() {
            window.location.reload();
        }, 20000); // 20s
    }
</script>
@endsection
