@extends('layouts.app')

@section('styles')
<style>
    .oled-card {
        background: rgba(0, 0, 0, 0.8) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 20px;
        color: #fff;
        transition: all 0.3s ease;
    }
    .oled-card:hover {
        transform: scale(1.01);
        border-color: #60a5fa !important;
    }
    .stat-label { color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; }
    .text-neon-green { color: #4ade80; text-shadow: 0 0 15px rgba(74, 222, 128, 0.3); }
    .text-neon-red { color: #f87171; text-shadow: 0 0 15px rgba(248, 113, 113, 0.3); }
</style>
@endsection

@section('content')
<div class="px-2 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-3">
        <div>
            <h5 class="stat-label mb-1">PILAR 2: CONTROL FINANCIERO</h5>
            <h1 class="fw-bold text-white mb-0" style="letter-spacing: -1px;">AUDITORÍA DE CAJAS <span class="text-primary">AUDREY</span></h1>
        </div>
        <div class="text-end">
            <div class="badge bg-primary px-4 py-2 rounded-pill shadow-lg border border-light">v1.2 PREMIUM</div>
        </div>
    </div>

    <div class="row g-4 mb-5 mt-2">
        @forelse($cierres as $cierre)
        <div class="col-md-12">
            <div class="oled-card p-4 shadow-lg">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="stat-label mb-1">Cajero / Operador</div>
                        <div class="text-white fw-bold fs-5">{{ $cierre->user->name }}</div>
                        <div class="small text-info opacity-75">{{ $cierre->fecha_apertura->format('d/m/Y H:i') }} hs</div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="row g-2 justify-content-center">
                            <div class="col-4 border-end border-light border-opacity-10 text-center">
                                <div class="stat-label">Efectivo</div>
                                <div class="text-white fw-bold font-monospace fs-5">${{ number_format($cierre->ventas_efectivo, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-4 border-end border-light border-opacity-10 text-center">
                                <div class="stat-label">Tarjeta</div>
                                <div class="text-white fw-bold font-monospace fs-5">${{ number_format($cierre->ventas_tarjeta, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="stat-label">Transf.</div>
                                <div class="text-white fw-bold font-monospace fs-5">${{ number_format($cierre->ventas_transferencia, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 text-center">
                        <div class="stat-label">Diferencia</div>
                        <div class="stat-value {{ $cierre->diferencia >= 0 ? 'text-neon-green' : 'text-neon-red' }}">
                            ${{ number_format($cierre->diferencia, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="col-md-2 text-end">
                        <a href="{{ route('empresa.personal.cajas.show', $cierre->id) }}" class="btn btn-primary rounded-pill px-4 shadow">
                            DETALLE <i class="bi bi-eye-fill ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="glass-card p-5">
                <i class="bi bi-safe2 text-primary opacity-20 mb-3" style="font-size: 5rem;"></i>
                <h3 class="fw-bold text-white">Sin cierres auditados</h3>
                <p class="text-muted mx-auto" style="max-width: 450px;">
                    No hemos encontrado cierres de caja registrados para este periodo o usuario. El historial aparecerá aquí automáticamente al cerrar turnos desde el POS.
                </p>
                <a href="{{ route('empresa.dashboard') }}" class="btn btn-outline-primary btn-sm mt-3 px-4 rounded-pill">Volver al Panel</a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $cierres->links() }}
    </div>
</div>
@endsection
