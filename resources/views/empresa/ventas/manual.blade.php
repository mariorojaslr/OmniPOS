@extends('layouts.empresa')

@section('content')

{{-- Estilos Luxury --}}
<style>
    .luxury-card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #ffffff; }
    .table-luxury thead th { background: #f8f9fa; border: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #6c757d; padding: 12px; }
    .table-luxury td { vertical-align: middle; border-bottom: 1px solid #f1f3f5; padding: 12px; }
    
    .search-highlight { background: #ffeb3b; color: #000; padding: 0 2px; border-radius: 2px; }
    
    .qty-input { width: 80px; text-align: center; font-weight: bold; }
    .price-input { width: 120px; text-align: end; font-weight: bold; }
    
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 8px !important;
    }

    .btn-confirm {
        background: linear-gradient(135deg, #198754, #146c43);
        border: none;
        transition: all 0.3s;
    }
    .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(25,135,84,0.3); }
</style>

<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">✍️ Venta Manual Profesional</h2>
            <p class="text-muted mb-0">Carga masiva de artículos para clientes mayoristas y oficina</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.ventas.index') }}" class="btn btn-outline-secondary btn-sm px-3">Historial</a>
        </div>
    </div>

    <form method="POST" action="{{ route('empresa.ventas.manual.store') }}" id="formVentaManual">
    @csrf

    <div class="row g-4">
        
        {{-- PANEL PRINCIPAL --}}
        <div class="col-md-9">
            
            {{-- SECCIÓN CLIENTE --}}
            <div class="card luxury-card mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Cliente / Razón Social</label>
                            <select name="client_id" id="clientSelect" class="form-select" required>
                                <option value="">Seleccionar Cliente del Catálogo...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->tipo_responsable ?? 'Consumidor Final' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Comprobante</label>
                            <select name="tipo_comprobante" class="form-select">
                                <option value="A">Factura A</option>
                                <option value="B">Factura B</option>
                                <option value="C">Factura C</option>
                                <option value="X" selected>Presupuesto / Interno</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Nro. Manual</label>
                            <input type="text" name="numero_comprobante" class="form-control" placeholder="Ej: 0001-00012345">
                        </div>
                    </div>
                </div>
            </div>

            {{-- DETALLE DE LA VENTA --}}
            <div class="card luxury-card overflow-hidden">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Listado de Artículos a Facturar</h6>
                    <span class="badge bg-light text-dark border fw-normal">Usa el lector de barras o busca por nombre</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-luxury mb-0" id="tablaVentaManual">
                        <thead>
                            <tr class="text-center">
                                <th class="text-start ps-4">Artículo / Variante</th>
                                <th width="120">Disponibilidad</th>
                                <th width="100">Cantidad</th>
                                <th width="150">Precio Unit.</th>
                                <th width="150">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Se genera dinámicamente --}}
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-light bg-opacity-25 border-top text-center">
                    <button type="button" class="btn btn-primary fw-bold px-4 shadow-sm" onclick="agregarFila()">
                        + AGREGAR ÍTEM MANUALMENTE
                    </button>
                </div>
            </div>
        </div>

        {{-- COLUMNA LATERAL: RESUMEN --}}
        <div class="col-md-3">
            <div class="card luxury-card sticky-top" style="top: 20px;">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-4">Total de Venta</h5>
                    
                    <div class="mb-4">
                        <h1 class="fw-extrabold text-primary mb-0" id="lblTotal">$ 0,00</h1>
                        <small class="text-muted">Total con IVA incluido</small>
                    </div>

                    <div class="text-start mb-4">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-muted">Neto Gravado</span>
                            <span id="lblNeto">$ 0,00</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">IVA (21%)</span>
                            <span id="lblIva">$ 0,00</span>
                        </div>
                    </div>

                    <input type="hidden" name="total_sin_iva" id="valNeto" value="0">
                    <input type="hidden" name="total_iva" id="valIva" value="0">
                    <input type="hidden" name="total_con_iva" id="valTotal" value="0">

                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold small text-uppercase">Método de Pago</label>
                        <select name="metodo_pago" class="form-select">
                            <option value="efectivo">Efectivo 💵</option>
                            <option value="transferencia">Transferencia 🏦</option>
                            <option value="tarjeta">Tarjeta 💳</option>
                            <option value="cuenta_corriente">Cuenta Corriente 👤</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success btn-confirm w-100 py-3 fw-bold fs-5 shadow">
                        REGISTRAR VENTA 🚀
                    </button>
                    
                    <p class="mt-3 small text-muted">Asegúrese de que el stock sea suficiente antes de confirmar.</p>
                </div>
            </div>
        </div>

    </div>
    </form>
</div>

@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
const products = {!! $products->toJson() !!};
let idx = 0;

$(document).ready(function() {
    // Inicializar Select2 para clientes
    $('#clientSelect').select2({
        theme: 'bootstrap-5'
    });

    // 📝 Lógica de Formateo de Número Manual (e.g. 1-2 -> 0001-00000002)
    const inputNumManual = document.querySelector('input[name="numero_comprobante"]');
    if (inputNumManual) {
        inputNumManual.addEventListener('blur', function() {
            let val = this.value.trim();
            if (val && val.includes('-')) {
                let parts = val.split('-');
                if (parts.length === 2) {
                    let suc = parts[0].padStart(4, '0');
                    let num = parts[1].padStart(8, '0');
                    this.value = `${suc}-${num}`;
                }
            }
        });
    }

    // Agregar primera fila por defecto
    agregarFila();

    // Lector de barras global
    let barcodeBuffer = "";
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Enter') {
            if(barcodeBuffer.length >= 3) {
                console.log("Barcode detectado:", barcodeBuffer);
                buscarYAgregarPorBarcode(barcodeBuffer);
            }
            barcodeBuffer = "";
        } else if(e.key.length === 1) {
            barcodeBuffer += e.key;
        }
        
        // Limpiamos buffer si no hay actividad
        setTimeout(() => { barcodeBuffer = ""; }, 200);
    });
});

