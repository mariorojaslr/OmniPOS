@extends('layouts.empresa')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5.min.css" />
<style>
    .luxury-card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #ffffff; }
    .table-luxury thead th { background: #f8f9fa; border: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #6c757d; padding: 12px; }
    .table-luxury td { vertical-align: middle; border-bottom: 1px solid #f1f3f5; padding: 12px; }
    .btn-confirm { background: linear-gradient(135deg, #198754, #146c43); border: none; transition: all 0.3s; }
    .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3); }
    .stk-badge { font-size: 0.7rem; padding: 3px 8px; border-radius: 6px; }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-primary">✍️ Venta Manual Profesional</h2>
            <p class="text-muted mb-0">Gestión de facturación mayorista y administración central</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.ventas.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-clock-history me-1"></i> Historial
            </a>
        </div>
    </div>

    @if(session('info'))
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4 animate__animated animate__fadeIn">
            <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
            <div><strong>Sugerencia:</strong> {{ session('info') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('empresa.ventas.manual.store') }}" id="formVentaManual">
    @csrf

    <div class="row g-4">
        <div class="col-md-9">
            <!-- CABECERA -->
            <div class="card luxury-card mb-4 border-start border-4 border-primary">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-uppercase text-muted">Cliente / Razón Social</label>
                            <select name="client_id" id="clientSelect" class="form-select" required>
                                <option value="">Seleccionar Cliente...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                        @if(isset($prefill['client_id']) && $prefill['client_id'] == $client->id) selected @endif>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Buscador CUIT (AFIP)</label>
                            <div class="input-group">
                                <input type="text" id="cuitSearch" class="form-control" placeholder="CUIT sin guiones">
                                <button class="btn btn-primary" type="button" id="btnSearchCuit" title="Buscar en AFIP">
                                    <span class="spinner-border spinner-border-sm d-none" id="cuitLoader"></span>
                                    <i class="bi bi-search" id="cuitIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tipo Comprobante</label>
                            <select name="tipo_comprobante" id="tipoComprobante" class="form-select">
                                <option value="A">Factura A</option>
                                <option value="B">Factura B</option>
                                <option value="C" selected>Factura C</option>
                                <option value="X">Presupuesto</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nro Operación</label>
                            <input type="text" name="numero_comprobante" class="form-control bg-light" placeholder="Automático" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA DE ARTÍCULOS -->
            <div class="card luxury-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-luxury mb-0" id="tablaVentaManual">
                        <thead>
                            <tr>
                                <th class="ps-4">Artículo / Producto</th>
                                <th width="100" class="text-center">Stock</th>
                                <th width="120" class="text-center">Cantidad</th>
                                <th width="180" class="text-end">Precio Unit. (IVA Inc.)</th>
                                <th width="180" class="text-end pe-4">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Se llena vía JS --}}
                        </tbody>
                    </table>
                </div>
                <div class="p-4 text-center bg-light border-top">
                    <button type="button" class="btn btn-outline-primary fw-bold px-4 rounded-pill" onclick="agregarFila()">
                        <i class="bi bi-plus-lg me-1"></i> AGREGAR POSICIÓN
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card luxury-card sticky-top" style="top: 90px;">
                <div class="card-body p-4">
                    <h6 class="text-uppercase fw-bold text-muted small mb-3 text-center">Resumen de Cargo</h6>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-bold" id="lblSubtotal">$ 0,00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span class="text-muted">IVA (21%):</span>
                        <span class="fw-bold" id="lblIva">$ 0,00</span>
                    </div>

                    <div class="text-center mb-4">
                        <h1 class="fw-extrabold text-primary mb-0" id="lblTotal">$ 0,00</h1>
                        <small class="text-muted">Total Final del Comprobante</small>
                    </div>

                    <label class="form-label fw-bold small text-muted">Condición de Pago</label>
                    <select name="metodo_pago" class="form-select mb-4">
                        <option value="efectivo">Efectivo 💵</option>
                        <option value="transferencia">Transferencia 🏦</option>
                        <option value="cuenta_corriente">Cuenta Corriente 👤</option>
                        <option value="tarjeta">Tarjeta (Débito/Crédito) 💳</option>
                    </select>

                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="hacer_remito" id="hacerRemito" checked>
                            <label class="form-check-label fw-bold small" for="hacerRemito">
                                📦 Generar Remito / Entrega
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Si se desmarca, la mercadería quedará "En Guarda" para entrega posterior.</small>
                    </div>

                    <button type="submit" class="btn btn-success btn-confirm w-100 py-3 fw-bold fs-5 shadow rounded-3">
                        TERMINAR VENTA 🚀
                    </button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const products = @json($products);
const prefill = @json($prefill);
let idx = 0;

