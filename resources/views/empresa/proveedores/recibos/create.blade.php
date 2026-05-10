@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('empresa.recibos-proveedores.index') }}" class="btn btn-light rounded-circle me-3 shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0 text-dark">Generar Nuevo Recibo</h2>
                    <p class="text-muted mb-0">Carga un pago para un proveedor específico</p>
                </div>
            </div>

            <form action="{{ route('empresa.recibos-proveedores.store') }}" method="POST" id="formRecibo">
                @csrf
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            {{-- SELECCIÓN DE PROVEEDOR --}}
                            <div class="col-lg-12 p-4 bg-white border-bottom">
                                <label class="small text-uppercase fw-bold text-muted mb-2 d-block">1. Seleccionar Proveedor</label>
                                <select name="supplier_id" id="supplier_id" class="form-select form-select-lg border-0 bg-light rounded-4 shadow-none fw-bold" required onchange="loadSupplierDeuda(this.value)">
                                    <option value="">-- Seleccionar un Proveedor --</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id }}" {{ (isset($selectedSupplier) && $selectedSupplier->id == $s->id) ? 'selected' : '' }}>
                                            {{ $s->name }} {{ $s->cuit ? '('.$s->cuit.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- PANEL IZQUIERDO: DEUDAS --}}
                            <div class="col-lg-7 p-4 bg-light" style="border-right: 1px solid #e5e7eb;">
                                <h6 class="text-uppercase fw-bold text-muted small mb-3">
                                    <i class="fas fa-list-check me-1"></i> 2. Comprobantes Pendientes
                                    <span id="loadingDeuda" class="spinner-border spinner-border-sm ms-2" style="display:none;"></span>
                                </h6>
                                <div id="deudasContainer" class="overflow-auto custom-scrollbar" style="max-height: 400px;">
                                    <div class="p-5 text-center text-muted opacity-50">
                                        <i class="fas fa-truck fa-3x mb-3"></i>
                                        <p>Selecciona un proveedor para ver sus deudas pendientes</p>
                                    </div>
                                </div>

                                {{-- DISPLAY DE TOTAL --}}
                                <div class="card border-0 bg-dark text-white p-4 rounded-4 shadow-lg text-center mt-4">
                                    <span class="text-uppercase text-light opacity-50 small fw-bold">Monto Total del Recibo</span>
                                    <h1 class="display-5 fw-bold mb-0" id="displayMontoTotal">$0,00</h1>
                                    <input type="hidden" name="monto" id="inputMontoTotal">
                                </div>
                            </div>

                            {{-- PANEL DERECHO: FORMAS DE PAGO --}}
                            <div class="col-lg-5 p-4 bg-white">
                                <h6 class="text-uppercase fw-bold text-muted small mb-3">
                                    <i class="fas fa-wallet me-1"></i> 3. Formas de Pago
                                </h6>
                                
                                <div id="paymentMethodsRows" class="mb-3">
                                    <div class="payment-item bg-light p-3 rounded-4 mb-3 border position-relative">
                                        <div class="mb-3">
                                            <label class="x-small fw-bold text-muted mb-1 d-block ms-2">Método de Pago</label>
                                            <select name="pagos_diferenciados[0][metodo_pago]" class="form-select border-0 shadow-none bg-white rounded-pill" onchange="toggleChequeInput(this, 0)" required>
                                                <option value="efectivo">💵 Efectivo</option>
                                                <option value="transferencia">🏦 Transferencia Bancaria</option>
                                                <option value="debito_automatico">💳 Débito Automático</option>
                                                <option value="cheque_tercero">✍️ Cheque de Terceros</option>
                                                <option value="cheque_propio">📝 Cheque Propio</option>
                                            </select>
                                        </div>
                                        
                                        <div id="cuenta-selection-0" class="mb-3">
                                            <label class="x-small fw-bold text-muted mb-1 d-block ms-2">Cuenta / Caja Origen</label>
                                            <select name="pagos_diferenciados[0][finanza_cuenta_id]" class="form-select border-0 shadow-none bg-white rounded-pill">
                                                <option value="">-- Seleccionar Cuenta --</option>
                                                @foreach($cuentas as $acc)
                                                    <option value="{{ $acc->id }}">{{ $acc->nombre }} (${{ number_format($acc->saldo_actual, 2) }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="cheque-selection-0" style="display:none;" class="mb-3">
                                            <label class="x-small fw-bold text-muted mb-1 d-block ms-2">Cheque de Terceros</label>
                                            <select name="pagos_diferenciados[0][cheque_id]" class="form-select border-0 shadow-none bg-white rounded-pill" onchange="applyChequeAmount(this, 0)">
                                                <option value="">-- Seleccionar Cheque --</option>
                                                @foreach($cheques as $c)
                                                    <option value="{{ $c->id }}" data-monto="{{ $c->monto }}">
                                                        #{{ $c->numero }} · {{ $c->banco }} · ${{ number_format($c->monto, 2, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="chequera-selection-0" style="display:none;" class="mb-3 bg-white p-3 rounded-4 border">
                                            <label class="x-small fw-bold text-muted mb-2 d-block">Emitir Cheque Propio</label>
                                            <select name="pagos_diferenciados[0][chequera_id]" class="form-select border-0 bg-light rounded-pill mb-2">
                                                <option value="">-- Seleccionar Chequera --</option>
                                                @foreach($chequeras as $cq)
                                                    <option value="{{ $cq->id }}">{{ $cq->banco }} ({{ $cq->numero_cuenta }})</option>
                                                @endforeach
                                            </select>
                                            <input type="date" name="pagos_diferenciados[0][fecha_pago]" class="form-control border-0 bg-light rounded-pill mb-2" placeholder="Fecha Vto">
                                            <input type="text" name="pagos_diferenciados[0][numero]" class="form-control border-0 bg-light rounded-pill" placeholder="Nº Cheque">
                                        </div>

                                        <div class="mb-0">
                                            <label class="x-small fw-bold text-muted mb-1 d-block ms-2">Importe</label>
                                            <div class="input-group">
                                                <span class="input-group-text border-0 bg-white text-muted fw-bold rounded-start-pill">$</span>
                                                <input type="number" step="0.01" name="pagos_diferenciados[0][monto]" class="form-control border-0 shadow-none bg-white fw-bold text-danger rounded-end-pill line-monto" placeholder="0.00" oninput="checkSums()" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-4 mb-4 w-100" onclick="addPaymentRow()">
                                    <i class="fas fa-plus-circle me-1"></i> Añadir otro medio de pago
                                </button>

                                <div class="mb-3">
                                    <label class="small text-muted fw-bold mb-1">Fecha del Recibo</label>
                                    <input type="date" name="fecha" class="form-control border-0 bg-light rounded-pill" value="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-lg" id="btnSubmitPago" disabled>
                                        <i class="fas fa-check-circle me-2"></i> GENERAR RECIBO DE PAGO
                                    </button>
                                    <div id="feedbackSum" class="small text-center mt-2 text-danger fw-bold" style="display:none;">
                                        <i class="fas fa-exclamation-triangle me-1"></i> El total de pagos no coincide con el total de comprobantes
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.75rem; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<script>
    let rowIndex = 1;

    function loadSupplierDeuda(supplierId) {
        if (!supplierId) {
            $('#deudasContainer').html('<div class="p-5 text-center text-muted opacity-50"><i class="fas fa-truck fa-3x mb-3"></i><p>Selecciona un proveedor para ver sus deudas pendientes</p></div>');
            return;
        }

        $('#loadingDeuda').show();
        
        // Simular carga de deudas vía AJAX (podemos crear una ruta de API o simplemente re-usar lo que tenemos)
        // Por ahora lo haré simple: el usuario selecciona los comprobantes si los hay.
        // Pero necesitamos una ruta que nos traiga el HTML o JSON de las deudas.
        
        fetch(`{{ route('empresa.api.supplier_deuda', ['supplier' => ':id']) }}`.replace(':id', supplierId))
            .then(response => response.json())
            .then(data => {
                $('#loadingDeuda').hide();
                let html = '';
                if (data.length === 0) {
                    html = '<div class="p-4 text-center text-muted border border-dashed rounded-4 bg-white"><i class="fas fa-check-circle text-success fs-3 mb-2 d-block"></i><p class="mb-0">Este proveedor no tiene deudas pendientes</p></div>';
                } else {
                    data.forEach(d => {
                        html += `
                        <label class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 border mb-2 cursor-pointer hover-shadow">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input me-3 compra-checkbox" type="checkbox" name="compras[]" value="${d.id}" data-monto="${d.pending_amount}" style="transform: scale(1.3);" onchange="recalculateTotal()">
                                <div>
                                    <span class="fw-bold d-block text-dark">${d.description}</span>
                                    <small class="text-muted">${d.date}</small>
                                </div>
                            </div>
                            <span class="text-danger fw-bold fs-6">$${parseFloat(d.pending_amount).toLocaleString('es-AR', {minimumFractionDigits: 2})}</span>
                        </label>`;
                    });
                }
                $('#deudasContainer').html(html);
                recalculateTotal();
            });
    }

    function toggleChequeInput(select, index) {
        const val = select.value;
        $(`#cheque-selection-${index}`).hide();
        $(`#chequera-selection-${index}`).hide();
        $(`#cuenta-selection-${index}`).show();

        if (val === 'cheque_tercero') {
            $(`#cheque-selection-${index}`).show();
            $(`#cuenta-selection-${index}`).hide();
        } else if (val === 'cheque_propio') {
            $(`#chequera-selection-${index}`).show();
        }
    }

    function applyChequeAmount(select, index) {
        const monto = $(select).find(':selected').data('monto');
        if (monto) {
            $(`input[name="pagos_diferenciados[${index}][monto]"]`).val(monto);
            checkSums();
        }
    }

    function addPaymentRow() {
        const row = `
        <div class="payment-item bg-light p-3 rounded-4 mb-3 border position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="$(this).parent().remove(); checkSums();"></button>
            <div class="mb-3">
                <label class="x-small fw-bold text-muted mb-1 d-block ms-2">Método de Pago</label>
                <select name="pagos_diferenciados[${rowIndex}][metodo_pago]" class="form-select border-0 shadow-none bg-white rounded-pill" onchange="toggleChequeInput(this, ${rowIndex})" required>
                    <option value="efectivo">💵 Efectivo</option>
                    <option value="transferencia">🏦 Transferencia Bancaria</option>
                    <option value="debito_automatico">💳 Débito Automático</option>
                    <option value="cheque_tercero">✍️ Cheque de Terceros</option>
                    <option value="cheque_propio">📝 Cheque Propio</option>
                </select>
            </div>
            <div id="cuenta-selection-${rowIndex}" class="mb-3">
                <select name="pagos_diferenciados[${rowIndex}][finanza_cuenta_id]" class="form-select border-0 shadow-none bg-white rounded-pill">
                    <option value="">-- Seleccionar Cuenta --</option>
                    @foreach($cuentas as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->nombre }} (${{ number_format($acc->saldo_actual, 2) }})</option>
                    @endforeach
                </select>
            </div>
            <div id="cheque-selection-${rowIndex}" style="display:none;" class="mb-3">
                <select name="pagos_diferenciados[${rowIndex}][cheque_id]" class="form-select border-0 shadow-none bg-white rounded-pill" onchange="applyChequeAmount(this, ${rowIndex})">
                    <option value="">-- Seleccionar Cheque --</option>
                    @foreach($cheques as $c)
                        <option value="{{ $c->id }}" data-monto="{{ $c->monto }}">
                            #{{ $c->numero }} · {{ $c->banco }} · ${{ number_format($c->monto, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-0">
                <div class="input-group">
                    <span class="input-group-text border-0 bg-white text-muted fw-bold rounded-start-pill">$</span>
                    <input type="number" step="0.01" name="pagos_diferenciados[${rowIndex}][monto]" class="form-control border-0 shadow-none bg-white fw-bold text-danger rounded-end-pill line-monto" placeholder="0.00" oninput="checkSums()" required>
                </div>
            </div>
        </div>`;
        
        $('#paymentMethodsRows').append(row);
        rowIndex++;
    }

    function recalculateTotal() {
        let total = 0;
        $('.compra-checkbox:checked').each(function() {
            total += parseFloat($(this).data('monto'));
        });
        
        $('#displayMontoTotal').text('$' + total.toLocaleString('es-AR', {minimumFractionDigits: 2}));
        $('#inputMontoTotal').val(total.toFixed(2));

        // Si hay una sola fila de pago vacía, autocompletar
        if ($('.line-monto').length === 1 && $('.line-monto').val() == '') {
            $('.line-monto').val(total.toFixed(2));
        }

        checkSums();
    }

    function checkSums() {
        const targetTotal = parseFloat($('#inputMontoTotal').val()) || 0;
        let sumPayments = 0;
        $('.line-monto').each(function() {
            sumPayments += parseFloat($(this).val()) || 0;
        });

        const btn = $('#btnSubmitPago');
        const feedback = $('#feedbackSum');

        // Permitir guardar si el total es > 0, sin importar si coincide EXACTO (a veces es un pago a cuenta)
        // Pero si hay seleccionados comprobantes, avisar si no coincide.
        if (sumPayments > 0) {
            btn.prop('disabled', false);
        } else {
            btn.prop('disabled', true);
        }

        if (targetTotal > 0 && Math.abs(sumPayments - targetTotal) > 0.01) {
            feedback.show();
        } else {
            feedback.hide();
        }
    }

    // Al cargar si ya hay un supplier seleccionado
    $(document).ready(function() {
        if ($('#supplier_id').val()) {
            loadSupplierDeuda($('#supplier_id').val());
        }
    });
</script>
@endsection
