@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    {{-- ════════════════════════════════════════════════════════
        CABECERA CON DATOS DEL PROVEEDOR
    ════════════════════════════════════════════════════════ --}}
    <div class="row align-items-center mb-4">
        <div class="col-lg-6">
            <a href="{{ route('empresa.proveedores.index') }}" class="text-muted text-decoration-none small d-inline-block mb-1">
                <i class="fas fa-arrow-left me-1"></i> Volver a Proveedores
            </a>
            <h2 class="fw-bold mb-0 text-dark">{{ $supplier->name }}</h2>
            <div class="d-flex gap-3 mt-1 flex-wrap">
                @if($supplier->cuit)
                    <span class="text-muted small"><i class="fas fa-id-card me-1"></i> CUIT: {{ $supplier->cuit }}</span>
                @endif
                @if($supplier->phone)
                    <span class="text-muted small"><i class="fas fa-phone me-1"></i> {{ $supplier->phone }}</span>
                @endif
                @if($supplier->email)
                    <span class="text-muted small"><i class="fas fa-envelope me-1"></i> {{ $supplier->email }}</span>
                @endif
            </div>
        </div>
        <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
            <div class="d-flex gap-2 justify-content-lg-end flex-wrap">
                <button type="button" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPago">
                    <i class="fas fa-hand-holding-usd me-1"></i> Registrar Pago
                </button>
                <button class="btn btn-outline-primary px-3 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCuentasBanco">
                    <i class="fas fa-university me-1"></i> Cuentas Banco
                </button>
                <a href="{{ route('empresa.compras.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-outline-dark px-3 fw-bold rounded-pill">
                    <i class="fas fa-plus me-1"></i> Nueva Compra
                </a>
                <a href="{{ route('empresa.proveedores.edit', $supplier->id) }}" class="btn btn-light border px-3 rounded-pill">
                    <i class="fas fa-pen me-1"></i> Editar
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════
        PANEL KPI PRINCIPAL — SALDO + AGING
    ════════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        {{-- SALDO NETO --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden {{ $saldo > 0 ? 'border-start border-danger border-4' : 'border-start border-success border-4' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-uppercase x-small fw-bold text-muted letter-spaced">Saldo Neto</span>
                        @if($saldo > 0)
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">Le debemos</span>
                        @elseif($saldo < 0)
                            <span class="badge bg-danger rounded-pill px-2 py-1 x-small">PENDIENTE</span>
                        @else
                            <span class="badge bg-success rounded-pill px-2 py-1 x-small">AL DÍA</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Deuda Impaga -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold text-muted d-block mb-1">Deuda Pendiente</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 text-dark">
                            ${{ number_format($deudas->sum('pending_amount'), 2, ',', '.') }}
                        </h3>
                        <span class="text-muted x-small fw-bold">{{ $deudas->count() }} Facturas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Créditos Disponibles -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold text-muted d-block mb-1">Créditos a Favor</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 text-success">
                            ${{ number_format($creditosAFavorDisponible, 2, ',', '.') }}
                        </h3>
                        @if($creditosAFavorDisponible > 0 && $deudas->count() > 0)
                            <button class="btn btn-xs btn-outline-success rounded-pill px-2 py-0 x-small fw-bold" data-bs-toggle="modal" data-bs-target="#modalAplicarSaldo">
                                Imputar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Aging Chart Compact -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold text-muted">Antigüedad</span>
                        <div class="d-flex gap-1">
                            @php $maxAging = max($aging['0_30'], $aging['31_60'], $aging['61_plus'], 1); @endphp
                            <div class="bg-success bg-opacity-50 rounded-1" style="width: 4px; height: {{ ($aging['0_30'] / $maxAging) * 15 }}px"></div>
                            <div class="bg-warning bg-opacity-50 rounded-1" style="width: 4px; height: {{ ($aging['31_60'] / $maxAging) * 15 }}px"></div>
                            <div class="bg-danger bg-opacity-50 rounded-1" style="width: 4px; height: {{ ($aging['61_plus'] / $maxAging) * 15 }}px"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between x-small text-muted font-monospace text-nowrap">
                        <span title="0-30d">${{ number_format($aging['0_30'], 0, '', '.') }}</span>
                        <span title="31-60d">${{ number_format($aging['31_60'], 0, '', '.') }}</span>
                        <span title="61+d">${{ number_format($aging['61_plus'], 0, '', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════
        DEUDAS PENDIENTES (ACCORDION COMPACTO)
    ════════════════════════════════════════════════════════ --}}
    @if($deudas->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Comprobantes Pendientes de Pago</h6>
            <span class="badge bg-danger rounded-pill px-3">{{ $deudas->count() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="bg-light">
                    <tr class="text-muted text-uppercase small fw-bold">
                        <th class="ps-4">Fecha</th>
                        <th>Comprobante</th>
                        <th class="text-end">Monto Original</th>
                        <th class="text-end">Ya Pagado</th>
                        <th class="text-end">Pendiente</th>
                        <th class="text-center">Antigüedad</th>
                        <th class="text-end pe-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deudas as $d)
                    @php $diasAntig = (int) $d->created_at->diffInDays(now()); @endphp
                    <tr>
                        <td class="ps-4 fw-semibold text-nowrap">{{ $d->created_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $d->description }}</span>
                            @if($d->reference_type == 'App\Models\Purchase')
                                <a href="{{ route('empresa.compras.show', $d->reference_id) }}" class="ms-1 text-primary small" title="Ver compra">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif
                        </td>
                        <td class="text-end text-muted">${{ number_format($d->amount, 2, ',', '.') }}</td>
                        <td class="text-end text-success">${{ number_format($d->amount - $d->pending_amount, 2, ',', '.') }}</td>
                        <td class="text-end fw-bold text-danger">${{ number_format($d->pending_amount, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @if($diasAntig > 60)
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">{{ $diasAntig }}d</span>
                            @elseif($diasAntig > 30)
                                <span class="badge bg-warning bg-opacity-10 text-dark rounded-pill px-2">{{ $diasAntig }}d</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">{{ $diasAntig }}d</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <button onclick="preselectCompra({{ $d->id }}, {{ $d->pending_amount }})" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalPago">
                                Pagar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td class="ps-4" colspan="4">TOTAL PENDIENTE</td>
                        <td class="text-end text-danger">${{ number_format($deudas->sum('pending_amount'), 2, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════
        HISTORIAL DE MOVIMIENTOS (LEDGER)
    ════════════════════════════════════════════════════════ --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-list-alt text-primary me-2"></i>Historial de Movimientos</h6>
            <form method="GET" class="d-flex gap-2 align-items-center">
                <input type="date" name="desde" class="form-control form-control-sm rounded-pill" value="{{ request('desde') }}" style="max-width: 150px;">
                <input type="date" name="hasta" class="form-control form-control-sm rounded-pill" value="{{ request('hasta') }}" style="max-width: 150px;">
                <button type="submit" class="btn btn-sm btn-dark px-3 fw-bold rounded-pill">Filtrar</button>
                @if(request('desde') || request('hasta'))
                    <a href="{{ route('empresa.proveedores.show', $supplier->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Limpiar</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="bg-light">
                    <tr class="text-muted text-uppercase small fw-bold">
                        <th style="width: 44px;"></th>
                        <th class="ps-3">Fecha</th>
                        <th>Concepto</th>
                        <th class="text-end">Debe</th>
                        <th class="text-end">Haber</th>
                        <th class="text-end pe-4">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $m)
                    <tr class="{{ $m->type == 'debit' ? '' : 'table-success table-active' }}" style="--bs-table-bg: {{ $m->type == 'credit' ? 'rgba(34, 197, 94, 0.04)' : 'transparent' }};">
                        <td class="text-center">
                            <button class="btn btn-sm btn-light p-1 rounded-circle border-0" type="button" data-bs-toggle="collapse" data-bs-target="#row-{{ $m->id }}">
                                <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
                            </button>
                        </td>
                        <td class="ps-3 text-nowrap">
                            <span class="fw-semibold">{{ $m->created_at ? $m->created_at->format('d/m/Y') : 'S/F' }}</span>
                            <div class="text-muted x-small">{{ $m->created_at ? $m->created_at->format('H:i') . ' hs' : '' }}</div>
                        </td>
                        <td>
                            @if($m->type == 'debit')
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill me-1 px-2" style="font-size:10px;">DÉBITO</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill me-1 px-2" style="font-size:10px;">CRÉDITO</span>
                            @endif
                            <span class="fw-bold text-dark">{{ $m->description }}</span>
                        </td>
                        <td class="text-end fw-bold {{ $m->type == 'debit' ? 'text-danger' : '' }}">
                            {{ $m->type == 'debit' ? '$' . number_format($m->amount, 2, ',', '.') : '' }}
                        </td>
                        <td class="text-end fw-bold {{ $m->type == 'credit' ? 'text-success' : '' }}">
                            {{ $m->type == 'credit' ? '$' . number_format($m->amount, 2, ',', '.') : '' }}
                        </td>
                        <td class="text-end pe-4">
                            @if($m->type == 'debit')
                                @if($m->paid)
                                    <span class="badge bg-success rounded-pill px-3 py-1">PAGADO</span>
                                @else
                                    <span class="fw-bold text-danger">${{ number_format($m->pending_amount, 2, ',', '.') }}</span>
                                @endif
                            @else
                                @if($m->pending_amount > 0)
                                    <span class="badge bg-warning bg-opacity-15 text-dark rounded-pill px-2 border border-warning">
                                        ${{ number_format($m->pending_amount, 2, ',', '.') }} sin imputar
                                    </span>
                                @else
                                    <span class="text-muted small">Aplicado</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    
                    {{-- FILA DE DETALLES (COLLAPSE) --}}
                    <tr class="collapse" id="row-{{ $m->id }}">
                        <td colspan="6" class="p-0 border-0">
                            <div class="bg-light p-4 border-top border-bottom" style="border-left: 4px solid {{ $m->type == 'debit' ? '#ef4444' : '#22c55e' }} !important;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="small text-uppercase fw-bold text-muted mb-3"><i class="fas fa-link me-1"></i> Detalle del Movimiento</h6>
                                        @if($m->type == 'debit')
                                            <p class="mb-2 small"><strong>Tipo:</strong> {{ $m->reference_type ? class_basename($m->reference_type) : 'Carga Manual' }}</p>
                                            @if($m->reference_type == 'App\Models\Purchase')
                                                <div class="d-flex gap-2 mb-3">
                                                    <a href="{{ route('empresa.compras.show', $m->reference_id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                                        <i class="fas fa-external-link-alt me-1"></i> Ver Compra Original
                                                    </a>
                                                    <a href="{{ route('empresa.compras.credit_note', $m->reference_id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm fw-bold" title="Hacer Nota de Crédito de esta Compra">
                                                        <i class="fas fa-undo me-1"></i> Nota de Crédito
                                                    </a>
                                                </div>
                                            @endif
                                            
                                            <div class="mt-3">
                                                <h6 class="small text-uppercase fw-bold text-muted mb-2">Pagos aplicados a este comprobante</h6>
                                                @if($m->imputaciones->count() > 0)
                                                    @foreach($m->imputaciones as $imp)
                                                        <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border mb-1 shadow-sm">
                                                            <span class="small"><i class="fas fa-check-circle text-success me-1"></i> OP #{{ $imp->ordenPago->numero_orden ?? '?' }} ({{ $imp->created_at->format('d/m/Y') }})</span>
                                                            <span class="fw-bold text-success small">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</span>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted small"><i class="fas fa-info-circle me-1"></i> No registra pagos aplicados aún.</span>
                                                @endif
                                            </div>
                                        @else
                                                @if($m->reference)
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <p class="mb-0 small"><strong>Tipo:</strong> Orden de Pago</p>
                                                        <a href="{{ route('proveedores.pagos.pdf', $m->reference_id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm fw-bold">
                                                            <i class="fas fa-print me-1"></i> Imprimir Comprobante
                                                        </a>
                                                    </div>
                                                <div class="mb-3">
                                                    <strong class="small">Medios de Pago Utilizados:</strong>
                                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                                        @foreach($m->reference->pagos as $dp)
                                                            <div class="bg-white border rounded-3 p-2 shadow-sm small">
                                                                <i class="fas fa-money-check-alt text-primary me-1"></i>
                                                                <strong>{{ ucfirst($dp->metodo_pago) }}</strong>: ${{ number_format($dp->monto, 2, ',', '.') }}
                                                                @if($dp->cheque_id) <span class="text-muted">(Cheque #{{ $dp->cheque->numero ?? '?' }})</span> @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                
                                                @if($m->reference->imputaciones->count() > 0)
                                                    <div class="mt-3">
                                                        <strong class="small text-muted text-uppercase">Comprobantes cancelados con esta OP:</strong>
                                                        @foreach($m->reference->imputaciones as $imp)
                                                            <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border mb-1 shadow-sm mt-1">
                                                                <span class="small">{{ $imp->ledger->description ?? 'Comprobante' }}</span>
                                                                <span class="fw-bold text-danger small">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-md-6 ps-md-4">
                                        @if($m->type == 'debit' && !$m->paid)
                                            <div class="bg-white p-3 rounded-3 border shadow-sm text-center">
                                                <p class="small text-muted mb-2">Restante por cancelar:</p>
                                                <h4 class="text-danger fw-bold mb-3">${{ number_format($m->pending_amount, 2, ',', '.') }}</h4>
                                                <button onclick="preselectCompra({{ $m->id }}, {{ $m->pending_amount }})" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPago">
                                                    <i class="fas fa-hand-holding-usd me-1"></i> Pagar ahora
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-receipt d-block mb-3 opacity-25" style="font-size: 3rem;"></i>
                            <p class="mb-0">No se encontraron movimientos para este proveedor.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($movimientos->hasPages())
            <div class="card-footer bg-white border-top px-4 py-3">
                {{ $movimientos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
    MODAL DE ORDEN DE PAGO
════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4 bg-dark text-white">
                <div>
                    <h5 class="modal-title fw-bold mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Nueva Orden de Pago</h5>
                    <small class="text-light opacity-50">{{ $supplier->name }}</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.proveedores.pagos.store', $supplier->id) }}" method="POST">
                @csrf
                <div class="modal-body p-0">
                    <div class="row g-0">
                        {{-- PANEL IZQUIERDO: DEUDAS --}}
                        <div class="col-lg-7 p-4 bg-light" style="border-right: 1px solid #e5e7eb;">
                            <h6 class="text-uppercase fw-bold text-muted small mb-3">
                                <i class="fas fa-list-check me-1"></i> 1. Seleccionar comprobantes a cancelar
                                <span class="text-dark fw-normal">(opcional)</span>
                            </h6>
                            <div class="overflow-auto custom-scrollbar" style="max-height: 350px;">
                                @forelse($deudas as $d)
                                @php $diasAntig = (int) $d->created_at->diffInDays(now()); @endphp
                                <label class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 border mb-2 cursor-pointer hover-shadow">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3 compra-checkbox" type="checkbox" name="compras[]" value="{{ $d->id }}" data-monto="{{ $d->pending_amount }}" style="transform: scale(1.3);">
                                        <div>
                                            <span class="fw-bold d-block text-dark">{{ $d->description }}</span>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>{{ $d->created_at->format('d/m/Y') }}
                                                <span class="ms-2">
                                                    @if($diasAntig > 60)
                                                        <span class="text-danger fw-bold">{{ $diasAntig }} días</span>
                                                    @elseif($diasAntig > 30)
                                                        <span class="text-warning fw-bold">{{ $diasAntig }} días</span>
                                                    @else
                                                        <span class="text-success">{{ $diasAntig }} días</span>
                                                    @endif
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                    <span class="text-danger fw-bold fs-6">${{ number_format($d->pending_amount, 2, ',', '.') }}</span>
                                </label>
                                @empty
                                    <div class="p-4 text-center text-muted border border-dashed rounded-3 bg-white">
                                        <i class="fas fa-check-circle text-success fs-3 mb-2 d-block"></i>
                                        <p class="mb-0">No hay comprobantes pendientes. ¡Todo al día!</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="seleccionarTodos()">
                                    <i class="fas fa-check-double me-1"></i> Seleccionar todos
                                </button>
                            </div>

                            {{-- DISPLAY DE TOTAL --}}
                            <div class="card border-0 bg-dark text-white p-4 rounded-4 shadow-lg text-center mt-3">
                                <span class="text-uppercase text-light opacity-50 small fw-bold">Total de la Orden de Pago</span>
                                <h1 class="display-5 fw-bold mb-0" id="displayMontoTotal">$0,00</h1>
                                <input type="hidden" name="monto" id="inputMontoTotal">
                            </div>
                        </div>

                        {{-- PANEL DERECHO: FORMAS DE PAGO --}}
                        <div class="col-lg-5 p-4 bg-white">
                            <h6 class="text-uppercase fw-bold text-muted small mb-3">
                                <i class="fas fa-wallet me-1"></i> 2. Forma de Pago
                            </h6>
                            
                            <div id="paymentMethodsRows" class="mb-3">
                                <div class="payment-item bg-light p-3 rounded-3 mb-2 border">
                                    <div class="mb-2">
                                        <select name="pagos_diferenciados[0][metodo_pago]" class="form-select form-select-sm border-0 shadow-none bg-white rounded-pill" onchange="toggleChequeInput(this, 0)" required>
                                            <option value="efectivo">💵 Efectivo</option>
                                            <option value="transferencia">🏦 Transferencia Bancaria</option>
                                            <option value="debito_automatico">💳 Débito Automático</option>
                                            <option value="cheque_tercero">✍️ Cheque de Terceros</option>
                                            <option value="cheque_propio">📝 Cheque Propio</option>
                                        </select>
                                    </div>
                                    <div id="cuenta-selection-0" class="mb-2">
                                        <label class="x-small fw-bold text-muted mb-1 d-block ms-2">Origen de Fondos (Caja/Banco)</label>
                                        <select name="pagos_diferenciados[0][finanza_cuenta_id]" class="form-select form-select-sm border-0 shadow-none bg-white rounded-pill">
                                            <option value="">-- Seleccionar Cuenta --</option>
                                            @foreach($cuentas as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->nombre }} (${{ number_format($acc->saldo_actual, 2) }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="cheque-selection-0" style="display:none;" class="mb-2">
                                        <select name="pagos_diferenciados[0][cheque_id]" class="form-select form-select-sm border-0 shadow-none bg-white rounded-pill" onchange="applyChequeAmount(this, 0)">
                                            <option value="">-- Seleccionar Cheque de Cartera --</option>
                                            @foreach($cheques as $c)
                                                <option value="{{ $c->id }}" data-monto="{{ $c->monto }}">
                                                    #{{ $c->numero }} · {{ $c->banco }} · ${{ number_format($c->monto, 2, ',', '.') }} · Vto: {{ $c->fecha_pago->format('d/m/Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="chequera-selection-0" style="display:none;" class="mb-2 mt-2 bg-white p-2 rounded-3 border">
                                        <label class="x-small fw-bold text-muted mb-1 d-block">Seleccionar Chequera Propia</label>
                                        <select name="pagos_diferenciados[0][chequera_id]" class="form-select form-select-sm border-0 bg-light rounded-pill mb-2" onchange="toggleManualNumber(this, 0)">
                                            <option value="">-- Seleccionar Chequera --</option>
                                            @foreach($chequeras as $cq)
                                                <option value="{{ $cq->id }}" data-tipo="{{ $cq->tipo }}">
                                                    {{ $cq->banco }} ({{ $cq->numero_cuenta }}) - {{ $cq->tipo === 'echeck' ? 'E-Check' : '#'.$cq->proximo_numero }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="manual-number-div-0" style="display:none;" class="mb-2">
                                            <label class="x-small fw-bold text-muted mb-1 d-block">Número de Cheque (E-Check)</label>
                                            <input type="text" name="pagos_diferenciados[0][numero]" class="form-control form-control-sm border-0 bg-light rounded-pill" placeholder="Ingresar número generado por el banco">
                                        </div>
                                        <label class="x-small fw-bold text-muted mb-1 d-block">Fecha de Pago (Vencimiento)</label>
                                        <input type="date" name="pagos_diferenciados[0][fecha_pago]" class="form-control form-control-sm border-0 bg-light rounded-pill">
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-0 bg-white text-muted fw-bold rounded-start-pill">$</span>
                                        <input type="number" step="0.01" name="pagos_diferenciados[0][monto]" class="form-control border-0 shadow-none bg-white fw-bold text-danger rounded-end-pill line-monto" placeholder="0.00" oninput="checkSums()" required>
                                    </div>
                                    <input type="text" name="pagos_diferenciados[0][referencia]" class="form-control form-control-sm border-0 bg-transparent mt-2 shadow-none text-muted" placeholder="Referencia / Nro. operación (opcional)">
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 mb-4 w-100" onclick="addPaymentRow()">
                                <i class="fas fa-plus-circle me-1"></i> Añadir otro medio de pago
                            </button>

                            <div class="mb-3">
                                <label class="small text-muted fw-bold mb-1">Fecha</label>
                                <input type="date" name="fecha" class="form-control form-control-sm border-0 bg-light rounded-pill shadow-none" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted fw-bold mb-1">Observaciones</label>
                                <textarea name="observaciones" class="form-control form-control-sm border-0 bg-light rounded-3 shadow-none" rows="2" placeholder="Nota interna..."></textarea>
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow" id="btnSubmitPago" disabled>
                                    <i class="fas fa-check-circle me-2"></i> Confirmar Orden de Pago
                                </button>
                                <div id="feedbackSum" class="small text-center mt-2 text-danger fw-bold" style="display:none;">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Los montos asignados no coinciden con el total
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
    ESTILOS
════════════════════════════════════════════════════════ --}}
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.75rem; }
    .btn-xs { padding: 0.15rem 0.5rem; font-size: 0.65rem; }
    .scale-up { transition: all 0.2s ease; }
    .letter-spaced { letter-spacing: 0.06em; }
    .aging-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 3px; vertical-align: middle; }
    .cursor-pointer { cursor: pointer; }
    .hover-shadow { transition: box-shadow 0.15s ease; }
    .hover-shadow:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .border-4 { border-width: 4px !important; }
</style>

{{-- ════════════════════════════════════════════════════════
    SCRIPTS
════════════════════════════════════════════════════════ --}}
<script>
    let rowIndex = 1;

    // ── Pre-seleccionar una compra desde el botón "Pagar" ──
    function preselectCompra(id, monto) {
        document.querySelectorAll('.compra-checkbox').forEach(c => c.checked = false);
        let chk = document.querySelector(`.compra-checkbox[value="${id}"]`);
        if(chk) chk.checked = true;
        recalculateTotal();
    }

    // ── Seleccionar todos los comprobantes ──
    function seleccionarTodos() {
        const checkboxes = document.querySelectorAll('.compra-checkbox');
        const allChecked = [...checkboxes].every(c => c.checked);
        checkboxes.forEach(c => c.checked = !allChecked);
        recalculateTotal();
    }

    // ── Listeners en checkboxes ──
    document.querySelectorAll('.compra-checkbox').forEach(input => {
        input.addEventListener('change', recalculateTotal);
    });

    // ── Recalcular total según selección ──
    function recalculateTotal() {
        let total = 0;
        document.querySelectorAll('.compra-checkbox:checked').forEach(input => {
            total += parseFloat(input.dataset.monto);
        });
        
        document.getElementById('displayMontoTotal').innerText = '$' + total.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('inputMontoTotal').value = total.toFixed(2);
        
        // Si hay una sola fila de pago, autocompletar
        let rows = document.querySelectorAll('.line-monto');
        if(rows.length === 1) {
            rows[0].value = total.toFixed(2);
        }
        
        checkSums();
    }

    // ── Validar que los montos de pago coincidan con el total ──
    function checkSums() {
        const targetTotal = parseFloat(document.getElementById('inputMontoTotal').value) || 0;
        let sumPayments = 0;
        document.querySelectorAll('.line-monto').forEach(input => {
            sumPayments += parseFloat(input.value) || 0;
        });

        const btn = document.getElementById('btnSubmitPago');
        const feedback = document.getElementById('feedbackSum');

        if (targetTotal > 0 && Math.abs(sumPayments - targetTotal) < 0.01) {
            btn.disabled = false;
            feedback.style.display = 'none';
        } else if (targetTotal == 0 && sumPayments > 0) {
            // Pago flotante (sin seleccionar comprobantes)
            btn.disabled = false;
            feedback.style.display = 'none';
            document.getElementById('displayMontoTotal').innerText = '$' + sumPayments.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('inputMontoTotal').value = sumPayments.toFixed(2);
        } else {
            btn.disabled = true;
            feedback.style.display = targetTotal > 0 ? 'block' : 'none';
        }
    }

    // ── Añadir fila de medio de pago ──
    function addPaymentRow() {
        const container = document.getElementById('paymentMethodsRows');
        const row = document.createElement('div');
        row.className = 'payment-item bg-light p-3 rounded-3 mb-2 border position-relative';
        row.innerHTML = `
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2" style="font-size:0.6rem;" onclick="this.parentElement.remove(); checkSums();"></button>
            <div class="mb-2">
                <select name="pagos_diferenciados[\${rowIndex}][metodo_pago]" class="form-select form-select-sm border-0 shadow-none bg-white rounded-pill" onchange="toggleChequeInput(this, \${rowIndex})" required>
                    <option value="efectivo">💵 Efectivo</option>
                    <option value="transferencia">🏦 Transferencia Bancaria</option>
                    <option value="debito_automatico">💳 Débito Automático</option>
                    <option value="cheque_tercero">✍️ Cheque de Terceros</option>
                    <option value="cheque_propio">📝 Cheque Propio</option>
                </select>
            </div>
            <div id="cuenta-selection-\${rowIndex}" class="mb-2">
                <label class="x-small fw-bold text-muted mb-1 d-block ms-2">Origen de Fondos (Caja/Banco)</label>
                <select name="pagos_diferenciados[\${rowIndex}][finanza_cuenta_id]" class="form-select form-select-sm border-0 shadow-none bg-white rounded-pill">
                    <option value="">-- Seleccionar Cuenta --</option>
                    @foreach($cuentas as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->nombre }} (\${{ number_format($acc->saldo_actual, 2) }})</option>
                    @endforeach
                </select>
            </div>
            <div id="cheque-selection-${rowIndex}" style="display:none;" class="mb-2">
                <select name="pagos_diferenciados[${rowIndex}][cheque_id]" class="form-select form-select-sm border-0 shadow-none bg-white rounded-pill" onchange="applyChequeAmount(this, ${rowIndex})">
                    <option value="">-- Seleccionar Cheque de Cartera --</option>
                    @foreach($cheques as $c)
                        <option value="{{ $c->id }}" data-monto="{{ $c->monto }}">
                            #{{ $c->numero }} · {{ $c->banco }} · ${{ number_format($c->monto, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="chequera-selection-${rowIndex}" style="display:none;" class="mb-2 mt-2 bg-white p-2 rounded-3 border">
                <label class="x-small fw-bold text-muted mb-1 d-block">Seleccionar Chequera Propia</label>
                <select name="pagos_diferenciados[${rowIndex}][chequera_id]" class="form-select form-select-sm border-0 bg-light rounded-pill mb-2" onchange="toggleManualNumber(this, ${rowIndex})">
                    <option value="">-- Seleccionar Chequera --</option>
                    @foreach($chequeras as $cq)
                        <option value="{{ $cq->id }}" data-tipo="{{ $cq->tipo }}">
                            {{ $cq->banco }} ({{ $cq->numero_cuenta }}) - {{ $cq->tipo === 'echeck' ? 'E-Check' : '#'.$cq->proximo_numero }}
                        </option>
                    @endforeach
                </select>
                <div id="manual-number-div-${rowIndex}" style="display:none;" class="mb-2">
                    <label class="x-small fw-bold text-muted mb-1 d-block">Número de Cheque (E-Check)</label>
                    <input type="text" name="pagos_diferenciados[${rowIndex}][numero]" class="form-control form-control-sm border-0 bg-light rounded-pill" placeholder="Ingresar número generado por el banco">
                </div>
                <label class="x-small fw-bold text-muted mb-1 d-block">Fecha de Pago (Vencimiento)</label>
                <input type="date" name="pagos_diferenciados[${rowIndex}][fecha_pago]" class="form-control form-control-sm border-0 bg-light rounded-pill">
            </div>
            <div class="input-group input-group-sm">
                <span class="input-group-text border-0 bg-white text-muted fw-bold rounded-start-pill">$</span>
                <input type="number" step="0.01" name="pagos_diferenciados[${rowIndex}][monto]" class="form-control border-0 shadow-none bg-white fw-bold text-danger rounded-end-pill line-monto" placeholder="0.00" oninput="checkSums()" required>
            </div>
            <input type="text" name="pagos_diferenciados[${rowIndex}][referencia]" class="form-control form-control-sm border-0 bg-transparent mt-2 shadow-none text-muted" placeholder="Referencia (opcional)">
        `;
        container.appendChild(row);
        rowIndex++;
    }

    // ── Toggle selector de cheques ──
    function toggleChequeInput(select, idx) {
        const chequeDiv = document.getElementById(`cheque-selection-${idx}`);
        const chequeraDiv = document.getElementById(`chequera-selection-${idx}`);
        const cuentaDiv = document.getElementById(`cuenta-selection-${idx}`);
        
        chequeDiv.style.display = select.value === 'cheque_tercero' ? 'block' : 'none';
        chequeraDiv.style.display = select.value === 'cheque_propio' ? 'block' : 'none';
        
        // Cuentas de fondos (no aplica para cheques, ya que el cheque es el fondo en sí o se emite desde chequera)
        cuentaDiv.style.display = (select.value !== 'cheque_tercero' && select.value !== 'cheque_propio') ? 'block' : 'none';
    }

    // --- Toggle número manual para E-Check ---
    function toggleManualNumber(select, idx) {
        const selectedOption = select.options[select.selectedIndex];
        const tipo = selectedOption.getAttribute('data-tipo');
        const manualDiv = document.getElementById(`manual-number-div-${idx}`);
        
        if (tipo === 'echeck') {
            manualDiv.style.display = 'block';
        } else {
            manualDiv.style.display = 'none';
        }
    }

    // ── Auto-aplicar monto del cheque seleccionado ──
    function applyChequeAmount(select, idx) {
        const option = select.options[select.selectedIndex];
        const monto = option.dataset.monto;
        if(monto) {
            const input = select.closest('.payment-item').querySelector('.line-monto');
            input.value = parseFloat(monto).toFixed(2);
            checkSums();
        }
    }
</script>

{{-- ════════════════════════════════════════════════════════
    MODAL GESTIÓN DE CUENTAS BANCARIAS DEL PROVEEDOR
    (Movido fuera del script para corregir error 500)
════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalCuentasBanco" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4 border-0">
                <h5 class="modal-title fw-bold">Cuentas Bancarias: {{ $supplier->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Listado de Cuentas Existentes -->
                    <div class="col-md-7">
                        <h6 class="text-uppercase x-small fw-bold text-muted mb-3">Cuentas Registradas</h6>
                        <div class="list-group list-group-flush rounded-3 border overflow-auto" style="max-height: 400px;">
                            @forelse($supplier->bankAccounts as $acc)
                                <div class="list-group-item p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $acc->bank_name }}</h6>
                                            <span class="x-small text-muted">{{ $acc->account_type ?: 'Cuenta' }} · {{ $acc->account_number }}</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-light text-dark rounded-pill x-small px-3 border">CBU: {{ $acc->cbu_cvu }}</span>
                                            <div class="x-small text-primary fw-bold mt-1">Alias: {{ $acc->alias }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-5 text-center text-muted small">
                                    <i class="fas fa-university fa-3x mb-3 opacity-25"></i>
                                    <p>No hay cuentas bancarias registradas para este proveedor.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Formulario Nueva Cuenta -->
                    <div class="col-md-5 bg-light p-4 rounded-4">
                        <h6 class="text-uppercase x-small fw-bold text-muted mb-3">Registrar Nueva Cuenta</h6>
                        <form action="{{ route('empresa.tesoreria.bank-accounts.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="holder_type" value="{{ get_class($supplier) }}">
                            <input type="hidden" name="holder_id" value="{{ $supplier->id }}">
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Banco</label>
                                <input type="text" name="bank_name" class="form-control form-control-sm border-0 bg-white rounded-pill px-3 shadow-sm" placeholder="Ej: Galicia, ICBC..." required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">CBU / CVU</label>
                                <input type="text" name="cbu_cvu" class="form-control form-control-sm border-0 bg-white rounded-pill px-3 shadow-sm" placeholder="22 dígitos">
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Alias</label>
                                <input type="text" name="alias" class="form-control form-control-sm border-0 bg-white rounded-pill px-3 shadow-sm" placeholder="Ej: PAGO.LAVA.SECCO">
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Tipo de Cuenta</label>
                                <select name="account_type" class="form-select form-select-sm border-0 bg-white rounded-pill px-3 shadow-sm">
                                    <option value="Cta. Corriente">Cta. Corriente</option>
                                    <option value="Caja de Ahorro">Caja de Ahorro</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">GUARDAR CUENTA</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
