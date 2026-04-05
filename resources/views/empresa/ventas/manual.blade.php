@extends('layouts.empresa')

@section('content')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5.min.css" />

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
@endsection

<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-primary">✍️ Venta Manual Profesional (Versión Hotfix)</h2>
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
            <div class="card luxury-card mb-4 border-start border-4 border-primary">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-bold small text-uppercase text-secondary">Cliente / Razón Social</label>
                                <button type="button" class="btn btn-link btn-sm p-0 mb-1 text-decoration-none" onclick="setConsumidorFinal()">👤 Consumidor Final</button>
                            </div>
                            <select name="client_id" id="clientSelect" class="form-select" required>
                                <option value="">Seleccionar Cliente del Catálogo...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->tipo_responsable ?? 'Consumidor Final' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-secondary">🔍 Buscar por CUIT/CUIL</label>
                            <div class="input-group">
                                <input type="text" id="cuitSearch" class="form-control" placeholder="CUIT Sin guiones">
                                <button class="btn btn-primary" type="button" id="btnSearchCuit" title="Buscar en AFIP">
                                    <span class="spinner-border spinner-border-sm d-none" id="searchSpinner"></span>
                                    🚀
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Comprobante</label>
                            <select name="tipo_comprobante" id="tipoComprobante" class="form-select">
                                <option value="A">Factura A</option>
                                <option value="B">Factura B</option>
                                <option value="C">Factura C</option>
                                <option value="F">Factura</option>
                                <option value="X" selected>Presupuesto</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Nro. Manual</label>
                            <input type="text" name="numero_comprobante" class="form-control" placeholder="Auto" title="Si usa AFIP, se genera solo">
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

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase">Método de Pago</label>
                        <select name="metodo_pago" class="form-select">
                            <option value="efectivo">Efectivo 💵</option>
                            <option value="transferencia">Transferencia 🏦</option>
                            <option value="tarjeta">Tarjeta 💳</option>
                            <option value="cuenta_corriente">Cuenta Corriente 👤</option>
                        </select>
                    </div>

                    <div class="mb-4 p-3 border rounded-3 bg-light border-success" style="border-style: dashed !important;">
                        <div class="form-check form-switch d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" name="hacer_remito" id="hacerRemito" style="cursor:pointer; transform: scale(1.2);">
                            <label class="form-check-label fw-bold text-dark mb-0" for="hacerRemito" style="cursor:pointer;">
                                📦 Hacer Remito (Entrega Parcial / En Guarda)
                            </label>
                        </div>
                    </div>

                    <div id="remitoDetails" style="display:none;" class="mb-4 p-3 bg-white border rounded shadow-sm text-start">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-bold small text-uppercase text-primary mb-0">
                                📦 Cantidades a Entregar
                            </label>
                            <button type="button" class="btn btn-xs btn-outline-primary fw-bold" style="font-size: 0.65rem;" onclick="setEntregaTotal()">
                                ENTREGA TOTAL (100%)
                            </button>
                        </div>
                        <div id="remitoItemsList" class="small overflow-auto" style="max-height: 250px;">
                            <!-- Dinámico -->
                        </div>
                        <div class="mt-2 text-muted" style="font-size: 0.75rem;">
                             Deje en 0 los productos que quedarán en guarda.
                        </div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
const products = {!! $products->toJson() !!};
let idx = 0;

$(document).ready(function() {
    // Inicializar Select2 para clientes
    $('#clientSelect').select2({
        theme: 'bootstrap-5'
    });

    console.log("MultiPOS JS Cargado ✅");

    // 🚀 BUSQUEDA POR CUIT (PADRON AFIP)
    $('#btnSearchCuit').on('click', function() {
        let cuit = $('#cuitSearch').val().trim();
        if(!cuit) return alert("Ingresa un CUIT primero");

        const btn = $(this);
        const spinner = $('#searchSpinner');
        
        btn.prop('disabled', true);
        spinner.removeClass('d-none');

        fetch(`{{ route('empresa.tax.search_cuit') }}?cuit=${cuit}`)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    // Si el cliente existe en el catálogo, lo seleccionamos
                    // (Esto requiere buscar por nombre o haberlo precargado)
                    let found = false;
                    $('#clientSelect option').each(function() {
                        if ($(this).text().includes(cuit)) {
                            $('#clientSelect').val($(this).val()).trigger('change');
                            found = true;
                        }
                    });

                    if(!found) {
                        if(confirm(`Encontrado: ${res.data.nombre}\n¿Deseas dar de ALTA este cliente ahora mismo?`)) {
                            quickCreateClient(res.data, cuit);
                        }
                    }
                } else {
                    alert("Error ARCA: " + res.error);
                }
            })
            .catch(err => alert("Error técnico: " + err))
            .finally(() => {
                btn.prop('disabled', false);
                spinner.addClass('d-none');
            });
    });

    // 📝 Lógica de Formateo de Número Manual (e.g. 1-35 -> 0001-00000035)
    $(document).on('change blur', 'input[name="numero_comprobante"]', function() {
        console.log("Formateando número...");
        let val = $(this).val().trim();
        if (val && val.includes('-')) {
            let parts = val.split('-');
            if (parts.length === 2) {
                let suc = parts[0].toString().padStart(4, '0');
                let num = parts[1].toString().padStart(8, '0');
                $(this).val(`${suc}-${num}`);
            }
        }
    });

    // Agregar primera fila por defecto
    agregarFila();

    // 🍎 Lector de barras global (Restaurado)
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

