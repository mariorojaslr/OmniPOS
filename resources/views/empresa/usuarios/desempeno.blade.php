@extends('layouts.empresa')

@php
    $config = auth()->user()->empresa->config;
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
@endphp

@section('styles')
<style>
    :root {
        --rr-gold: #d4af37;
        --rr-gold-glow: rgba(212, 175, 55, 0.3);
    }

    @if($modoOscuro)
    :root {
        --rr-dark-bg: #050505;
        --rr-card-bg: rgba(15, 15, 15, 0.9);
        --rr-border: rgba(212, 175, 55, 0.15);
    }
    body { background-color: var(--rr-dark-bg) !important; color: #e0e0e0 !important; }
    .oled-card { background: var(--rr-card-bg) !important; backdrop-filter: blur(20px); border: 1px solid var(--rr-border) !important; border-radius: 15px !important; }
    .stat-value { color: #fff; }
    .premium-table { background: transparent !important; color: #e0e0e0 !important; }
    .premium-table thead th { background: rgba(255, 255, 255, 0.05); color: var(--rr-gold); font-size: 0.7rem; border: none !important; }
    .premium-table tbody tr { border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
    @else
    .oled-card { background: #ffffff !important; border: 1px solid #e0e0e0 !important; border-radius: 15px !important; box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important; }
    .stat-value { color: var(--color-primario); font-weight: 800; }
    .premium-table thead th { background: #f8f9fa; color: #444; font-size: 0.7rem; border: none !important; }
    .badge-premium { background: rgba(var(--color-primario-rgb), 0.1); border: 1px solid var(--color-primario); color: var(--color-primario); }
    @endif

    .premium-header { border-left: 3px solid var(--rr-gold); padding-left: 15px; margin-bottom: 25px; }
    .stat-value { font-family: 'Inter', sans-serif; font-weight: 800; letter-spacing: -0.5px; }

    /* Score Circular Compacto */
    .score-container { position: relative; width: 110px; height: 110px; margin: 0 auto; }
    .score-svg { transform: rotate(-90deg); width: 110px; height: 110px; }
    .score-bg { fill: none; stroke: rgba(128, 128, 128, 0.1); stroke-width: 8; }
    .score-fill { fill: none; stroke: @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif; stroke-width: 8; stroke-linecap: round; }
    .score-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }

    .badge-premium {
        @if($modoOscuro)
        background: rgba(212, 175, 55, 0.1); border: 1px solid var(--rr-gold); color: var(--rr-gold);
        @endif
        font-weight: 700; text-transform: uppercase; font-size: 0.6rem; padding: 4px 10px; letter-spacing: 0.5px;
    }
    .x-small { font-size: 0.65rem; }
    .ls-1 { letter-spacing: 1px; }
</style>
@endsection

@section('content')
<div class="p-3 @if(!$modoOscuro) pt-1 @endif">
    {{-- ENCABEZADO COMPACTO --}}
    <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2 premium-header">
        <div>
            <h3 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif">
                DESEMPEÑO <span class="@if($modoOscuro) gold-text @else text-primary @endif">OPERATIVO</span>
            </h3>
            <p class="text-muted mb-0 x-small">
                <i class="bi bi-person-badge me-1"></i>Analizando a: <span class="@if($modoOscuro) text-white @else text-dark fw-bold @endif">{{ $user->name }}</span> 
                <span class="mx-2">|</span> {{ now()->translatedFormat('F Y') }}
            </p>
        </div>
        <div class="d-flex gap-2">
            @if($user->activo)
                <span class="badge-premium rounded-pill"><i class="bi bi-dot me-1"></i>SISTEMA ACTIVO</span>
            @endif
            @php $enTurno = $asistencias->first() && !$asistencias->first()->salida; @endphp
            @if($enTurno)
                <span class="badge bg-primary px-3 py-1 rounded-pill shadow-sm x-small fw-bold"> EN TURNO </span>
            @endif
        </div>
    </div>

    {{-- KPIs COMPACTOS --}}
    <div class="row g-3 mb-4">
        {{-- Ventas --}}
        <div class="col-xl-3 col-md-6">
            <div class="card oled-card h-100 border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-info h5 mb-0"><i class="bi bi-lightning-charge"></i></div>
                        <span class="text-muted x-small fw-bold ls-1">VENTAS NETAS</span>
                    </div>
                    <div class="h4 mb-1 stat-value">$ {{ number_format($ventas->monto ?? 0, 2, ',', '.') }}</div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info-subtle text-info x-small me-2">{{ $ventas->cant ?? 0 }} Op.</span>
                        <div class="progress flex-grow-1 @if($modoOscuro) bg-dark @else bg-light-subtle @endif" style="height: 3px;">
                            <div class="progress-bar bg-info" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Asistencia --}}
        <div class="col-xl-3 col-md-6">
            <div class="card oled-card h-100 border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-success h5 mb-0"><i class="bi bi-calendar2-check"></i></div>
                        <span class="text-muted x-small fw-bold ls-1">ASISTENCIA</span>
                    </div>
                    <div class="h4 mb-1 stat-value text-success">{{ number_format($porcentajeAsistencia, 0) }}%</div>
                    <div class="text-muted x-small">
                        En <b class="@if($modoOscuro) text-white @else text-dark @endif">{{ $diasPresente }}</b> / 22 jornadas.
                    </div>
                </div>
            </div>
        </div>

        {{-- Horas --}}
        <div class="col-xl-3 col-md-6">
            <div class="card oled-card h-100 border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-primary h5 mb-0"><i class="bi bi-stopwatch"></i></div>
                        <span class="text-muted x-small fw-bold ls-1">HORAS TOTALES</span>
                    </div>
                    <div class="h4 mb-1 stat-value @if($modoOscuro) text-white @else text-primary @endif">{{ number_format($totalHoras, 1) }}h</div>
                    <div class="text-muted x-small">Tiempo total acumulado.</div>
                </div>
            </div>
        </div>

        {{-- Diferencia de Caja --}}
        <div class="col-xl-3 col-md-6">
            <div class="card oled-card h-100 border-0 {{ $faltantes > 0 ? 'border border-danger' : '' }}">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="{{ $faltantes > 0 ? 'text-danger' : 'text-warning' }} h5 mb-0"><i class="bi bi-currency-dollar"></i></div>
                        <span class="text-muted x-small fw-bold ls-1">DIFERENCIA CAJA</span>
                    </div>
                    <div class="h4 mb-1 stat-value {{ $faltantes > 0 ? 'text-danger' : '' }}">
                        $ {{ number_format($faltantes, 2, ',', '.') }}
                    </div>
                    <div class="text-muted x-small">Diferencia detectada cierres.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- HISTORIAL TURNOS --}}
        <div class="col-lg-8">
            <div class="card oled-card h-100 border-0">
                <div class="card-header border-0 bg-transparent py-3 px-4">
                    <h6 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif">
                        <i class="bi bi-activity text-primary me-2"></i>Historial de Turnos
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table premium-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Fecha</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Duración</th>
                                    <th class="text-end pe-4">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asistencias->take(8) as $reg)
                                <tr>
                                    <td class="ps-4 fw-bold @if($modoOscuro) text-white @else text-dark @endif small">{{ $reg->entrada->translatedFormat('d M, Y') }}</td>
                                    <td class="text-success small">{{ $reg->entrada->format('H:i') }}</td>
                                    <td class="small">
                                        @if($reg->salida) <span class="text-danger">{{ $reg->salida->format('H:i') }}</span> @else <span class="text-primary italic">...</span> @endif
                                    </td>
                                    <td class="fw-bold small">
                                        @if($reg->salida) {{ number_format($reg->entrada->diffInMinutes($reg->salida) / 60, 1) }} hs @else -- @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($reg->salida) <span class="badge-premium rounded-pill">Cerrado</span> @else <span class="badge bg-primary rounded-pill x-small px-3">Activo</span> @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted x-small">Sin registros de actividad.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- SCORE OPERADOR --}}
        <div class="col-lg-4">
            <div class="card oled-card h-100 border-0">
                <div class="card-header border-0 bg-transparent py-3 px-4">
                    <h6 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif">
                        <i class="bi bi-stars text-warning me-2"></i>Puntuación
                    </h6>
                </div>
                <div class="card-body text-center p-3">
                    @php $dashArray = 345; $dashOffset = $dashArray - ($dashArray * $score) / 100; @endphp
                    <div class="score-container mb-3">
                        <svg class="score-svg" viewBox="0 0 120 120">
                            <circle class="score-bg" cx="60" cy="60" r="55"></circle>
                            <circle class="score-fill" cx="60" cy="60" r="55" style="stroke-dasharray: {{ $dashArray }}; stroke-dashoffset: {{ $dashOffset }};"></circle>
                        </svg>
                        <div class="score-text">
                            <div class="h2 fw-bold @if($modoOscuro) text-white @else text-dark @endif mb-0">{{ $score }}</div>
                            <div class="text-muted x-small fw-bold">PUNTOS</div>
                        </div>
                    </div>

                    <div class="p-2 mb-3 rounded-3 @if($modoOscuro) bg-white bg-opacity-5 @else bg-light @endif">
                        <h6 class="text-primary x-small fw-bold mb-2 text-uppercase ls-1">Métricas Clave</h6>
                        <div class="d-flex justify-content-between mb-1 x-small">
                            <span class="text-muted">Asistencia</span>
                            <span class="fw-bold">{{ number_format($porcentajeAsistencia, 0) }}%</span>
                        </div>
                        <div class="d-flex justify-content-between x-small">
                            <span class="text-muted">Integridad Caja</span>
                            <span class="{{ $faltantes == 0 ? 'text-success' : 'text-danger' }} fw-bold">{{ $faltantes == 0 ? 'Perfecta' : 'Revisar' }}</span>
                        </div>
                    </div>

                    <p class="text-muted x-small italic mb-0">
                        @if($score >= 85) "Operador de nivel superior. Consistencia excepcional." @elseif($score >= 60) "Rendimiento estándar según parámetros." @else "Estado crítico. Requiere revisión de eficiencia." @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .ls-1 { letter-spacing: 2px; }
    .x-small { font-size: 0.7rem; }
    .transition-all { transition: all 0.3s ease; }
    .transition-all:hover { transform: translateX(-5px); }
    .animate-pulse { animation: pulse 1.5s infinite; }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.3; }
        100% { opacity: 1; }
    }
</style>

@endsection
