@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --oled-black: #000000;
        --reseller-cyan: #22d3ee;
        --glass-border: rgba(255, 255, 255, 0.4);
    }

    body {
        background-color: var(--oled-black) !important;
        font-family: 'Outfit', sans-serif;
    }

    .revendedor-bg { position: relative; min-height: 100vh; z-index: 1; }
    .revendedor-bg::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background:
            radial-gradient(ellipse at 10% 0%, rgba(34, 211, 238, 0.08) 0%, transparent 45%),
            radial-gradient(ellipse at 90% 90%, rgba(168, 85, 247, 0.05) 0%, transparent 45%);
        pointer-events: none;
        z-index: 0;
    }

    .oled-card {
        background: rgba(15, 20, 35, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px; padding: 1.5rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.5);
        transition: all 0.3s ease;
    }
    .oled-card:hover { border-color: rgba(255,255,255,0.8); }

    .kpi-card {
        background: rgba(15, 20, 35, 0.7);
        border: 1px solid var(--glass-border);
        border-radius: 14px; padding: 1.2rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    .kpi-card:hover { transform: translateY(-3px); border-color: var(--reseller-cyan); }
    .kpi-value { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .kpi-label { font-size: 0.65rem; letter-spacing: 1.5px; color: #94a3b8; font-weight: 700; text-transform: uppercase; }

    .header-title {
        font-weight: 800; font-size: 2rem;
        background: linear-gradient(135deg, #fff 0%, var(--reseller-cyan) 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }

    .company-row {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px; padding: 0.8rem; margin-bottom: 0.5rem;
    }

    .status-badge {
        font-size: 0.55rem; padding: 3px 8px; border-radius: 6px; font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="revendedor-bg px-3 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-muted" style="font-size: 0.7rem; letter-spacing: 2px;">PANEL DE REVENDEDOR</span>
            <h1 class="header-title">Mi Cartera de Clientes</h1>
        </div>
        <div class="text-end">
            <div class="text-muted" style="font-size: 0.7rem;">COMISIÓN ESTIMADA</div>
            <div class="kpi-value" style="color: #22c55e;">${{ number_format($comisionEstimada, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-value">{{ $totalEmpresas }}</div>
                <div class="kpi-label">Empresas Totales</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-value text-info">{{ $nuevasEsteMes }}</div>
                <div class="kpi-label">Altas este Mes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-value text-success">${{ number_format($mrr, 0, ',', '.') }}</div>
                <div class="kpi-label">MRR de Cartera</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-value text-warning">{{ $tasaComision }}%</div>
                <div class="kpi-label">Tasa de Comisión</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="oled-card">
                <h5 class="text-white fw-bold mb-3" style="font-size: 0.9rem;">MIS EMPRESAS ASIGNADAS</h5>
                @forelse($empresas as $emp)
                <div class="company-row d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white fw-bold">{{ $emp->nombre_comercial }}</div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="status-badge" style="background: rgba(34, 211, 238, 0.1); color: #22d3ee;">{{ $emp->plan->name ?? 'Plan Personal' }}</span>
                            <span class="text-muted" style="font-size: 0.6rem;">{{ $emp->slug }}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="text-white fw-bold" style="font-size: 0.8rem;">${{ number_format($emp->custom_price ?? ($emp->plan->price ?? 0), 0, ',', '.') }}/mes</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="status-badge" style="background: {{ $emp->activo ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $emp->activo ? '#22c55e' : '#ef4444' }};">
                                {{ $emp->activo ? 'ACTIVA' : 'INACTIVA' }}
                            </span>
                            <a href="{{ route('revendedor.empresas.config', $emp->id) }}" class="btn btn-sm btn-outline-light py-0 px-2" style="font-size: 0.6rem; border-radius: 4px;">
                                <i class="bi bi-gear-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-building text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2">Aún no tienes empresas asignadas.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="oled-card mb-3">
                <h5 class="text-white fw-bold mb-3" style="font-size: 0.9rem;">ÚLTIMOS COBROS</h5>
                @forelse($ultimosPagos as $pago)
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded" style="background: rgba(255,255,255,0.02);">
                    <div>
                        <div class="text-white" style="font-size: 0.75rem;">{{ $pago->empresa->nombre_comercial }}</div>
                        <div class="text-muted" style="font-size: 0.6rem;">{{ $pago->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div class="text-success fw-bold" style="font-size: 0.75rem;">+${{ number_format($pago->amount, 0, ',', '.') }}</div>
                </div>
                @empty
                <p class="text-muted text-center" style="font-size: 0.7rem;">Sin pagos registrados recientemente.</p>
                @endforelse
            </div>

            <div class="oled-card">
                <h5 class="text-white fw-bold mb-3" style="font-size: 0.9rem;">ACCIONES RÁPIDAS</h5>
                <button class="btn btn-outline-info w-100 mb-2 py-2 fw-bold" style="font-size: 0.75rem; border-radius: 10px;">
                    <i class="bi bi-plus-circle me-2"></i> SOLICITAR ALTA EMPRESA
                </button>
                <button class="btn btn-outline-secondary w-100 py-2 fw-bold" style="font-size: 0.75rem; border-radius: 10px;">
                    <i class="bi bi-headset me-2"></i> SOPORTE TÉCNICO
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
