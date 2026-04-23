@extends('layouts.empresa')

@php
    $config = auth()->user()->empresa->config ?? null;
    $primary = $config?->color_primary ?? '#2563eb';
    $modoOscuro = ($config?->theme ?? 'light') === 'dark';
@endphp

@section('styles')
<style>
    :root {
        --rr-gold: #d4af37;
        --rr-gold-glow: rgba(212, 175, 55, 0.3);
    }
    .audrey-detail-container {
        padding: 2rem;
        background: {{ $modoOscuro ? '#000' : '#f4f6f9' }};
        min-height: 100vh;
    }
    .oled-card { 
        background: {{ $modoOscuro ? '#0a0a0a' : '#fff' }}; 
        border: 1px solid {{ $modoOscuro ? 'rgba(212, 175, 55, 0.12)' : 'rgba(0, 0, 0, 0.05)' }}; 
        border-radius: 20px; 
        color: {{ $modoOscuro ? '#e2e8f0' : '#1e293b' }}; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .text-main-contrast { color: {{ $modoOscuro ? '#fff' : '#0f172a' }} !important; }
    .label-mini { 
        color: var(--rr-gold); 
        font-size: 0.65rem; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        font-weight: 800; 
    }
    .stat-value { font-size: 2.5rem; font-weight: 800; line-height: 1; letter-spacing: -1.5px; }
    
    .movement-row { 
        border-bottom: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }}; 
        padding: 12px 15px;
        transition: all 0.2s;
    }
    .movement-row:hover {
        background: {{ $modoOscuro ? 'rgba(212, 175, 55, 0.05)' : 'rgba(0,0,0,0.02)' }};
    }
    .movement-row:last-child { border-bottom: none; }
    
    .neon-text-green { color: #10b981; }
    .neon-text-red { color: #f43f5e; }
    
    .btn-return {
        background: {{ $modoOscuro ? '#111' : '#fff' }};
        color: {{ $modoOscuro ? '#fff' : '#000' }} !important;
        font-weight: 800;
        font-size: 0.75rem;
        padding: 8px 20px;
        border-radius: 100px;
        text-decoration: none;
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }};
    }

    .account-badge {
        font-size: 0.6rem;
        padding: 3px 8px;
        border-radius: 6px;
        background: rgba(212, 175, 55, 0.1);
        color: var(--rr-gold);
        border: 1px solid rgba(212, 175, 55, 0.2);
    }
</style>
@endsection

