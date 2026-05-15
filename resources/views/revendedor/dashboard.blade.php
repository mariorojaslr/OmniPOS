@extends('layouts.reseller')

@section('styles')
<style>
    .reseller-header {
        background: linear-gradient(135deg, rgba(34, 211, 238, 0.1) 0%, transparent 100%);
        border-bottom: 1px solid rgba(34, 211, 238, 0.2);
        padding: 40px 30px;
        margin-top: -20px;
        margin-bottom: 30px;
    }
    .glass-card {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 2px solid rgba(255, 255, 255, 0.6);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        height: 100%;
        transition: all 0.3s ease;
    }
    .glass-card:hover {
        border-color: var(--reseller-cyan);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(34, 211, 238, 0.15);
    }
    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }
    .stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 8px;
    }
    .company-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 15px 20px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }
    .company-item:hover {
        background: rgba(34, 211, 238, 0.08);
        border-color: var(--reseller-cyan);
    }
    .btn-action {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 12px;
        border-radius: 12px;
        transition: all 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-action:hover {
        background: var(--reseller-cyan);
        color: #000;
        border-color: var(--reseller-cyan);
    }
    .commission-badge {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        padding: 5px 12px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.7rem;
    }
</style>
@endsection

@section('content')
<div class="reseller-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="badge mb-2" style="background: rgba(34, 211, 238, 0.2); color: #22d3ee; letter-spacing: 2px;">SISTEMA DE PARTNERS V2.0</span>
                <h1 class="fw-800" style="font-size: 2.5rem; color: #fff;">Mi Cartera Pro</h1>
                <p class="text-muted">Gestiona tus empresas asignadas y monitorea tus comisiones en tiempo real.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-inline-block text-start p-3 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1);">
                    <div class="stat-label mt-0">Comisión Acumulada (Mes)</div>
                    <div class="stat-value text-success">${{ number_format($comisionEstimada, 0, ',', '.') }}</div>
                    <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.1);">
                        <div class="progress-bar bg-success" style="width: 65%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-5">
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-card text-center">
                <div class="stat-value">{{ $totalEmpresas }}</div>
                <div class="stat-label">Empresas Activas</div>
                <div class="mt-3 opacity-50"><i class="bi bi-building fs-3"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card text-center">
                <div class="stat-value text-info">{{ $nuevasEsteMes }}</div>
                <div class="stat-label">Nuevas Altas (30d)</div>
                <div class="mt-3 opacity-50"><i class="bi bi-graph-up-arrow fs-3"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card text-center">
                <div class="stat-value text-warning">${{ number_format($mrr, 0, ',', '.') }}</div>
                <div class="stat-label">MRR de Cartera</div>
                <div class="mt-3 opacity-50"><i class="bi bi-cash-stack fs-3"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card text-center">
                <div class="stat-value text-primary">{{ $tasaComision }}%</div>
                <div class="stat-label">Tu Tasa Partner</div>
                <div class="mt-3 opacity-50"><i class="bi bi-award fs-3"></i></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- LISTADO DE EMPRESAS --}}
        <div class="col-lg-8">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-800 m-0"><i class="bi bi-list-stars me-2 text-info"></i> LISTADO DE CLIENTES</h5>
                    <div class="input-group input-group-sm w-auto">
                        <span class="input-group-text bg-transparent border-secondary text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-transparent border-secondary text-white" placeholder="Buscar empresa...">
                    </div>
                </div>

                @forelse($empresas as $emp)
                <div class="company-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(34, 211, 238, 0.1); color: var(--reseller-cyan); border: 1px solid rgba(34, 211, 238, 0.2);">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white fs-6">{{ $emp->nombre_comercial }}</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-dark border border-secondary text-muted" style="font-size: 0.6rem;">{{ $emp->plan->name ?? 'Plan Custom' }}</span>
                                <span class="text-muted" style="font-size: 0.6rem;">ID: #{{ str_pad($emp->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <div class="text-end d-none d-md-block">
                            <div class="stat-label mt-0" style="font-size: 0.55rem;">PAGO MENSUAL</div>
                            <div class="fw-800 text-white">${{ number_format($emp->custom_price ?? ($emp->plan->price ?? 0), 0, ',', '.') }}</div>
                        </div>
                        <div class="text-end">
                            <span class="commission-badge">+${{ number_format(($emp->custom_price ?? ($emp->plan->price ?? 0)) * ($tasaComision/100), 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('revendedor.empresas.config', $emp->id) }}" class="btn btn-sm btn-outline-info" title="Configurar Módulos">
                                <i class="bi bi-sliders"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-light" title="Ver Reportes">
                                <i class="bi bi-bar-chart"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-building-dash text-muted fs-1"></i>
                    <p class="text-muted mt-3">No hay empresas en tu cartera aún.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ACCIONES Y PAGOS --}}
        <div class="col-lg-4">
            <div class="glass-card mb-4" style="border-color: rgba(34, 211, 238, 0.3);">
                <h5 class="fw-800 mb-4"><i class="bi bi-lightning-charge me-2 text-warning"></i> ACCIONES MASTER</h5>
                <div class="d-flex flex-column gap-2">
                    <a href="#" class="btn-action">
                        <i class="bi bi-plus-square"></i> ALTA DE NUEVA EMPRESA
                    </a>
                    <a href="#" class="btn-action">
                        <i class="bi bi-file-earmark-medical"></i> SOLICITAR MÓDULO MÉDICO
                    </a>
                    <a href="#" class="btn-action">
                        <i class="bi bi-megaphone"></i> REPORTAR INCIDENCIA
                    </a>
                </div>
            </div>

            <div class="glass-card">
                <h5 class="fw-800 mb-4"><i class="bi bi-wallet2 me-2 text-success"></i> ÚLTIMOS PAGOS RECIBIDOS</h5>
                @forelse($ultimosPagos as $pago)
                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-success fs-4"><i class="bi bi-check-circle-fill"></i></div>
                        <div>
                            <div class="text-white fw-bold" style="font-size: 0.8rem;">{{ $pago->empresa->nombre_comercial }}</div>
                            <div class="text-muted" style="font-size: 0.6rem;">{{ $pago->created_at->format('d M, Y') }}</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-800 text-success" style="font-size: 0.85rem;">+${{ number_format($pago->amount, 0, ',', '.') }}</div>
                        <div class="text-muted" style="font-size: 0.55rem;">LIQUIDADO</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 opacity-50">
                    <i class="bi bi-clock-history fs-2"></i>
                    <p class="small mt-2">Esperando primer cobro...</p>
                </div>
                @endforelse
                <a href="#" class="btn btn-sm btn-link text-info text-decoration-none w-100 text-center mt-2 fw-bold" style="font-size: 0.7rem;">VER HISTORIAL COMPLETO</a>
            </div>
        </div>
    </div>
</div>
@endsection