function setConsumidorFinal() {
    // Buscar opción que diga Consumidor Final
    $('#clientSelect option').each(function() {
        if ($(this).text().toUpperCase().includes('CONSUMIDOR FINAL')) {
            $('#clientSelect').val($(this).val()).trigger('change');
        }
    });
    $('#tipoComprobante').val('B').trigger('change');
}

function quickCreateClient(data, cuit) {
    // API Call para crear cliente rápido (Ruta corregida a 'clientes')
    fetch("{{ route('empresa.clientes.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            name: data.nombre,
            document: cuit,
            address: data.direccion + ' ' + data.localidad,
            condicion_iva: data.condicion_iva,
            email: 'automatico@multipos.system'
        })
    })
    .then(res => res.json())
    .then(res => {
        if(res.id) {
            // Agregar al select y seleccionar
            let newOption = new Option(res.name, res.id, true, true);
            $('#clientSelect').append(newOption).trigger('change');
            
            // Sugerir comprobante según IVA
            if(data.condicion_iva.includes('Inscripto')) {
                $('#tipoComprobante').val('A');
            } else {
                $('#tipoComprobante').val('B');
            }
        }
    });
}

function agregarFila() {
    const tbody = document.querySelector("#tablaVentaManual tbody");
    const tr = document.createElement("tr");
    const currentRowIdx = idx; // Capturamos el idx actual para esta fila
    tr.id = `row_${currentRowIdx}`;
    
    tr.innerHTML = `
        <td class="ps-4">
            <select name="items[${currentRowIdx}][product_id]" class="form-select prod-sel" required>
                <option value="">Escribe nombre o escanea...</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" 
                            data-barcode="{{ $p->barcode }}" 
                            data-price="{{ $p->price }}" 
                            data-stock="{{ $p->stock }}">
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
            <div class="v-wrap mt-2" style="display:none;">
                <select name="items[${currentRowIdx}][variant_id]" class="form-select form-select-sm var-sel">
                    <option value="">Seleccionar Talle/Color...</option>
                </select>
            </div>
        </td>
        <td class="text-center fw-bold text-muted stk-disp">-</td>
        <td>
            <input type="number" name="items[${currentRowIdx}][quantity]" class="form-control qty-in" value="1" min="0.01" step="0.01">
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="items[${currentRowIdx}][price]" class="form-control prc-in" step="0.01">
            </div>
        </td>
        <td class="text-end fw-bold text-dark pe-4 fs-5 sub-disp">$ 0,00</td>
        <td class="text-center">
            <button type="button" class="btn btn-link text-danger p-0 btn-quitar">🗑️</button>
        </td>
    `;

    tbody.appendChild(tr);

    const $row = $(tr);
    const $sel = $row.find('.prod-sel');

    // Inicializar Select2
    $sel.select2({
        theme: 'bootstrap-5',
        placeholder: 'Buscar por nombre...'
    });

    // Eventos de la Fila (Compatibilidad total con Select2)
    $sel.on('select2:select change', function() { 
        console.log("Producto seleccionado en fila " + currentRowIdx);
        vincularArticulo($(this)); 
    });
    
    $row.find('.var-sel').on('change', function() { vincularVariante($(this)); });
    $row.find('.qty-in, .prc-in').on('input', recalcularTotales);
    $row.find('.btn-quitar').on('click', function() { 
        $row.remove(); 
        recalcularTotales(); 
    });

    idx++;
}

