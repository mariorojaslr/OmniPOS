@extends('layouts.app')

@section('content')
<style>
    :root {
        --oled-bg: #000000;
        --oled-card: #0a0a0a;
        --oled-border: rgba(255, 255, 255, 0.1);
        --accent-color: #3b82f6; /* Azul eléctrico premium */
        --accent-glow: rgba(59, 130, 246, 0.3);
    }

    body {
        background-color: var(--oled-bg) !important;
        color: #e5e7eb;
    }

    .main-content {
        background-color: var(--oled-bg) !important;
    }

    .premium-header {
        background: linear-gradient(180deg, rgba(59, 130, 246, 0.1) 0%, rgba(0, 0, 0, 0) 100%);
        padding: 40px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--oled-border);
    }

    .oled-card {
        background: var(--oled-card);
        border: 1px solid var(--oled-border);
        border-radius: 20px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .oled-card:hover {
        border-color: rgba(59, 130, 246, 0.4);
        box-shadow: 0 0 20px var(--accent-glow);
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid var(--oled-border) !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 12px 20px !important;
        transition: all 0.3s ease;
    }

    .glass-input:focus {
        background: rgba(255, 255, 255, 0.07) !important;
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 10px var(--accent-glow) !important;
    }

    .glass-select {
        background: rgba(255, 255, 255, 0.03) url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px !important;
        border: 1px solid var(--oled-border) !important;
        color: white !important;
        border-radius: 12px !important;
    }

    .btn-premium {
        background: var(--accent-color);
        border: none;
        color: white;
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px var(--accent-glow);
        filter: brightness(1.1);
    }

    .premium-table thead {
        background: rgba(255, 255, 255, 0.02);
        border-bottom: 2px solid var(--oled-border);
    }

    .premium-table th {
        color: #9ca3af;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 20px 15px;
        border: none;
    }

    .premium-table td {
        color: #e5e7eb;
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        background: transparent;
    }

    .premium-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    .badge-status {
        padding: 8px 15px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pendiente { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .status-en_proceso { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
    .status-pedido_armado { background: rgba(139, 92, 246, 0.15); color: #a78bfa; border: 1px solid rgba(139, 92, 246, 0.3); }
    .status-enviado { background: rgba(6, 182, 212, 0.15); color: #22d3ee; border: 1px solid rgba(6, 182, 212, 0.3); }
    .status-entregado { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .status-cancelado { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

    .pagination .page-link {
        background: var(--oled-card);
        border-color: var(--oled-border);
        color: #9ca3af;
    }

    .pagination .page-item.active .page-link {
        background: var(--accent-color);
        border-color: var(--accent-color);
        color: white;
    }
</style>

<div class="premium-header">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-6 fw-bold text-white mb-2">Pedidos de Catálogo</h1>
                <p class="text-secondary mb-0">Gestión profesional de ventas entrantes</p>
            </div>
            <div class="col-md-6 text-md-end mt-4 mt-md-0">
                <div class="d-inline-block p-3 oled-card border-0" style="background: rgba(59, 130, 246, 0.15);">
                    <span class="h4 fw-bold text-white mb-0">{{ $orders->total() }}</span>
                    <span class="small text-secondary d-block">Pedidos Totales</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-5">
    <!-- Filtros Avanzados -->
    <div class="oled-card mb-5 p-4">
        <form action="{{ route('empresa.orders.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-lg-5 col-md-4">
                <label class="small text-secondary fw-bold mb-2 text-uppercase">Buscar Cliente u Orden</label>
                <div class="position-relative">
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-secondary">🔍</span>
                    <input type="text" name="search" class="form-control glass-input ps-5" placeholder="Nombre, email o ID de pedido..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="small text-secondary fw-bold mb-2 text-uppercase">Estado actual</label>
                <select name="status" class="form-select glass-input glass-select">
                    <option value="">Cualquier estado</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>🕒 Pendiente / Recibido</option>
                    <option value="en_proceso" {{ request('status') == 'en_proceso' ? 'selected' : '' }}>⚙️ En Proceso</option>
                    <option value="pedido_armado" {{ request('status') == 'pedido_armado' ? 'selected' : '' }}>📦 Pedido Armado</option>
                    <option value="enviado" {{ request('status') == 'enviado' ? 'selected' : '' }}>🚚 Enviado</option>
                    <option value="entregado" {{ request('status') == 'entregado' ? 'selected' : '' }}>✅ Entregado</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>❌ Cancelado</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <button type="submit" class="btn btn-premium w-100">Filtrar</button>
            </div>
            <div class="col-lg-2">
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('empresa.orders.index') }}" class="btn btn-outline-secondary w-100 border-0 text-decoration-none py-2 mt-2">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Pedidos -->
    <div class="oled-card border-0">
        <div class="table-responsive">
            <table class="table premium-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Identificador</th>
                        <th>Fecha y Hora</th>
                        <th>Cliente</th>
                        <th>Estado Logístico</th>
                        <th>Importe Total</th>
                        <th class="text-end pe-4">Gestión</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-white fs-5">#{{ $order->id }}</span>
                        </td>
                        <td>
                            <div class="text-white">{{ $order->created_at->format('d M, Y') }}</div>
                            <div class="small text-secondary text-uppercase">{{ $order->created_at->format('H:i') }} hs</div>
                        </td>
                        <td>
                            <div class="fw-bold text-white fs-6">{{ $order->nombre_cliente }}</div>
                            <div class="small text-secondary">{{ $order->email }}</div>
                        </td>
                        <td>
                            <span class="badge-status status-{{ $order->estado }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-white fs-5">$ {{ number_format($order->total, 0, ',', '.') }}</div>
                            <small class="text-secondary text-uppercase">{{ $order->metodo_pago }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('empresa.orders.show', $order) }}" class="btn btn-outline-light btn-sm rounded-pill px-4 border shadow-sm">
                                <i class="fas fa-eye me-1"></i> Gestionar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <div class="display-1 mb-3">📭</div>
                                <h4 class="text-white fw-bold">No hay pedidos registrados</h4>
                                <p class="text-secondary">Los pedidos que los clientes realicen por el catálogo aparecerán aquí.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-4 border-top border-secondary opacity-25">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
