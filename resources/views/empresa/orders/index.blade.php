@extends('layouts.empresa')

@section('content')
<style>
    :root {
        --bg-light: #f4f6f9;
        --card-white: #ffffff;
        --accent: {{ $empresa->config->color_primary ?? '#0d6efd' }};
        --table-border: #eef2f7;
    }

    body {
        background-color: var(--bg-light) !important;
        color: #334155;
    }

    .main-content {
        background-color: var(--bg-light) !important;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--table-border);
        padding: 20px;
    }

    .premium-table thead {
        background: #f8fafc;
        border-bottom: 2px solid var(--table-border);
    }

    .premium-table th {
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px;
        border: none;
    }

    .premium-table td {
        padding: 15px;
        border-bottom: 1px solid var(--table-border);
        color: #1e293b;
    }

    .premium-table tr:hover td {
        background-color: #f8fafc;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
    }

    /* Estados claros */
    .status-pendiente { background: #fef3c7; color: #92400e; }
    .status-en_proceso { background: #dbeafe; color: #1e40af; }
    .status-pedido_armado { background: #f3e8ff; color: #6b21a8; }
    .status-enviado { background: #cffafe; color: #155e75; }
    .status-entregado { background: #d1fae5; color: #065f46; }
    .status-cancelado { background: #fee2e2; color: #991b1b; }

    .glass-input {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
    }

</style>

<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold mb-0">Pedidos de Catálogo</h2>
        <p class="text-secondary small mb-0">Gestión de ventas online recibidas</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <div class="stat-card d-inline-block">
            <span class="text-secondary small d-block">TOTAL PEDIDOS</span>
            <span class="h4 fw-bold mb-0">{{ $orders->total() }}</span>
        </div>
    </div>
</div>

<!-- Filtros Clásicos -->
<div class="stat-card mb-4">
    <form action="{{ route('empresa.orders.index') }}" method="GET" class="row g-3">
        <div class="col-lg-5 col-md-4">
            <input type="text" name="search" class="form-control glass-input" placeholder="Buscar por cliente o ID..." value="{{ request('search') }}">
        </div>
        <div class="col-lg-3 col-md-4">
            <select name="status" class="form-select glass-input">
                <option value="">Cualquier estado</option>
                <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="en_proceso" {{ request('status') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                <option value="pedido_armado" {{ request('status') == 'pedido_armado' ? 'selected' : '' }}>Pedido Armado</option>
                <option value="enviado" {{ request('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                <option value="entregado" {{ request('status') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <button type="submit" class="btn btn-primary w-100 fw-bold">Filtrar</button>
        </div>
        <div class="col-lg-2">
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('empresa.orders.index') }}" class="btn btn-outline-secondary w-100 border-0">Limpiar</a>
            @endif
        </div>
    </form>
</div>

<!-- Listado de Pedidos -->
<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
    <div class="table-responsive">
        <table class="table premium-table align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Orden</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th class="text-end pe-4">Gestión</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="ps-4">
                        <span class="fw-bold">#{{ $order->id }}</span>
                    </td>
                    <td>
                        <div>{{ $order->created_at->format('d/m/Y') }}</div>
                        <small class="text-secondary">{{ $order->created_at->format('H:i') }} hs</small>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $order->nombre_cliente }}</div>
                        <small class="text-secondary">{{ $order->email }}</small>
                    </td>
                    <td>
                        <span class="badge-status status-{{ $order->estado }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">$ {{ number_format($order->total, 0, ',', '.') }}</div>
                        <small class="text-muted text-uppercase" style="font-size: 0.65rem;">{{ $order->metodo_pago }}</small>
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('empresa.orders.show', $order) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                            Gestionar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-secondary">
                        No se encontraron pedidos.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-top">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection
