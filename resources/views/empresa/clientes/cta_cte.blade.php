@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA PROFESIONAL --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item small"><a href="{{ route('empresa.clientes.index') }}" class="text-decoration-none text-muted">Clientes</a></li>
                    <li class="breadcrumb-item small active text-primary" aria-current="page">Cuenta Corriente</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1 text-dark">{{ $client->name }}</h2>
            <div class="d-flex gap-3 text-muted small">
                <span><i class="fas fa-id-card me-1"></i> {{ $client->document ?: 'Sin documento' }}</span>
                <span><i class="fas fa-phone me-1"></i> {{ $client->phone ?: 'Sin teléfono' }}</span>
                <span><i class="fas fa-envelope me-1"></i> {{ $client->email ?: 'Sin email' }}</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCobro">
                <i class="fas fa-hand-holding-usd me-1"></i> Registrar Cobro
            </button>
            <button class="btn btn-outline-primary px-3 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCuentasBanco">
                <i class="fas fa-university me-1"></i> Cuentas Banco
            </button>
            <a href="{{ route('empresa.ventas.manual', ['client_id' => $client->id]) }}" class="btn btn-dark px-4 fw-bold rounded-pill shadow-sm">
                <i class="fas fa-plus me-1"></i> Nueva Venta
            </a>
            <button class="btn btn-light border rounded-pill px-3 shadow-sm" id="btnExportar">
                <i class="fas fa-print"></i>
            </button>
        </div>
    </div>

    {{-- DASHBOARD DE CTA CTE --}}
    <div class="row g-2 mb-4">
        <!-- Saldo Neto -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 {{ $saldo > 0 ? 'border-start border-danger border-4' : 'border-start border-success border-4' }}">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold text-muted d-block mb-1">Saldo Neto</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 {{ $saldo > 0 ? 'text-danger' : 'text-success' }}">
                            ${{ number_format(abs($saldo), 2, ',', '.') }}
                        </h3>
                        @if($saldo > 0)
                            <span class="badge bg-danger rounded-pill px-2 py-1 x-small">NOS DEBE</span>
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
                    <span class="text-uppercase x-small fw-bold text-muted d-block mb-1">Pagos a Favor</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 text-success">
                            ${{ number_format($saldoAFavorDisponible, 2, ',', '.') }}
                        </h3>
                        @if($saldoAFavorDisponible > 0 && $deudas->count() > 0)
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
                    <div class="d-flex justify-content-between x-small text-muted font-monospace">
                        <span title="0-30d">${{ number_format($aging['0_30'], 0, '', '.') }}</span>
                        <span title="31-60d">${{ number_format($aging['31_60'], 0, '', '.') }}</span>
                        <span title="61+d">${{ number_format($aging['61_plus'], 0, '', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- LISTADO DE MOVIMIENTOS --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list-ul me-2 text-primary"></i> Historial de Movimientos</h5>
                    <form method="GET" class="d-flex gap-2">
                        <input type="date" name="desde" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" value="{{ request('desde') }}">
                        <input type="date" name="hasta" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" value="{{ request('hasta') }}">
                        <button type="submit" class="btn btn-sm btn-dark rounded-circle p-1" style="width: 32px; height: 32px;"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="x-small fw-bold text-muted text-uppercase">
                                <th class="ps-4">Fecha</th>
                                <th>Concepto</th>
                                <th class="text-end">Debe (+)</th>
                                <th class="text-end">Haber (-)</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimientos as $m)
                            <tr class="cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $m->id }}">
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $m->created_at->format('d/m/Y') }}</div>
                                    <div class="x-small text-muted">{{ $m->created_at->format('H:i') }} hs</div>
                                </td>
                                <td>
                                    @if($m->type == 'debit')
                                        <i class="fas fa-file-invoice text-danger me-2 opacity-75"></i>
                                    @else
                                        <i class="fas fa-check-circle text-success me-2 opacity-75"></i>
                                    @endif
                                    <span class="fw-semibold text-dark">{{ $m->description }}</span>
                                </td>
                                <td class="text-end fw-bold {{ $m->type == 'debit' ? 'text-danger' : 'text-muted opacity-25' }}">
                                    {{ $m->type == 'debit' ? '$' . number_format($m->amount, 2, ',', '.') : '-' }}
                                </td>
                                <td class="text-end fw-bold {{ $m->type == 'credit' ? 'text-success' : 'text-muted opacity-25' }}">
                                    {{ $m->type == 'credit' ? '$' . number_format($m->amount, 2, ',', '.') : '-' }}
                                </td>
                                <td class="text-end pe-4">
                                    <span class="btn btn-sm btn-light rounded-circle shadow-none"><i class="fas fa-chevron-down x-small text-muted"></i></span>
                                </td>
                            </tr>
                            {{-- DETALLE COLAPSABLE --}}
                            <tr class="collapse" id="collapse-{{ $m->id }}">
                                <td colspan="5" class="p-0 border-0">
                                    <div class="bg-light p-4 border-bottom shadow-inner">
                                        <div class="row g-4">
                                            @if($m->type == 'debit')
                                                <div class="col-md-6">
                                                    <h6 class="x-small fw-bold text-uppercase text-muted mb-3">Imputaciones de Cobro</h6>
                                                    @if($m->imputaciones->count() > 0)
                                                        @foreach($m->imputaciones as $imp)
                                                            <div class="d-flex justify-content-between bg-white p-2 rounded-3 border mb-2">
                                                                <span class="small fw-semibold">Recibo #{{ str_pad($imp->recibo->numero_recibo ?? 0, 8, '0', STR_PAD_LEFT) }}</span>
                                                                <span class="small fw-bold text-success">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</span>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p class="text-muted small">No hay cobros aplicados aún.</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <h6 class="x-small fw-bold text-uppercase text-muted mb-3">Estado de Factura</h6>
                                                    <div class="mb-3">
                                                        @if($m->paid)
                                                            <span class="badge bg-success rounded-pill px-4 py-2">TOTALMENTE COBRADA</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark rounded-pill px-4 py-2">PENDIENTE: ${{ number_format($m->pending_amount, 2, ',', '.') }}</span>
                                                            <div class="mt-3">
                                                                <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCobro" onclick="preselectFactura({{ $m->id }}, {{ $m->pending_amount }})">
                                                                    Cobrar Saldo
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-6">
                                                    <h6 class="x-small fw-bold text-uppercase text-muted mb-3">Facturas que cubrió este cobro</h6>
                                                    @php $imputaciones = $m->reference ? $m->reference->imputaciones : collect([]); @endphp
                                                    @forelse($imputaciones as $imp)
                                                        <div class="d-flex justify-content-between bg-white p-2 rounded-3 border mb-2 text-sm">
                                                            <span class="small">{{ $imp->ledger->description }}</span>
                                                            <span class="small fw-bold text-danger">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</span>
                                                        </div>
                                                    @empty
                                                        <p class="text-muted small">Este cobro quedó como saldo a favor sin imputar.</p>
                                                    @endforelse
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <h6 class="x-small fw-bold text-uppercase text-muted mb-3">Medios de Pago Utilizados</h6>
                                                    @php $pagos = $m->reference ? $m->reference->pagos : collect([]); @endphp
                                                    @foreach($pagos as $p)
                                                        <div class="small fw-bold text-dark">{{ $p->metodo_pago }}: <span class="text-success">${{ number_format($p->monto, 2, ',', '.') }}</span></div>
                                                        <div class="x-small text-muted mb-2">{{ $p->referencia }}</div>
                                                    @endforeach
                                                    @if($m->reference_id)
                                                        <div class="mt-3">
                                                            <a href="{{ route('empresa.pagos.show', $m->reference_id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">Ver Recibo Oficial</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center">
                                    <i class="fas fa-folder-open fa-3x text-muted opacity-25 mb-3"></i>
                                    <h5 class="text-muted">Sin movimientos registrados</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($movimientos->hasPages())
                <div class="card-footer bg-white p-4">{{ $movimientos->links() }}</div>
                @endif
            </div>
        </div>

        {{-- COLUMNA LATERAL: AGING Y PENDIENTES --}}
        <div class="col-md-4">
            {{-- TABLA DE COMPROBANTES PENDIENTES --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-file-invoice text-danger me-2"></i> Facturas Impagas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table align-middle mb-0 text-sm">
                            <thead class="bg-light x-small fw-bold text-muted">
                                <tr>
                                    <th class="ps-4">Vto / Días</th>
                                    <th class="text-end">Importe</th>
                                    <th class="text-end pe-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deudas as $d)
                                @php $dias = $d->created_at->diffInDays(now()); @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $d->created_at->format('d/m/Y') }}</div>
                                        <div class="x-small {{ $dias > 30 ? 'text-danger fw-bold' : 'text-muted' }}">{{ $dias }} días de antigüedad</div>
                                    </td>
                                    <td class="text-end fw-bold text-danger">${{ number_format($d->pending_amount, 2, ',', '.') }}</td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#modalCobro" onclick="preselectFactura({{ $d->id }}, {{ $d->pending_amount }})">
                                            <i class="fas fa-plus text-success"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-5 text-center text-muted">No hay facturas pendientes</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- HISTORIAL DE RECIBOS --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-receipt text-success me-2"></i> Últimos Recibos</h5>
                </div>
                <div class="card-body p-0 pb-4">
                    @foreach($ultimosRecibos as $r)
                        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                            <div>
                                <div class="fw-bold text-dark">Recibo #{{ str_pad($r->numero_recibo, 8, '0', STR_PAD_LEFT) }}</div>
                                <div class="x-small text-muted">{{ $r->fecha->format('d/m/Y') }} · {{ $r->metodo_pago }}</div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success d-block">${{ number_format($r->monto_total, 2, ',', '.') }}</span>
                                <a href="{{ route('empresa.pagos.show', $r->id) }}" class="x-small text-decoration-none fw-bold">Ver</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE COBRO (RECIBO) --}}
