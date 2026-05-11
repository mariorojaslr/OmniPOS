@extends('layouts.empresa')

@section('styles')
<style>
    .audit-container {
        padding: 1.5rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .premium-header {
        border-left: 3px solid #2563eb;
        padding-left: 1.5rem;
        margin-bottom: 2rem;
    }

    .audit-title {
        font-weight: 800;
        letter-spacing: -1px;
        color: #1e293b;
        font-size: 1.8rem;
    }

    .audit-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .audit-table thead th {
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 15px;
        border: none;
    }

    .audit-table tbody td {
        padding: 12px 15px;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .status-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    .diff-box {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .diff-positive { background: #dcfce7; color: #166534; }
    .diff-negative { background: #fee2e2; color: #991b1b; }
    .diff-perfect { background: #f0fdf4; color: #15803d; border: 1px solid #bcf0da; }

    .btn-action-sm {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .btn-action-sm:hover {
        background: #2563eb;
        color: white;
    }

    .date-badge {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 8px;
        border-radius: 8px;
        text-align: center;
        min-width: 45px;
    }
</style>
@endsection

@section('content')
<div class="audit-container">
    {{-- ENCABEZADO --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="premium-header">
            <h1 class="audit-title mb-1">CASH AUDIT</h1>
            <p class="text-muted small mb-0">
                <i class="bi bi-shield-check me-1 text-primary"></i>Historial de arqueos de caja y rendiciones de turno.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-3">
                <i class="bi bi-grid-3x3-gap me-1"></i>Panel de Control
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3 rounded-3 shadow-sm">
                <i class="bi bi-download me-1"></i>Exportar Historial
            </button>
        </div>
    </div>

    {{-- LISTADO --}}
    <div class="audit-card">
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-list-check me-2 text-primary"></i>Registros de Cierre
            </h6>
            <span class="text-muted x-small">Últimos 50 movimientos</span>
        </div>
        <div class="table-responsive">
            <table class="table audit-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Operador / Fecha</th>
                        <th class="text-center">Horario Turno</th>
                        <th class="text-end">Ventas Efectivo</th>
                        <th class="text-end">Ventas Digitales</th>
                        <th class="text-end">Saldo Esperado</th>
                        <th class="text-end">Saldo Real</th>
                        <th class="text-center">Diferencia</th>
                        <th class="text-end pe-4">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cierres as $c)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="date-badge">
                                    <div class="fw-bold text-dark lh-1">{{ $c->fecha_apertura->format('d') }}</div>
                                    <div class="text-muted x-small text-uppercase">{{ $c->fecha_apertura->translatedFormat('M') }}</div>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $c->user->name ?? 'Sistema' }}</div>
                                    <div class="text-muted x-small">ID Arqueo: #{{ $c->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border fw-normal">
                                <i class="bi bi-clock me-1 text-primary"></i>
                                {{ $c->fecha_apertura->format('H:i') }} - {{ $c->fecha_cierre ? $c->fecha_cierre->format('H:i') : 'Activa' }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark">$ {{ number_format($c->ventas_efectivo, 2, ',', '.') }}</td>
                        <td class="text-end text-muted small">$ {{ number_format($c->ventas_digital, 2, ',', '.') }}</td>
                        <td class="text-end text-muted fw-medium">$ {{ number_format($c->saldo_esperado, 2, ',', '.') }}</td>
                        <td class="text-end fw-bold text-primary">$ {{ number_format($c->saldo_real, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @if($c->estado == 'abierta')
                                <span class="text-muted italic small">En curso...</span>
                            @else
                                <div class="diff-box {{ $c->diferencia == 0 ? 'diff-perfect' : ($c->diferencia > 0 ? 'diff-positive' : 'diff-negative') }}">
                                    {{ $c->diferencia > 0 ? '+' : '' }} $ {{ number_format($c->diferencia, 2, ',', '.') }}
                                </div>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($c->estado == 'abierta')
                                <span class="status-badge bg-primary bg-opacity-10 text-primary">Abierta</span>
                            @else
                                <span class="status-badge bg-success bg-opacity-10 text-success">Cerrada</span>
                            @endif
                            <a href="{{ route('empresa.personal.cajas.show', $c->id) }}" class="btn-action-sm ms-2" title="Ver Detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox text-muted display-6 d-block mb-2"></i>
                            <span class="text-muted">No se encontraron registros de auditoría en el período.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.65rem; }
    .italic { font-style: italic; }
</style>
@endsection
ion
