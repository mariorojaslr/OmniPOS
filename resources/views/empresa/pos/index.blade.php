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

{{-- MODAL --}}
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
<input type="text" id="montoPagado" class="form-control" placeholder="Ej: 2x10000 + 5x2000">
<small class="text-muted">Podés usar: + - x (ej: 3x1000 + 2x500)</small>
</div>

<strong>Vuelto: $ <span id="vuelto">0.00</span></strong>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
<button class="btn btn-success" id="confirmarVenta">Confirmar venta</button>
</div>

</div>
</div>
</div>

@push('scripts')
<script>

const products = {!! json_encode($productsData) !!};

let cart={},cols=4,rows=3;

const grid=document.getElementById('productGrid');
const modalCobrar=new bootstrap.Modal(document.getElementById('modalCobrar'));

/* ================= PRODUCTOS ================= */

function renderProducts(){
grid.style.gridTemplateColumns=`repeat(${cols},1fr)`;
grid.innerHTML='';
const q=document.getElementById('search').value.toLowerCase();
let filtered=products;
if(q)filtered=products.filter(p=>p.name.toLowerCase().includes(q));

filtered.slice(0,cols*rows).forEach(p=>{
const card=document.createElement('div');
card.className='card product-card';
card.innerHTML=`<img src="${p.img}"><div class="card-body text-center"><strong>${p.name}</strong><div class="text-success">$${p.price}</div></div>`;
card.onclick=()=>addToCart(p);
grid.appendChild(card);
});
}

document.getElementById('cols').oninput=e=>{cols=parseInt(e.target.value)||4;renderProducts();}
document.getElementById('rows').oninput=e=>{rows=parseInt(e.target.value)||3;renderProducts();}
document.getElementById('search').oninput=renderProducts;

/* ================= CARRITO ================= */

function addToCart(p){
if(!cart[p.id])cart[p.id]={...p,qty:1};else cart[p.id].qty++;
renderCart();
}

function renderCart(){
let html='',total=0,i=1;
Object.values(cart).forEach(p=>{
const sub=p.qty*p.price;total+=sub;
html+=`<div class="venta-row">
<div>${i++}</div>
<div>${p.name}</div>
<div class="qty-box">
<button onclick="changeQty(${p.id},-1)">-</button>
<input value="${p.qty}" onchange="setQty(${p.id},this.value)">
<button onclick="changeQty(${p.id},1)">+</button>
</div>
<button class="trash-btn" onclick="removeItem(${p.id})">🗑</button>
<div class="text-end"><strong>$${sub.toFixed(2)}</strong></div>
</div>`;
});
document.getElementById('cart').innerHTML=html;
document.getElementById('total').innerText=total.toFixed(2);
}

function changeQty(id,d){cart[id].qty+=d;if(cart[id].qty<=0)delete cart[id];renderCart();}
function setQty(id,v){v=parseInt(v);if(v<=0)delete cart[id];else cart[id].qty=v;renderCart();}
function removeItem(id){delete cart[id];renderCart();}

/* ================= CALCULADORA ================= */

function calcularExpresion(expr){
expr=expr.toLowerCase().replace(/x/g,'*').replace(/,/g,'.').replace(/[^0-9\.\+\-\*\/]/g,'');
try{return Function("return "+expr)();}catch{return 0;}
}

document.getElementById('montoPagado').oninput=()=>{
const pagado=calcularExpresion(document.getElementById('montoPagado').value)||0;
const total=parseFloat(document.getElementById('modalTotal').innerText);
document.getElementById('vuelto').innerText=Math.max(0,pagado-total).toFixed(2);
};

/* ================= COBRAR ================= */

document.getElementById('checkout').onclick=()=>{
if(!Object.keys(cart).length)return alert('Carrito vacío');
document.getElementById('modalTotal').innerText=document.getElementById('total').innerText;
document.getElementById('montoPagado').value='';
document.getElementById('vuelto').innerText='0.00';
modalCobrar.show();
};

document.getElementById('confirmarVenta').onclick=()=>{

const items=Object.values(cart).map(p=>{
const subtotal=p.qty*p.price;
const iva=subtotal*0.21;
const total=subtotal+iva;
return{product_id:p.id,cantidad:p.qty,precio:p.price,subtotal_sin_iva:subtotal,iva:iva,total:total};
});

const totalSinIva=items.reduce((s,i)=>s+i.subtotal_sin_iva,0);
const totalIva=items.reduce((s,i)=>s+i.iva,0);
const totalConIva=items.reduce((s,i)=>s+i.total,0);

const montoPagado=calcularExpresion(document.getElementById('montoPagado').value)||0;
const vuelto=Math.max(0,montoPagado-totalConIva);

fetch("{{ url('/empresa/pos/checkout') }}",{
method:'POST',
headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
body:JSON.stringify({
items,total_sin_iva:totalSinIva,total_iva:totalIva,total_con_iva:totalConIva,
metodo_pago:document.getElementById('metodoPago').value,
monto_pagado:montoPagado,vuelto:vuelto
})
})
.then(r=>r.json())
.then(r=>{
if(!r.ok){alert('ERROR AL GUARDAR');return;}
modalCobrar.hide();cart={};renderCart();
const flash=document.getElementById('ventaFlash');
flash.style.display='block';
setTimeout(()=>flash.style.display='none',1200);
})
.catch(()=>alert('Error conexión'));
};

renderProducts();

</script>
@endpush

@endsection
