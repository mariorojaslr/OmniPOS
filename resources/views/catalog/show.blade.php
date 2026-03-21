<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ $product->name }} - {{ $empresa->nombre_comercial }}</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f5f6f8; }

.product-wrapper {
    background:white;
    padding:40px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.06);
}

.main-image {
    width:100%;
    height:600px;
    object-fit:cover;
    border-radius:14px;
    transition:transform .4s ease;
}

.main-image:hover { transform:scale(1.06); }

.thumbnail {
    width:100%;
    height:95px;
    object-fit:cover;
    border-radius:10px;
    cursor:pointer;
    opacity:.7;
    transition:.3s ease;
}

.thumbnail:hover { transform:scale(1.05); opacity:1; }

.active-thumb { border:2px solid #0d6efd; opacity:1; }

.video-thumb {
    height:160px;
    object-fit:cover;
    border-radius:12px;
    cursor:pointer;
    transition:.3s ease;
}

.video-thumb:hover { transform:scale(1.04); }

.play-overlay {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    font-size:38px;
    color:white;
    pointer-events:none;
}

.price {
    font-size:32px;
    font-weight:bold;
    color:#198754;
}

.section-title {
    font-weight:600;
    margin-top:25px;
}

/* ===== Selector Profesional ===== */
.quantity-wrapper {
    display:flex;
    align-items:center;
    gap:15px;
    margin-top:25px;
}

.quantity-box {
    display:flex;
    align-items:center;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
    overflow:hidden;
    border:1px solid #ddd;
    background:white;
}

.quantity-box button {
    width:40px;
    height:40px;
    border:none;
    background:#0d6efd;
    color:white;
    font-size:20px;
    font-weight:bold;
    transition:.2s;
}

.quantity-box button:hover {
    background:#084298;
}

.quantity-box input {
    width:70px;
    height:40px;
    text-align:center;
    border:none;
    font-weight:600;
    font-size:16px;
}

.quantity-box input:focus {
    outline:none;
}

.stock-info {
    font-size:13px;
    color:#6c757d;
    margin-top:6px;
}

.cart-bounce {
    animation:bounce .5s ease;
}

@keyframes bounce {
    0%{transform:scale(1);}
    30%{transform:scale(1.3);}
    60%{transform:scale(.9);}
    100%{transform:scale(1);}
}
</style>
</head>

<body>

<nav class="navbar navbar-light bg-white shadow-sm mb-5">
<div class="container d-flex justify-content-between">
<span class="navbar-brand fw-bold">
{{ $empresa->nombre_comercial }}
</span>

<a href="{{ route('cart.index') }}" id="cartIcon" class="btn btn-outline-secondary">
🛒
</a>
</div>
</nav>

@if(session('success'))
<div class="position-fixed top-0 end-0 p-4" style="z-index:9999; margin-top:80px;">
    <div id="cartToast" class="toast align-items-center text-bg-success border-0 show shadow">
        <div class="d-flex">
            <div class="toast-body">
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif

<div class="container">
<div class="product-wrapper">

<div class="row g-5">

<div class="col-lg-7">

<div class="row">
<div class="col-2">
<div class="d-flex flex-column gap-3">
@foreach($product->images as $index => $image)
<img src="{{ url('images/'.$image->url) }}"
class="thumbnail {{ $index == 0 ? 'active-thumb' : '' }}"
onclick="changeImage(this)">
@endforeach
</div>
</div>

<div class="col-10">
@if($product->images->count())
<img id="mainImage"
src="{{ url('images/'.$product->images->first()->url) }}"
class="main-image">
@endif
</div>
</div>

@if($product->videos->count())
<div class="row mt-4 g-3">
@foreach($product->videos as $video)
<div class="col-md-4">
<div class="position-relative"
data-bs-toggle="modal"
data-bs-target="#videoModal{{ $video->id }}">
<img src="{{ $video->thumbnail }}" class="w-100 video-thumb">
<div class="play-overlay">▶</div>
</div>
</div>
@endforeach
</div>
@endif

</div>

<div class="col-lg-5">

<h1 class="fw-bold">{{ $product->name }}</h1>

<div class="price mt-3">
${{ number_format($product->price,2) }}
</div>

@if($product->descripcion_corta)
<div class="section-title">Descripción breve</div>
<p>{{ $product->descripcion_corta }}</p>
@endif

<form id="addToCartForm" method="POST" action="{{ route('cart.add', $product) }}">
@csrf

<input type="hidden" name="variant_id" id="variantIdInput">

@if($product->has_variants && $product->variants->count() > 0)
    <div class="variants-section mt-4">
        <div class="fw-bold mb-2">Seleccione una opción</div>
        <div class="row g-2">
            @foreach($product->variants as $v)
                <div class="col-6 col-md-4">
                    <button type="button" 
                            class="btn btn-outline-dark btn-sm w-100 variant-btn"
                            data-id="{{ $v->id }}"
                            data-price="{{ $v->price ?: $product->price }}"
                            data-stock="{{ $v->stock }}"
                            onclick="selectVariant(this)">
                        {{ $v->size }} / {{ $v->color }}
                    </button>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="quantity-wrapper mt-4">
    <div class="fw-bold">Cantidad</div>

    <div class="quantity-box">
        <button type="button" onclick="changeQty(-1)">−</button>
        <input type="number"
               id="quantityInput"
               name="quantity"
               value="1"
               min="1"
               max="{{ $product->stock }}">
        <button type="button" onclick="changeQty(1)">+</button>
    </div>
</div>

<div class="stock-info">
    Stock disponible: <span id="displayStock">{{ number_format($product->stock, 2) }}</span>
</div>

<button type="submit" id="addToCartBtn" class="btn btn-primary btn-lg w-100 mt-4" 
        {{ $product->has_variants ? 'disabled' : '' }}>
    Agregar al carrito
</button>

@if($product->has_variants)
    <p id="variantWarning" class="text-danger small mt-1">Por favor, seleccione una variante antes de agregar.</p>
@endif

</form>

</div>
</div>

@if($product->descripcion_larga)
<hr class="my-5">
<h4 class="fw-bold">Descripción detallada</h4>
<p>{{ $product->descripcion_larga }}</p>
@endif

</div>
</div>

@foreach($product->videos as $video)
<div class="modal fade" id="videoModal{{ $video->id }}" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-body p-0">
<div class="ratio ratio-16x9">
<iframe src="{{ $video->embed_url }}" allowfullscreen></iframe>
</div>
</div>
</div>
</div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function changeImage(element){
    document.getElementById('mainImage').src = element.src;
    document.querySelectorAll('.thumbnail').forEach(img=>img.classList.remove('active-thumb'));
    element.classList.add('active-thumb');
}

function selectVariant(btn){
    // Highlight
    document.querySelectorAll('.variant-btn').forEach(b => b.classList.replace('btn-dark', 'btn-outline-dark'));
    btn.classList.replace('btn-outline-dark', 'btn-dark');

    // Data
    const id = btn.dataset.id;
    const price = parseFloat(btn.dataset.price);
    const stock = parseFloat(btn.dataset.stock);

    // Update UI
    document.getElementById('variantIdInput').value = id;
    document.querySelector('.price').innerText = '$' + price.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('displayStock').innerText = stock.toFixed(2);
    
    const qtyInput = document.getElementById('quantityInput');
    qtyInput.max = stock;
    if(parseInt(qtyInput.value) > stock) qtyInput.value = stock;

    // Enable button
    document.getElementById('addToCartBtn').disabled = false;
    const warning = document.getElementById('variantWarning');
    if(warning) warning.style.display = 'none';
}

function changeQty(amount){
    const input = document.getElementById('quantityInput');
    const max = parseInt(input.max) || 99999;
    let current = parseInt(input.value) || 1;

    current += amount;

    if(current < 1) current = 1;
    if(current > max) current = max;

    input.value = current;
}

@if(session('success'))
document.getElementById('cartIcon').classList.add('cart-bounce');

setTimeout(()=>{
    const toast = document.getElementById('cartToast');
    if(toast){ toast.remove(); }
},3000);
@endif
</script>

</body>
</html>