function vincularArticulo($select) {
    const $opt = $select.find(':selected');
    const pId = $select.val();
    const $row = $select.closest('tr');
    
    console.log("Vinculando artículo ID:", pId);

    // Prioridad 1: Sacar de los data-attributes (más rápido y seguro)
    const priceFromAttr = $opt.data('price');
    const stockFromAttr = $opt.data('stock');
    
    // Prioridad 2: Buscar en el JSON global como respaldo
    const prod = products.find(p => String(p.id) === String(pId));
    
    const $vWrap = $row.find('.v-wrap');
    const $vSel = $row.find('.var-sel');
    const $stkDisp = $row.find('.stk-disp');
    const $prcIn = $row.find('.prc-in');

    $vWrap.hide();
    $vSel.html('<option value="">Variante...</option>');

    // Lógica de Variantes
    if(prod && prod.variants && prod.variants.length > 0) {
        $vWrap.show();
        prod.variants.forEach(v => {
            $vSel.append(`<option value="${v.id}" data-stock="${v.stock}">${v.size} / ${v.color} (Stk: ${v.stock})</option>`);
        });
        $stkDisp.text("-");
    } else {
        // Usamos el fallback si prod es null
        const finalStock = prod ? (prod.stock || 0) : (stockFromAttr || 0);
        $stkDisp.text(finalStock);
    }

    // Precio: Prioridad Attr > JSON
    const finalPrice = priceFromAttr !== undefined ? priceFromAttr : (prod ? prod.price : 0);
    $prcIn.val(finalPrice);
    
    console.log("Datos cargados -> Precio:", finalPrice, "Stock:", $stkDisp.text());
    recalcularTotales();
}

function vincularVariante($vSel) {
    const $row = $vSel.closest('tr');
    const $opt = $vSel.find(':selected');
    const $stkDisp = $row.find('.stk-disp');

    if($vSel.val()) {
        $stkDisp.text($opt.data('stock'));
    }
    recalcularTotales();
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

// LOGICA REMITO PARCIAL
document.getElementById('hacerRemito').onchange = (e) => {
    document.getElementById('remitoDetails').style.display = e.target.checked ? 'block' : 'none';
    if(e.target.checked) renderRemitoItems();
};

function renderRemitoItems() {
    const list = document.getElementById('remitoItemsList');
    list.innerHTML = '';
    
    document.querySelectorAll("#tablaVentaManual tbody tr").forEach(tr => {
        const sel = tr.querySelector(".prod-sel");
        if(!sel.value) return;
        
        const name = sel.options[sel.selectedIndex].text;
        const q = tr.querySelector(".qty-in").value;
        const pId = sel.value;
        const vId = tr.querySelector(".var-sel")?.value || null;

        const div = document.createElement('div');
        div.className = 'd-flex justify-content-between align-items-center mb-2 pb-2 border-bottom';
        div.innerHTML = `
            <div style="flex:1;">
                <div class="fw-bold text-truncate" style="max-width: 150px;">${name}</div>
                <small class="text-muted">Total: ${q}</small>
            </div>
            <div style="width: 70px;">
                <input type="number" 
                       class="form-control form-control-sm text-center fw-bold item-entrega" 
                       data-id="${pId}" 
                       data-variant="${vId || ''}"
                       value="${q}" 
                       min="0" 
                       max="${q}" 
                       step="0.01">
            </div>
        `;
        list.appendChild(div);
    });
}

function getItemsEntregar() {
    if(!document.getElementById('hacerRemito').checked) return null;
    let items = [];
    document.querySelectorAll('.item-entrega').forEach(input => {
        items.push({
            id: input.dataset.id,
            variant_id: input.dataset.variant || null,
            quantity_delivery: parseFloat(input.value) || 0
        });
    });
    return items;
}

function setEntregaTotal() {
    document.querySelectorAll('.item-entrega').forEach(input => {
        input.value = input.max;
    });
}

// SUBMIT VIA AJAX PARA AUTO-OPEN REMITO
document.getElementById('formVentaManual').onsubmit = async function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = 'PROCESANDO... ⏳';

    const formData = new FormData(this);
    const dataObj = Object.fromEntries(formData.entries());
    
    // Obtener items del listado
    let items = [];
    document.querySelectorAll("#tablaVentaManual tbody tr").forEach(tr => {
        const pId = tr.querySelector(".prod-sel").value;
        if(pId) {
            items.push({
                product_id: pId,
                variant_id: tr.querySelector(".var-sel")?.value || null,
                quantity: tr.querySelector(".qty-in").value,
                price: tr.querySelector(".prc-in").value
            });
        }
    });

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ...dataObj,
                items: items,
                hacer_remito: document.getElementById('hacerRemito').checked,
                items_entregar: getItemsEntregar()
            })
        });

        const res = await response.json();

        if(!response.ok) throw new Error(res.error || 'Error al procesar venta');

        // ABRIR REMITO SI EXISTE
        if(res.remito_id) {
            window.open("{{ url('empresa/remitos') }}/" + res.remito_id + "/pdf", '_blank');
        }

        // REDIRIGIR AL INDEX CON EXITO
        window.location.href = "{{ route('empresa.ventas.index') }}?success=" + encodeURIComponent(res.message);

    } catch (err) {
        alert(err.message);
        btn.disabled = false;
        btn.innerHTML = 'REGISTRAR VENTA 🚀';
    }
};
</script>
@endsection
