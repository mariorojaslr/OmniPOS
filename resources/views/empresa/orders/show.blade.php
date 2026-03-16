@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Detalle del Pedido -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Detalle del Pedido #{{ $order->id }}</h5>
                    <span class="badge bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->product->name }}</div>
                                        @if($item->variant)
                                            <small class="text-muted">Variante: {{ $item->variant->size }} / {{ $item->variant->color }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->cantidad }}</td>
                                    <td class="text-end">${{ number_format($item->precio, 2) }}</td>
                                    <td class="text-end fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fs-5 fw-bold">TOTAL</td>
                                    <td class="text-end fs-5 fw-bold text-primary">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Datos del Cliente -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Información de Envío / Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted d-block">Cliente</label>
                            <span class="fw-bold">{{ $order->nombre_cliente }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted d-block">Email / Teléfono</label>
                            <span>{{ $order->email }} / {{ $order->telefono }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted d-block">Método de Entrega</label>
                            <span class="badge bg-light text-dark border">{{ strtoupper($order->metodo_entrega) }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted d-block">Método de Pago</label>
                            <span class="badge bg-light text-dark border">{{ strtoupper($order->metodo_pago) }}</span>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block">Dirección de Envío</label>
                            <div class="p-3 bg-light rounded border">
                                {{ $order->direccion ?: 'Retiro en Local' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones y Estados -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">Acciones</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('empresa.orders.updateStatus', $order) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <label class="form-label fw-bold">Cambiar Estado</label>
                        <select name="status" class="form-select mb-3" onchange="this.form.submit()">
                            <option value="pendiente" {{ $order->estado == 'pendiente' ? 'selected' : '' }}>Pendiente / Recibido</option>
                            <option value="en_proceso" {{ $order->estado == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="pedido_armado" {{ $order->estado == 'pedido_armado' ? 'selected' : '' }}>Pedido Armado</option>
                            <option value="enviado" {{ $order->estado == 'enviado' ? 'selected' : '' }}>Enviado</option>
                            <option value="entregado" {{ $order->estado == 'entregado' ? 'selected' : '' }}>Entregado (Finaliza)</option>
                            <option value="cancelado" {{ $order->estado == 'cancelado' ? 'selected' : '' }}>Cancelar Pedido</option>
                        </select>
                        <p class="small text-muted">
                            <i class="fas fa-info-circle"></i> Cambiar a "En Proceso" o "Armado" descontará automáticamente el stock del inventario.
                        </p>
                    </form>

                    <div class="d-grid gap-2">
                        <a href="{{ route('empresa.orders.picking', $order) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Imprimir Hoja de Armado
                        </a>
                        <a href="{{ route('empresa.orders.label', $order) }}" target="_blank" class="btn btn-outline-success">
                            <i class="fas fa-tag"></i> Imprimir Etiqueta de Caja
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <a href="{{ route('empresa.orders.index') }}" class="btn btn-link link-dark text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