$(document).ready(function() {
    $('#clientSelect').select2({ theme: 'bootstrap-5' });

    // Cargar pre-llenado si existe
    if (prefill && prefill.items && prefill.items.length > 0) {
        prefill.items.forEach(item => {
            agregarFila(item.product_id, item.qty, item.price);
        });
    } else {
        agregarFila();
    }

    // Búsqueda por CUIT logic
    $('#btnSearchCuit').on('click', function() {
        const cuitInput = $('#cuitSearch').val().replace(/-/g, '').replace(/ /g, '');
        if (cuitInput.length < 11) {
            alert("CUIT inválido. Debe tener 11 dígitos numéricos.");
            return;
        }

        const btn = $(this);
        const loader = $('#cuitLoader');
        const icon = $('#cuitIcon');

        btn.prop('disabled', true);
        loader.removeClass('d-none');
        icon.addClass('d-none');

        $.get("{{ route('empresa.tax.search_cuit') }}", { cuit: cuitInput }, function(res) {
            btn.prop('disabled', false);
            loader.addClass('d-none');
            icon.removeClass('d-none');

            if (res.success) {
                alert("Contribuyente: " + res.data.nombre + "\nDir: " + res.data.direccion + "\nCondición: " + res.data.condicion_iva);
            } else {
                alert("Error AFIP: " + res.error);
            }
        }).fail(function() {
            btn.prop('disabled', false);
            loader.addClass('d-none');
            icon.removeClass('d-none');
            alert("Error de conexión con el padrón AFIP.");
        });
    });
});

function agregarFila(prodId = null, qty = 1, price = null) {
    const tbody = document.querySelector("#tablaVentaManual tbody");
    const tr = document.createElement("tr");
    tr.id = `row_${idx}`;
    tr.innerHTML = `
        <td class="ps-4">
            <select name="items[${idx}][product_id]" class="form-select prod-sel" required>
                <option value="">Seleccione Producto...</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="text-center fw-bold stk-disp text-muted">-</td>
        <td><input type="number" name="items[${idx}][quantity]" class="form-control qty-in text-center" value="${qty}" step="0.01"></td>
        <td><input type="number" name="items[${idx}][price]" class="form-control prc-in text-end fw-bold text-primary" value="${price || ''}" step="0.01"></td>
        <td class="text-end pe-4 fw-bold sub-disp">$ 0,00</td>
        <td class="text-center"><button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('tr').remove(); recalcularTotales();"><i class="bi bi-trash3 fs-5"></i></button></td>
    `;
    tbody.appendChild(tr);
    
    const $sel = $(tr).find('.prod-sel');
    $sel.select2({ theme: 'bootstrap-5' });

    if (prodId) {
        $sel.val(prodId).trigger('change');
    }

    $sel.on('change', function() {
        const opt = $(this).find(':selected');
        const stock = parseFloat(opt.data('stock')) || 0;
        const priceFromDb = parseFloat(opt.data('price')) || 0;
        
        const stkBadge = stock <= 0 ? `<span class="badge bg-danger stk-badge">Sin Stock</span>` : `<span class="badge bg-success stk-badge">${stock}</span>`;
        $(tr).find('.stk-disp').html(stkBadge);
        
        if (!price) {
            $(tr).find('.prc-in').val(priceFromDb);
        }
        recalcularTotales();
    });

    $(tr).find('input').on('input', recalcularTotales);
    recalcularTotales();
    idx++;
}

function recalcularTotales() {
    let total = 0;
    document.querySelectorAll("#tablaVentaManual tbody tr").forEach(tr => {
        const q = parseFloat(tr.querySelector(".qty-in").value) || 0;
        const p = parseFloat(tr.querySelector(".prc-in").value) || 0;
        const sub = q * p;
        tr.querySelector(".sub-disp").innerText = "$ " + sub.toLocaleString('es-AR', {minimumFractionDigits:2});
        total += sub;
    });

    const subtotal = total / 1.21;
    const iva = total - subtotal;

    document.getElementById("lblSubtotal").innerText = "$ " + subtotal.toLocaleString('es-AR', {minimumFractionDigits:2});
    document.getElementById("lblIva").innerText = "$ " + iva.toLocaleString('es-AR', {minimumFractionDigits:2});
    document.getElementById("lblTotal").innerText = "$ " + total.toLocaleString('es-AR', {minimumFractionDigits:2});
}

document.getElementById('formVentaManual').onsubmit = async function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true; 
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>PROCESANDO...';

    let items = [];
    document.querySelectorAll("#tablaVentaManual tbody tr").forEach((tr) => {
        const prodId = tr.querySelector(".prod-sel").value;
        if(prodId) {
            items.push({
                product_id: prodId,
                quantity: tr.querySelector(".qty-in").value,
                price: tr.querySelector(".prc-in").value
            });
        }
    });

    if (items.length === 0) {
        alert("Agregue al menos un producto.");
        btn.disabled = false; btn.innerHTML = 'TERMINAR VENTA 🚀';
        return;
    }

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    data.items = items;
    data.hacer_remito = document.getElementById('hacerRemito').checked;

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json' 
            },
            body: JSON.stringify(data)
        });

        const res = await response.json();
        if(!response.ok) throw new Error(res.error || 'Error desconocido al registrar venta');

        window.location.href = "{{ route('empresa.ventas.index') }}?success=" + encodeURIComponent(res.message);
    } catch (err) {
        alert(err.message); 
        btn.disabled = false; 
        btn.innerHTML = 'TERMINAR VENTA 🚀';
    }
};
</script>
@endsection
