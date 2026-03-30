@extends('layouts.empresa')

@php
    // Detectar modo oscuro desde la configuración de la empresa
    $config = auth()->user()->empresa->config;
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
@endphp

@section('styles')
<style>
    /* VARIABLES DE DISEÑO */
    :root {
        --rr-gold: #d4af37;
        --rr-gold-glow: rgba(212, 175, 55, 0.3);
    }

    /* 🌙 ESTILOS PARA MODO OSCURO (OLED) */
    @if($modoOscuro)
    :root {
        --rr-dark-bg: #050505;
        --rr-card-bg: rgba(15, 15, 15, 0.9);
        --rr-border: rgba(212, 175, 55, 0.15);
    }

    body {
        background-color: var(--rr-dark-bg) !important;
        color: #e0e0e0 !important;
    }

    .oled-card {
        background: var(--rr-card-bg) !important;
        backdrop-filter: blur(20px);
        border: 1px solid var(--rr-border) !important;
        border-radius: 20px !important;
    }

    .stat-value {
        background: linear-gradient(135deg, #fff 0%, #a0a0a0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .premium-table {
        background: transparent !important;
        color: #e0e0e0 !important;
    }

    .premium-table thead th { background: rgba(255, 255, 255, 0.05); }
    .premium-table tbody tr { border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
    
    /* FIX: Forzar que el fondo de la tabla sea oscuro si el global falla */
    .table-responsive, .table { background-color: transparent !important; }
    .table td, .table th { border-color: rgba(255,255,255,0.05) !important; color: #eee !important; }

    @else
    /* ☀️ ESTILOS PARA MODO CLARO (VÍA NORMAL) */
    .oled-card {
        background: #ffffff !important;
        border: 1px solid #e0e0e0 !important;
        border-radius: 20px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;
    }

    .stat-value {
        color: var(--color-primario);
        font-weight: 800;
    }

    .gold-text {
        color: var(--color-primario) !important;
    }

    .premium-table thead th {
        background: #f8f9fa;
        color: #333;
    }
    
    .badge-premium {
        background: rgba(var(--color-primario-rgb), 0.1);
        border: 1px solid var(--color-primario);
        color: var(--color-primario);
    }
    @endif

    /* COMUNES */
    .premium-header {
        border-left: 4px solid var(--rr-gold);
        padding-left: 20px;
        margin-bottom: 40px;
    }

    .stat-value {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .gold-text-accent {
        color: var(--rr-gold) !important;
    }

    /* Score Circular */
    .score-container { position: relative; width: 150px; height: 150px; margin: 0 auto; }
    .score-svg { transform: rotate(-90deg); width: 150px; height: 150px; }
    .score-bg { fill: none; stroke: rgba(128, 128, 128, 0.1); stroke-width: 10; }
    .score-fill { fill: none; stroke: @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif; stroke-width: 10; stroke-linecap: round; transition: stroke-dasharray 1s ease-out; }
    .score-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }

    .badge-premium {
        @if($modoOscuro)
        background: rgba(212, 175, 55, 0.1);
        border: 1px solid var(--rr-gold);
        color: var(--rr-gold);
        @endif
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        padding: 5px 12px;
    }

    .animate-pulse-gold { animation: pulse-gold 2s infinite; }
    @keyframes pulse-gold {
        0% { box-shadow: 0 0 0 0 @if($modoOscuro) rgba(212, 175, 55, 0.4) @else rgba(var(--color-primario-rgb), 0.4) @endif; }
        70% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); }
        100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
    }
</style>
@endsection

@section('content')

<div class="main-content p-4">
    {{-- ENCABEZADO --}}
    <div class="mb-5 d-flex align-items-center justify-content-between flex-wrap gap-3 premium-header">
        <div>
            <h1 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif display-5">
                PERformance <span class="@if($modoOscuro) gold-text-accent @else text-primary @endif">HUB</span>
            </h1>
            <p class="text-muted mb-0 mt-2">
                <i class="bi bi-shield-check me-2"></i>Análisis Operativo: <span class="@if($modoOscuro) text-white @else text-dark fw-bold @endif">{{ $user->name }}</span> 
                <span class="mx-2">|</span> 
                {{ now()->translatedFormat('F Y') }}
            </p>
        </div>
        <div class="d-flex gap-2">
            @if($user->activo)
                <span class="badge-premium rounded-pill">
                    <i class="bi bi-dot me-1"></i>System Active
                </span>
            @endif
            
            @php $enTurno = $asistencias->first() && !$asistencias->first()->salida; @endphp
            @if($enTurno)
                <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm animate-pulse-gold">
                    <i class="bi bi-clock-history me-1"></i>ON DUTY
                </span>
            @endif
        </div>
    </div>

    {{-- KPIs PRINCIPALES --}}
    <div class="row g-4 mb-5">
        
        {{-- Total Ventas --}}
        <div class="col-xl-3 col-md-6">
            <div class="card oled-card h-100 shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-info fs-3">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <span class="text-muted small">NET SALES</span>
                    </div>
                    <div class="stat-value display-6">$ {{ number_format($ventas->monto ?? 0, 2, ',', '.') }}</div>
                    <div class="mt-3 d-flex align-items-center">
                        <span class="badge bg-info-subtle text-info me-2">{{ $ventas->cant ?? 0 }} Tx</span>
                        <div class="progress flex-grow-1 @if($modoOscuro) bg-dark @else bg-light @endif" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Asistencia --}}
        <div class="col-xl-3 col-md-6">
            <div class="card oled-card h-100 shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-success fs-3">
                            <i class="bi bi-calendar2-check"></i>
                        </div>
                        <span class="text-muted small">ATTENDANCE</span>
                    </div>
                    <div class="stat-value display-6">{{ number_format($porcentajeAsistencia, 0) }}%</div>
                    <div class="mt-3 text-muted small">
                        Presente en <b class="@if($modoOscuro) text-white @else text-dark @endif">{{ $diasPresente }}</b> / 22 jornadas laborales.
                    </div>
                </div>
            </div>
        </div>

        {{-- Horas --}}
        <div class="col-xl-3 col-md-6">
            <div class="card oled-card h-100 shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-primary fs-3">
                            <i class="bi bi-stopwatch"></i>
                        </div>
                        <span class="text-muted small">TOTAL HOURS</span>
                    </div>
                    <div class="stat-value display-6 @if($modoOscuro) text-white @else text-primary @endif">{{ number_format($totalHoras, 1) }}h</div>
                    <div class="mt-3 text-muted small">
                        Tiempo total acumulado en el sistema pos.
                    </div>
                </div>
            </div>
        </div>

        {{-- Faltantes --}}
        <div class="col-xl-3 col-md-6">
            <div class="card oled-card h-100 shadow-lg border-0 {{ $faltantes > 0 ? 'border border-danger' : '' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="{{ $faltantes > 0 ? 'text-danger' : ($modoOscuro ? 'gold-text-accent' : 'text-warning') }} fs-3">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <span class="text-muted small">CASH DELTA</span>
                    </div>
                    <div class="stat-value display-6 {{ $faltantes > 0 ? 'text-danger' : '' }}">
                        $ {{ number_format($faltantes, 2, ',', '.') }}
                    </div>
                    <div class="mt-3 text-muted small">
                        Diferencias de caja detectadas al cierre.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-5">
        
        {{-- TABLA DE ACTIVIDAD --}}
        <div class="col-lg-8">
            <div class="card oled-card h-100 border-0 shadow-lg">
                <div class="card-header border-0 bg-transparent p-4">
                    <h5 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif">
                        <i class="bi bi-activity @if($modoOscuro) gold-text-accent @else text-primary @endif me-3"></i>Historial de Turnos
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table premium-table align-middle mb-0 @if($modoOscuro) table-dark @endif">
                            <thead>
                                <tr>
                                    <th class="ps-4">Timestamp</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Net Duration</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="border-0">
                                @forelse($asistencias->take(8) as $reg)
                                <tr class="border-0">
                                    <td class="ps-4 border-0">
                                        <span class="@if($modoOscuro) text-white @else text-dark @endif">{{ $reg->entrada->translatedFormat('d M, Y') }}</span>
                                    </td>
                                    <td class="border-0"><span class="text-success">{{ $reg->entrada->format('H:i') }}</span></td>
                                    <td class="border-0">
                                        @if($reg->salida)
                                            <span class="text-danger">{{ $reg->salida->format('H:i') }}</span>
                                        @else
                                            <span class="text-primary italic animate-pulse">...</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold @if($modoOscuro) text-white @else text-dark @endif border-0">
                                        @if($reg->salida)
                                            {{ number_format($reg->entrada->diffInMinutes($reg->salida) / 60, 1) }} hs
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 border-0">
                                        @if($reg->salida)
                                            <span class="badge-premium rounded-pill px-3">Closed</span>
                                        @else
                                            <span class="badge bg-primary rounded-pill px-3">Active</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted border-0">No activity logs found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- PRODUCTIVIDAD SCORE --}}
        <div class="col-lg-4">
            <div class="card oled-card h-100 shadow-lg border-0 @if($modoOscuro) border-rr-gold @endif">
                <div class="card-header border-0 bg-transparent p-4">
                    <h5 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif">
                        <i class="bi bi-stars @if($modoOscuro) gold-text-accent @else text-warning @endif me-3"></i>Operator Score
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    
                    @php
                        $dashArray = 440; // 2 * PI * r
                        $dashOffset = $dashArray - ($dashArray * $score) / 100;
                    @endphp

                    <div class="score-container mb-4">
                        <svg class="score-svg">
                            <circle class="score-bg" cx="75" cy="75" r="70"></circle>
                            <circle class="score-fill" cx="75" cy="75" r="70" 
                                style="stroke-dasharray: {{ $dashArray }}; stroke-dashoffset: {{ $dashOffset }};">
                            </circle>
                        </svg>
                        <div class="score-text">
                            <div class="display-5 fw-bold @if($modoOscuro) text-white @else text-dark @endif stat-value">{{ $score }}</div>
                            <div class="@if($modoOscuro) gold-text-accent @else text-muted @endif fw-bold x-small">POINTS</div>
                        </div>
                    </div>

                    <div class="p-3 mb-4 rounded-4" style="background: @if($modoOscuro) rgba(255,255,255,0.03) @else #f8f9fa @endif;">
                        <h6 class="@if($modoOscuro) gold-text-accent @else text-primary @endif small fw-bold mb-3 text-uppercase ls-1">Core Metrics</h6>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Attendance Precision</span>
                            <span class="@if($modoOscuro) text-white @else text-dark @endif">{{ number_format($porcentajeAsistencia, 0) }}%</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Sales Impact</span>
                            <span class="@if($modoOscuro) text-white @else text-dark @endif">High</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Cash Integrity</span>
                            <span class="{{ $faltantes == 0 ? 'text-success' : 'text-danger' }}">
                                {{ $faltantes == 0 ? 'Perfect' : 'Review Required' }}
                            </span>
                        </div>
                    </div>

                    <div class="text-start">
                        <div class="mb-2"><i class="bi bi-chat-right-quote @if($modoOscuro) gold-text-accent @else text-primary @endif me-2"></i><span class="small fw-bold @if($modoOscuro) text-white @else text-dark @endif">SYSTEM FEEDBACK:</span></div>
                        <p class="text-muted small italic">
                            @if($score >= 85)
                                "Top Tier Operator. Demonstrates exceptional consistency and sales conversion results."
                            @elseif($score >= 60)
                                "Standard Performance. Operates within baseline parameters."
                            @else
                                "Critical Status. Performance metrics are below minimum efficiency."
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ACCIONES --}}
    <div class="d-flex align-items-center justify-content-between mt-5">
        <a href="{{ route('empresa.usuarios.index') }}" class="btn @if($modoOscuro) btn-dark border-secondary @else btn-outline-secondary @endif px-5 py-3 rounded-pill transition-all">
            <i class="bi bi-arrow-left me-2"></i>Back to Control Center
        </a>
        <button onclick="window.print()" class="btn btn-outline-warning px-4 py-3 rounded-pill">
            <i class="bi bi-printer me-2"></i>Export Analysis
        </button>
    </div>
</div>

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
