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
        --rr-card-bg: rgba(15, 15, 15, 0.92);
        --rr-border: rgba(212, 175, 55, 0.15);
    }
    body { background-color: var(--rr-dark-bg) !important; color: #e0e0e0 !important; }
    .oled-card { background: var(--rr-card-bg) !important; backdrop-filter: blur(20px); border: 1px solid var(--rr-border) !important; border-radius: 15px !important; }
    .top-operator-card { background: linear-gradient(135deg, #0a0a0a 0%, #151515 100%) !important; border: 1px solid var(--rr-gold) !important; box-shadow: 0 5px 25px rgba(0, 0, 0, 0.8); }
    .premium-table { background: transparent !important; color: #e0e0e0 !important; }
    .premium-table thead th { background: rgba(255, 255, 255, 0.05); color: var(--rr-gold); font-size: 0.75rem; text-transform: uppercase; border: none !important; }
    .premium-table tbody tr:hover { background: rgba(212, 175, 55, 0.05) !important; }
    .gold-text { color: var(--rr-gold) !important; text-shadow: 0 0 8px var(--rr-gold-glow); }
    @else
    .oled-card { background: #ffffff !important; border: 1px solid #eef0f2 !important; border-radius: 15px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important; }
    .top-operator-card { background: #fff !important; border: 1px solid var(--color-primario) !important; box-shadow: 0 10px 20px rgba(var(--color-primario-rgb), 0.05); }
    .premium-table thead th { background: #f8fbff; color: var(--color-primario); font-size: 0.75rem; text-transform: uppercase; border: none !important; }
    .gold-text { color: var(--color-primario) !important; }
    @endif

    .premium-header { border-left: 3px solid var(--rr-gold); padding-left: 15px; margin-bottom: 25px; }
    .stat-value { font-family: 'Inter', sans-serif; font-weight: 800; letter-spacing: -0.5px; }
    .avatar-circle { width: 38px; height: 38px; border: 1px solid @if($modoOscuro) var(--rr-border) @else #eee @endif; background: @if($modoOscuro) #111 @else #f8f9fa @endif; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif; font-weight: 700; font-size: 0.9rem; }
    .btn-view-desempeno { background: @if($modoOscuro) rgba(212, 175, 55, 0.1) @else rgba(var(--color-primario-rgb), 0.05) @endif; border: 1px solid @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif; color: @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif; font-weight: 600; border-radius: 10px; padding: 6px 14px; transition: all 0.3s ease; font-size: 0.75rem; }
    .btn-view-desempeno:hover { background: @if($modoOscuro) var(--rr-gold) @else var(--color-primario) @endif; color: @if($modoOscuro) #000 @else #fff @endif; }
    .x-small { font-size: 0.65rem; }
    .ls-1 { letter-spacing: 1px; }
</style>
@endsection

@section('content')
<div class="row @if(!$modoOscuro) pt-2 @endif">
    <div class="col-12 px-4">
        {{-- ENCABEZADO COMPACTO --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 premium-header">
            <div>
                <h3 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif">
                    LÍDERES DEL <span class="gold-text">SISTEMA</span>
                </h3>
                <p class="text-muted mb-0 x-small">
                    <i class="bi bi-graph-up-arrow me-1 text-primary"></i>Ranking de productividad operativa — {{ now()->translatedFormat('F Y') }}
                </p>
            </div>
            <div>
                <button class="btn @if($modoOscuro) btn-dark @else btn-outline-secondary @endif btn-sm rounded-pill px-3">
                    <i class="bi bi-clock-history me-1"></i>Historial Global
                </button>
            </div>
        </div>

        {{-- TOP OPERADOR DEL MES COMPACTO --}}
        @php $mejorOperador = $empleados->first(); @endphp
        @if($mejorOperador && $mejorOperador->total_mes > 0)
        <div class="card oled-card top-operator-card mb-4 overflow-hidden">
            <div class="row g-0">
                <div class="col-md-9 p-4 d-flex flex-column justify-content-center">
                    <div class="badge @if($modoOscuro) bg-primary-emphasis text-primary @else bg-primary text-white @endif px-2 py-1 mb-2 rounded-pill d-inline-block x-small fw-bold ls-1" style="width: fit-content;">ESTADO ÉLITE</div>
                    <h2 class="fw-bold mb-1 @if($modoOscuro) text-white @else text-dark @endif stat-value">{{ $mejorOperador->name }}</h2>
                    <p class="text-muted mb-3 small">Liderando el ranking de ventas con una efectividad excepcional.</p>
                    <div class="row g-3 mt-1">
                        <div class="col-auto">
                            <div class="h4 mb-0 gold-text stat-value">$ {{ number_format($mejorOperador->total_mes, 2, ',', '.') }}</div>
                            <small class="text-muted text-uppercase fw-bold x-small ls-1">Facturación Mensual</small>
                        </div>
                        <div class="col-auto border-start @if($modoOscuro) border-secondary @else border-light @endif ps-3">
                            <div class="h4 mb-0 @if($modoOscuro) text-white @else text-dark @endif stat-value">{{ $mejorOperador->total_trans }}</div>
                            <small class="text-muted text-uppercase fw-bold x-small ls-1">Tickets Emitidos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-center" style="background: @if($modoOscuro) rgba(212, 175, 55, 0.03) @else #fafafa @endif; border-left: 1px solid @if($modoOscuro) var(--rr-border) @else #eee @endif;">
                    <div class="text-center p-3">
                        <div class="position-relative d-inline-block">
                            <i class="bi bi-trophy-fill h1 gold-text"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger x-small" style="border: 1.5px solid #000;">#1</span>
                        </div>
                        <div class="fw-bold gold-text x-small mt-1 ls-1">OPERADOR TOP</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- LISTADO GLOBAL OPTIMIZADO --}}
        <div class="card oled-card border-0">
            <div class="card-header border-0 bg-transparent py-3 px-4">
                <h6 class="fw-bold mb-0 @if($modoOscuro) text-white @else text-dark @endif">
                    <i class="bi bi-list-stars gold-text me-2"></i>Desempeño del Personal
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table premium-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Operador</th>
                                <th class="text-center">Estado</th>
                                <th class="text-end">Volumen de Ventas</th>
                                <th class="text-center">Tickets</th>
                                <th class="text-end">Ticket Prom.</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empleados as $e)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">{{ substr($e->name, 0, 1) }}</div>
                                        <div>
                                            <div class="fw-bold @if($modoOscuro) text-white @else text-dark @endif small">{{ $e->name }}</div>
                                            <div class="text-muted x-small text-uppercase">Nivel: {{ $e->role == 'empresa' ? 'Administrador' : 'Vendedor' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($e->en_turno)
                                        <span class="badge @if($modoOscuro) bg-success-subtle text-success @else bg-success text-white @endif rounded-pill px-2 py-1 x-small">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.35rem;"></i> EN TURNO
                                        </span>
                                    @else
                                        <span class="badge @if($modoOscuro) bg-dark text-muted @else bg-secondary text-white @endif rounded-pill px-2 py-1 x-small">FUERA</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold gold-text small">
                                    $ {{ number_format($e->total_mes, 2, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge @if($modoOscuro) bg-dark border-secondary @else bg-light text-dark border @endif rounded-pill px-2 x-small">{{ $e->total_trans }}</span>
                                </td>
                                <td class="text-end text-muted x-small">
                                    $ {{ number_format($e->promedio_ticket, 2, ',', '.') }}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('empresa.usuarios.desempeno', $e->id) }}" class="btn-view-desempeno">
                                        <i class="bi bi-graph-up-arrow me-1"></i>ANALIZAR
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
</div>
@endsection