<div class="modal fade" id="modalCobro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-primary text-white p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-hand-holding-usd me-2"></i> Nuevo Recibo de Cobro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCobro" action="{{ route('empresa.clientes.recibos.store', $client->id) }}" method="POST">
                @csrf
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <!-- Lado izquierdo: Selección de facturas -->
                        <div class="col-md-5 bg-light border-end p-4 border-bottom border-md-bottom-0">
                            <h6 class="x-small fw-bold text-uppercase text-muted mb-3 d-flex justify-content-between">
                                Facturas a Cobrar
                                <span class="text-primary fw-bold" style="cursor:pointer;" onclick="selectAllFacturas()">Todos</span>
                            </h6>
                            <div class="list-group list-group-flush rounded-3 border overflow-auto mb-3 shadow-sm" style="max-height: 350px;">
                                @forelse($deudas as $deuda)
                                    <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2" style="cursor:pointer;">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-3 factura-checkbox checkbox-primary" type="checkbox" name="facturas[]" value="{{ $deuda->id }}" id="chk-factura-{{ $deuda->id }}" data-monto="{{ $deuda->pending_amount }}" onchange="actualizarMontoRecibo()">
                                            <div>
                                                <span class="fw-bold d-block text-dark small">{{ $deuda->description }}</span>
                                                <small class="text-muted fs-xs">{{ $deuda->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                        <span class="fw-bold text-danger small">${{ number_format($deuda->pending_amount, 2, ',', '.') }}</span>
                                    </label>
                                @empty
                                    <div class="p-3 text-center text-muted small">No hay facturas pendientes.</div>
                                @endforelse
                            </div>
                            <div class="alert alert-info border-0 shadow-sm p-3 rounded-4 mb-0">
                                <i class="fas fa-info-circle me-1"></i> El monto se ajustará automáticamente a las facturas marcadas, pero puedes corregirlo manualmente si lo deseas.
                            </div>
                        </div>

                        <!-- Lado derecho: Medios de Pago -->
                        <div class="col-md-7 p-4 bg-white">
                            <div class="d-flex justify-content-between align-items-end mb-4 bg-light p-3 rounded-4 border">
                                <div class="flex-grow-1">
                                    <span class="x-small fw-bold text-muted text-uppercase d-block mb-1">Total del Recibo</span>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-4 fw-bold text-dark me-2">$</span>
                                        <input type="number" step="0.01" name="monto" id="inputMontoCobro" class="form-control form-control-lg border-0 bg-transparent fw-bold text-primary p-0 fs-3 no-arrows" placeholder="0.00" value="0.00" oninput="checkSums()" required>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <input type="date" name="fecha" class="form-control form-control-sm border-0 bg-white rounded-pill shadow-sm" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <label class="x-small fw-bold text-muted text-uppercase d-flex justify-content-between mb-3">
                                Composición del Pago
                                <span class="text-primary fw-bold" style="cursor:pointer;" onclick="addPaymentRow()"><i class="fas fa-plus-circle me-1"></i>Añadir medio</span>
                            </label>
                            
                            <div id="paymentRows" class="mb-4">
                                <div class="row g-2 align-items-start mb-3 payment-row" id="row-0">
                                    <div class="col-sm-5">
                                        <select name="pagos_diferenciados[0][metodo_pago]" class="form-select border-0 bg-light rounded-pill px-3" onchange="toggleCheckInput(this, 0)" required>
                                            <option value="efectivo">Efectivo 💵</option>
                                            <option value="transferencia">Transferencia 🏦</option>
                                            <option value="tarjeta">Tarjeta 💳</option>
                                            <option value="mercado_pago">Mercado Pago 📱</option>
                                            <option value="cheque_tercero">Cheque de Tercero ✍️</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light text-muted fw-bold rounded-start-pill">$</span>
                                            <input type="number" step="0.01" name="pagos_diferenciados[0][monto]" class="form-control border-0 bg-light fw-bold text-dark rounded-end-pill line-monto" placeholder="0.00" oninput="checkSums()" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 text-center pt-1">
                                        <!-- No delete button for first row -->
                                    </div>
                                    
                                    {{-- CAMPOS PARA CHEQUE --}}
                                    <div id="check-fields-0" style="display:none;" class="col-12 mt-2 bg-light p-3 rounded-4 border border-primary border-opacity-25">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="x-small fw-bold text-muted mb-1 d-block">Banco</label>
                                                <input type="text" name="pagos_diferenciados[0][banco]" class="form-control form-control-sm border-0 bg-white rounded-pill px-3" placeholder="Ej: Galicia">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="x-small fw-bold text-muted mb-1 d-block">Número</label>
                                                <input type="text" name="pagos_diferenciados[0][numero]" class="form-control form-control-sm border-0 bg-white rounded-pill px-3" placeholder="Nro Cheque">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="x-small fw-bold text-muted mb-1 d-block">Vto Pago</label>
                                                <input type="date" name="pagos_diferenciados[0][fecha_pago]" class="form-control form-control-sm border-0 bg-white rounded-pill px-3">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-1 px-1">
                                        <input type="text" name="pagos_diferenciados[0][referencia]" class="form-control form-control-sm border-0 border-bottom bg-transparent px-2 x-small" placeholder="Opcional: N° Comprobante, Operación, etc.">
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="metodo_pago" value="multiple">

                            <div id="sumValidation" class="alert d-none py-2 x-small rounded-pill"></div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm mt-3 border-0 scale-up">
                                <i class="fas fa-save me-2"></i> REGISTRAR Y DESCARGAR RECIBO
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL CRUZAR SALDO --}}
<div class="modal fade" id="modalAplicarSaldo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-warning text-dark p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-magic me-2"></i> Imputar Saldo a Favor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.clientes.aplicar_saldo', $client->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <p class="text-muted small">Tienes <strong>${{ number_format($saldoAFavorDisponible, 2, ',', '.') }}</strong> sin imputar. Selecciona qué facturas deseas descontar con este dinero:</p>
                    <div class="list-group list-group-flush rounded-3 border overflow-auto mb-3 text-start shadow-sm" style="max-height: 250px;">
                        @foreach($deudas as $deuda)
                            <label class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <input class="form-check-input me-3" type="checkbox" name="facturas_aplicar[]" value="{{ $deuda->id }}">
                                    <span class="small fw-bold">{{ $deuda->description }}</span>
                                </div>
                                <span class="fw-bold text-danger small">${{ number_format($deuda->pending_amount, 2, ',', '.') }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer p-4 pt-0 border-0">
                    <button type="submit" class="btn btn-warning w-100 py-3 rounded-pill fw-bold text-dark shadow-sm">Confirmar Imputación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.75rem; }
    .fs-xs { font-size: 0.7rem; }
    .btn-xs { padding: 0.15rem 0.5rem; font-size: 0.65rem; }
    .scale-up { transition: all 0.2s ease; }
    .scale-up:hover { transform: scale(1.02); }
    .shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.06); }
    .no-arrows::-webkit-outer-spin-button, .no-arrows::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

