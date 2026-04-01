@extends('layouts.empresa')

@section('styles')
<style>
    .oled-card { background: #000; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; color: #fff; }
    .stat-label { color: #60a5fa; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
    .stat-value { font-size: 2.5rem; font-weight: 800; line-height: 1; }
    .neon-border-green { border-color: #22c55e !important; box-shadow: 0 0 15px rgba(34, 197, 94, 0.2); }
    .neon-border-red { border-color: #ef4444 !important; box-shadow: 0 0 15px rgba(239, 68, 68, 0.2); }
    .expense-row { border-bottom: 1px solid rgba(255,255,255,0.05); padding: 12px 0; }
    .expense-row:last-child { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="px-3 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('empresa.personal.cajas.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> VOLVER AL HISTORIAL
        </a>
        <div class="text-end">
            <span class="badge {{ $cierre->estado === 'cerrada' ? 'bg-danger' : 'bg-success' }} px-3 py-2 uppercase">
                CAJA {{ strtoupper($cierre->estado) }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        {{-- COLUMNA INFO GENERAL --}}
        <div class="col-md-5">
            <div class="oled-card p-4 h-100">
                <div class="stat-label mb-2">Responsable del Turno</div>
                <h3 class="fw-bold mb-4">{{ $cierre->user->name }}</h3>

                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <div class="stat-label">Apertura</div>
                        <div class="text-white">{{ $cierre->fecha_apertura->format('d/m/Y H:i') }} hs</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="stat-label">Cierre</div>
                        <div class="text-white">{{ $cierre->fecha_cierre ? $cierre->fecha_cierre->format('d/m/Y H:i') . ' hs' : '-' }}</div>
                    </div>
                </div>

                <div class="p-3 bg-dark bg-opacity-50 rounded-4 border border-white border-opacity-10 mb-4">
                    <div class="stat-label mb-1">Observaciones / Notas</div>
                    <div class="small text-white-50 italic">"{{ $cierre->observaciones ?: 'Sin observaciones registradas' }}"</div>
                </div>

                <div class="mt-auto">
                    <div class="stat-label mb-2">Diferencia Final (Arqueo)</div>
                    <div class="stat-value {{ $cierre->diferencia >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($cierre->diferencia, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA FINANZAS DETALLADAS --}}
        <div class="col-md-7">
            <div class="oled-card p-4 mb-4">
                <h5 class="fw-bold mb-4 small ls-1 text-primary">DESGLOSE POR MÉTODO DE PAGO</h5>
                <div class="row g-3 text-center">
                    <div class="col-4 border-end border-white border-opacity-10">
                        <div class="stat-label">Efectivo</div>
                        <div class="fs-4 fw-bold">${{ number_format($cierre->ventas_efectivo, 0) }}</div>
                    </div>
                    <div class="col-4 border-end border-white border-opacity-10">
                        <div class="stat-label">Tarjeta</div>
                        <div class="fs-4 fw-bold text-info">${{ number_format($cierre->ventas_tarjeta, 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-label">Transf.</div>
                        <div class="fs-4 fw-bold text-warning">${{ number_format($cierre->ventas_transferencia, 0) }}</div>
                    </div>
                </div>
            </div>

            <div class="oled-card p-4">
                <h5 class="fw-bold mb-3 small ls-1 text-primary">MOVIMIENTOS DE GASTOS (CAMPO)</h5>
                @if(count($gastos) > 0)
                    @foreach($gastos as $g)
                    <div class="expense-row">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="bg-primary bg-opacity-25 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                    <i class="bi bi-cart"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="fw-bold d-block">{{ $g->provider ?: 'S/P' }}</div>
                                <div class="small text-white-50">{{ $g->category->name ?? 'Gastos' }} · {{ $g->created_at->format('H:i') }} hs</div>
                            </div>
                            <div class="col-auto text-end">
                                <div class="fw-bold text-danger">-$ {{ number_format($g->amount, 0) }}</div>
                                @if($g->receipt_url)
                                <a href="{{ asset('storage/' . $g->receipt_url) }}" target="_blank" class="badge bg-light text-dark text-decoration-none">Ver Foto 📸</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-white-50 italic">No se registraron gastos durante este turno.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
