@extends('layouts.app')

@section('content')
<style>
    :root {
        --oled-bg: #000000;
        --oled-card: #0a0a0a;
        --oled-border: rgba(255, 255, 255, 0.1);
        --accent-color: #3b82f6;
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

    .card-title-premium {
        color: #fff;
        font-weight: 800;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid var(--oled-border) !important;
        color: white !important;
        border-radius: 12px !important;
    }

    .premium-table th {
        color: #9ca3af;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1px solid var(--oled-border);
        padding: 15px;
    }

    .premium-table td {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: #e5e7eb;
    }

    .badge-status-lg {
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .status-pendiente { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); }
    .status-en_proceso { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); }
    .status-pedido_armado { background: rgba(139, 92, 246, 0.2); color: #a78bfa; border: 1px solid rgba(139, 92, 246, 0.4); }
    .status-enviado { background: rgba(6, 182, 212, 0.2); color: #22d3ee; border: 1px solid rgba(6, 182, 212, 0.4); }
    .status-entregado { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
    .status-cancelado { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }

    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #6b7280;
        font-weight: 800;
        letter-spacing: 1px;
        margin-bottom: 5px;
        display: block;
    }

    .info-value {
        color: #f3f4f6;
        font-weight: 600;
        font-size: 1rem;
    }

    .btn-action-premium {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--oled-border);
        color: white;
        padding: 15px;
        border-radius: 15px;
        transition: all 0.3s ease;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 15px;
        width: 100%;
        margin-bottom: 12px;
    }

    .btn-action-premium:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--accent-color);
        transform: translateX(5px);
        color: white;
    }

    .action-icon {
        width: 40px;
        height: 40px;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-color);
    }
</style>

<div class="premium-header">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('empresa.orders.index') }}" class="text-secondary text-decoration-none small mb-2 d-inline-block">
                    <i class="fas fa-chevron-left me-1"></i> VOLVER AL LISTADO
                </a>
                <h1 class="display-6 fw-bold text-white mb-0">Gestión de Pedido #{{ $order->id }}</h1>
            </div>
            <div class="badge-status-lg status-{{ $order->estado }}">
                {{ $order->status_label }}
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Items del Pedido -->
            <div class="oled-card mb-4">
                <div class="p-4 border-bottom border-secondary border-opacity-10">
                    <h5 class="card-title-premium mb-0">
                        <div class="action-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;"><i class="fas fa-shopping-bag"></i></div>
                        Productos Solicitados
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table premium-table mb-0">
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
                                    <div class="fw-bold text-white fs-6">{{ $item->product->name }}</div>
                                    @if($item->variant)
                                        <div class="small text-secondary mt-1">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                                {{ $item->variant->size }} / {{ $item->variant->color }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center fs-5 fw-bold text-white">{{ $item->cantidad }}</td>
                                <td class="text-end text-secondary">$ {{ number_format($item->precio, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-white fs-5">$ {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end py-4 text-secondary text-uppercase fw-bold letter-spacing-1">Total del Pedido</td>
                                <td class="text-end py-4 fw-bold text-white display-6" style="color: var(--accent-color) !important;">
                                    $ {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Datos del Cliente -->
            <div class="oled-card">
                <div class="p-4 border-bottom border-secondary border-opacity-10">
                    <h5 class="card-title-premium mb-0">
                        <div class="action-icon"><i class="fas fa-user-tie"></i></div>
                        Información del Cliente y Envío
                    </h5>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="info-label">Nombre del Cliente</label>
                            <div class="info-value">{{ $order->nombre_cliente }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Email de Contacto</label>
                            <div class="info-value">{{ $order->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Teléfono / WhatsApp</label>
                            <div class="info-value">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->telefono) }}" target="_blank" class="text-success text-decoration-none">
                                    <i class="fab fa-whatsapp me-1"></i> {{ $order->telefono }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Método de Pago Seleccionado</label>
                            <div class="info-value text-uppercase text-info">{{ $order->metodo_pago }}</div>
                        </div>
                        <div class="col-12">
                            <label class="info-label">Destino de Envío / Modalidad</label>
                            <div class="p-3 fs-5 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid var(--oled-border);">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                {{ $order->direccion ?: 'Retiro en Sucursal / Local' }}
                                <div class="mt-2 text-secondary small text-uppercase">
                                    Método: {{ $order->metodo_entrega }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Cambio de Estado -->
            <div class="oled-card mb-4">
                <div class="p-4 border-bottom border-secondary border-opacity-10">
                    <h5 class="card-title-premium mb-0">Actualizar Estado</h5>
                </div>
                <div class="p-4">
                    <form action="{{ route('empresa.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <select name="status" class="form-select glass-input p-3 fs-6" onchange="this.form.submit()" style="background-color: #111 !important;">
                                <option value="pendiente" {{ $order->estado == 'pendiente' ? 'selected' : '' }}>🕒 Pendiente</option>
                                <option value="en_proceso" {{ $order->estado == 'en_proceso' ? 'selected' : '' }}>⚙️ En Proceso</option>
                                <option value="pedido_armado" {{ $order->estado == 'pedido_armado' ? 'selected' : '' }}>📦 Pedido Armado</option>
                                <option value="enviado" {{ $order->estado == 'enviado' ? 'selected' : '' }}>🚚 Enviado</option>
                                <option value="entregado" {{ $order->estado == 'entregado' ? 'selected' : '' }}>✅ Entregado</option>
                                <option value="cancelado" {{ $order->estado == 'cancelado' ? 'selected' : '' }}>❌ Cancelar Pedido</option>
                            </select>
                        </div>
                        <div class="alert alert-info bg-opacity-10 border-info border-opacity-20 text-info small">
                            <i class="fas fa-info-circle me-2"></i> 
                            Al pasar a <b>En Proceso</b> o <b>Armado</b>, el sistema descontará el stock automáticamente.
                        </div>
                    </form>
                </div>
            </div>

            <!-- Imprimibles y Documentación -->
            <div class="oled-card">
                <div class="p-4 border-bottom border-secondary border-opacity-10">
                    <h5 class="card-title-premium mb-0">Documentación</h5>
                </div>
                <div class="p-4">
                    <a href="{{ route('empresa.orders.picking', $order) }}" target="_blank" class="btn-action-premium">
                        <div class="action-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fas fa-clipboard-list"></i></div>
                        <div>
                            <div class="fw-bold">Hoja de Armado</div>
                            <div class="small text-secondary">Packing list para depósito</div>
                        </div>
                    </a>

                    <a href="{{ route('empresa.orders.label', $order) }}" target="_blank" class="btn-action-premium">
                        <div class="action-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fas fa-print"></i></div>
                        <div>
                            <div class="fw-bold">Etiqueta de Envío</div>
                            <div class="small text-secondary">QR y datos de entrega</div>
                        </div>
                    </a>

                    @if($order->venta_id)
                        <a href="{{ route('empresa.ventas.pdf', $order->venta_id) }}" target="_blank" class="btn-action-premium">
                            <div class="action-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div>
                                <div class="fw-bold">Comprobante de Venta</div>
                                <div class="small text-secondary">Documento fiscal generado</div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
