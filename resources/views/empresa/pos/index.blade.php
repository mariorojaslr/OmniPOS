@extends('layouts.empresa')

@section('content')

<style>
.product-grid{display:grid;gap:12px}
.product-card{cursor:pointer;transition:.15s}
.product-card:hover{transform:scale(1.03);box-shadow:0 4px 14px rgba(0,0,0,.15)}
.product-card img{width:100%;height:140px;object-fit:contain}

.venta-wrapper{border:1px solid #198754;border-radius:8px;display:flex;flex-direction:column;height:100%}
.venta-header{background:#198754;color:#fff;text-align:center;font-weight:bold;padding:8px}
.venta-body{flex:1;overflow-y:auto}

.venta-row{display:grid;grid-template-columns:30px 1fr 130px 40px 110px;align-items:center;padding:6px 8px;font-size:14px}
.venta-row:nth-child(odd){background:#f0f7ff}

.qty-box{display:flex;gap:4px;justify-content:center}
.qty-box input{width:38px;text-align:center;font-size:12px;padding:2px;border:1px solid #ced4da;border-radius:4px}

.trash-btn{border:1px solid #dc3545;color:#dc3545;background:none;font-size:12px;padding:2px 6px;border-radius:4px;transition:.2s}
.trash-btn:hover{background:#dc3545;color:#fff}

.falta{color:#dc3545;font-weight:bold}
.vuelto{color:#198754;font-weight:bold}
.saldo{color:#333;font-weight:bold}
.highlight{background:yellow;font-weight:bold}

/* Scanner Effects */
.barcode-status{transition:.3s;font-size:12px;font-weight:600;display:flex;align-items:center;gap:6px}
.status-ready{color:#198754}
.status-busy{color:#ffc107}

.scanner-overlay{position:fixed;top:0;left:0;width:100%;height:4px;background:#ff0000;box-shadow:0 0 10px #ff0000;z-index:9999;display:none;animation:laser-scan 2s infinite ease-in-out}
@keyframes laser-scan{0%{top:0}50%{top:100%}100%{top:0}}

.scan-pulse{animation:pulse-green 2s infinite}
@keyframes pulse-green{
    0%{box-shadow:0 0 0 0 rgba(25,135,84,0.4)}
    70%{box-shadow:0 0 0 10px rgba(25,135,84,0)}
    100%{box-shadow:0 0 0 0 rgba(25,135,84,0)}
}
</style>
<div class="scanner-overlay" id="laserLine"></div>

<div class="row">

<div class="col-lg-8">

<div class="d-flex justify-content-between mb-2">

<div class="d-flex align-items-center gap-3">
<h3 class="mb-0">Punto de Venta</h3>

<div id="ventaFlash" style="display:none;background:#c8f7c5;color:#1b5e20;padding:6px 12px;border-radius:6px;font-size:14px">
✔ Venta registrada
</div>
</div>

<div class="d-flex gap-2">
<div><small>Cols</small><input type="number" id="cols" value="4" min="2" max="8" style="width:60px"></div>
<div><small>Filas</small><input type="number" id="rows" value="3" min="1" max="6" style="width:60px"></div>
</div>

</div>

<div class="d-flex gap-3 mb-3">
    <div class="flex-grow-1 position-relative">
        <input type="text" id="search" class="form-control" placeholder="🔍 Buscar producto por nombre...">
    </div>
    <div class="position-relative">
        <input type="text" id="barcodeInput" class="form-control bg-dark text-white border-success" 
               placeholder="⌨️ Escanear código..." style="width:200px">
        <div id="barcodeStatus" class="barcode-status position-absolute mt-1" style="right:0">
            <span class="status-ready">● Escáner Listo</span>
        </div>
    </div>
    <button class="btn btn-outline-dark" id="btnCameraScan" title="Escanear con cámara">
        📷
    </button>
</div>

<div id="productGrid" class="product-grid"></div>

</div>

<div class="col-lg-4">
<div class="venta-wrapper">
<div class="venta-header">Venta actual</div>
<div class="venta-body" id="cart"></div>

<div class="p-3 border-top">
<h4>Total: $ <span id="total">0</span></h4>
<button class="btn btn-success w-100" id="checkout">Cobrar</button>
</div>
</div>
</div>

</div>

{{-- ================= MODAL COBRAR ================= --}}
<div class="modal fade" id="modalCobrar">
<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="modal-content">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">Cobrar venta</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<strong>Total a cobrar</strong>
<h2 class="text-success">$ <span id="modalTotal">0.00</span></h2>

<hr>

<div class="mb-3">
<label class="form-label"><strong>Cliente</strong></label>

<div class="d-flex justify-content-between align-items-center border rounded p-2" style="background:#f8f9fa">
<span id="clienteNombre">CONSUMIDOR FINAL</span>
<button type="button" class="btn btn-sm btn-outline-primary" id="btnBuscarCliente">
Clientes
</button>
</div>

<input type="hidden" id="cliente_id" value="">
</div>

<div class="mb-3" id="tipoVentaClienteBox" style="display:none">
<label class="form-label"><strong>Tipo de venta</strong></label>
<select id="tipoVentaCliente" class="form-select">
<option value="contado">Contado</option>
<option value="cuenta_corriente">Cuenta corriente</option>
</select>
</div>

<div class="mb-3">
<label>Método de pago</label>
<select id="metodoPago" class="form-select">
<option value="efectivo">Efectivo</option>
<option value="tarjeta">Tarjeta</option>
<option value="transferencia">Transferencia</option>
<option value="qr">QR</option>
</select>
</div>

<div class="mb-3">
<label class="form-label"><strong>Tipo de Comprobante</strong></label>
<div class="d-flex gap-3">
    <div class="form-check">
        <input class="form-check-input" type="radio" name="tipoComprobante" id="tipoTicket" value="ticket" checked>
        <label class="form-check-label" for="tipoTicket">Ticket</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="tipoComprobante" id="tipoFactura" value="factura">
        <label class="form-check-label" for="tipoFactura">Factura</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="tipoComprobante" id="tipoNC" value="NC">
        <label class="form-check-label" for="tipoNC" class="text-danger fw-bold">Nota de Crédito (Devolución)</label>
    </div>
</div>

<div class="mb-3 p-3 border rounded bg-light border-success" style="border-style: dashed !important;">
    <div class="form-check form-switch d-flex align-items-center gap-2">
        <input class="form-check-input" type="checkbox" id="hacerRemito" style="cursor:pointer; transform: scale(1.3);">
        <label class="form-check-label fw-bold text-dark mb-0" for="hacerRemito" style="cursor:pointer;">
            📦 Hacer Remito (Entrega Parcial / En Guarda)
        </label>
    </div>
    <small class="text-muted d-block mt-1">MultiPOS genera entrega 100% por defecto si no lo marcas.</small>
</div>

<div id="remitoDetails" style="display:none;" class="mt-3 p-3 bg-white border rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="fw-bold small text-uppercase text-primary mb-0">
            📦 Cantidades a Entregar
        </label>
        <button type="button" class="btn btn-xs btn-outline-primary fw-bold" style="font-size: 0.65rem;" onclick="setEntregaTotal()">
            ENTREGA TOTAL (100%)
        </button>
    </div>
    <div id="remitoItemsList" class="small overflow-auto" style="max-height: 200px;">
        <!-- Se genera vía JS -->
    </div>
    <div class="mt-2 text-muted" style="font-size: 0.75rem;">
        <i class="bi bi-info-circle me-1"></i> Deje en 0 los productos que quedarán en guarda.
    </div>
</div>

</div>

<div class="mb-3">
<label>Monto recibido</label>

<input type="text" id="montoPagado" class="form-control">

<small class="text-muted">
Podés usar: 3x1000 + 2x500
</small>

<div class="mt-2 text-primary fw-bold">
Resultado parcial: $ <span id="resultadoParcial">0</span>
</div>

</div>

<h4 id="resultadoPago" class="saldo">SALDO 0</h4>

<h4 id="vueltoBox" class="vuelto" style="display:none">
VUELTO: $ <span id="vueltoValor">0</span>
</h4>

</div>

<div class="modal-footer">

    <button class="btn btn-secondary" data-bs-dismiss="modal">
        Cancelar
    </button>

    <button class="btn btn-success" id="confirmarVenta">
        Confirmar venta
    </button>

    <button class="btn btn-primary" id="imprimirVenta">
        Confirmar e imprimir
    </button>

</div>

</div>
</div>
</div>

{{-- ================= MODAL CLIENTES ================= --}}
<div class="modal fade" id="modalBuscarCliente">
<div class="modal-dialog modal-xl modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-primary text-white">
<h5 class="modal-title">Seleccionar cliente</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="text" id="buscarClienteInput" class="form-control mb-3"
placeholder="Buscar por nombre, documento, CUIT o teléfono...">

<div style="max-height:400px;overflow:auto">

<table class="table table-hover table-bordered">

<thead class="table-light">
<tr>
<th>Nombre</th>
<th>Documento</th>
<th>Teléfono</th>
</tr>
</thead>

<tbody id="tablaClientes"></tbody>

</table>

</div>

</div>
</div>
</div>
</div>

{{-- ================= MODAL CÁMARA SCANNER ================= --}}
<div class="modal fade" id="modalCamera" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:15px; overflow:hidden">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title font-monospace"><span class="text-success">●</span> Escáner Móvil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div id="reader" style="width: 100%; border:0"></div>
                <div class="position-absolute top-50 start-50 translate-middle w-75 h-50 border border-success border-2 rounded-3" 
                     style="pointer-events:none; box-shadow: 0 0 0 1000px rgba(0,0,0,0.5)">
                </div>
            </div>
            <div class="modal-footer bg-dark border-0 justify-content-center">
                <small class="text-secondary font-monospace">Alineá el código dentro del recuadro</small>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL VARIACIONES ================= --}}
<div class="modal fade" id="modalVariante" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Seleccionar Variante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="variantList">
                {{-- Se llena con JS --}}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

const products = {!! json_encode($productsData) !!};
const clientes = {!! json_encode($clientesData ?? []) !!};

let cart={},cols=4,rows=3;

const grid=document.getElementById('productGrid');
const modalCobrar=new bootstrap.Modal(document.getElementById('modalCobrar'));
const modalBuscarCliente=new bootstrap.Modal(document.getElementById('modalBuscarCliente'));
const modalVariante=new bootstrap.Modal(document.getElementById('modalVariante'));

let consumidorFinal = clientes.find(c =>
c.name?.toUpperCase() === 'CONSUMIDOR FINAL'
);

/* ================= PRODUCTOS ================= */

function renderProducts(){

grid.style.gridTemplateColumns=`repeat(${cols},1fr)`;

grid.innerHTML='';

const q=document.getElementById('search').value.toLowerCase();

let filtered=products;

if(q)
filtered=products.filter(p=>p.name.toLowerCase().includes(q));

filtered.slice(0,cols*rows).forEach(p=>{

const card=document.createElement('div');

card.className='card product-card';

card.innerHTML=`<img src="${p.img}">
<div class="card-body text-center">
<strong>${p.name}</strong>
<div class="text-success">$${p.price}</div>
</div>`;

card.onclick=()=>addToCart(p);

grid.appendChild(card);

});

}

document.getElementById('cols').oninput=e=>{cols=parseInt(e.target.value)||4;renderProducts();}
document.getElementById('rows').oninput=e=>{rows=parseInt(e.target.value)||3;renderProducts();}
document.getElementById('search').oninput=renderProducts;

/* ================= CARRITO ================= */

function addToCart(p){
    if (p.has_variants && p.variants && p.variants.length > 0) {
        showVariantsModal(p);
        return;
    }
    if(!cart[p.id]) cart[p.id]={...p,qty:1};
    else cart[p.id].qty++;
    renderCart();
}

function renderCart(){

let html='',total=0,i=1;

Object.values(cart).forEach(p=>{

const sub=p.qty*p.price;

total+=sub;

        const id = p.variant_id ? `v${p.variant_id}` : p.id;
        html+=`
        <div class="venta-row">

        <div>${i++}</div>

        <div>${p.name}</div>

        <div class="qty-box">
        <button onclick="changeQty('${id}',-1)">-</button>
        <input value="${p.qty}" onchange="setQty('${id}',this.value)">
        <button onclick="changeQty('${id}',1)">+</button>
        </div>

        <button class="trash-btn" onclick="removeItem('${id}')">🗑</button>

        <div class="text-end">
        <strong>$${sub.toFixed(2)}</strong>
        </div>

        </div>`;

});

document.getElementById('cart').innerHTML=html;

document.getElementById('total').innerText=total.toFixed(2);

}

function changeQty(id,d){
    if(cart[id]){
        cart[id].qty+=d;
        if(cart[id].qty<=0)delete cart[id];
        renderCart();
    }
}

function setQty(id,v){
    v=parseInt(v);
    if(cart[id]){
        if(v<=0)delete cart[id];
        else cart[id].qty=v;
        renderCart();
    }
}

function removeItem(id){
    delete cart[id];
    renderCart();
}

/**
 * Muestra el modal de variantes para elegir talle/color
 */
function showVariantsModal(p) {
    const list = document.getElementById('variantList');
    list.innerHTML = '';
    
    p.variants.forEach(v => {
        const btn = document.createElement('button');
        btn.className = 'btn btn-light w-100 text-start p-3 border-bottom rounded-0';
        btn.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Talle:</strong> ${v.size || '-'} | <strong>Color:</strong> ${v.color || '-'}
                </div>
                <div class="fw-bold text-success">$${parseFloat(v.price || p.price).toFixed(2)}</div>
            </div>
        `;
        btn.onclick = () => {
            selectVariant(p, v);
            modalVariante.hide();
        };
        list.appendChild(btn);
    });
    
    modalVariante.show();
}

/**
 * Agrega la variante específica al carrito
 */
function selectVariant(p, v) {
    const variantId = `v${v.id}`;
    if(cart[variantId]){
        cart[variantId].qty++;
    } else {
        cart[variantId] = {
            id: p.id,
            variant_id: v.id,
            name: `${p.name} (${v.size || ''} ${v.color || ''})`,
            price: parseFloat(v.price || p.price),
            qty: 1
        };
    }
    renderCart();
}

/* ================= CALCULADORA ================= */

function evaluarExpresion(expr){

try{

expr=expr.toLowerCase().replace(/x/g,'*').replace(/,/g,'.');

expr=expr.replace(/[^0-9\.\+\-\*\/]/g,'');

if(!expr)return 0;

return Function('"use strict";return ('+expr+')')();

}catch{return 0;}

}

document.getElementById('montoPagado').oninput=()=>{

const pagado=evaluarExpresion(document.getElementById('montoPagado').value);

document.getElementById('resultadoParcial').innerText=pagado.toLocaleString();

const total=parseFloat(document.getElementById('modalTotal').innerText)||0;

const diff=pagado-total;

const res=document.getElementById('resultadoPago');

if(diff<0){

res.className='falta';
res.innerText='FALTA $ '+Math.abs(diff).toLocaleString();

document.getElementById('vueltoBox').style.display='none';

}

else if(diff>0){

res.className='saldo';
res.innerText='SALDO 0';

document.getElementById('vueltoBox').style.display='block';
document.getElementById('vueltoValor').innerText=diff.toLocaleString();

}

else{

res.className='saldo';
res.innerText='SALDO 0';

document.getElementById('vueltoBox').style.display='none';

}

};

/* ================= CLIENTES ================= */

document.getElementById('btnBuscarCliente').onclick=()=>{
renderTablaClientes();
modalBuscarCliente.show();
};

function renderTablaClientes(){

const q=document.getElementById('buscarClienteInput').value?.toLowerCase()||'';

const tbody=document.getElementById('tablaClientes');

tbody.innerHTML='';

clientes.forEach(c=>{

let datos=[c.name??'',c.document??'',c.phone??''];

let coincide=datos.some(d=>d.toLowerCase().includes(q));

if(q && !coincide) return;

let fila=document.createElement('tr');

fila.style.cursor='pointer';

fila.innerHTML=`
<td>${resaltar(c.name??'',q)}</td>
<td>${resaltar(c.document??'',q)}</td>
<td>${resaltar(c.phone??'',q)}</td>
`;

fila.onclick=()=>{

document.getElementById('cliente_id').value=c.id;

document.getElementById('clienteNombre').innerText=c.name;

document.getElementById('tipoVentaClienteBox').style.display='block';

modalBuscarCliente.hide();

};

tbody.appendChild(fila);

});

}

function resaltar(texto,busqueda){

if(!busqueda)return texto;

let regex=new RegExp(`(${busqueda})`,'gi');

return texto.replace(regex,'<span class="highlight">$1</span>');

}

document.getElementById('buscarClienteInput').oninput=renderTablaClientes;

/* ================= COBRAR ================= */

document.getElementById('checkout').onclick=()=>{

    if(!Object.keys(cart).length){
        alert('Carrito vacío');
        return;
    }

    document.getElementById('modalTotal').innerText=
    document.getElementById('total').innerText;

    renderRemitoItems();
    modalCobrar.show();
};

document.getElementById('hacerRemito').onchange = (e) => {
    document.getElementById('remitoDetails').style.display = e.target.checked ? 'block' : 'none';
};

function renderRemitoItems() {
    const list = document.getElementById('remitoItemsList');
    list.innerHTML = '';

    Object.values(cart).forEach(item => {
        const div = document.createElement('div');
        div.className = 'd-flex justify-content-between align-items-center mb-2 pb-2 border-bottom';
        div.innerHTML = `
            <div style="flex:1;">
                <div class="fw-bold text-truncate" style="max-width: 180px;">${item.name}</div>
                <small class="text-muted">Vendidos: ${item.qty}</small>
            </div>
            <div style="width: 80px;">
                <input type="number" 
                       class="form-control form-control-sm text-center fw-bold item-entrega" 
                       data-id="${item.id}" 
                       data-variant="${item.variant_id || ''}"
                       value="${item.qty}" 
                       min="0" 
                       max="${item.qty}" 
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


/* ================= COBRAR ACCIONES ================= */

async function procesarVenta(imprimir = false) {
    if(!Object.keys(cart).length){
        alert('Carrito vacío');
        return;
    }

    const total = parseFloat(document.getElementById('modalTotal').innerText) || 0;
    const pagado = evaluarExpresion(document.getElementById('montoPagado').value);

    // Permitir pagos menores si es cuenta corriente
    const tipoVenta = document.getElementById('tipoVentaCliente').value;
    if(tipoVenta === 'contado' && pagado < total){
        alert('Pago insuficiente para venta al contado');
        return;
    }

    let clienteID = document.getElementById('cliente_id').value;

    if(!clienteID && consumidorFinal){
        clienteID = consumidorFinal.id;
    }

    const items = Object.values(cart).map(p => ({
        product_id: p.id,
        cantidad: p.qty
    }));

    try {
        const response = await fetch("{{ route('empresa.pos.checkout') }}", {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body:JSON.stringify({
                items:items,
                cliente_id:clienteID,
                tipo_venta_cliente:document.getElementById('tipoVentaCliente') ? document.getElementById('tipoVentaCliente').value : 'contado',
                metodo_pago:document.getElementById('metodoPago').value,
                tipo_comprobante: document.querySelector('input[name="tipoComprobante"]:checked').value,
                hacer_remito: document.getElementById('hacerRemito').checked,
                items_entregar: getItemsEntregar()
            })
        });

        const data = await response.json();

        if(!data.ok){
            alert('Error al guardar venta: ' + (data.error || 'Error desconocido'));
            return;
        }

        // SI SE GENERÓ REMITO -> ABRIR PDF EN NUEVA PESTAÑA
        if(data.remito_id) {
            window.open("{{ url('empresa/remitos') }}/" + data.remito_id + "/pdf", '_blank');
        }

        modalCobrar.hide();

        if (imprimir) {
            if (data.tipo_comprobante === 'factura') {
                window.open("{{ url('empresa/ventas') }}/" + data.venta_id + "/pdf", '_blank');
            } else {
                let ticket = `
                MULTIPOS
                ---------------------------
                Venta #${data.venta_id}
                
                `;

                Object.values(cart).forEach(p=>{
                    ticket += `${p.name}\n`;
                    ticket += `${p.qty} x $${p.price}\n`;
                    ticket += `$${(p.qty*p.price).toFixed(2)}\n\n`;
                });

                ticket += `
                ---------------------------
                TOTAL: $${data.total}
                
                Gracias por su compra
                `;

                const w = window.open('', 'PRINT', 'height=600,width=350');
                w.document.write('<pre>'+ticket+'</pre>');
                w.document.close();
                w.focus();
                w.print();
                w.close();
            }
        }

        /* LIMPIAR POS */
        cart={};
        renderCart();

        const flash=document.getElementById('ventaFlash');
        flash.style.display='block';
        setTimeout(()=>flash.style.display='none', 1500);

        document.getElementById('montoPagado').value='';
        document.getElementById('cliente_id').value='';
        document.getElementById('clienteNombre').innerText='CONSUMIDOR FINAL';
        document.getElementById('tipoVentaClienteBox').style.display='none';
        document.getElementById('hacerRemito').checked = false;

    } catch(err) {
        alert('Error en conexión: ' + err.message);
    }
}

document.getElementById('confirmarVenta').onclick = () => procesarVenta(false);
document.getElementById('imprimirVenta').onclick = () => procesarVenta(true);



/* ================= BARCODE SCANNER LOGIC ================= */

const barcodeInput = document.getElementById('barcodeInput');
const barcodeStatus = document.getElementById('barcodeStatus');
const laserLine = document.getElementById('laserLine');

// Sonido de escaneo exitoso (Base64 Bip corto)
const beepSound = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YTdvT18AZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZ=");

// Mantener el foco en el input de barcode (opcional)
document.addEventListener('keydown', (e) => {
    // Si no estamos en un input de búsqueda o modal, enfocamos el scanner
    if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
        barcodeInput.focus();
    }
});

barcodeInput.onkeypress = async (e) => {
    if (e.key === 'Enter') {
        const code = barcodeInput.value.trim();
        if (!code) return;

        barcodeInput.value = '';
        barcodeInput.disabled = true;
        barcodeStatus.innerHTML = '<span class="status-busy">⏳ Buscando...</span>';
        
        // Animación de láser
        laserLine.style.display = 'block';
        setTimeout(() => laserLine.style.display = 'none', 800);

        try {
            const res = await fetch(`{{ route('empresa.pos.barcode') }}?barcode=${code}`);
            const data = await res.json();

            if (data.ok) {
                // Pip!
                beepSound.play();
                
                // Si es un producto base o variante ya resuelta por el controlador
                if (data.variant_id) {
                    // Es una variante específica
                    const cartId = `v${data.variant_id}`;
                    if(cart[cartId]) cart[cartId].qty++;
                    else {
                        cart[cartId] = {
                            id: data.product_id,
                            variant_id: data.variant_id,
                            name: data.name,
                            price: data.price,
                            qty: 1
                        };
                    }
                } else {
                    // Es un producto normal o combo
                    if(!cart[data.product_id]) {
                        cart[data.product_id] = {
                            id: data.product_id,
                            name: data.name,
                            price: data.price,
                            qty: 1
                        };
                    } else {
                        cart[data.product_id].qty++;
                    }
                }
                
                // Efecto visual en el carrito
                document.getElementById('cart').classList.add('scan-pulse');
                setTimeout(() => document.getElementById('cart').classList.remove('scan-pulse'), 1000);

                renderCart();
            } else {
                console.warn("Producto no encontrado:", code);
                // Aquí podrías disparar un sonido de error si quieres
            }
        } catch (err) {
            console.error(err);
        } finally {
            barcodeInput.disabled = false;
            barcodeInput.focus();
            barcodeStatus.innerHTML = '<span class="status-ready">● Escáner Listo</span>';
        }
    }
};


/* ================= CAMERA SCANNER LOGIC ================= */

let html5QrCode = null;
const modalCamera = new bootstrap.Modal(document.getElementById('modalCamera'));

document.getElementById('btnCameraScan').onclick = () => {
    modalCamera.show();
    
    // Pequeño delay para que el modal termine de animar antes de iniciar cámara
    setTimeout(() => {
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }
        
        const config = { fps: 10, qrbox: { width: 250, height: 150 } };
        
        // Intentar usar cámara trasera por defecto
        html5QrCode.start(
            { facingMode: "environment" }, 
            config,
            (decodedText) => {
                // Éxito: Procesar código
                console.log("Cámara detectó:", decodedText);
                
                // Simular el proceso del input normal
                processScannerCode(decodedText);
                
                // Feedback táctil (vibrate)
                if (navigator.vibrate) navigator.vibrate(100);
                
                // Cerrar modal automáticamente tras éxito
                modalCamera.hide();
            },
            (errorMessage) => {
                // Aquí se disparan errores persistentes mientras busca, ignoramos
            }
        ).catch((err) => {
            alert("Error al iniciar cámara: " + err);
        });
    }, 500);
};

// Detener cámara al cerrar modal
document.getElementById('modalCamera').addEventListener('hidden.bs.modal', () => {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => {
            console.log("Cámara detenida.");
        }).catch(err => console.error("Error al detener cámara:", err));
    }
});

/**
 * Función centralizada para procesar un código venga de donde venga
 */
async function processScannerCode(code) {
    if (!code) return;
    
    barcodeStatus.innerHTML = '<span class="status-busy">⏳ Buscando...</span>';
    laserLine.style.display = 'block';
    setTimeout(() => laserLine.style.display = 'none', 800);

    try {
        const res = await fetch(`{{ route('empresa.pos.barcode') }}?barcode=${code}`);
        const data = await res.json();

        if (data.ok) {
            beepSound.play();
            
            if (data.variant_id) {
                const cartId = `v${data.variant_id}`;
                if(cart[cartId]) cart[cartId].qty++;
                else {
                    cart[cartId] = {
                        id: data.product_id,
                        variant_id: data.variant_id,
                        name: data.name,
                        price: data.price,
                        qty: 1
                    };
                }
            } else {
                if(!cart[data.product_id]) {
                    cart[data.product_id] = {
                        id: data.product_id,
                        name: data.name,
                        price: data.price,
                        qty: 1
                    };
                } else {
                    cart[data.product_id].qty++;
                }
            }
            
            document.getElementById('cart').classList.add('scan-pulse');
            setTimeout(() => document.getElementById('cart').classList.remove('scan-pulse'), 1000);
            renderCart();
        } else {
            console.warn("No encontrado:", code);
        }
    } catch (err) {
        console.error(err);
    } finally {
        barcodeStatus.innerHTML = '<span class="status-ready">● Escáner Listo</span>';
    }
}

/* ================= INICIALIZAR POS ================= */
renderProducts();
barcodeInput.focus();

</script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
// La lógica ya fue integrada arriba, eliminamos el script duplicado
</script>
@endpush

@endsection
