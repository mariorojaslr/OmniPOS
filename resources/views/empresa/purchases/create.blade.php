@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   CABECERA
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Nueva Compra</h2>
        <small class="text-muted">Factura de proveedor</small>
    </div>

    <a href="{{ route('empresa.compras.index') }}" class="btn btn-secondary">
        Volver
    </a>
</div>

<form method="POST" action="{{ route('empresa.compras.store') }}" id="formCompra">
@csrf

{{-- =========================================================
   DATOS CABECERA FACTURA
========================================================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Proveedor</label>
                <select name="supplier_id" class="form-select" required>
                    <option value="">Seleccionar proveedor</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Fecha</label>
                <input type="date" name="purchase_date" class="form-control"
                       value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label fw-semibold">Comprobante</label>
                <select name="invoice_type" class="form-select">
                    <option value="A">Factura A</option>
                    <option value="B">Factura B</option>
                    <option value="C">Factura C</option>
                    <option value="NC">Nota de Crédito</option>
                    <option value="T">Ticket</option>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Sucursal / Número</label>
                <input type="text"
                       id="invoice_number"
                       name="invoice_number"
                       class="form-control"
                       placeholder="0000-00000000"
                       maxlength="13">
            </div>

        </div>
    </div>
</div>

{{-- =========================================================
   DETALLE FACTURA
========================================================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header fw-bold">Detalle de Productos</div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0" id="tablaItems">
            <thead class="table-light text-center">
                <tr>
                    <th>Producto</th>
                    <th width="150">Código</th>
                    <th width="120">Últ. Compra</th>
                    <th width="80">Cantidad</th>
                    <th width="110">Precio s/IVA</th>
                    <th width="80">IVA</th>
                    <th width="110">Precio c/IVA</th>
                    <th width="100">Total</th>
                    <th width="50"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="p-3">
            <button type="button" class="btn btn-outline-primary btn-sm"
                    onclick="agregarFila()">+ Agregar producto</button>
        </div>
    </div>
</div>

{{-- =========================================================
   TOTALES
========================================================= --}}
<div class="row justify-content-end">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <strong id="subtotal">0.00</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>IVA</span>
                    <strong id="ivaTotal">0.00</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5">
                    <span>Total</span>
                    <strong id="totalGeneral">0.00</strong>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- =========================================================
   BOTONES
========================================================= --}}
<div class="text-end mt-4 d-flex justify-content-end gap-2">

    <button type="submit" name="accion" value="guardar"
            class="btn btn-primary btn-lg">
        💾 Guardar
    </button>

    <button type="submit" name="accion" value="guardar_imprimir"
            class="btn btn-warning btn-lg fw-bold text-dark">
        🏷️ Guardar e Imprimir Etiquetas
    </button>

    <button type="submit" name="accion" value="guardar_pagar"
            class="btn btn-success btn-lg">
        💰 Guardar y Pagar ahora
    </button>
</div>

</form>

@endsection


@section('scripts')
<script>

const products = {!! $products->toJson() !!};
let index = 0;
const ivaRate = 0.21;

/* =========================================================
   FORMATEO COMPROBANTE (SE MANTIENE)
========================================================= */
document.addEventListener("DOMContentLoaded", function(){

    const input = document.getElementById("invoice_number");
    if(!input) return;

    input.addEventListener("blur", function(){

        let value = input.value.trim();
        if(value === ""){
            input.value = "0000-00000000";
            return;
        }

        let parts = value.split("-");
        let suc = (parts[0] ?? "").replace(/\D/g,"");
        let num = (parts[1] ?? "").replace(/\D/g,"");

        suc = suc.padStart(4,"0").substring(0,4);
        num = num.padStart(8,"0").substring(0,8);

        input.value = suc + "-" + num;
    });

});


/* =========================================================
   NUMÉRICOS — FORMATO B (12,500.55)
========================================================= */

function limpiarNumero(valor){
    if(!valor) return "0";
    return valor.toString().replace(/,/g,''); // quita miles, NO toca el punto
}

function parseNumero(valor){
    return parseFloat(limpiarNumero(valor)) || 0;
}

function formatNumero(valor){
    return new Intl.NumberFormat('en-US',{
        minimumFractionDigits:2,
        maximumFractionDigits:2
    }).format(valor);
}

function agregarFila(){

    const tbody = document.querySelector("#tablaItems tbody");
    const row = document.createElement("tr");

    row.innerHTML = `
        <td>
            <select name="items[${index}][product_id]" class="form-select product-select" required onchange="handleProductChange(this, ${index})">
                <option value="">Producto</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-barcode="{{ $product->barcode }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <div class="variant-wrapper mt-2" style="display:none;" id="variant_wrapper_${index}">
                <label class="small fw-bold">Variante (Talle/Color):</label>
                <select name="items[${index}][variant_id]" class="form-select form-select-sm variant-select" id="variant_select_${index}" onchange="updateLastPrice(${index})">
                    <option value="">Seleccionar variante</option>
                </select>
            </div>
        </td>

        <td>
            <input type="text" 
                   name="items[${index}][barcode]" 
                   class="form-control text-center barcode-input" 
                   placeholder="Escanear...">
        </td>

        <td class="text-center align-middle">
            <span id="last_price_disp_${index}" class="fw-bold text-muted">—</span>
            <input type="hidden" id="last_price_val_${index}" value="0">
        </td>

        <td>
            <input type="number" min="1" step="0.01"
                   name="items[${index}][quantity]"
                   class="form-control text-end cantidad"
                   value="1">
        </td>

        <td>
            <input type="text"
                   name="items[${index}][price_sin_iva]"
                   class="form-control precioSinIva text-end">
            <div id="diff_pct_${index}" class="small text-center mt-1 fw-bold" style="display:none;"></div>
        </td>

        <td class="text-end align-middle">
            <span class="ivaCol">0.00</span>
            <input type="hidden"
                   name="items[${index}][iva]"
                   class="ivaInput">
        </td>

        <td>
            <input type="text"
                   name="items[${index}][price_con_iva]"
                   class="form-control precioConIva text-end">
        </td>

        <td class="text-end align-middle totalLinea">0.00</td>

        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger"
                onclick="this.closest('tr').remove(); calcularTotales();">X</button>
        </td>
    `;

    tbody.appendChild(row);

    const currentIndex = index; // Para closures
    row.querySelector(".precioSinIva").addEventListener("blur",()=> {
        calcularDesdeBase(row, currentIndex);
    });
    row.querySelector(".precioConIva").addEventListener("blur",()=> {
        calcularDesdeFinal(row, currentIndex);
    });
    row.querySelector(".cantidad").addEventListener("input",()=>recalcularFila(row));

    index++;
}

async function updateLastPrice(idx){
    const row = document.querySelector(`#tablaItems tbody tr:nth-child(${(idx+1)})`); // Cuidado si borran filas, esto es frágil.
    // Buscamos mejor por los selectores internos si el IDX cambia, pero index siempre crece.

    const pSelect = document.querySelectorAll('.product-select')[idx];
    const vSelect = document.querySelectorAll('.variant-select')[idx];

    const pId = pSelect.value;
    const vId = vSelect ? vSelect.value : null;

    if(!pId) return;

    try {
        const url = `/empresa/compras/ultimo-precio/${pId}${vId && vId != "" ? "/"+vId : ""}`;
        const response = await fetch(url);
        const data = await response.json();

        const cost = data.cost || 0;
        document.getElementById(`last_price_disp_${idx}`).innerText = cost > 0 ? '$ ' + formatNumero(cost) : '—';
        document.getElementById(`last_price_val_${idx}`).value = cost;

        // Si ya hay un precio puesto, recalcular diferencia
        const inputBase = document.querySelectorAll('.precioSinIva')[idx];
        if(inputBase.value){
             calcularDesdeBase(inputBase.closest('tr'), idx);
        }

    } catch(e) {
        console.error("Error fetching last price", e);
    }
}

function handleProductChange(select, idx){
    const productId = select.value;
    const wrapper = document.getElementById(`variant_wrapper_${idx}`);
    const vSelect = document.getElementById(`variant_select_${idx}`);

    vSelect.innerHTML = '<option value="">Seleccionar variante</option>';
    wrapper.style.display = 'none';
    vSelect.required = false;

    if(!productId) {
        document.getElementById(`last_price_disp_${idx}`).innerText = '—';
        document.getElementById(`last_price_val_${idx}`).value = 0;
        return;
    }

    const product = products.find(p => p.id == productId);
    if(product && product.has_variants && product.variants.length > 0){
        wrapper.style.display = 'block';
        vSelect.required = true;
        product.variants.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.innerText = `${v.size} / ${v.color} (Stock: ${v.stock})`;
            vSelect.appendChild(opt);
        });
    } else {
        updateLastPrice(idx);
    }
}

