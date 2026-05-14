@extends('layouts.guest')

@php
    $isCatalog = true;
    $config = $empresa->config ?? null;
    $primary   = $config->color_primary   ?? '#2563eb';
    $secondary = $config->color_secondary ?? '#16a34a';
    $cartCount = session('cart') ? count(session('cart')) : 0;
    $logo      = $config ? $config->logo_url : asset('images/logo_omnipos.png');
@endphp

@section('content')

<style>
    :root {
        --catalog-primary: {{ $primary }};
        --catalog-secondary: {{ $secondary }};
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.3);
    }

    body {
        background-color: transparent !important;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .catalog-fluid-container {
        width: 100%;
        max-width: 1200px; /* Centrado para lectura en detalle */
        margin: 0 auto;
        padding: 0 20px 100px 20px;
    }

    .glass-nav {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-bottom: 1px solid var(--glass-border);
        padding: 15px 40px;
        position: sticky;
        top: 0;
        z-index: 1000;
        margin-bottom: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .product-wrapper {
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(10px);
        padding: 40px;
        border-radius: 32px;
        border: 1px solid rgba(255,255,255,0.4);
        box-shadow: 0 20px 50px rgba(0,0,0,0.05);
    }

    .main-image {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .thumbnail {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        opacity: 0.6;
        transition: 0.3s ease;
        border: 2px solid transparent;
    }

    .thumbnail:hover, .thumbnail.active-thumb {
        opacity: 1;
        border-color: var(--catalog-primary);
        transform: translateY(-2px);
    }

    .price-tag {
        font-size: 2.5rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -1px;
    }

    .quantity-box {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 16px;
        padding: 5px;
        width: fit-content;
    }

    .quantity-box btn { padding: 10px 15px; border: none; background: transparent; font-weight: bold; }
    .quantity-box input { width: 50px; border: none; background: transparent; text-align: center; font-weight: 700; }

    .btn-buy {
        border-radius: 18px;
        padding: 18px;
        font-weight: 800;
        font-size: 1.1rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-buy:hover:not(:disabled) {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
    }

    @media (max-width: 768px) {
        .glass-nav { padding: 10px 15px; }
        .product-wrapper { padding: 20px; border-radius: 20px; }
        .price-tag { font-size: 1.8rem; }
    }

    .btn-buy {
        background: var(--catalog-primary);
        border: none;
        color: white;
    }
</style>

<div class="glass-nav w-100 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        @if($logo)
            <img src="{{ $logo }}" height="40" class="me-3 rounded-2 shadow-sm">
        @endif
        <div class="brand-id h5 m-0 fw-bold d-none d-md-block">
            {{ $empresa->nombre_comercial }}
        </div>
    </div>

    <div class="d-flex gap-3 align-items-center">
        <a href="{{ route('catalog.index', $empresa) }}" class="btn btn-light rounded-pill px-4 shadow-sm border small fw-bold text-muted d-none d-md-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Seguir Comprando
        </a>
        <a href="{{ route('cart.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm position-relative fw-bold">
            <i class="bi bi-cart-fill me-1"></i> Carrito
            @if($cartCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
    </div>
</div>

<div class="catalog-fluid-container mt-4">
    <div class="product-wrapper">
        <div class="row g-5">
            <div class="col-lg-6">
                @if($product->images->count())
                    <img id="mainImage" src="{{ $product->images->first()->url }}" class="main-image">
                    
                    <div class="row mt-3 g-2">
                        @foreach($product->images as $index => $image)
                            <div class="col-3">
                                <img src="{{ $image->url }}" 
                                     class="thumbnail {{ $index == 0 ? 'active-thumb' : '' }}" 
                                     onclick="changeImage(this)">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="main-image d-flex align-items-center justify-content-center bg-light text-muted fs-1">
                        📷
                    </div>
                @endif
            </div>

            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('catalog.index', $empresa) }}" class="text-decoration-none text-muted">Catálogo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                  </ol>
                </nav>

                <h1 class="fw-bold mb-3" style="color: #0f172a;">{{ $product->name }}</h1>
                
                <div class="price-tag mb-4">
                    $ {{ number_format($product->price, 0, ',', '.') }}
                </div>

                @if($product->descripcion_corta)
                    <div class="text-muted mb-4 fs-5">{{ $product->descripcion_corta }}</div>
                @endif

                <form id="addToCartForm" method="POST" action="{{ route('cart.add', $product) }}" class="mt-5">
                    @csrf
                    <input type="hidden" name="variant_id" id="variantIdInput">

                    @if($product->has_variants && $product->variants->count() > 0)
                        <div class="variants-section mb-4">
                            <label class="fw-bold mb-2">Seleccione una opción</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($product->variants as $v)
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm variant-btn rounded-pill px-3"
                                            data-id="{{ $v->id }}"
                                            data-price="{{ $v->price ?: $product->price }}"
                                            data-stock="{{ $v->stock }}"
                                            onclick="selectVariant(this)">
                                        {{ $v->size }} / {{ $v->color }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row align-items-center g-3 mb-4">
                        <div class="col-auto">
                            <label class="fw-bold">Cantidad</label>
                            <div class="quantity-box mt-1">
                                <button type="button" class="btn" onclick="changeQty(-1)">-</button>
                                <input type="number" id="quantityInput" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                                <button type="button" class="btn" onclick="changeQty(1)">+</button>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small">Stock disponible: <span id="displayStock">{{ number_format($product->stock, 0) }}</span></div>
                        </div>
                    </div>

                    <button type="submit" id="addToCartBtn" class="btn btn-primary btn-buy w-100" 
                            {{ $product->has_variants ? 'disabled' : '' }}>
                        <i class="bi bi-cart-plus-fill me-2"></i> Agregar al Carrito
                    </button>

                    @if($product->has_variants)
                        <p id="variantWarning" class="text-danger small mt-2 text-center">⚠️ Seleccione una variante para continuar.</p>
                    @endif
                </form>
            </div>
        </div>

        @if($product->descripcion_larga)
            <div class="mt-5 pt-5 border-top">
                <h4 class="fw-bold mb-4">Detalles del Producto</h4>
                <div class="fs-5 text-secondary" style="line-height: 1.8;">
                    {!! nl2br(e($product->descripcion_larga)) !!}
                </div>
            </div>
        @endif

        @if($product->videos->count())
            <div class="mt-5 pt-5 border-top">
                <h4 class="fw-bold mb-4"><i class="bi bi-play-circle me-2"></i> Videos y Demostraciones</h4>
                <div class="row g-4">
                    @foreach($product->videos as $video)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ $video->embed_url }}" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function changeImage(element){
        document.getElementById('mainImage').src = element.src;
        document.querySelectorAll('.thumbnail').forEach(img => img.classList.remove('active-thumb'));
        element.classList.add('active-thumb');
    }

    function selectVariant(btn){
        document.querySelectorAll('.variant-btn').forEach(b => {
             b.classList.remove('btn-dark');
             b.classList.add('btn-outline-secondary');
        });
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-dark');

        const id = btn.dataset.id;
        const price = parseFloat(btn.dataset.price);
        const stock = parseFloat(btn.dataset.stock);

        document.getElementById('variantIdInput').value = id;
        document.querySelector('.price-tag').innerText = '$ ' + price.toLocaleString('es-AR');
        document.getElementById('displayStock').innerText = stock;
        
        document.getElementById('quantityInput').max = stock;
        document.getElementById('addToCartBtn').disabled = false;
        if(document.getElementById('variantWarning')) document.getElementById('variantWarning').style.display = 'none';
    }

    function changeQty(amount){
        const input = document.getElementById('quantityInput');
        let current = parseInt(input.value) || 1;
        current += amount;
        if(current < 1) current = 1;
        if(current > parseInt(input.max)) current = parseInt(input.max);
        input.value = current;
    }
</script>

@endsection
