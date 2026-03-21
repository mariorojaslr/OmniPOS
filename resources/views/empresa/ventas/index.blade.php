@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   ENCABEZADO
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Administración de Ventas</h2>
        <small class="text-muted">Historial completo y comprobantes <b>ARCA</b></small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('empresa.pos.index') }}" class="btn btn-primary">
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
        <div class="card text-center border-success">
            <div class="card-body py-2">
                <small class="text-muted">Hoy</small>
                <h6 class="mb-0 fw-bold text-success">$ {{ number_format($kpiHoy ?? 0, 2, ',', '.') }}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-info">
            <div class="card-body py-2">
                <small class="text-muted">Esta semana</small>
                <h6 class="mb-0 fw-bold text-info">$ {{ number_format($kpiSemana ?? 0, 2, ',', '.') }}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-primary">
            <div class="card-body py-2">
                <small class="text-muted">Este mes</small>
                <h6 class="mb-0 fw-bold text-primary">$ {{ number_format($kpiMes ?? 0, 2, ',', '.') }}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-dark">
            <div class="card-body py-2">
                <small class="text-muted">Total ventas</small>
                <h6 class="mb-0 fw-bold">{{ $totalVentas ?? 0 }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
   FILTROS
========================================================= --}}
<form method="GET" class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="row g-2 align-items-end">

            <div class="col-md-3">
                <label class="form-label small mb-1">Buscar (Nº comprobante o cliente)</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control form-control-sm" placeholder="Ej: 00001-00000005">
            </div>

            <div class="col-md-2">
                <label class="form-label small mb-1">Desde</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <label class="form-label small mb-1">Hasta</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <label class="form-label small mb-1">Tipo / ARCA</label>
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Cualquier tipo</option>
                    <option value="A"   @selected(request('tipo')=='A')>Factura A</option>
                    <option value="B"   @selected(request('tipo')=='B')>Factura B</option>
                    <option value="C"   @selected(request('tipo')=='C')>Factura C</option>
                    <option value="T"   @selected(request('tipo')=='T')>Ticket</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small mb-1">Método pago</label>
                <select name="metodo" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="efectivo"    @selected(request('metodo')=='efectivo')>Efectivo</option>
                    <option value="tarjeta"     @selected(request('metodo')=='tarjeta')>Tarjeta</option>
                    <option value="transferencia" @selected(request('metodo')=='transferencia')>Transferencia</option>
                    <option value="qr"          @selected(request('metodo')=='qr')>QR / Mercado Pago</option>
                </select>
            </div>

            <div class="col-md-1 d-flex gap-1">
                <button class="btn btn-dark btn-sm w-100">Filtrar</button>
            </div>

        </div>
    </div>
</form>

{{-- =========================================================
   LISTADO
========================================================= --}}
<div class="card shadow-sm">

    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <div>{{ $ventas->withQueryString()->links('pagination::bootstrap-5') }}</div>
        <form method="GET" class="d-flex align-items-center gap-2">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="from"   value="{{ request('from') }}">
            <input type="hidden" name="to"     value="{{ request('to') }}">
            <input type="hidden" name="tipo"   value="{{ request('tipo') }}">
            <input type="hidden" name="metodo" value="{{ request('metodo') }}">
            <label class="mb-0 small text-muted">Mostrar</label>
            <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()" style="width:90px;">
                @foreach([15, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="130">Fecha</th>
                    <th width="180">N° Comprobante</th>
                    <th>Cliente</th>
                    <th width="100">Tipo</th>
                    <th width="120">Método</th>
                    <th width="130" class="text-end">Total</th>
                    <th width="160" class="text-center">Imprimir</th>
                    <th width="80" class="text-center">Detalle</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>

                    <td>
                        {{ $venta->numero_comprobante ?: '—' }}
                    </td>

                    <td>
                        {{ optional($venta->cliente)->name ?? 'Consumidor Final' }}
                    </td>

                    <td>
                        @if($venta->tipo_comprobante === 'factura')
                            <span class="badge bg-primary">Factura</span>
                        @else
                            <span class="badge bg-secondary">Ticket</span>
                        @endif
                    </td>

                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ ucfirst($venta->metodo_pago ?? 'efectivo') }}
                        </span>
                    </td>

                    <td class="text-end fw-bold">
                        $ {{ number_format($venta->total_con_iva, 2, ',', '.') }}
                    </td>

                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('empresa.ventas.pdf', $venta->id) }}" 
                               target="_blank" 
                               class="btn btn-outline-danger" 
                               title="Descargar Factura A4">
                               <i class="bi bi-file-pdf"></i> A4
                            </a>
                            <a href="{{ route('empresa.ventas.pdf', [$venta->id, 'format' => 'ticket']) }}" 
                               target="_blank" 
                               class="btn btn-outline-dark" 
                               title="Imprimir Ticket 80mm">
                               <i class="bi bi-printer"></i> Ticket
                            </a>
                        </div>
                    </td>

                    <td class="text-center">
                        <button class="btn btn-sm btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalVenta{{ $venta->id }}">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>


                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay ventas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top">
        {{ $ventas->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- MODALES DETALLE (FUERA DE LA TABLA) --}}
@foreach($ventas as $venta)
<div class="modal fade" id="modalVenta{{ $venta->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    Detalle — {{ $venta->numero_comprobante ?: 'Venta #'.$venta->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-4">
                    <div class="col-md-6 text-start">
                        <p class="mb-1"><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Cliente:</strong> {{ optional($venta->cliente)->name ?? 'Consumidor Final' }}</p>
                        <p class="mb-0"><strong>Método pago:</strong> {{ ucfirst($venta->metodo_pago ?? '—') }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="mb-1"><strong>N° Comprobante:</strong> {{ $venta->numero_comprobante ?: '—' }}</p>
                        <p class="mb-1"><strong>Tipo:</strong> {{ ucfirst($venta->tipo_comprobante ?? '—') }}</p>
                        <p class="mb-0"><strong>Vendedor:</strong> {{ optional($venta->user)->name ?? '—' }}</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center" width="80">Cant.</th>
                                <th class="text-end" width="120">P. Unit.</th>
                                <th class="text-end" width="120">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->items as $item)
                            <tr>
                                <td class="text-start">
                                    {{ optional($item->product)->name ?? '—' }}
                                    @if($item->variant)
                                        <br><small class="text-muted">Variante: {{ $item->variant->size }} / {{ $item->variant->color }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->cantidad }}</td>
                                <td class="text-end">$ {{ number_format($item->total_item_con_iva / $item->cantidad, 2, ',', '.') }}</td>
                                <td class="text-end fw-bold">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light text-end">
                            <tr>
                                <th colspan="3">TOTAL</th>
                                <th class="fw-bold fs-5">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <div class="me-auto">
                    <a href="{{ route('empresa.ventas.pdf', [$venta->id, 'format' => 'ticket']) }}" target="_blank" class="btn btn-dark">
                        <i class="bi bi-printer"></i> Imprimir Ticket (80mm)
                    </a>
                </div>
                <a href="{{ route('empresa.ventas.pdf', $venta->id) }}" target="_blank" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Descargar Factura (A4)
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