function calcularDesdeBase(row, idx){
    const base = parseNumero(row.querySelector(".precioSinIva").value);
    if(base <= 0) return;

    const iva = base * ivaRate;
    const final = base + iva;

    row.querySelector(".precioSinIva").value = formatNumero(base);
    row.querySelector(".precioConIva").value = formatNumero(final);
    row.querySelector(".ivaCol").innerText = formatNumero(iva);
    row.querySelector(".ivaInput").value = iva.toFixed(6);

    // Comparativa con último precio
    const lastPrice = parseFloat(document.getElementById(`last_price_val_${idx}`).value);
    const diffContainer = document.getElementById(`diff_pct_${idx}`);

    if(lastPrice > 0){
        const diff = ((base - lastPrice) / lastPrice) * 100;
        diffContainer.style.display = 'block';

        if(diff > 0){
             diffContainer.innerHTML = `<span class="text-danger">🔼 ${diff.toFixed(1)}%</span>`;
        } else if(diff < 0){
             diffContainer.innerHTML = `<span class="text-success">🔽 ${Math.abs(diff).toFixed(1)}%</span>`;
        } else {
             diffContainer.innerHTML = `<span class="text-muted">No varió</span>`;
        }
    } else {
        diffContainer.style.display = 'none';
    }

    recalcularFila(row);
}