@section('content')
<div class="audrey-detail-container">
    {{-- BARRA SUPERIOR --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url()->previous() ?: route('empresa.reportes.caja_diaria') }}" class="btn-return shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> VOLVER AL LISTADO
        </a>
        <div class="text-end">
             <h4 class="mb-0 fw-800 text-main-contrast"><i class="bi bi-shield-check me-2 text-success"></i>Auditoría Blindada</h4>
             <span class="text-muted small">Corte de Caja #{{ $cierre->id }}</span>
        </div>
    </div>

    <div class="row g-4">
        {{-- PANEL DE RESUMEN Y BALANCE --}}
        <div class="col-lg-4">
            <div class="oled-card p-4 h-100 d-flex flex-column">
                <div class="mb-3">
                    <div class="label-mini mb-1">Operador / Responsable</div>
                    <h4 class="fw-bold text-main-contrast mb-0">{{ $cierre->user->name }}</h4>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="label-mini">Inicio</div>
                        <div class="fw-bold small">{{ $cierre->fecha_apertura->format('d/m H:i') }} hs</div>
                    </div>
                    <div class="col-6">
                        <div class="label-mini">Cierre</div>
                        <div class="fw-bold small">{{ $cierre->fecha_cierre ? $cierre->fecha_cierre->format('d/m H:i') . ' hs' : 'ACTIVA' }}</div>
                    </div>
                </div>

                <div class="p-3 rounded-4 bg-dark bg-opacity-10 border border-secondary border-opacity-10 mb-4">
                    <div class="label-mini mb-3">Balance de Auditoría</div>
                    <div class="stat-value {{ $cierre->diferencia >= 0 ? 'neon-text-green' : 'neon-text-red' }}" style="font-size: 2.2rem;">
                        {{ $cierre->diferencia > 0 ? '+' : '' }}$ {{ number_format($cierre->diferencia, 2, ',', '.') }}
                    </div>
                    <div class="small text-muted mt-2">Diferencia entre Saldo Esperado y Real informado.</div>
                </div>

                <div class="mt-auto">
                    <div class="label-mini mb-2">Comentarios de Cierre</div>
                    <div class="p-3 rounded-3 bg-light bg-opacity-5 small italic border-start border-4 border-warning">
                         "{{ $cierre->observaciones ?: 'Sin observaciones registradas por el cajero.' }}"
                    </div>
                </div>
            </div>
        </div>

        {{-- CANALES DE PAGO Y DESGLOSE POR CUENTA --}}
        <div class="col-lg-8">
            <div class="row g-4">
                {{-- BREAKDOWN POR TIPO --}}
                <div class="col-12">
                    <div class="oled-card p-4">
                        <div class="label-mini mb-4">Ingresos / Egresos por Canal</div>
                        <div class="row text-center g-0">
                            <div class="col-3 border-end border-secondary border-opacity-10">
                                <div class="text-main-contrast fw-bold mb-1 fs-5">$ {{ number_format($breakdown['Efectivo'], 2, ',', '.') }}</div>
                                <div class="x-small text-muted text-uppercase fw-bold ls-1">Efectivo</div>
                            </div>
                            <div class="col-3 border-end border-secondary border-opacity-10">
                                <div class="text-info fw-bold mb-1 fs-5">$ {{ number_format($breakdown['Transferencias'], 2, ',', '.') }}</div>
                                <div class="x-small text-muted text-uppercase fw-bold ls-1">Digitales</div>
                            </div>
                            <div class="col-3 border-end border-secondary border-opacity-10">
                                <div class="text-warning fw-bold mb-1 fs-5">$ {{ number_format($breakdown['Cheques'], 2, ',', '.') }}</div>
                                <div class="x-small text-muted text-uppercase fw-bold ls-1">Cheques</div>
                            </div>
                            <div class="col-3">
                                <div class="text-danger fw-bold mb-1 fs-5">$ {{ number_format($cierre->egresos, 2, ',', '.') }}</div>
                                <div class="x-small text-muted text-uppercase fw-bold ls-1">Retiros/Gastos</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DETALLE DE CUENTAS --}}
                <div class="col-md-5">
                    <div class="oled-card p-4 h-100">
                        <div class="label-mini mb-4">Cajas / Bancos Impactados</div>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless align-middle">
                                <thead>
                                    <tr class="x-small text-muted text-uppercase">
                                        <th>Cuenta</th>
                                        <th class="text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resumenPorCuenta as $rc)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-main-contrast small">{{ $rc['nombre'] }}</div>
                                            <div class="x-small text-muted">{{ ucfirst($rc['tipo']) }}</div>
                                        </td>
                                        <td class="text-end fw-bold {{ $rc['balance'] >=0 ? 'text-success' : 'text-danger' }}">
                                            $ {{ number_format($rc['balance'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- MOVIMIENTOS DETALLADOS --}}
                <div class="col-md-7">
                    <div class="oled-card h-100 d-flex flex-column">
                        <div class="p-4 border-bottom border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                            <div class="label-mini">Cronología de Movimientos</div>
                            <span class="badge bg-secondary rounded-pill x-small">{{ count($movimientos) }} MOV.</span>
                        </div>
                        <div class="flex-grow-1 overflow-auto" style="max-height: 400px;">
                            @forelse($movimientos as $m)
                            <div class="movement-row">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold small text-main-contrast">{{ $m->concepto }}</div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="account-badge">{{ $m->cuenta->nombre }}</span>
                                            <span class="x-small text-muted">{{ $m->created_at->format('H:i') }} hs</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold {{ $m->tipo == 'ingreso' ? 'text-success' : 'text-danger' }}">
                                            {{ $m->tipo == 'ingreso' ? '+' : '-' }} $ {{ number_format($m->monto, 2, ',', '.') }}
                                        </div>
                                        <div class="x-small text-muted">{{ $m->categoria }}</div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="bi bi-clock-history fs-2 opacity-10"></i>
                                <p class="text-muted small mt-2">No hubo movimientos de fondos directos.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- GASTOS INFORMADOS --}}
        <div class="col-12">
            <div class="oled-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                     <div class="label-mini">Retiros de Caja / Gastos de Turno</div>
                     <span class="text-muted small">{{ count($gastos) }} registros</span>
                </div>
                <div class="table-responsive">
                    <table class="table audit-table align-middle">
                        <thead>
                            <tr class="x-small text-muted text-uppercase">
                                <th>Concepto / Proveedor</th>
                                <th>Categoría</th>
                                <th>Hora</th>
                                <th class="text-end">Monto</th>
                                <th class="text-center">Doc.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gastos as $g)
                            <tr>
                                <td>
                                    <div class="fw-bold text-main-contrast">{{ $g->provider ?: 'Gasto General' }}</div>
                                    <div class="small text-muted">{{ $g->description }}</div>
                                </td>
                                <td><span class="badge bg-dark border border-secondary">{{ $g->category->name ?? 'Varios' }}</span></td>
                                <td>{{ $g->created_at->format('H:i') }} hs</td>
                                <td class="text-end fw-bold text-danger">-$ {{ number_format($g->amount, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($g->receipt_url)
                                        <a href="{{ asset('storage/'.$g->receipt_url) }}" target="_blank" class="text-primary fs-5"><i class="bi bi-file-earmark-image"></i></a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if(count($gastos) == 0)
                            <tr><td colspan="5" class="text-center py-4 text-muted small italic">No se informaron gastos durante el turno.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .ls-1 { letter-spacing: 1px; }
    .x-small { font-size: 0.7rem; }
    .italic { font-style: italic; }
    
    .table.audit-table thead th {
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        color: var(--rr-gold);
        border-bottom: 2px solid rgba(212, 175, 55, 0.1);
        padding: 15px;
    }
    .table.audit-table tbody td {
        padding: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
</style>
@endsection
