@extends('layouts.empresa')

@section('content')
@php
    $config = $empresa->config ?? null;
    $primary = $config?->color_primary ?? '#2563eb';
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
@endphp

<style>
    /* =========================================================
       AUDREY OLED - PREMIUM AUDIT INTERFACE
       ========================================================= */
    .audrey-container {
        padding: 2rem;
        background: {{ $modoOscuro ? '#000' : '#f8f9fa' }};
        min-height: 100vh;
    }

    .audrey-header {
        margin-bottom: 3rem;
        border-bottom: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }};
        padding-bottom: 2rem;
    }

    .audrey-card {
        background: {{ $modoOscuro ? 'rgba(20, 20, 20, 0.8)' : '#ffffff' }};
        backdrop-filter: blur(12px);
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }};
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin-bottom: 1.5rem;
    }

    .audrey-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        border-color: {{ $primary }} !important;
    }

    .tag-premium {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        background: linear-gradient(90deg, {{ $primary }}, #000);
        padding: 6px 16px;
        border-radius: 100px;
        color: white;
    }

    .label-mini {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: {{ $modoOscuro ? '#6b7280' : '#9ca3af' }};
        margin-bottom: 0.5rem;
    }

    .neon-text-green { color: #10b981; text-shadow: 0 0 10px rgba(16, 185, 129, 0.2); }
    .neon-text-red { color: #ef4444; text-shadow: 0 0 10px rgba(239, 68, 68, 0.2); }

    .btn-audrey {
        background: {{ $primary }};
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-audrey:hover {
        transform: scale(1.05);
        background: #000;
        color: white;
    }

    .empty-symbol {
        font-size: 6rem;
        background: linear-gradient(135deg, {{ $primary }}50, transparent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
    }
</style>

<div class="audrey-container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-end audrey-header">
        <div>
            <div class="label-mini mb-2">PILAR 2: AUDITORÍA FINANCIERA INTELIGENTE</div>
            <h1 class="display-5 fw-bold mb-0 text-white" style="letter-spacing: -2px;">AUDREY <span style="font-weight: 300; opacity: 0.5;">PREMIUM</span></h1>
        </div>
        <div class="text-end">
            <span class="tag-premium">v1.2 ALPHA OPS</span>
        </div>
    </div>

    {{-- Filters / Quick Stats --}}
    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="audrey-card p-4 text-center">
                <div class="label-mini">Auditados Totales</div>
                <div class="h2 fw-bold mb-0 text-white">{{ $cierres->total() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="audrey-card p-4 text-center border-danger border-opacity-25">
                <div class="label-mini">Alertas de Caja</div>
                <div class="h2 fw-bold mb-0 text-danger">--</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="audrey-card p-4">
                <form action="{{ route('empresa.personal.cajas.index') }}" method="GET" class="row g-2">
                    <div class="col-8">
                        <input type="date" name="desde" class="form-control bg-dark border-0 text-white" style="border-radius: 10px;">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-audrey w-100">FILTRAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Main List --}}
    <div class="row g-3">
        @forelse($cierres as $cierre)
            <div class="col-12">
                <div class="audrey-card p-4">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: white;">
                                    {{ substr($cierre->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="label-mini mb-0">OPERADOR</div>
                                    <div class="fw-bold text-white">{{ $cierre->user->name }}</div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">ID #{{ $cierre->id }} · {{ $cierre->fecha_apertura->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 border-start border-end border-white border-opacity-10">
                            <div class="row text-center px-lg-4">
                                <div class="col-4">
                                    <div class="label-mini">EFECTIVO</div>
                                    <div class="fw-bold text-white fs-5">${{ number_format($cierre->ventas_efectivo, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="label-mini">OTRAS FORMAS</div>
                                    <div class="fw-bold text-white fs-5">${{ number_format($cierre->ventas_tarjeta + $cierre->ventas_transferencia, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="label-mini">DIFERENCIA</div>
                                    <div class="fw-bold fs-5 {{ $cierre->diferencia >= 0 ? 'neon-text-green' : 'neon-text-red' }}">
                                        {{ $cierre->diferencia >= 0 ? '+' : '' }}${{ number_format($cierre->diferencia, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 text-end ps-4">
                            <div class="small text-muted mb-2">Turno: {{ $cierre->fecha_apertura->format('H:i') }} a {{ $cierre->fecha_cierre ? $cierre->fecha_cierre->format('H:i') : 'En curso' }}</div>
                            <a href="{{ route('empresa.personal.cajas.show', $cierre->id) }}" class="btn-audrey">
                                VER AUDITORÍA <i class="bi bi-shield-check ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="audrey-card p-5 border-dashed" style="border: 2px dashed rgba(255,255,255,0.1) !important;">
                    <div class="empty-symbol animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-fingerprint"></i>
                    </div>
                    <h2 class="fw-bold text-white mb-2">Awaiting Financial Signature</h2>
                    <p class="text-muted mx-auto" style="max-width: 500px; font-size: 0.95rem;">
                        No se han detectado cierres de caja en el espectro actual. 
                        Audrey comenzará a trazar el historial operativo en cuanto se registre el primer cierre de turno desde el terminal POS.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('empresa.dashboard') }}" class="btn btn-outline-light rounded-pill px-5 fw-bold" style="font-size: 0.8rem;">VOLVER AL PANEL CENTRAL</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $cierres->links() }}
    </div>
</div>
@endsection
