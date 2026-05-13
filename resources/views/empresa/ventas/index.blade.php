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
                    <option value="NC"  @selected(request('tipo')=='NC')>Nota de Crédito</option>
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
                        <th width="40"></th>
                        <th width="140" class="ps-3 text-start">Fecha</th>
                        <th width="180" class="text-start">N° Comprobante</th>
                        <th class="text-start">Cliente</th>
                        <th width="100">Tipo</th>
                        <th width="120">Estado Pago</th>
                        <th width="140" class="text-end">Total</th>
                        <th width="120" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    @php
                        $estadoPago = 'PAGO TOTAL';
                        $badgePago  = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25';
                        if ($venta->metodo_pago === 'cuenta_corriente' && $venta->ledger) {
                            $ledger = $venta->ledger;
                            if (round($ledger->pending_amount, 2) == round($ledger->amount, 2) && $ledger->amount > 0) {
                                $estadoPago = 'SIN PAGO';
                                $badgePago  = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                            } elseif ($ledger->pending_amount > 0 && $ledger->pending_amount < $ledger->amount) {
                                $estadoPago = 'PAGO PARCIAL';
                                $badgePago  = 'bg-warning bg-opacity-10 text-warning border border-warning text-dark border-opacity-25';
                            }
                        }
                        $tieneRecibos = $venta->ledger && $venta->ledger->imputaciones->count() > 0;
                    @endphp
                    <tr>
                        <td class="text-center align-middle">
                            @if($tieneRecibos)
                            <button class="btn btn-sm btn-light border p-1 rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePagos-{{ $venta->id }}" title="Ver Recibos Relacionados">
                                <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
                            </button>
                            @endif
                        </td>
                        <td class="ps-3 text-start text-muted align-middle" style="font-size: 0.85rem;">
                            <span class="fw-bold text-dark">{{ $venta->created_at->format('d/m/Y') }}</span><br>
                            {{ $venta->created_at->format('H:i') }}
                        </td>

                        <td class="text-start align-middle">
                            @php
                                $tipoLetra = strtoupper(substr($venta->tipo_comprobante ?? 'B', -1));
                                if($venta->tipo_comprobante == 'ticket') $tipoLetra = 'T';
                                
                                // Si ya tiene número oficial (de AFIP), usamos ese. Si no, el interno.
                                $fullNum = $venta->numero_comprobante ?: (str_pad($empresa->arca_punto_venta ?? '1', 4, '0', STR_PAD_LEFT) . '-' . str_pad($venta->id, 8, '0', STR_PAD_LEFT));
                            @endphp
                            <span class="badge bg-light text-dark border me-1">{{ $tipoLetra }}</span>
                            <span class="fw-bold">{{ $fullNum }}</span>
                        </td>

                        <td class="text-start align-middle">
                            {{ optional($venta->cliente)->name ?? 'CONSUMIDOR FINAL' }}
                        </td>

                        <td class="align-middle">
                            @if($venta->tipo_comprobante === 'factura' || str_contains($venta->tipo_comprobante, 'factura'))
                                <span class="badge bg-primary-subtle text-primary py-1 border border-primary">Factura</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary py-1 border border-secondary">Ticket</span>
                            @endif
                            @if($venta->credit_notes_count > 0)
                                <div class="mt-1"><span class="badge bg-danger">ANULADA (NC)</span></div>
                            @endif
                        </td>

                        <td class="align-middle text-center">
                            <span class="badge {{ $badgePago }} py-1 d-block w-100" style="font-size:0.75rem">{{ $estadoPago }}</span>
                            @if($estadoPago == 'PAGO PARCIAL')
                                <small class="text-muted d-block" style="font-size:0.7rem">Resta: ${{ number_format($venta->ledger->pending_amount, 2, ',', '.') }}</small>
                            @endif
                        </td>

                        <td class="text-end fw-bold align-middle fs-6">
                            $ {{ number_format($venta->total_con_iva, 2, ',', '.') }}
                        </td>

                        <td class="pe-3 text-center align-middle">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border px-2 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Opciones
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-7">
                                    @if(!$venta->cae)
                                        <li>
                                            <form action="{{ route('empresa.ventas.fiscalizar', $venta) }}" method="POST" class="px-2">
                                                @csrf
                                                <button type="submit" class="dropdown-item py-2 fw-bold text-primary rounded">
                                                    <i class="fas fa-rocket me-2"></i> Hacer Factura ARCA
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li><h6 class="dropdown-header">Exportar</h6></li>
                                    <li><a class="dropdown-item py-2" target="_blank" href="{{ route('empresa.ventas.pdf', $venta->id) }}"><i class="fas fa-file-pdf text-danger me-2"></i> Factura A4</a></li>
                                    <li><a class="dropdown-item py-2" target="_blank" href="{{ route('empresa.ventas.pdf', [$venta->id, 'format' => 'ticket']) }}"><i class="fas fa-receipt text-secondary me-2"></i> Formato Ticket</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 fw-bold text-danger" href="{{ route('empresa.ventas.credit_note', $venta->id) }}"><i class="fas fa-undo me-2"></i> Hacer Nota de Crédito</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Logística</h6></li>
                                    @if($venta->es_guarda_pendiente)
                                        <li><a class="dropdown-item py-2 fw-bold text-danger" href="{{ route('empresa.ventas.show', $venta->id) }}"><i class="fas fa-truck-loading me-2"></i> Remitir Guarda</a></li>
                                    @else
                                        <li><a class="dropdown-item py-2" href="{{ route('empresa.ventas.show', $venta->id) }}"><i class="fas fa-eye me-2"></i> Ver Remito</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- FILA EXPANDIBLE DE RECIBOS IMPUTADOS -->
                    @if($tieneRecibos)
                    <tr class="collapse" id="collapsePagos-{{ $venta->id }}">
                        <td colspan="8" class="p-0 border-0">
                            <div class="p-3 bg-light border-bottom" style="box-shadow: inset 0px 4px 6px -4px rgba(0,0,0,0.1);">
                                <h6 class="fw-bold text-muted small text-uppercase mb-3"><i class="fas fa-link me-1"></i> Cobros aplicados a esta factura</h6>
                                <div class="row g-2">
                                    @foreach($venta->ledger->imputaciones as $imp)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="bg-white border rounded shadow-sm p-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-receipt text-success me-2"></i>
                                                <a href="{{ route('empresa.pagos.show', $imp->recibo_id) }}" class="fw-bold text-decoration-none text-dark">
                                                    Recibo #{{ str_pad(optional($imp->recibo)->numero_recibo ?? 0, 8, '0', STR_PAD_LEFT) }}
                                                </a>
                                                <small class="text-muted d-block"><i class="far fa-calendar-alt me-1"></i> {{ optional($imp->recibo)->fecha ?? $imp->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="d-block text-muted small text-uppercase">Monto Pagado</span>
                                                <span class="fw-bold text-success fs-5">$ {{ number_format($imp->monto_aplicado, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
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
