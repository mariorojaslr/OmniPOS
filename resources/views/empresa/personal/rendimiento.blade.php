@extends('layouts.empresa')

@php
    // Detectar modo oscuro desde la configuración de la empresa
    $config = auth()->user()->empresa->config;
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
@endphp

@section('styles')
<style>
    /* VARIABLES GLOBALES */
    :root {
        --rr-gold: #d4af37;
        --rr-gold-glow: rgba(212, 175, 55, 0.3);
    }

    /* 🌙 MODO OSCURO (OLED AESTHETIC) */
    @if($modoOscuro)
    :root {
        --rr-dark-bg: #050505;
        --rr-card-bg: rgba(15, 15, 15, 0.92);
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

    .top-operator-card {
        background: linear-gradient(135deg, #0a0a0a 0%, #151515 100%) !important;
        border: 1px solid var(--rr-gold) !important;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.8), 0 0 20px rgba(212, 175, 55, 0.1);
    }

    .premium-table {
        background: transparent !important;
        color: #e0e0e0 !important;
    }

    .premium-table thead th {
        background: rgba(255, 255, 255, 0.05);
        color: var(--rr-gold);
    }

    .premium-table tbody tr:hover {
        background: rgba(212, 175, 55, 0.05) !important;
    }

    /* FIX: No more white blocks in dark mode tables */
    .table-responsive, .table { background: transparent !important; }
    .table td, .table th { border-color: rgba(255,255,255,0.05) !important; color: #eee !important; }

    .gold-text {
        color: var(--rr-gold) !important;
        text-shadow: 0 0 10px var(--rr-gold-glow);
    }

    @else
    /* ☀️ MODO CLARO (VÍA NORMAL) */
    .oled-card {
        background: #ffffff !important;
        border: 1px solid #eef0f2 !important;
        border-radius: 20px !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03) !important;
    }

    .top-operator-card {
        background: #fff !important;
        border: 2px solid var(--color-primario) !important;
        box-shadow: 0 10px 30px rgba(var(--color-primario-rgb), 0.05);
    }

    .premium-table thead th {
        background: #f8fbff;
        color: var(--color-primario);
    }

    .gold-text {
        color: var(--color-primario) !important;
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

    .avatar-circle {
        width: 45px; height: 45px;
        border: 2px solid @if($modoOscuro) var(--rr-border) @else #eee @endif;
        background: @if($modoOscuro) #111 @else #f8f9fa @endif;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        color: @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif;
        font-weight: 800;
    }

    .btn-view-desempeno {
        background: @if($modoOscuro) rgba(212, 175, 55, 0.1) @else rgba(var(--color-primario-rgb), 0.05) @endif;
        border: 1px solid @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif;
        color: @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif;
        font-weight: 600;
        border-radius: 12px;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }

    .btn-view-desempeno:hover {
        background: @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif;
        color: #fff;
        @if($modoOscuro) color: #000; box-shadow: 0 0 15px var(--rr-gold-glow); @endif
    }
</style>
@endsection

@section('content')

<div class="p-4">
    {{-- ENCABEZADO --}}
    <div class="mb-5 d-flex align-items-center justify-content-between flex-wrap gap-3 premium-header">
        <div>
            <h1 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif display-5">
                SYSTEM <span class="gold-text">LEADERS</span>
            </h1>
            <p class="text-muted mb-0 mt-2">
                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Ranking de productividad operativa — Período: {{ now()->translatedFormat('F Y') }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn @if($modoOscuro) btn-dark border-secondary @else btn-outline-secondary @endif px-4 rounded-pill">
                <i class="bi bi-clock-history me-2"></i>Global History
            </button>
        </div>
    </div>

    {{-- TOP OPERADOR DEL MES --}}
    @php
        $mejorOperador = $empleados->first();
    @endphp
    @if($mejorOperador && $mejorOperador->total_mes > 0)
    <div class="card oled-card top-operator-card mb-5">
        <div class="row g-0">
            <div class="col-md-8 p-5 d-flex flex-column justify-content-center">
                <div class="badge @if($modoOscuro) bg-primary-subtle text-primary border border-primary @else bg-primary text-white @endif px-3 py-1 mb-3 rounded-pill d-inline-block w-fit-content" style="width: fit-content; font-size: 0.7rem; font-weight: 800; letter-spacing: 2px;">ELITE STATUS</div>
                <h2 class="fw-bold mb-1 @if($modoOscuro) text-white @else text-dark @endif display-4 stat-value">{{ $mejorOperador->name }}</h2>
                <p class="text-muted mb-4 fs-5">Liderando el ranking de ventas con una efectividad excepcional.</p>
                <div class="row g-4 mt-2">
                    <div class="col-auto">
                        <div class="stat-value display-6 gold-text">$ {{ number_format($mejorOperador->total_mes, 2, ',', '.') }}</div>
                        <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Facturación Mensual</small>
                    </div>
                    <div class="col-auto border-start border-secondary ps-4">
                        <div class="stat-value display-6 @if($modoOscuro) text-white @else text-dark @endif">{{ $mejorOperador->total_trans }}</div>
                        <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Tickets Emitidos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-center" style="background: @if($modoOscuro) rgba(212, 175, 55, 0.05) @else #fcfcfc @endif;">
                <div class="text-center p-5">
                    <div class="mb-3 position-relative d-inline-block">
                        <i class="bi bi-trophy-fill display-1 gold-text"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.8rem; border: 2px solid #000;">#1</span>
                    </div>
                    <h5 class="fw-bold gold-text mt-3">OPERADOR TOP</h5>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- LISTADO GLOBAL --}}
    <div class="card oled-card shadow-lg">
        <div class="card-header border-0 bg-transparent p-4">
            <h5 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif">
                <i class="bi bi-list-stars gold-text me-3"></i>Desempeño del Personal
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table premium-table align-middle mb-0 @if($modoOscuro) table-dark @endif">
                    <thead>
                        <tr>
                            <th class="ps-5 border-0">Operator</th>
                            <th class="text-center border-0">System Status</th>
                            <th class="text-end border-0">Sales Volume</th>
                            <th class="text-center border-0">Tickets</th>
                            <th class="text-end border-0">Avg. Ticket</th>
                            <th class="text-end pe-5 border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @foreach($empleados as $e)
                        <tr class="border-0">
                            <td class="ps-5 border-0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle">
                                        {{ substr($e->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold @if($modoOscuro) text-white @else text-dark @endif">{{ $e->name }}</div>
                                        <div class="text-muted x-small text-uppercase ls-1">Level: {{ ucfirst($e->role) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center border-0">
                                @if($e->en_turno)
                                    <span class="badge @if($modoOscuro) bg-success-subtle text-success border border-success @else bg-success text-white @endif rounded-pill px-3 py-1">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.4rem;"></i> ONLINE
                                    </span>
                                @else
                                    <span class="badge @if($modoOscuro) bg-dark text-muted border border-secondary @else bg-secondary text-white @endif rounded-pill px-3 py-1">
                                        OFFLINE
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-bold gold-text border-0">
                                $ {{ number_format($e->total_mes, 2, ',', '.') }}
                            </td>
                            <td class="text-center border-0">
                                <span class="badge @if($modoOscuro) bg-dark border border-secondary @else bg-light text-dark border @endif rounded-pill px-3">{{ $e->total_trans }}</span>
                            </td>
                            <td class="text-end text-muted small border-0">
                                $ {{ number_format($e->promedio_ticket, 2, ',', '.') }}
                            </td>
                            <td class="text-end pe-5 border-0">
                                <a href="{{ route('empresa.usuarios.desempeno', $e->id) }}" class="btn-view-desempeno">
                                    <i class="bi bi-graph-up-arrow me-2"></i>ANALYSIS
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1.5px; }
    .ls-2 { letter-spacing: 2px; }
    .x-small { font-size: 0.65rem; }
    .w-fit-content { width: fit-content; }
</style>

@endsection
