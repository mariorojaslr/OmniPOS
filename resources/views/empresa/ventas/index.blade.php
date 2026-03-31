@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   ENCABEZADO
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0 fw-bold">Administración de Ventas</h2>
        <small class="text-muted">Historial completo y comprobantes oficiales</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('empresa.pos.index') }}" class="btn btn-primary shadow-sm">
            🛒 Abrir POS
        </a>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary">
            📊 Reportes
        </a>
    </div>
</div>

{{-- =========================================================
   KPIs
========================================================= --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center border-success shadow-sm">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1">VENTAS HOY</small>
                <h5 class="mb-0 fw-bold text-success">$ {{ number_format($kpiHoy ?? 0, 2, ',', '.') }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-info shadow-sm">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1">ESTA SEMANA</small>
                <h5 class="mb-0 fw-bold text-info">$ {{ number_format($kpiSemana ?? 0, 2, ',', '.') }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-primary shadow-sm">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1">ESTE MES</small>
                <h5 class="mb-0 fw-bold text-primary">$ {{ number_format($kpiMes ?? 0, 2, ',', '.') }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-dark shadow-sm">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1">TOTAL TRANSACCIONES</small>
                <h5 class="mb-0 fw-bold">{{ $totalVentas ?? 0 }}</h5>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
   FILTROS
========================================================= --}}
<form method="GET" class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body">
        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <label class="form-label small fw-bold">Buscador</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Nº comprobante o cliente...">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold">Desde</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold">Hasta</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold">Tipo Comprobante</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="A"   @selected(request('tipo')=='A')>Factura A</option>
                    <option value="B"   @selected(request('tipo')=='B')>Factura B</option>
                    <option value="C"   @selected(request('tipo')=='C')>Factura C</option>
                    <option value="T"   @selected(request('tipo')=='T')>Ticket</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold">Pago</label>
                <select name="metodo" class="form-select">
                    <option value="">Todos</option>
                    <option value="efectivo"    @selected(request('metodo')=='efectivo')>Efectivo</option>
                    <option value="tarjeta"     @selected(request('metodo')=='tarjeta')>Tarjeta</option>
                    <option value="transferencia" @selected(request('metodo')=='transferencia')>Transferencia</option>
                </select>
            </div>

            <div class="col-md-1">
                <button class="btn btn-dark w-100">Filtrar</button>
            </div>

        </div>
    </div>
</form>

{{-- =========================================================
   LISTADO
========================================================= --}}
<div class="card shadow-sm border-0">

    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white">
        <div>{{ $ventas->withQueryString()->links('pagination::bootstrap-5') }}</div>
        <div class="small text-muted">
            Mostrando {{ $ventas->count() }} de {{ $ventas->total() }} registros
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th width="140" class="ps-3 text-start">Fecha</th>
                        <th width="180" class="text-start">N° Comprobante</th>
                        <th class="text-start">Cliente</th>
                        <th width="100">Tipo</th>
                        <th width="120">Método</th>
                        <th width="140" class="text-end">Total</th>
                        <th width="180">Imprimir</th>
                        <th width="100" class="pe-3">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td class="ps-3 text-start text-muted" style="font-size: 0.85rem;">
                            {{ $venta->created_at->format('d/m/Y') }}<br>
                            {{ $venta->created_at->format('H:i') }}
                        </td>

                        <td class="text-start">
                            @php
                                $tipoLetra = strtoupper(substr($venta->tipo_comprobante ?? 'B', -1));
                                if($venta->tipo_comprobante == 'ticket') $tipoLetra = 'T';
                                $fullNum = str_pad($empresa->arca_punto_venta ?? '1', 4, '0', STR_PAD_LEFT) . '-' . str_pad($venta->id, 8, '0', STR_PAD_LEFT);
                            @endphp
                            <span class="badge bg-light text-dark border me-1">{{ $tipoLetra }}</span>
                            <span class="fw-bold">{{ $fullNum }}</span>
                        </td>

                        <td class="text-start">
                            {{ optional($venta->cliente)->name ?? 'CONSUMIDOR FINAL' }}
                        </td>

                        <td>
                            @if($venta->tipo_comprobante === 'factura' || str_contains($venta->tipo_comprobante, 'factura'))
                                <span class="badge bg-primary-subtle text-primary border-primary">Factura</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border-secondary">Ticket</span>
                            @endif
                        </td>

                        <td>
                            <span class="small text-muted">{{ ucfirst($venta->metodo_pago ?? 'Contado') }}</span>
                        </td>

                        <td class="text-end fw-bold">
                            $ {{ number_format($venta->total_con_iva, 2, ',', '.') }}
                        </td>

                        <td>
                            <div class="btn-group btn-group-sm shadow-sm">
                                <a href="{{ route('empresa.ventas.pdf', $venta->id) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-danger" 
                                   title="Ver Factura A4">
                                   A4
                                </a>
                                <a href="{{ route('empresa.ventas.pdf', [$venta->id, 'format' => 'ticket']) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-dark" 
                                   title="Imprimir Ticket">
                                   Ticket
                                </a>
                            </div>
                        </td>

                        <td class="pe-3 text-center">
                            @if($venta->es_guarda_pendiente)
                                <a href="{{ route('empresa.ventas.show', $venta->id) }}" class="btn btn-sm btn-danger px-2 py-1 fw-bold shadow-sm" style="font-size: 0.7rem;">
                                    🚚 GUARDA
                                </a>
                            @else
                                <a href="{{ route('empresa.ventas.show', $venta->id) }}" class="btn btn-sm btn-outline-secondary px-2 py-1" style="font-size: 0.72rem; letter-spacing: -0.2px;">
                                    REMITO
                                </a>
                            @endif
                        </td>



                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-search d-block fs-3 mb-2"></i>
                            No se encontraron ventas con los filtros seleccionados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="p-3 border-top bg-light">
        {{ $ventas->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- MODALES DETALLE --}}
@foreach($ventas as $venta)
<div class="modal fade" id="modalVenta{{ $venta->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-dark shadow-lg border-0" style="border-radius: 15px;">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    Detalle de Venta — {{ $venta->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">FECHA Y HORA</p>
                        <p class="fw-bold">{{ $venta->created_at->format('d/m/Y H:i') }} hs</p>
                        
                        <p class="mb-1 text-muted small mt-3">CLIENTE</p>
                        <p class="fw-bold">{{ optional($venta->cliente)->name ?? 'Consumidor Final' }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1 text-muted small">COMPROBANTE</p>
                        <p class="fw-bold fs-5">{{ $venta->numero_comprobante ?: 'Interno #'.$venta->id }}</p>
                        
                        <p class="mb-1 text-muted small mt-3">MÉTODO DE PAGO</p>
                        <p class="fw-bold text-success">{{ strtoupper($venta->metodo_pago ?? 'Efectivo') }}</p>
                    </div>
                </div>

                <div class="table-responsive rounded border">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 py-2">Producto</th>
                                <th class="text-center" width="80">Cant.</th>
                                <th class="text-end" width="120">P. Unit.</th>
                                <th class="text-end pe-3" width="130">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->items as $item)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold">{{ optional($item->product)->name ?? 'Producto Eliminado' }}</div>
                                    @if($item->variant)
                                        <small class="text-muted">{{ $item->variant->size }} / {{ $item->variant->color }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->cantidad }}</td>
                                <td class="text-end">$ {{ number_format($item->total_item_con_iva / $item->cantidad, 2, ',', '.') }}</td>
                                <td class="text-end pe-3 fw-bold">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr class="fs-5">
                                <th colspan="3" class="text-end ps-3 py-3">IMPORTE TOTAL</th>
                                <th class="text-end pe-3 py-3 text-primary fw-bold">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="modal-footer bg-light">
                <div class="me-auto">
                    <a href="{{ route('empresa.ventas.pdf', [$venta->id, 'format' => 'ticket']) }}" target="_blank" class="btn btn-dark btn-sm px-3">
                        🖨️ Ticket 80mm
                    </a>
                </div>
                <a href="{{ route('empresa.ventas.pdf', $venta->id) }}" target="_blank" class="btn btn-danger btn-sm px-3">
                    📄 Factura A4
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
    // Función para manejar el detalle si fuera necesario vía JS
</script>
@endsection
