@extends('layouts.empresa')

@section('content')
<style>
    .product-grid {
        display: grid;
        gap: 12px;
    }

    .product-card {
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .product-card:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 14px rgba(0,0,0,.15);
    }

    .product-card img {
        width: 100%;
        height: 140px;
        object-fit: contain;
    }

    .venta-wrapper {
        border: 1px solid #198754;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .venta-header {
        background: #198754;
        color: #fff;
        text-align: center;
        font-weight: bold;
        padding: 8px;
    }

    .venta-body {
        flex: 1;
        overflow-y: auto;
    }

    .venta-row {
        display: grid;
        grid-template-columns: 30px 1fr 130px 40px 110px;
        align-items: center;
        padding: 6px 8px;
        font-size: 14px;
    }

    .venta-row:nth-child(odd) {
        background: #eaf3ff;
    }

    .venta-row button {
        padding: 0 6px;
        font-size: 12px;
    }

    .qty-box {
        display: flex;
        gap: 4px;
        justify-content: center;
        align-items: center;
    }

    .qty-box input {
        width: 38px;
        text-align: center;
        font-size: 12px;
        padding: 2px;
    }

    .trash-btn {
        border: 1px solid #dc3545;
        color: #dc3545;
        background: none;
        font-size: 12px;
        padding: 2px 6px;
    }
</style>

<div class="row">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3>Punto de Venta</h3>
            <div class="d-flex gap-2">
                <div>
                    <small>Cols</small>
                    <input type="number" id="cols" value="6" min="2" max="8" style="width:60px">
                </div>
                <div>
                    <small>Filas</small>
                    <input type="number" id="rows" value="3" min="1" max="6" style="width:60px">
                </div>
            </div>
        </div>

        <input type="text" id="search" class="form-control mb-3" placeholder="🔍 Buscar producto...">

        <div id="productGrid" class="product-grid"></div>
    </div>

    <div class="col-lg-4">
        <div class="venta-wrapper" id="ventaWrapper">
            <div class="venta-header">Venta actual</div>

            <div class="venta-body" id="cart"></div>

            <div class="p-3 border-top">
                <h4>Total: $ <span id="total">0</span></h4>
                <button class="btn btn-success w-100" id="checkout">
                    Cobrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================
     MODAL COBRAR
========================== -->
<div class="modal fade" id="modalCobrar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Cobrar venta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <strong>Total a cobrar</strong>
                    <h2 class="text-success">
                        $ <span id="modalTotal">0.00</span>
                    </h2>
                </div>

                <div class="mb-3">
                    <label class="form-label">Método de pago</label>
                    <select id="metodoPago" class="form-select">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="qr">QR</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Monto recibido</label>
                    <input type="number" step="0.01" id="montoPagado" class="form-control">
                </div>

                <div>
                    <strong>Vuelto:</strong>
                    $ <span id="vuelto">0.00</span>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-success" id="confirmarVenta">
                    Confirmar venta
                </button>
            </div>

        </div>
    </div>
</div>


<!-- =========================
     SCRIPT
========================== -->
@push('scripts')
<script>

const products = {!! json_encode($productsData) !!};

let cart = {};
let cols = 6;
let rows = 3;

const grid = document.getElementById('productGrid');
const modalCobrar = new bootstrap.Modal(document.getElementById('modalCobrar'));

function renderProducts() {
    grid.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
    grid.innerHTML = '';

    const visible = products.slice(0, cols * rows);

    visible.forEach(p => {
        const card = document.createElement('div');
        card.className = 'card product-card';
        card.innerHTML = `
            <img src="${p.img}">
            <div class="card-body text-center">
                <strong>${p.name}</strong>
                <div class="text-success">$${p.price}</div>
            </div>
        `;
        card.onclick = () => addToCart(p);
        grid.appendChild(card);
    });

    syncVentaHeight();
}

function syncVentaHeight() {
    const rowHeight = document.querySelector('.product-card')?.offsetHeight || 180;
    document.getElementById('ventaWrapper').style.height =
        (rowHeight * rows + 140) + 'px';
}

function addToCart(p) {
    if (!cart[p.id]) cart[p.id] = {...p, qty: 1};
    else cart[p.id].qty++;
    renderCart();
}

function renderCart() {
    let html = '';
    let total = 0;
    let i = 1;

    Object.values(cart).forEach(p => {
        const sub = p.qty * p.price;
        total += sub;

        html += `
            <div class="venta-row">
                <div>${i++}</div>
                <div>${p.name}</div>
                <div class="qty-box">
                    <button onclick="changeQty(${p.id},-1)">-</button>
                    <input value="${p.qty}" onchange="setQty(${p.id},this.value)">
                    <button onclick="changeQty(${p.id},1)">+</button>
                </div>
                <button class="trash-btn" onclick="removeItem(${p.id})">🗑</button>
                <div class="text-end"><strong>$${sub.toFixed(2)}</strong></div>
            </div>
        `;
    });

    document.getElementById('cart').innerHTML = html;
    document.getElementById('total').innerText = total.toFixed(2);
}

function changeQty(id, d) {
    cart[id].qty += d;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}

function setQty(id, v) {
    v = parseInt(v);
    if (v <= 0) delete cart[id];
    else cart[id].qty = v;
    renderCart();
}

function removeItem(id) {
    delete cart[id];
    renderCart();
}

document.getElementById('cols').oninput = e => {
    cols = parseInt(e.target.value);
    renderProducts();
};

document.getElementById('rows').oninput = e => {
    rows = parseInt(e.target.value);
    renderProducts();
};

document.getElementById('search').oninput = e => {
    const q = e.target.value.toLowerCase();
    grid.querySelectorAll('.product-card').forEach(c => {
        c.style.display = c.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
};

document.getElementById('checkout').onclick = () => {
    if (!Object.keys(cart).length) return alert('Carrito vacío');

    document.getElementById('modalTotal').innerText =
        document.getElementById('total').innerText;

    document.getElementById('montoPagado').value = '';
    document.getElementById('vuelto').innerText = '0.00';

    modalCobrar.show();
};

document.getElementById('montoPagado').oninput = () => {
    const total = parseFloat(document.getElementById('modalTotal').innerText);
    const pagado = parseFloat(document.getElementById('montoPagado').value || 0);
    document.getElementById('vuelto').innerText =
        Math.max(0, pagado - total).toFixed(2);
};

document.getElementById('confirmarVenta').onclick = () => {
    fetch("{{ route('empresa.pos.checkout') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            items: Object.values(cart),
            total: document.getElementById('modalTotal').innerText,
            metodo_pago: document.getElementById('metodoPago').value,
            monto_pagado: document.getElementById('montoPagado').value
        })
    }).then(() => {
        modalCobrar.hide();
        cart = {};
        renderCart();
        alert('Venta registrada correctamente');
    });
};

renderProducts();

</script>
@endpush
@endsection
