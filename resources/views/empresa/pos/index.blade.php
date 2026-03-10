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
.venta-row:nth-child(odd){background:#eaf3ff}

.qty-box{display:flex;gap:4px;justify-content:center}
.qty-box input{width:38px;text-align:center;font-size:12px;padding:2px}

.trash-btn{border:1px solid #dc3545;color:#dc3545;background:none;font-size:12px;padding:2px 6px}

.falta{color:#dc3545;font-weight:bold}
.vuelto{color:#198754;font-weight:bold}
.saldo{color:#333;font-weight:bold}
.highlight{background:yellow;font-weight:bold}
</style>

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

<input type="text" id="search" class="form-control mb-3" placeholder="🔍 Buscar producto...">
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

@push('scripts')
<script>

const products = {!! json_encode($productsData) !!};
const clientes = {!! json_encode($clientesData ?? []) !!};

let cart={},cols=4,rows=3;

const grid=document.getElementById('productGrid');
const modalCobrar=new bootstrap.Modal(document.getElementById('modalCobrar'));
const modalBuscarCliente=new bootstrap.Modal(document.getElementById('modalBuscarCliente'));

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
if(!cart[p.id]) cart[p.id]={...p,qty:1};
else cart[p.id].qty++;
renderCart();
}

function renderCart(){

let html='',total=0,i=1;

Object.values(cart).forEach(p=>{

const sub=p.qty*p.price;

total+=sub;

html+=`
<div class="venta-row">

<div>${i++}</div>

<div>${p.name}</div>

<div class="qty-box">
<button onclick="changeQty(${p.id},-1)">-</button>
<input value="${p.qty}" onchange="setQty(${p.id},this.value)">
<button onclick="changeQty(${p.id},1)">+</button>
</div>

<button class="trash-btn" onclick="removeItem(${p.id})">🗑</button>

<div class="text-end">
<strong>$${sub.toFixed(2)}</strong>
</div>

</div>`;

});

document.getElementById('cart').innerHTML=html;

document.getElementById('total').innerText=total.toFixed(2);

}

function changeQty(id,d){
cart[id].qty+=d;
if(cart[id].qty<=0)delete cart[id];
renderCart();
}

function setQty(id,v){
v=parseInt(v);
if(v<=0)delete cart[id];
else cart[id].qty=v;
renderCart();
}

function removeItem(id){
delete cart[id];
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

modalCobrar.show();

};


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
                metodo_pago:document.getElementById('metodoPago').value
            })
        });

        const data = await response.json();

        if(!data.ok){
            alert('Error al guardar venta: ' + (data.error || 'Error desconocido'));
            return;
        }

        modalCobrar.hide();

        if (imprimir) {
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

    } catch(err) {
        alert('Error en conexión: ' + err.message);
    }
}

document.getElementById('confirmarVenta').onclick = () => procesarVenta(false);
document.getElementById('imprimirVenta').onclick = () => procesarVenta(true);


/* ================= INICIALIZAR POS ================= */

renderProducts();

</script>
@endpush

@endsection
