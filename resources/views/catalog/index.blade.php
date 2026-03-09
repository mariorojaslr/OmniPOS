@extends('layouts.guest')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN VISUAL MULTIEMPRESA
|--------------------------------------------------------------------------
*/
$config = $empresa->configuracion ?? null;

$primary   = $config->color_primario   ?? '#2563eb';
$secondary = $config->color_secundario ?? '#16a34a';

$mostrarLogo = $config->mostrar_logo ?? true;
$logo        = $config->logo ?? null;

$cartCount = session('cart') ? count(session('cart')) : 0;
@endphp


<style>

/* ================= CONTAINER ================= */

.catalog-app{
    width:100%;
    max-width:1600px;
    margin:auto;
    padding:14px;
}


/* ================= HEADER ================= */

.catalog-header{
    background: {{ $primary }};
    color:white;
    border-radius:14px;
    padding:14px 18px;
}

.catalog-title{
    font-weight:700;
    font-size:1.1rem;
}


/* ================= SEARCH ================= */

.catalog-search input{
    border-radius:30px;
    padding:12px 16px;
    border:1px solid #ddd;
}


/* ================= FILTER BUTTONS ================= */

.filterBtn{
    border-radius:20px;
    padding:6px 14px;
}

.filter-active{
    background: {{ $primary }} !important;
    color:white !important;
    border-color: {{ $primary }} !important;
}


/* ================= PRODUCT CARD ================= */

.product-card{
    border-radius:16px;
    transition:.25s;
    border:none;
    background:white;
}

.product-card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 24px rgba(0,0,0,.10);
}

.product-image{
    height:200px;
    object-fit:cover;
    border-top-left-radius:16px;
    border-top-right-radius:16px;
}


/* ================= BADGES ================= */

