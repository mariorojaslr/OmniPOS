@extends('layouts.app')

@section('styles')
<style>
    .dashboard-header {
        margin-bottom: 2rem;
    }
    .header-title {
        background: linear-gradient(135deg, #f8fafc, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        letter-spacing: -1px;
    }
    .stat-card {
        padding: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.4);
    }
    .stat-icon {
        position: absolute;
        top: -10px;
        right: -10px;
        font-size: 5rem;
        opacity: 0.05;
        transform: rotate(-15deg);
    }
    .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #f8fafc;
        line-height: 1.1;
    }
    .text-glow-primary { text-shadow: 0 0 15px rgba(59, 130, 246, 0.5); color: #60a5fa !important; }
    .text-glow-success { text-shadow: 0 0 15px rgba(34, 197, 94, 0.5); color: #4ade80 !important; }
    .text-glow-warning { text-shadow: 0 0 15px rgba(234, 179, 8, 0.5); color: #facc15 !important; }
    .text-glow-danger { text-shadow: 0 0 15px rgba(239, 68, 68, 0.5); color: #f87171 !important; }
    .text-glow-purple { text-shadow: 0 0 15px rgba(168, 85, 247, 0.5); color: #c084fc !important; }

    .action-btn {
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        transition: all 0.3s;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
        background: rgba(255,255,255,0.05);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .action-btn:hover {
        background: rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.5);
        color: #fff;
        transform: translateY(-2px);
    }
    .table-dark-custom {
        --bs-table-bg: transparent;
        --bs-table-color: #cbd5e1;
        --bs-table-border-color: rgba(255,255,255,0.05);
    }
</style>
@endsection

@section('content')
<div class="px-3">

    {{-- Encabezado --}}
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="header-title mb-1">Centro de Comando Owner</h2>
            <p class="text-muted mb-0" style="color: #64748b !important;">Visión omnisciente y control global de la infraestructura MultiPOS.</p>
        </div>
        <div>
            <a href="{{ route('owner.empresas.create') }}" class="btn btn-primary shadow-lg" style="border-radius:12px;">
                + Nueva Empresa
            </a>
        </div>
    </div>

    {{-- Row 1: Finanzas & Suscripciones (Métricas Principales) --}}
    <div class="row g-4 mb-4">
        
        <div class="col-md-3">
            <div class="glass-card stat-card h-100" style="border-left: 4px solid #3b82f6;">
                <div class="stat-icon text-primary">🏢</div>
                <div class="stat-label">Total Clientes</div>
                <div class="stat-value text-glow-primary">{{ $empresasCount }}</div>
                <div class="mt-2 small text-muted">
                    <span class="text-success"><i class="bi bi-circle-fill" style="font-size:8px;"></i> {{ $empresasActivas }} Activas</span>
                    <span class="ms-2 text-danger"><i class="bi bi-circle-fill" style="font-size:8px;"></i> {{ $empresasVencidas }} Vencidas</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="glass-card stat-card h-100" style="border-left: 4px solid #a855f7;">
                <div class="stat-icon text-purple">👥</div>
                <div class="stat-label">Usuarios Globales</div>
                <div class="stat-value text-glow-purple">{{ $usuariosCount }}</div>
                <div class="mt-2 small text-muted">A lo largo de todas las empresas</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="glass-card stat-card h-100" style="border-left: 4px solid #22c55e;">
                <div class="stat-icon text-success">💳</div>
                <div class="stat-label">MRR (Suscripciones)</div>
                <div class="stat-value text-glow-success">{{ $mrr }}</div>
                <div class="mt-2 small text-muted">Ingreso Mensual Recurrente</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="glass-card stat-card h-100" style="border-left: 4px solid #eab308;">
                <div class="stat-icon text-warning">💰</div>
                <div class="stat-label">Facturación del Mes</div>
                <div class="stat-value text-glow-warning">{{ $facturacionMes }}</div>
                <div class="mt-2 small text-muted">Pagos únicos y renovaciones</div>
            </div>
        </div>
    </div>

    {{-- Row 2: Infraestructura y Consumos (Nube Privada) --}}
    <h5 class="fw-bold mb-3 mt-5" style="color: #cbd5e1;">Infraestructura & Nube Privada</h5>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="glass-card stat-card h-100">
                <div class="stat-label text-info">Storage Usado</div>
                <div class="fs-3 fw-bold text-white">{{ $consumoStorage }}</div>
                <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                    <div class="progress-bar bg-info" style="width: 45%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card stat-card h-100">
                <div class="stat-label text-info">Tráfico / CDN</div>
                <div class="fs-3 fw-bold text-white">{{ $consumoTrafico }}</div>
                <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card stat-card h-100">
                <div class="stat-label text-info">Stream de Video</div>
                <div class="fs-3 fw-bold text-white">{{ $streamingMensual }}</div>
                <div class="mt-2 small text-muted">{{ $archivosSubidos }} archivos totales</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card stat-card h-100">
                <div class="stat-label text-info">Imágenes Subidas</div>
                <div class="fs-3 fw-bold text-white">{{ $imagenesSubidas }}</div>
                <div class="mt-2 small text-muted">Productos y configuración</div>
            </div>
        </div>
    </div>

    {{-- Row 3: Enlaces Rápidos y Panel de Control --}}
    <div class="row g-4 mt-4 mb-5">
        <div class="col-md-12">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4" style="color: #cbd5e1;">Acciones Administrativas</h5>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('owner.empresas.index') }}" class="action-btn">
                        🏢 Gestionar Clientes y Empresas
                    </a>
                    <a href="{{ route('owner.facturacion.index') }}" class="action-btn">
                        🧾 Sistema de Facturación
                    </a>
                    <a href="{{ route('owner.soporte.index') }}" class="action-btn">
                        🎧 Tickets de Soporte
                    </a>
                    <a href="{{ route('owner.planes.index') }}" class="action-btn">
                        ⚙️ Configurar Planes de Suscripción
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
