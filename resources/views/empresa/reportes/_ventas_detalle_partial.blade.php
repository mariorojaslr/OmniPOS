<div class="p-3 bg-light border-bottom">
    <div class="table-responsive">
        <table class="table table-sm table-borderless mb-0">
            <thead class="text-secondary small text-uppercase">
                <tr>
                    <th>Hora</th>
                    <th>Vendedor</th>
                    <th>Comprobante</th>
                    <th>Ítems / Detalle</th>
                    <th class="text-end">Monto total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                    <tr class="border-top">
                        <td class="align-top py-2">
                            <i class="bi bi-clock me-1 text-muted"></i>
                            {{ $venta->created_at->format('H:i') }}
                        </td>
                        <td class="align-top py-2">
                            {{ $venta->user->name ?? 'N/A' }}
                        </td>
                        <td class="align-top py-2">
                            <span class="badge bg-white text-dark border">
                                #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="py-2">
                            <ul class="list-unstyled mb-0 small">
                                @foreach($venta->items as $item)
                                    <li class="mb-1">
                                        <span class="text-primary fw-bold">{{ $item->cantidad }}x</span>
                                        {{ $item->product ? $item->product->name : 'Producto Eliminado' }}
                                        <span class="text-muted italic">($ {{ number_format($item->total_item_con_iva, 2, ',', '.') }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="align-top text-end py-2 fw-bold text-success">
                            $ {{ number_format($venta->total_con_iva, 2, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No se encontraron detalles para esta fecha.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