function calcularDesdeFinal(row, idx){
    const final = parseNumero(row.querySelector(".precioConIva").value);
    if(final <= 0) return;

    const base = final / (1 + ivaRate);
    const iva = final - base;

    row.querySelector(".precioSinIva").value = formatNumero(base);
    row.querySelector(".precioConIva").value = formatNumero(final);
    row.querySelector(".ivaCol").innerText = formatNumero(iva);
    row.querySelector(".ivaInput").value = iva.toFixed(6);

    // Comparativa con último precio
    const lastPrice = parseFloat(document.getElementById(`last_price_val_${idx}`).value);
    const diffContainer = document.getElementById(`diff_pct_${idx}`);

    if(lastPrice > 0){
        const diff = ((base - lastPrice) / lastPrice) * 100;
        diffContainer.style.display = 'block';

        if(diff > 0){
             diffContainer.innerHTML = `<span class="text-danger">🔼 ${diff.toFixed(1)}%</span>`;
        } else if(diff < 0){
             diffContainer.innerHTML = `<span class="text-success">🔽 ${Math.abs(diff).toFixed(1)}%</span>`;
        } else {
             diffContainer.innerHTML = `<span class="text-muted">No varió</span>`;
        }
    } else {
        diffContainer.style.display = 'none';
    }

    recalcularFila(row);
}

function recalcularFila(row){
    const qty = parseNumero(row.querySelector(".cantidad").value);
    const final = parseNumero(row.querySelector(".precioConIva").value);
    const total = qty * final;
    row.querySelector(".totalLinea").innerText = formatNumero(total);
    calcularTotales();
}

function calcularTotales(){
    let subtotal=0, ivaTotal=0, total=0;

    document.querySelectorAll("#tablaItems tbody tr").forEach(row=>{
        const qty  = parseNumero(row.querySelector(".cantidad").value);
        const base = parseNumero(row.querySelector(".precioSinIva").value);
        const fin  = parseNumero(row.querySelector(".precioConIva").value);

        subtotal += base * qty;
        ivaTotal += (base * ivaRate) * qty;
        total    += fin * qty;
    });

    document.getElementById("subtotal").innerText     = formatNumero(subtotal);
    document.getElementById("ivaTotal").innerText     = formatNumero(ivaTotal);
    document.getElementById("totalGeneral").innerText = formatNumero(total);
}

// BÚSQUEDA POR BARCODE EN LA FILA
document.querySelector("#tablaItems").addEventListener("input", function(e) {
    if (e.target.classList.contains("barcode-input")) {
        const input = e.target;
        const row = input.closest("tr");
        const index = Array.from(row.parentNode.children).indexOf(row);
        const barcode = input.value.trim();

        if (barcode.length >= 3) { // Mínimo 3 caracteres para buscar
            const product = products.find(p => p.barcode == barcode);
            if (product) {
                const select = row.querySelector(".product-select");
                select.value = product.id;
                handleProductChange(select, index);
            }
        }
    }
});

// MODAL PARA NUEVO PRODUCTO RÁPIDO
function abrirModalNuevoProducto() {
    new bootstrap.Modal(document.getElementById('modalNuevoProducto')).show();
}

async function guardarNuevoProducto() {
    const btn = document.getElementById('btnGuardarNuevoProd');
    const form = document.getElementById('formNuevoProd');
    const formData = new FormData(form);

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    try {
        const response = await fetch("{{ route('empresa.products.store') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();

        if (data.id) {
            // Añadir al listado local de productos
            products.push(data);
            
            // Actualizar todos los selects de productos en la tabla
            document.querySelectorAll(".product-select").forEach(select => {
                const opt = document.createElement("option");
                opt.value = data.id;
                opt.text = data.name;
                opt.dataset.barcode = data.barcode;
                select.appendChild(opt);
            });

            alert("Producto creado y añadido al catálogo.");
            bootstrap.Modal.getInstance(document.getElementById('modalNuevoProducto')).hide();
            form.reset();
        }
    } catch (e) {
        console.error(e);
        alert("Error al guardar el producto. Revisa los datos.");
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Guardar Producto';
    }
}
</script>

{{-- MODAL NUEVO PRODUCTO --}}
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title">Crear Producto Nuevo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoProd">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre del Artículo</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ej: Camisa Slim Fit">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Código de Barras</label>
                        <input type="text" name="barcode" id="new_prod_barcode" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio Venta (Est.)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rubro</label>
                            <select name="rubro_id" class="form-select">
                                <option value="">Sin rubro</option>
                                {{-- Se podrían cargar rubros aquí si fuera necesario --}}
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="active" value="1">
                    <input type="hidden" name="ajax" value="1">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarNuevoProd" onclick="guardarNuevoProducto()">Guardar Producto</button>
            </div>
        </div>
    </div>
</div>

<div class="px-3 pb-3">
    <button type="button" class="btn btn-outline-dark btn-sm" onclick="abrirModalNuevoProducto()">
        ➕ Si el producto no existe en la lista, crealo rápido aquí
    </button>
</div>


</script>
@endsection