@endsection

@section('scripts')
<script>
    let rowCount = 1;

    function preselectFactura(id, monto) {
        document.querySelectorAll('.factura-checkbox').forEach(c => c.checked = false);
        const chk = document.getElementById(`chk-factura-${id}`);
        if(chk) {
            chk.checked = true;
            actualizarMontoRecibo();
        }
    }

    function selectAllFacturas() {
        document.querySelectorAll('.factura-checkbox').forEach(c => c.checked = true);
        actualizarMontoRecibo();
    }

    function actualizarMontoRecibo() {
        let total = 0;
        document.querySelectorAll('.factura-checkbox:checked').forEach(c => {
            total += parseFloat(c.getAttribute('data-monto'));
        });
        document.getElementById('inputMontoCobro').value = total.toFixed(2);
        
        // Sincronizar con la primera línea si solo hay una
        const lines = document.querySelectorAll('.line-monto');
        if(lines.length === 1) {
            lines[0].value = total.toFixed(2);
            checkSums();
        }
    }

    function addPaymentRow() {
        const container = document.getElementById('paymentRows');
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-start mb-3 payment-row';
        row.id = `row-${rowCount}`;
        row.innerHTML = `
            <div class="col-sm-5">
                <select name="pagos_diferenciados[${rowCount}][metodo_pago]" class="form-select border-0 bg-light rounded-pill px-3" onchange="toggleCheckInput(this, ${rowCount})" required>
                    <option value="efectivo">Efectivo 💵</option>
                    <option value="transferencia">Transferencia 🏦</option>
                    <option value="tarjeta">Tarjeta 💳</option>
                    <option value="mercado_pago">Mercado Pago 📱</option>
                    <option value="cheque_tercero">Cheque de Tercero ✍️</option>
                </select>
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light text-muted fw-bold rounded-start-pill">$</span>
                    <input type="number" step="0.01" name="pagos_diferenciados[${rowCount}][monto]" class="form-control border-0 bg-light fw-bold text-dark rounded-end-pill line-monto" placeholder="0.00" oninput="checkSums()" required>
                </div>
            </div>
            <div class="col-sm-1 text-center pt-1">
                <button type="button" class="btn btn-sm btn-outline-danger border-0 p-0" onclick="removeRow(${rowCount})"><i class="fas fa-times-circle fs-5"></i></button>
            </div>
            <div id="check-fields-${rowCount}" style="display:none;" class="col-12 mt-2 bg-light p-3 rounded-4 border border-primary border-opacity-25">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="x-small fw-bold text-muted mb-1 d-block">Banco</label>
                        <input type="text" name="pagos_diferenciados[${rowCount}][banco]" class="form-control form-control-sm border-0 bg-white rounded-pill px-3" placeholder="Ej: Galicia">
                    </div>
                    <div class="col-md-4">
                        <label class="x-small fw-bold text-muted mb-1 d-block">Número</label>
                        <input type="text" name="pagos_diferenciados[${rowCount}][numero]" class="form-control form-control-sm border-0 bg-white rounded-pill px-3" placeholder="Nro Cheque">
                    </div>
                    <div class="col-md-4">
                        <label class="x-small fw-bold text-muted mb-1 d-block">Vto Pago</label>
                        <input type="date" name="pagos_diferenciados[${rowCount}][fecha_pago]" class="form-control form-control-sm border-0 bg-white rounded-pill px-3">
                    </div>
                </div>
            </div>
            <div class="col-12 mt-1 px-1">
                <input type="text" name="pagos_diferenciados[${rowCount}][referencia]" class="form-control form-control-sm border-0 border-bottom bg-transparent px-2 x-small" placeholder="Referencia opcional...">
            </div>
        `;
        container.appendChild(row);
        rowCount++;
    }

    function removeRow(id) {
        document.getElementById(`row-${id}`).remove();
        checkSums();
    }

    function toggleCheckInput(select, idx) {
        const div = document.getElementById(`check-fields-${idx}`);
        div.style.display = select.value === 'cheque_tercero' ? 'block' : 'none';
        
        const inputs = div.querySelectorAll('input');
        if(select.value === 'cheque_tercero') {
            inputs.forEach(i => i.setAttribute('required', 'required'));
        } else {
            inputs.forEach(i => i.removeAttribute('required'));
        }
    }

    function checkSums() {
        const totalPrincipal = parseFloat(document.getElementById('inputMontoCobro').value) || 0;
        let sumaLineas = 0;
        document.querySelectorAll('.line-monto').forEach(i => sumaLineas += parseFloat(i.value) || 0);

        const badge = document.getElementById('sumValidation');
        if (Math.abs(totalPrincipal - sumaLineas) > 0.01) {
            badge.className = "alert alert-danger py-2 x-small rounded-pill d-block mt-3";
            badge.innerText = `⚠️ La suma de medios ($${sumaLineas.toFixed(2)}) no coincide con el total ($${totalPrincipal.toFixed(2)})`;
        } else {
            badge.className = "d-none";
        }
    }
{{-- MODAL GESTIÓN DE CUENTAS BANCARIAS DEL CLIENTE --}}
<div class="modal fade" id="modalCuentasBanco" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4 border-0">
                <h5 class="modal-title fw-bold">Cuentas Bancarias: {{ $client->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Listado de Cuentas Existentes -->
                    <div class="col-md-7">
                        <h6 class="text-uppercase x-small fw-bold text-muted mb-3">Cuentas Registradas</h6>
                        <div class="list-group list-group-flush rounded-3 border overflow-auto" style="max-height: 400px;">
                            @forelse($client->bankAccounts as $acc)
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
                                    <p>No hay cuentas bancarias registradas para este cliente.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Formulario Nueva Cuenta -->
                    <div class="col-md-5 bg-light p-4 rounded-4">
                        <h6 class="text-uppercase x-small fw-bold text-muted mb-3">Registrar Nueva Cuenta</h6>
                        <form action="{{ route('empresa.tesoreria.bank-accounts.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="holder_type" value="{{ get_class($client) }}">
                            <input type="hidden" name="holder_id" value="{{ $client->id }}">
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Banco</label>
                                <input type="text" name="bank_name" class="form-control form-control-sm border-0 bg-white rounded-pill px-3 shadow-sm" placeholder="Ej: Galicia, BBVA..." required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">CBU / CVU</label>
                                <input type="text" name="cbu_cvu" class="form-control form-control-sm border-0 bg-white rounded-pill px-3 shadow-sm" placeholder="22 dígitos">
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Alias</label>
                                <input type="text" name="alias" class="form-control form-control-sm border-0 bg-white rounded-pill px-3 shadow-sm" placeholder="Ej: CASA.PERRO.GATO">
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
</script>
@endsection
