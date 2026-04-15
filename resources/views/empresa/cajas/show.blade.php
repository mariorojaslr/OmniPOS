@extends('layouts.empresa')

@php
    $config = $empresa->config ?? null;
    $primary = $config?->color_primary ?? '#2563eb';
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
@endphp

@section('styles')
<style>
    .audrey-detail-container {
        padding: 2rem;
        background: {{ $modoOscuro ? '#000' : '#f4f6f9' }};
        min-height: 100vh;
    }
    .oled-card { 
        background: {{ $modoOscuro ? '#111' : '#fff' }}; 
        border: 2px solid {{ $modoOscuro ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)' }}; 
        border-radius: 16px; 
        color: {{ $modoOscuro ? '#fff' : '#1e293b' }}; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .text-main-contrast { color: {{ $modoOscuro ? '#fff' : '#0f172a' }} !important; }
    .label-mini { 
        color: {{ $primary }}; 
        font-size: 0.65rem; 
        text-transform: uppercase; 
        letter-spacing: 1.5px; 
        font-weight: 800; 
    }
    .stat-value { font-size: 2.8rem; font-weight: 800; line-height: 1; letter-spacing: -2px; }
    .expense-row { border-bottom: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }}; padding: 15px 0; }
    .expense-row:last-child { border-bottom: none; }
    .neon-text-green { color: #10b981; }
    .neon-text-red { color: #ef4444; }
    
    .btn-return {
        background: {{ $modoOscuro ? '#222' : '#f8fafc' }};
        color: {{ $modoOscuro ? '#fff' : '#000' }} !important;
        font-weight: 800;
        font-size: 0.75rem;
        padding: 8px 20px;
        border-radius: 100px;
        text-decoration: none;
        border: 1px solid rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')
<div class="audrey-detail-container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('empresa.personal.cajas.index') }}" class="btn-return shadow-sm">
            <i class="bi bi-arrow-left-short fs-5 align-middle me-1"></i> VOLVER AL HISTORIAL
        </a>
        <div class="text-end">
            <span class="badge {{ $cierre->estado === 'cerrada' ? 'bg-danger' : 'bg-success' }} px-4 py-2 rounded-pill fw-bold ls-1" style="font-size: 0.7rem;">
                CAJA {{ strtoupper($cierre->estado) }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        {{-- COLUMNA INFO GENERAL --}}
        <div class="col-md-5">
            <div class="oled-card p-5 h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-4 opacity-10">
                    <i class="bi bi-shield-lock" style="font-size: 8rem; transform: rotate(15deg);"></i>
                </div>
                
                <div class="label-mini mb-2">Responsable del Turno</div>
                <h2 class="fw-bold mb-4 text-main-contrast display-6" style="letter-spacing: -1.5px;">{{ $cierre->user->name }}</h2>

                <div class="row g-4 mb-5">
                    <div class="col-6">
                        <div class="label-mini">Hélicestatus Apertura</div>
                        <div class="text-main-contrast opacity-50 fw-bold">{{ $cierre->fecha_apertura->format('d/m/Y H:i') }} hs</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="label-mini">Cierre Efectivo</div>
                        <div class="text-main-contrast opacity-50 fw-bold">{{ $cierre->fecha_cierre ? $cierre->fecha_cierre->format('d/m/Y H:i') . ' hs' : '-' }}</div>
                    </div>
                </div>

                <div class="p-4 bg-primary bg-opacity-5 rounded-4 border border-primary border-opacity-10 mb-5">
                    <div class="label-mini mb-2">Análisis de Operador</div>
                    <div class="small text-main-contrast opacity-75 italic" style="line-height: 1.6;">
                        "{{ $cierre->observaciones ?: 'Apertura de turno (Cajero): Sin detalles registrados en sistema.' }}"
                    </div>
                </div>

                <div class="mt-auto">
                    <div class="label-mini mb-3">Balance Final Auditoría</div>
                    <div class="stat-value {{ $cierre->diferencia >= 0 ? 'neon-text-green' : 'neon-text-red' }}">
                        {{ $cierre->diferencia >= 0 ? '+' : '' }}${{ number_format($cierre->diferencia, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA FINANZAS DETALLADAS --}}
        <div class="col-md-7">
            <div class="oled-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                    <h5 class="fw-bold mb-0 label-mini">Desglose Operativo por Canal</h5>
                    <i class="bi bi-graph-up-arrow text-primary"></i>
                </div>
                <div class="row g-3 text-center">
                    <div class="col-4 border-end {{ $modoOscuro ? 'border-white' : 'border-dark' }} border-opacity-10">
                        <div class="label-mini">EFECTIVO</div>
                        <div class="text-main-contrast fw-bold fs-4">${{ number_format($cierre->ventas_efectivo, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-4 border-end {{ $modoOscuro ? 'border-white' : 'border-dark' }} border-opacity-10">
                        <div class="label-mini">TARJETA</div>
                        <div class="text-info fw-bold fs-4">${{ number_format($cierre->ventas_tarjeta, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-4">
                        <div class="label-mini">TRANSF.</div>
                        <div class="text-warning fw-bold fs-4">${{ number_format($cierre->ventas_transferencia, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="oled-card p-4 overflow-hidden">
                <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                    <h5 class="fw-bold mb-0 label-mini">Movimientos Manuales (Campo)</h5>
                    <span class="badge bg-danger rounded-pill" style="font-size: 0.6rem;">{{ count($gastos) }} GASTOS</span>
                </div>
                
                <div class="px-2">
                    @if(count($gastos) > 0)
                        @foreach($gastos as $g)
                        <div class="expense-row">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto">
                                    <div class="bg-white bg-opacity-10 text-white rounded-circle d-flex align-items-center justify-content-center" style="width:42px; height:42px;">
                                        <i class="bi bi-receipt-cutoff"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fw-bold text-white mb-0" style="font-size: 0.95rem;">{{ $g->provider ?: 'Sin Proveedor' }}</div>
                                    <div class="label-mini" style="font-size: 0.6rem; opacity: 0.6;">{{ $g->category->name ?? 'Gastos Operativos' }} · {{ $g->created_at->format('H:i') }} hs</div>
                                </div>
                                <div class="col-auto text-end">
                                    <div class="fw-bold fs-5 text-danger" style="letter-spacing: -0.5px;">-${{ number_format($g->amount, 0, ',', '.') }}</div>
                                    @if($g->receipt_url)
                                    <a href="{{ asset('storage/' . $g->receipt_url) }}" target="_blank" class="badge bg-primary bg-opacity-25 text-primary text-decoration-none border border-primary border-opacity-25" style="font-size: 0.6rem;">
                                        VER COMPROBANTE <i class="bi bi-camera ms-1"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check2-circle text-success fs-1 opacity-25 d-block mb-2"></i>
                            <div class="text-white-50 italic">Sin movimientos de gastos informados.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
