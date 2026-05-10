@extends('layouts.empresa')

@section('content')
<style>
    :root {
        --card-bg: #ffffff;
        --border-color: rgba(0, 0, 0, 0.05);
        --text-main: #334155;
    }

    .order-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .premium-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 24px;
        margin-bottom: 24px;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-custom th {
        background: #f8fafc;
        padding: 12px 15px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid var(--border-color);
    }

    .table-custom td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-main);
    }

    .status-pill {
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .status-pendiente { background: #fef3c7; color: #92400e; }
    .status-en_proceso { background: #dbeafe; color: #1e40af; }
    .status-completado { background: #d1fae5; color: #065f46; }

    .btn-action {
        border-radius: 10px;
        font-weight: 700;
        padding: 10px 20px;
        transition: all 0.2s;
    }

    .info-label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-weight: 600;
        color: var(--text-main);
    }
</style>

<div class="order-container">
    {{-- Navegación --}}
    <div class="mb-4">
        <a href="{{ route('empresa.orders.index') }}" class="btn btn-sm btn-light border text-secondary fw-bold px-3">
            <i class="bi bi-arrow-left me-2"></i> VOLVER AL LISTADO
        </a>
    </div>

    {{-- Encabezado --}}
    <div class="order-header">
        <div>
            <h2 class="fw-bold mb-1">Gestión de Pedido #{{ $order->id }}</h2>
            <p class="text-muted small mb-0">Recibido el {{ $order->created_at->format('d/m/Y H:i') }} hs</p>
        </div>
        <div class="status-pill status-{{ $order->estado }}">
            {{ strtoupper($order->estado) }}
        </div>
    </div>

    <div class="row g-4">
        {{-- Productos --}}
        <div class="col-lg-8">
            <div class="premium-card">
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <i class="bi bi-box-seam me-2 text-primary"></i> Productos Solicitados
                </h5>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Producto / Especificación</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">P. Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $item->product->name ?? $item->product_name }}</div>
                                    @if($item->variant)
                                        <small class="text-secondary">{{ $item->variant->name }}</small>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end">$ {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">$ {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end border-0 pt-4">
                                    <span class="h5 fw-bold text-muted me-3">TOTAL DEL PEDIDO</span>
                                </td>
                                <td class="text-end border-0 pt-4">
                                    <span class="h3 fw-bold text-primary">$ {{ number_format($order->total, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="premium-card">
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <i class="bi bi-person-circle me-2 text-primary"></i> Información del Cliente y Envío
                </h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-label">Nombre del Cliente</div>
                        <div class="info-value">{{ $order->nombre_cliente }}</div>
                        <div class="mt-3">
                            <div class="info-label">Teléfono / WhatsApp</div>
                            <div class="info-value text-success">{{ $order->telefono ?? 'No informado' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Email de contacto</div>
                        <div class="info-value">{{ $order->email }}</div>
                        <div class="mt-3">
                            <div class="info-label">Método de Pago</div>
                            <div class="info-value text-uppercase">{{ $order->metodo_pago }}</div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="info-label">Destino de Envío / Modalidad</div>
                            <div class="info-value">{{ $order->direccion ?? 'RETIRO EN LOCAL' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="col-lg-4">
            <div class="premium-card">
                <h6 class="fw-bold mb-3">Actualizar Estado</h6>
                <form action="{{ route('empresa.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-3 fw-bold">
                        <option value="pendiente" {{ $order->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_proceso" {{ $order->estado == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="pedido_armado" {{ $order->estado == 'pedido_armado' ? 'selected' : '' }}>Armado</option>
                        <option value="enviado" {{ $order->estado == 'enviado' ? 'selected' : '' }}>Enviado</option>
                        <option value="entregado" {{ $order->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                        <option value="cancelado" {{ $order->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Guardar Estado</button>
                </form>
            </div>

            <div class="premium-card">
                <h6 class="fw-bold mb-3">Documentación</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('empresa.orders.picking', $order) }}" target="_blank" class="btn btn-light border text-start fw-bold p-3">
                        <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> Hoja de Armado
                    </a>
                    <a href="{{ route('empresa.orders.label', $order) }}" target="_blank" class="btn btn-light border text-start fw-bold p-3">
                        <i class="bi bi-qr-code-scan me-2 text-dark"></i> Etiqueta de Envío
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