function agregarFila() {
    const tbody = document.querySelector("#tablaVentaManual tbody");
    const tr = document.createElement("tr");
    tr.id = `row_${idx}`;
    
    tr.innerHTML = `
        <td class="ps-4">
            <select name="items[${idx}][product_id]" class="form-select prod-sel" required onchange="vincularArticulo(this, ${idx})">
                <option value="">Escribe nombre o escanea...</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" data-barcode="{{ $p->barcode }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock }}">
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
            <div id="v_wrap_${idx}" class="mt-2" style="display:none;">
                <select name="items[${idx}][variant_id]" class="form-select form-select-sm var-sel" onchange="vincularVariante(this, ${idx})">
                    <option value="">Seleccionar Talle/Color...</option>
                </select>
            </div>
        </td>
        <td class="text-center fw-bold text-muted" id="stk_disp_${idx}">-</td>
        <td>
            <input type="number" name="items[${idx}][quantity]" class="form-control qty-in" value="1" min="0.01" step="0.01" oninput="recalcularTotales()">
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="items[${idx}][price]" class="form-control prc-in" step="0.01" oninput="recalcularTotales()">
            </div>
        </td>
        <td class="text-end fw-bold text-dark pe-4 fs-5" id="sub_disp_${idx}">$ 0,00</td>
        <td class="text-center">
            <button type="button" class="btn btn-link text-danger p-0" onclick="quitarFila(${idx})">🗑️</button>
        </td>
    `;

    tbody.appendChild(tr);

    // Inicializar Select2 para este producto
    $(`#row_${idx} .prod-sel`).select2({
        theme: 'bootstrap-5',
        placeholder: 'Buscar por nombre...'
    });

    idx++;
}

function vincularArticulo(select, i) {
    const pId = select.value;
    const prod = products.find(p => p.id == pId);
    if(!prod) return;

    const vWrap = document.getElementById(`v_wrap_${i}`);
    const vSel = vWrap.querySelector("select");

    vWrap.style.display = 'none';
    vSel.innerHTML = '<option value="">Variante...</option>';

    if(prod.variants && prod.variants.length > 0) {
        vWrap.style.display = 'block';
        prod.variants.forEach(v => {
            const op = document.createElement("option");
            op.value = v.id;
            op.dataset.stock = v.stock;
            op.innerText = `${v.size} / ${v.color} (Stk: ${v.stock})`;
            vSel.appendChild(op);
        });
        document.getElementById(`stk_disp_${i}`).innerText = "-";
        document.querySelector(`#row_${i} .prc-in`).value = prod.price;
    } else {
        document.getElementById(`stk_disp_${i}`).innerText = prod.stock;
        document.querySelector(`#row_${i} .prc-in`).value = prod.price;
    }
    recalcularTotales();
}

function vincularVariante(select, i) {
    const opt = select.options[select.selectedIndex];
    if(opt.value) {
        document.getElementById(`stk_disp_${i}`).innerText = opt.dataset.stock;
    }
    recalcularTotales();
}

function quitarFila(i) {
    document.getElementById(`row_${i}`).remove();
    recalcularTotales();
}

function recalcularTotales() {
    let total = 0;
    document.querySelectorAll("#tablaVentaManual tbody tr").forEach(tr => {
        const q = parseFloat(tr.querySelector(".qty-in").value) || 0;
        const p = parseFloat(tr.querySelector(".prc-in").value) || 0;
        const sub = q * p;
        tr.querySelector("[id^='sub_disp_']").innerText = "$ " + sub.toLocaleString('es-AR', {minimumFractionDigits:2});
        total += sub;
    });

    const iva = total - (total / 1.21);
    const neto = total - iva;

    document.getElementById("lblTotal").innerText = "$ " + total.toLocaleString('es-AR', {minimumFractionDigits:2});
    document.getElementById("lblNeto").innerText = "$ " + neto.toLocaleString('es-AR', {minimumFractionDigits:2});
    document.getElementById("lblIva").innerText = "$ " + iva.toLocaleString('es-AR', {minimumFractionDigits:2});

    document.getElementById("valTotal").value = total.toFixed(2);
    document.getElementById("valNeto").value = neto.toFixed(2);
    document.getElementById("valIva").value = iva.toFixed(2);
}

function buscarYAgregarPorBarcode(code) {
    const prod = products.find(p => p.barcode == code);
    if(prod) {
        // Buscamos si hay una fila vacía para usar
        let filaLibre = null;
        document.querySelectorAll(".prod-sel").forEach(s => {
            if(!s.value) filaLibre = s.closest("tr");
        });

        if(!filaLibre) {
            agregarFila();
            filaLibre = document.querySelector("#tablaVentaManual tbody tr:last-child");
        }

        const i = filaLibre.id.split("_")[1];
        const $sel = $(`#row_${i} .prod-sel`);
        $sel.val(prod.id).trigger('change');
    }
}
</script>
@endsection
