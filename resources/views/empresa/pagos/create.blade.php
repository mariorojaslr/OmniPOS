@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0 text-dark"><i class="fas fa-hand-holding-usd text-success me-2"></i> Registrar Nuevo Cobro</h2>
                    <p class="text-muted mb-0">Registre un ingreso e impútelo inteligentemente a las deudas del cliente.</p>
                </div>
                <a href="{{ route('empresa.pagos.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Volver a Pagos
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm border-0 rounded-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('empresa.pagos.store') }}" method="POST" id="formCobro">
                @csrf
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-5">
                    
                    <!-- HEADER SECTION: CLIENT & DATE -->
                    <div class="bg-light p-4 border-bottom">
                        <div class="row g-4 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label text-uppercase small fw-extrabold text-muted tracking-wider">Cliente que abona</label>
                                <select name="client_id" id="clientSelect" class="form-select form-select-lg shadow-sm border-0 fw-bold" required autofocus>
                                    <option value="">-- Buscar o Seleccionar Cliente --</option>
                                    @foreach($clientes as $c)
                                        <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }} {{ $c->document ? '('.$c->document.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-uppercase small fw-extrabold text-muted tracking-wider">Fecha del Registro</label>
                                <input type="date" name="fecha" class="form-control form-control-lg shadow-sm border-0" value="{{ old('fecha', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- SMART INFO PANEL (HIDDEN BY DEFAULT) -->
                    <div id="smartDebtPanel" class="d-none">
                        <!-- RESUMEN ENCABEZADO -->
                        <div class="row g-0 border-bottom text-center">
                            <div class="col-6 p-3 bg-danger bg-opacity-10 border-end">
                                <span class="small text-uppercase fw-bold text-danger d-block mb-1">Deuda Pendiente</span>
                                <h3 class="mb-0 text-danger fw-extrabold" id="lblDeudaTotal">$0.00</h3>
                            </div>
                            <div class="col-6 p-3 bg-success bg-opacity-10">
                                <span class="small text-uppercase fw-bold text-success d-block mb-1">Saldo a Favor Existente</span>
                                <h3 class="mb-0 text-success fw-extrabold" id="lblSaldoFavor">$0.00</h3>
                            </div>
                        </div>

                        <div class="p-4" style="background: #fdfdfd;">
                            <!-- LISTA DE FACTURAS -->
                            <div id="seccionFacturas" class="d-none mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-list-check text-primary me-2"></i>Facturas Impagas Elegibles</h6>
                                    <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm" id="countFacturas">0</span>
                                </div>
                                <p class="small text-muted mb-3">Tilda las facturas que el cliente está pagando. El monto a cobrar se calculará solo.</p>
                                
                                <div class="border rounded-3 overflow-hidden shadow-sm">
                                    <div class="list-group list-group-flush custom-scrollbar" id="listaFacturasContainer" style="max-height: 250px; overflow-y: auto;">
                                        <!-- Rendereado via JS -->
                                    </div>
                                </div>
                            </div>
                            <div id="noFacturasMsg" class="d-none alert alert-light border shadow-sm text-center py-4 mb-4">
                                <i class="fas fa-check-circle text-success fs-1 mb-2"></i>
                                <h6 class="fw-bold text-dark mb-0">¡Cliente al día!</h6>
                                <p class="text-muted small mb-0">Este cliente no registra deudas. Cualquier importe cobrado quedará como saldo a favor.</p>
                            </div>

                            <!-- MEDIOS DE PAGO -->
                            <div class="bg-white p-4 rounded-4 shadow-sm border mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-wallet text-warning me-2"></i>Composición del Cobro</h6>
                                        <small class="text-muted">¿Cómo nos pagó este importe?</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm fw-bold border-2" onclick="addPaymentMethod()">
                                        <i class="fas fa-plus me-1"></i> Añadir Medio
                                    </button>
                                </div>

                                <div id="paymentRows" class="mb-3">
                                    <!-- Fila Inicial -->
                                    <div class="row g-2 align-items-center mb-2 payment-row">
                                        <div class="col-md-3">
                                            <select name="pagos_diferenciados[0][metodo_pago]" class="form-select form-select-sm border-0 bg-light shadow-none fw-bold" required>
                                                <option value="Efectivo">Efectivo 💵</option>
                                                <option value="Transferencia">Transferencia 🏦</option>
                                                <option value="Mercado Pago">Mercado Pago 📱</option>
                                                <option value="Tarjeta">Tarjeta 💳</option>
                                                <option value="E-Check">Cheque ✍️</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="input-group input-group-sm shadow-sm rounded">
                                                <span class="input-group-text bg-white border-end-0 text-muted fw-bold">$</span>
                                                <input type="number" step="0.01" name="pagos_diferenciados[0][monto]" class="form-control border-start-0 ps-0 pago-monto text-primary fs-5 fw-extrabold text-end bg-white" placeholder="0.00" required onkeyup="syncGlobalMonto()" id="montoPrincipal">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="pagos_diferenciados[0][referencia]" class="form-control form-control-sm border-0 bg-light shadow-none" placeholder="Referencia / Nro...">
                                        </div>
                                        <div class="col-md-1 text-center"></div>
                                    </div>
                                </div>

                                <!-- TOTAL FINAL -->
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3">
                                    <span class="text-uppercase small fw-extrabold text-muted tracking-wider">TOTAL A RECIBIR</span>
                                    <h1 class="mb-0 fw-black text-success" id="displayMontoTotal">$0.00</h1>
                                    <input type="hidden" name="monto" id="inputHiddenMonto" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FOOTER ACTIONS -->
                    <div class="card-footer bg-white p-4 border-top text-end">
                        <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow fw-bold" id="btnSubmitForm" disabled>
                            <i class="fas fa-check-circle me-2"></i> ACREDITAR PAGO
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .fw-extrabold { font-weight: 800; }
    .tracking-wider { letter-spacing: 0.1em; }
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8f9fa; }
    
    .chk-factura:checked + .factura-label {
        background-color: #f0fdf4;
        border-color: #22c55e !important;
    }
</style>

<script>
    let paymentRowIndex = 1;

    document.getElementById('clientSelect').addEventListener('change', function() {
        const clientId = this.value;
        const panel = document.getElementById('smartDebtPanel');
        const btnSubmit = document.getElementById('btnSubmitForm');
        
        if (!clientId) {
            panel.classList.add('d-none');
            btnSubmit.disabled = true;
            return;
        }

        // Mostrar un pequeño loader
        panel.classList.remove('d-none');
        btnSubmit.disabled = false;
        document.getElementById('lblDeudaTotal').innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        document.getElementById('lblSaldoFavor').innerHTML = '';
        
        // Fetch API
        fetch(`{{ url('empresa/clientes') }}/${clientId}/deudas`)
            .then(res => res.json())
            .then(data => {
                // Actualizar contadores globales
                document.getElementById('lblDeudaTotal').innerText = '$' + parseFloat(data.deuda_total).toLocaleString('en-US', {minimumFractionDigits:2});
                document.getElementById('lblSaldoFavor').innerText = '$' + parseFloat(data.saldo_a_favor).toLocaleString('en-US', {minimumFractionDigits:2});

                const count = data.deudas.length;
                document.getElementById('countFacturas').innerText = count;

                const listContainer = document.getElementById('listaFacturasContainer');
                listContainer.innerHTML = '';

                if (count > 0) {
                    document.getElementById('seccionFacturas').classList.remove('d-none');
                    document.getElementById('noFacturasMsg').classList.add('d-none');

                    data.deudas.forEach(d => {
                        listContainer.innerHTML += `
                            <div class="list-group-item list-group-item-action p-0 border-bottom border-light">
                                <label class="d-flex align-items-center w-100 p-3 m-0 factura-label" style="cursor:pointer; transition: all 0.2s;">
                                    <div class="me-3">
                                        <input class="form-check-input chk-factura fs-5 m-0 shadow-sm" type="checkbox" name="facturas[]" value="${d.id}" data-monto="${d.pendiente}">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark">${d.descripcion}</h6>
                                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> Emitida: ${d.fecha}</small>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="mb-0 fw-extrabold text-danger">$${parseFloat(d.pendiente).toLocaleString('en-US', {minimumFractionDigits:2})}</h5>
                                    </div>
                                </label>
                            </div>
                        `;
                    });

                    // Añadir listeners a los checkboxes
                    document.querySelectorAll('.chk-factura').forEach(chk => {
                        chk.addEventListener('change', autoCalcFromInvoices);
                    });

                } else {
                    document.getElementById('seccionFacturas').classList.add('d-none');
                    document.getElementById('noFacturasMsg').classList.remove('d-none');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Ocurrió un error consultando la cuenta corriente');
            });
    });

    function autoCalcFromInvoices() {
        let sum = 0;
        let selectedAny = false;
        document.querySelectorAll('.chk-factura:checked').forEach(chk => {
            sum += parseFloat(chk.getAttribute('data-monto'));
            selectedAny = true;
        });

        // Si el usuario marcó facturas, modificamos SOLO EL PRIMER INPUT y reseteamos el resto si no tienen custom amounts
        if (selectedAny || sum > 0) {
            // Obtenemos todos los inputs de pago
            let paymentInputs = document.querySelectorAll('.pago-monto');
            
            // Si hay un solo input, le asignamos el total completo
            if (paymentInputs.length === 1) {
                paymentInputs[0].value = sum.toFixed(2);
            } else {
                // Si hay varios, intentamos poner el remanente en el primero que esté vacío, o en el principal
                // Para no sobreescribir lógica manual complicada, si el usuario desmarca facturas, actualizamos el principal.
                // Esta es una implementación simplificada:
                let totalOtrosMts = 0;
                for(let i=1; i<paymentInputs.length; i++) {
                    totalOtrosMts += parseFloat(paymentInputs[i].value) || 0;
                }
                
                let residual = sum - totalOtrosMts;
                if(residual < 0) residual = sum; // Fallback
                paymentInputs[0].value = residual.toFixed(2);
            }
            syncGlobalMonto();
        }
    }

    function addPaymentMethod() {
        const container = document.getElementById('paymentRows');
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center mb-2 payment-row fade-in';
        row.innerHTML = `
            <div class="col-md-3">
                <select name="pagos_diferenciados[${paymentRowIndex}][metodo_pago]" class="form-select form-select-sm border-0 bg-light shadow-none fw-bold" required>
                    <option value="Efectivo">Efectivo 💵</option>
                    <option value="Transferencia">Transferencia 🏦</option>
                    <option value="Mercado Pago">Mercado Pago 📱</option>
                    <option value="Tarjeta">Tarjeta 💳</option>
                    <option value="E-Check">Cheque ✍️</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group input-group-sm shadow-sm rounded">
                    <span class="input-group-text bg-white border-end-0 text-muted fw-bold">$</span>
                    <input type="number" step="0.01" name="pagos_diferenciados[${paymentRowIndex}][monto]" class="form-control border-start-0 ps-0 pago-monto text-primary fs-5 fw-extrabold text-end bg-white" placeholder="0.00" required onkeyup="syncGlobalMonto()">
                </div>
            </div>
            <div class="col-md-3">
                <input type="text" name="pagos_diferenciados[${paymentRowIndex}][referencia]" class="form-control form-control-sm border-0 bg-light shadow-none" placeholder="Referencia / Nro...">
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle" onclick="removePaymentMethod(this)"><i class="fas fa-times"></i></button>
            </div>
        `;
        container.appendChild(row);
        paymentRowIndex++;
    }

    function removePaymentMethod(btn) {
        btn.closest('.payment-row').remove();
        syncGlobalMonto();
    }

    function syncGlobalMonto() {
        let total = 0;
        document.querySelectorAll('.pago-monto').forEach(input => {
            let val = parseFloat(input.value);
            if (!isNaN(val)) total += val;
        });
        document.getElementById('displayMontoTotal').innerText = '$' + total.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        document.getElementById('inputHiddenMonto').value = total.toFixed(2);
    }
</script>
@endsection
