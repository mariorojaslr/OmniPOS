@extends('layouts.empresa')

@section('content')
@php
    $config = $empresa->config ?? null;
    $primary = $config?->color_primary ?? '#2563eb';
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
@endphp

<style>
    .audrey-container {
        padding: 1.5rem;
        background: {{ $modoOscuro ? '#000' : '#f4f6f9' }};
        min-height: 100vh;
    }

    .audrey-title {
        color: {{ $modoOscuro ? '#fff' : '#000' }};
        font-weight: 800;
        letter-spacing: -1.5px;
        font-size: 2.2rem;
    }

    .audrey-card {
        background: {{ $modoOscuro ? '#111' : '#fff' }};
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)' }};
        border-radius: 12px;
        transition: all 0.2s ease;
        margin-bottom: 0.75rem;
    }

    .audrey-card:hover {
        border-color: {{ $primary }};
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .label-mini {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: {{ $modoOscuro ? '#666' : '#999' }};
    }

    .val-main {
        font-weight: 700;
        color: {{ $modoOscuro ? '#fff' : '#000' }};
        font-size: 1rem;
    }

    .neon-text-green { color: #10b981; font-weight: 800; }
    .neon-text-red { color: #ef4444; font-weight: 800; }

    .btn-audrey-sm {
        background: {{ $primary }};
        color: white !important;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 6px 15px;
        border-radius: 6px;
        text-decoration: none;
        text-transform: uppercase;
    }
</style>

<div class="audrey-container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="label-mini mb-0">PILAR 2 · AUDITORÍA FINANCIERA</div>
            <h1 class="audrey-title mb-0">AUDREY <span style="font-weight: 400; opacity: 0.5;">v1.2</span></h1>
        </div>
        <div class="text-end">
            <span class="badge bg-dark text-white px-3 py-2 rounded-pill" style="font-size: 0.6rem; letter-spacing: 1px;">PREMIUM ACCESS</span>
        </div>
    </div>

    {{-- Quick Filter --}}
    <div class="audrey-card p-2 mb-4 d-flex align-items-center justify-content-between px-3">
        <div class="d-flex gap-4">
            <div>
                <span class="label-mini">TRANSACCIONES</span>
                <div class="val-main">{{ $cierres->total() }}</div>
            </div>
            <div class="border-start ps-4">
                <span class="label-mini">ESTADO</span>
                <div class="text-success fw-bold small">CONECTADO</div>
            </div>
        </div>
        <form action="{{ route('empresa.personal.cajas.index') }}" method="GET" class="d-flex gap-2">
            <input type="date" name="desde" class="form-control form-control-sm border-0 bg-light" style="width: 150px;">
            <button type="submit" class="btn btn-dark btn-sm px-3">FILTRAR</button>
        </form>
    </div>

    {{-- Main List (Compacta e Igual a Stock) --}}
    <div class="row g-2">
        @forelse($cierres as $cierre)
            <div class="col-12">
                <div class="audrey-card p-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-dark rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($cierre->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="val-main" style="font-size: 0.9rem;">{{ $cierre->user->name }}</div>
                                    <div class="label-mini" style="font-size: 0.6rem;">ID #{{ $cierre->id }} · {{ $cierre->fecha_apertura->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="label-mini">EFECTIVO</div>
                                    <div class="val-main">${{ number_format($cierre->ventas_efectivo, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4 border-start border-end">
                                    <div class="label-mini">DIGITAL</div>
                                    <div class="val-main">${{ number_format($cierre->ventas_tarjeta + $cierre->ventas_transferencia, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="label-mini">DIFERENCIA</div>
                                    <div class="fs-6 {{ $cierre->diferencia >= 0 ? 'neon-text-green' : 'neon-text-red' }}">
                                        {{ $cierre->diferencia >= 0 ? '+' : '' }}${{ number_format($cierre->diferencia, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 text-end">
                            <span class="small text-muted me-3" style="font-size: 0.7rem;">{{ $cierre->fecha_apertura->format('H:i') }} hs</span>
                            <a href="{{ route('empresa.personal.cajas.show', $cierre->id) }}" class="btn-audrey-sm">
                                DETALLE <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="audrey-card p-5 border-dashed" style="border: 2px dashed rgba(0,0,0,0.05) !important;">
                    <i class="bi bi-inbox text-muted opacity-25 d-block mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold text-dark">Sin registros de auditoría</h5>
                    <p class="text-muted small">Los cierres de caja aparecerán aquí automáticamente.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $cierres->links() }}
    </div>
</div>
@endsection
