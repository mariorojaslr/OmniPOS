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
                    <th width="90">Cantidad</th>
                    <th width="140">Precio s/IVA</th>
                    <th width="110">IVA</th>
                    <th width="140">Precio c/IVA</th>
                    <th width="140">Total</th>
                    <th width="60"></th>
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

    <button type="submit" name="accion" value="guardar_pagar"
            class="btn btn-success btn-lg">
        💰 Guardar y Pagar ahora
    </button>

</div>

</form>

@endsection


@section('scripts')
<script>

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
            <select name="items[${index}][product_id]" class="form-select" required>
                <option value="">Producto</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="number" min="1"
                   name="items[${index}][quantity]"
                   class="form-control text-end cantidad"
                   value="1">
        </td>

        <td>
            <input type="text"
                   name="items[${index}][price_sin_iva]"
                   class="form-control precioSinIva text-end">
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
    index++;

    row.querySelector(".precioSinIva").addEventListener("blur",()=>calcularDesdeBase(row));
    row.querySelector(".precioConIva").addEventListener("blur",()=>calcularDesdeFinal(row));
    row.querySelector(".cantidad").addEventListener("input",()=>recalcularFila(row));
}

function calcularDesdeBase(row){
    const base = parseNumero(row.querySelector(".precioSinIva").value);
    if(base <= 0) return;

    const iva = base * ivaRate;
    const final = base + iva;

    row.querySelector(".precioSinIva").value = formatNumero(base);
    row.querySelector(".precioConIva").value = formatNumero(final);
    row.querySelector(".ivaCol").innerText = formatNumero(iva);
    row.querySelector(".ivaInput").value = iva.toFixed(6);

    recalcularFila(row);
}

function calcularDesdeFinal(row){
    const final = parseNumero(row.querySelector(".precioConIva").value);
    if(final <= 0) return;

    const base = final / (1 + ivaRate);
    const iva = final - base;

    row.querySelector(".precioSinIva").value = formatNumero(base);
    row.querySelector(".precioConIva").value = formatNumero(final);
    row.querySelector(".ivaCol").innerText = formatNumero(iva);
    row.querySelector(".ivaInput").value = iva.toFixed(6);

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

</script>
@endsection
