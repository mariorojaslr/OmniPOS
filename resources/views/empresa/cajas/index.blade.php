@extends('layouts.empresa')

@section('content')
@php
    $config = $empresa->config ?? null;
    $primary = $config?->color_primary ?? '#2563eb';
    // Forzamos modo claro para cumplir con el requerimiento de estética habitual blanca
    $modoOscuro = false; 
@endphp

<style>
    .audrey-container {
        padding: 1.5rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .audrey-title {
        color: #1e293b;
        font-weight: 800;
        letter-spacing: -1.2px;
        font-size: 1.8rem;
    }

    .audrey-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 10px;
        transition: all 0.2s ease;
        margin-bottom: 0.5rem;
    }

    .audrey-card:hover {
        border-color: {{ $primary }};
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .label-mini {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #94a3b8;
    }

    .val-main {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .neon-text-green { color: #10b981; font-weight: 700; }
    .neon-text-red { color: #ef4444; font-weight: 700; }

    .btn-audrey-sm {
        background: #f1f5f9;
        color: #475569 !important;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        text-decoration: none;
        text-transform: uppercase;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .btn-audrey-sm:hover {
        background: {{ $primary }};
        color: white !important;
        border-color: {{ $primary }};
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.05);
        padding: 10px 20px;
    }
</style>

<div class="audrey-container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="label-mini mb-0" style="color: {{ $primary }};">PILAR 2 · AUDITORÍA FINANCIERA</div>
            <h1 class="audrey-title mb-0">AUDREY <span style="font-weight: 300; opacity: 0.4;">v1.2</span></h1>
        </div>
        <div class="text-end">
            <span class="badge bg-light text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill" style="font-size: 0.6rem; letter-spacing: 1px; font-weight: 700;">PREMIUM ACCESS</span>
        </div>
    </div>

    {{-- Quick Filter --}}
    <div class="filter-card mb-4 d-flex align-items-center justify-content-between shadow-sm">
        <div class="d-flex gap-4">
            <div>
                <span class="label-mini">TRANSACCIONES</span>
                <div class="val-main text-primary">{{ $cierres->total() }}</div>
            </div>
            <div class="border-start ps-4">
                <span class="label-mini">ESTADO SISTEMA</span>
                <div class="text-success fw-bold" style="font-size: 0.8rem;">
                    <i class="bi bi-patch-check-fill me-1"></i>CONECTADO
                </div>
            </div>
        </div>
        <form action="{{ route('empresa.personal.cajas.index') }}" method="GET" class="d-flex gap-2">
            <input type="date" name="desde" class="form-control form-control-sm border shadow-none bg-light" style="width: 140px; font-size: 0.75rem;">
            <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold" style="font-size: 0.7rem;">FILTRAR</button>
        </form>
    </div>

    {{-- Main List (Compacta y Elegante) --}}
    <div class="row g-2">
        @forelse($cierres as $cierre)
            <div class="col-12">
                <div class="audrey-card p-2 px-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.7rem;">
                                    {{ substr($cierre->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="val-main" style="font-size: 0.85rem;">{{ $cierre->user->name }}</div>
                                    <div class="label-mini" style="font-size: 0.55rem; opacity: 0.7;">ID #{{ $cierre->id }} · {{ $cierre->fecha_apertura->format('d/m/y') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="label-mini">EFECTIVO</div>
                                    <div class="val-main" style="font-size: 0.8rem;">${{ number_format($cierre->ventas_efectivo, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4 border-start border-end">
                                    <div class="label-mini">DIGITAL</div>
                                    <div class="val-main" style="font-size: 0.8rem;">${{ number_format($cierre->ventas_tarjeta + $cierre->ventas_transferencia, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="label-mini">DIFERENCIA</div>
                                    <div style="font-size: 0.85rem;" class="fw-bold {{ $cierre->diferencia >= 0 ? 'neon-text-green' : 'neon-text-red' }}">
                                        {{ $cierre->diferencia >= 0 ? '+' : '' }}${{ number_format($cierre->diferencia, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 text-end">
                            <span class="small text-muted me-2" style="font-size: 0.65rem;">{{ $cierre->fecha_apertura->format('H:i') }} hs</span>
                            <a href="{{ route('empresa.personal.cajas.show', $cierre->id) }}" class="btn-audrey-sm">
                                <i class="bi bi-search me-1"></i>DETALLE
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="audrey-card p-5 border-dashed" style="border: 2px dashed #e2e8f0 !important; background: transparent;">
                    <i class="bi bi-inbox text-muted opacity-25 d-block mb-3" style="font-size: 2.5rem;"></i>
                    <h6 class="fw-bold text-dark mb-1">Sin registros de auditoría</h6>
                    <p class="text-muted x-small">Los cierres de caja aparecerán aquí automáticamente.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $cierres->links() }}
    </div>
</div>
@endsection