.badge-new{background:#0ea5e9;}
.badge-top{background:#f59e0b;color:#000;}
.badge-promo{background:#ef4444;}


/* ================= RESPONSIVE ================= */

@media (min-width:1400px){
    .col-xxl-2{width:16.66%;}
}

@media (max-width:768px){
    .product-image{height:160px;}
}

@media (max-width:480px){
    .product-image{height:140px;}
}

</style>



<div class="catalog-app">

    {{-- ================= HEADER ================= --}}
    <div class="catalog-header d-flex justify-content-between align-items-center shadow-sm">

        <div class="d-flex align-items-center gap-2">

            @if($mostrarLogo && $logo)
                <img src="{{ asset('storage/'.$logo) }}" height="38">
            @endif

            <div class="catalog-title">
                {{ $empresa->nombre_comercial }}
            </div>
        </div>

        {{-- ICONOS --}}
        <div class="d-flex gap-2 align-items-center">

            <button class="btn btn-light btn-sm" title="Mi cuenta">
                👤
            </button>

            <button class="btn btn-light btn-sm" title="Favoritos">
                ❤️
            </button>

            {{-- CARRITO FUNCIONAL --}}
            <a href="{{ route('cart.index') }}"
               class="btn btn-light btn-sm position-relative"
               title="Carrito">

                🛒

                @if($cartCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

        </div>
    </div>



    {{-- ================= TITLE ================= --}}
    <h4 id="sectionTitle" class="text-center fw-bold mt-3 mb-3">
        Todos los productos
    </h4>



    {{-- ================= SEARCH + FILTERS ================= --}}
    <div class="row mb-3 align-items-center">

        <div class="col-md-6 mb-2 catalog-search">
            <input type="text" id="search" class="form-control" placeholder="Buscar producto...">
        </div>

        <div class="col-md-6 text-md-end">

            <button class="btn btn-outline-primary btn-sm filterBtn filter-active" data-filter="all">
                Todos
            </button>

            <button class="btn btn-outline-info btn-sm filterBtn" data-filter="new">
                🆕 Nuevos
            </button>

            <button class="btn btn-outline-warning btn-sm filterBtn" data-filter="top">
                🔥 Más vendidos
            </button>

            <button class="btn btn-outline-danger btn-sm filterBtn" data-filter="promo">
                💰 Promos
            </button>

        </div>
    </div>



    {{-- ================= PRODUCT GRID ================= --}}
    <div class="row g-3" id="productGrid">

        @foreach($products as $product)

        @php
            $mainImage = $product->images->where('is_main',1)->first()
                        ?? $product->images->first();

            $isNew   = $product->created_at->diffInDays(now()) <= 7;
            $isTop   = ($product->sales ?? 0) > 10;
            $isPromo = $product->price < 1000;
        @endphp


        <div class="
            col-6
            col-sm-6
            col-md-4
            col-lg-3
            col-xl-3
            col-xxl-2
            product-item"
             data-new="{{ $isNew ? 1 : 0 }}"
             data-top="{{ $isTop ? 1 : 0 }}"
             data-promo="{{ $isPromo ? 1 : 0 }}">

            <div class="card product-card h-100">

                {{-- IMAGE --}}
                @if($mainImage)
                    <img src="{{ asset('storage/'.$mainImage->path) }}" class="w-100 product-image">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center product-image">
                        Sin imagen
                    </div>
                @endif


                <div class="card-body d-flex flex-column">

                    <div class="fw-bold">
                        {{ $product->name }}
                    </div>

                    <div class="text-success fw-bold mb-1">
                        $ {{ number_format($product->price,2) }}
                    </div>


                    {{-- BADGES --}}
                    <div class="mb-2">

                        @if($isNew)
                            <span class="badge badge-new">Nuevo</span>
                        @endif

                        @if($isTop)
                            <span class="badge badge-top">Top</span>
                        @endif

                        @if($isPromo)
                            <span class="badge badge-promo">Promo</span>
                        @endif

                    </div>


                    <div class="mt-auto">
                        <a href="{{ route('catalog.show', [$empresa, $product]) }}"
                           class="btn btn-sm w-100"
                           style="background:{{ $secondary }}; color:white;">
                            Ver producto
                        </a>
                    </div>

                </div>
            </div>
        </div>

        @endforeach

    </div>
</div>



    {{-- MENSAJE DE ESTADO VACÍO (Oculto por defecto) --}}
    <div id="emptyState" class="text-center py-5" style="display: none;">
        <div class="mb-3" style="font-size: 3rem; opacity: 0.5;">📭</div>
        <h5 class="text-muted fw-bold">No hay productos que coincidan con este filtro.</h5>
        <p class="text-secondary small">Intenta buscar otro término o cambiar de categoría.</p>
        <button class="btn btn-outline-primary btn-sm mt-3" onclick="document.querySelector('[data-filter=\'all\']').click()">
            Ver todos los productos
        </button>
    </div>

</div>

{{-- ================= SEARCH + FILTER SCRIPT ================= --}}
<script>

const items = document.querySelectorAll('.product-item');
const sectionTitle = document.getElementById('sectionTitle');
const emptyState = document.getElementById('emptyState');

function checkEmptyState() {
    let hasVisible = false;
    items.forEach(item => {
        if(item.style.display !== 'none') hasVisible = true;
    });
    emptyState.style.display = hasVisible ? 'none' : 'block';
}

// SEARCH
document.getElementById('search').addEventListener('keyup', function(){
    const value = this.value.toLowerCase();
    items.forEach(item=>{
        item.style.display = item.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
    checkEmptyState();
});

// FILTERS
document.querySelectorAll('.filterBtn').forEach(btn=>{
    btn.addEventListener('click', function(){
        document.querySelectorAll('.filterBtn').forEach(b=>b.classList.remove('filter-active'));
        this.classList.add('filter-active');

        const filter = this.dataset.filter;

        items.forEach(item=>{
            if(filter === 'all'){
                item.style.display = '';
                return;
            }
            const val = item.dataset[filter];
            item.style.display = (val == 1) ? '' : 'none';
        });

        if(filter==='all') sectionTitle.innerText="Todos los productos";
        if(filter==='new') sectionTitle.innerText="Productos nuevos";
        if(filter==='top') sectionTitle.innerText="Más vendidos";
        if(filter==='promo') sectionTitle.innerText="Productos en promoción";

        checkEmptyState();
    });
});
</script>

@endsection
