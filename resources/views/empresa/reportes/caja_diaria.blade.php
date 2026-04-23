@extends('layouts.empresa')

@section('styles')
<style>
    /* ROLLS-ROYCE OLED AESTHETIC */
    :root {
        --rr-gold: #d4af37;
        --rr-gold-glow: rgba(212, 175, 55, 0.3);
        --rr-dark-bg: #050505;
        --rr-card-bg: rgba(15, 15, 15, 0.82);
        --rr-border: rgba(212, 175, 55, 0.12);
    }

    body {
        background-color: var(--rr-dark-bg) !important;
        color: #e0e0e0 !important;
    }

    .gold-text {
        color: var(--rr-gold) !important;
        text-shadow: 0 0 10px var(--rr-gold-glow);
    }

    .premium-header {
        border-left: 4px solid var(--rr-gold);
        padding-left: 20px;
        margin-bottom: 40px;
    }

    .oled-card {
        background: var(--rr-card-bg) !important;
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid var(--rr-border) !important;
        border-radius: 20px !important;
        overflow: hidden;
    }

    /* Tabla de Auditoría */
    .audit-table {
        background: transparent !important;
        color: #e0e0e0 !important;
    }

    .audit-table thead th {
        background: rgba(255, 255, 255, 0.03);
        border: none;
        color: var(--rr-gold);
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 2px;
        padding: 20px;
    }

    .audit-table tbody tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .audit-table tbody tr:hover {
        background: rgba(212, 175, 55, 0.04) !important;
    }

    .status-badge {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        padding: 5px 12px;
        border-radius: 50px;
        text-transform: uppercase;
    }

    .diff-box {
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
    }

    .diff-positive { background: rgba(212, 175, 55, 0.15); color: var(--rr-gold); }
    .diff-negative { background: rgba(255, 0, 0, 0.1); color: #ff4d4d; border: 1px solid rgba(255, 0, 0, 0.2); }
    .diff-perfect { background: rgba(0, 255, 127, 0.1); color: #00ff7f; }

    .btn-action {
        background: rgba(255,255,255,0.05);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        transition: all 0.3s;
    }

    .btn-action:hover {
        background: var(--rr-gold);
        color: #000;
    }
</style>
@endsection

@section('content')

<div class="p-4">
    {{-- ENCABEZADO --}}
    <div class="mb-5 d-flex align-items-center justify-content-between flex-wrap gap-3 premium-header">
        <div>
            <h1 class="fw-bold mb-0 text-dark display-5">
                CASH <span class="gold-text">AUDIT</span>
            </h1>
            <p class="text-muted mb-0 mt-2">
                <i class="bi bi-shield-lock me-2 text-primary"></i>Historial detallado de arqueos de caja y rendiciones de turno.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-dark border-secondary px-4 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i>Control Center
            </a>
            <button onclick="window.print()" class="btn btn-outline-warning px-4 rounded-pill">
                <i class="bi bi-printer me-2"></i>Export Logs
            </button>
        </div>
    </div>

    {{-- LISTADO DE ARQUEOS --}}
    <div class="card oled-card shadow-lg">
        <div class="card-header border-0 bg-transparent p-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0 text-white">
                <i class="bi bi-receipt-cutoff gold-text me-3"></i>Registros de Cierre (Últimos 50)
            </h5>
            <span class="text-muted small">Mostrando historial operativo.</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table audit-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Operador / Fecha</th>
                            <th class="text-center">Turno (Hs)</th>
                            <th class="text-end">Ventas Efectivo</th>
                            <th class="text-end">Digitales</th>
                            <th class="text-end">Saldo Esperado</th>
                            <th class="text-end">Saldo Real</th>
                            <th class="text-center">Diferencia</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cierres as $c)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-dark rounded p-2 border border-secondary text-center" style="min-width: 50px;">
                                        <div class="fw-bold text-white fs-4">{{ $c->fecha_apertura->format('d') }}</div>
                                        <div class="text-muted x-small text-uppercase">{{ $c->fecha_apertura->translatedFormat('M') }}</div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white">{{ $c->user->name ?? 'Sistema' }}</div>
                                        <div class="text-muted x-small text-uppercase ls-1">ID Arqueo: #{{ $c->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="small text-white">
                                    <i class="bi bi-clock-history me-1 text-primary"></i>
                                    {{ $c->fecha_apertura->format('H:i') }} - {{ $c->fecha_cierre ? $c->fecha_cierre->format('H:i') : 'Activa' }}
                                </div>
                            </td>
                            <td class="text-end fw-bold text-white">$ {{ number_format($c->ventas_efectivo, 2, ',', '.') }}</td>
                            <td class="text-end text-muted small">$ {{ number_format($c->ventas_digital, 2, ',', '.') }}</td>
                            <td class="text-end text-muted fw-medium">$ {{ number_format($c->saldo_esperado, 2, ',', '.') }}</td>
                            <td class="text-end fw-bold text-white">$ {{ number_format($c->saldo_real, 2, ',', '.') }}</td>
                            <td class="text-center">
                                @if($c->estado == 'abierta')
                                    <span class="text-primary italic small">Calculando...</span>
                                @else
                                    <div class="diff-box {{ $c->diferencia == 0 ? 'diff-perfect' : ($c->diferencia > 0 ? 'diff-positive' : 'diff-negative') }}">
                                        {{ $c->diferencia > 0 ? '+' : '' }} $ {{ number_format($c->diferencia, 2, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($c->estado == 'abierta')
                                    <span class="status-badge border border-primary text-primary">Abierta</span>
                                @else
                                    <span class="status-badge border border-success text-success">Cerrada</span>
                                @endif
                                <a href="{{ route('empresa.personal.cajas.show', $c->id) }}" class="btn btn-action ms-2" title="Ver Detalles/Obs" data-bs-toggle="tooltip">
                                    <i class="bi bi-search"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted"> No hay registros de arqueos disponibles en el período.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1.5px; }
    .x-small { font-size: 0.6rem; }
    .italic { font-style: italic; }
</style>

@endsection
