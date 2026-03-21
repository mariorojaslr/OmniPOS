@extends('layouts.guest')

@section('content')

@php
$config = $empresa->config ?? null;
$primary   = $config->color_primary   ?? '#2563eb';
$secondary = $config->color_secondary ?? '#16a34a';
$cartCount = session('cart') ? count(session('cart')) : 0;
$logo      = $config ? $config->logo_url : asset('images/logo_premium.png');
@endphp

<style>
    :root {
        --catalog-primary: {{ $primary }};
        --catalog-secondary: {{ $secondary }};
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(255, 255, 255, 0.8);
    }

    body {
        background-color: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
    }

    .catalog-wrapper {
        width: 100%;
        padding: 10px;
    }

    /* GLASS HEADER */
    .glass-header {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 15px 25px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        position: sticky;
        top: 20px;
        z-index: 1000;
    }

    .brand-id {
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        color: var(--catalog-primary);
        text-transform: uppercase;
    }

    /* SEARCH & FILTERS */
    .search-input {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--catalog-primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .filter-pill {
        border-radius: 30px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.875rem;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        transition: all 0.3s ease;
        margin-right: 8px;
        margin-bottom: 8px;
        cursor: pointer;
    }

    .filter-pill:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .filter-pill.active {
        background: var(--catalog-primary);
        color: white;
        border-color: var(--catalog-primary);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    /* PRODUCT CARDS */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
    }

    .premium-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .premium-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .image-container {
        position: relative;
        padding-top: 100%; /* Aspect ratio 1:1 */
        background: #f8fafc;
        overflow: hidden;
    }

    .card-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .premium-card:hover .card-img {
        transform: scale(1.1);
    }

    .card-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        z-index: 10;
        color: white;
    }

    .content-box {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 5px;
        line-height: 1.3;
        color: #1e293b;
        height: 2.6rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-price {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--catalog-secondary);
        margin-bottom: 12px;
    }

    .btn-action {
        border-radius: 12px;
        font-weight: 700;
        padding: 10px;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-view {
        background: #f1f5f9;
        color: #475569;
        border: none;
    }

    .btn-view:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* CART BUTTON FIXED */
    .cart-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 65px;
        height: 65px;
        background: var(--catalog-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        z-index: 2000;
        transition: all 0.3s ease;
        text-decoration: none !important;
    }

    .cart-float:hover {
        transform: scale(1.1) rotate(-5deg);
        color: white;
    }

    .cart-badge-float {
        position: absolute;
        top: 0;
        right: 0;
        background: #ef4444;
        color: white;
        font-size: 0.75rem;
        font-weight: 900;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    /* EMPTY STATE */
    #emptyState {
        background: white;
        border-radius: 20px;
        padding: 60px 40px;
        border: 1px dashed #cbd5e1;
    }

    @media (max-width: 576px) {
        .catalog-wrapper { padding: 5px; }
        .product-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
        .glass-header { margin-bottom: 10px; top: 10px; padding: 5px 10px; }
        .brand-id { font-size: 0.9rem; }
    }
</style>

<nav class="navbar navbar-light bg-white shadow-sm mb-4 sticky-top">
<div class="container-fluid px-4 d-flex justify-content-between">
<span class="navbar-brand fw-bold">
@if($logo)
    <img src="{{ $logo }}" height="35" class="me-2">
@endif
{{ $empresa->nombre_comercial }}
</span>

<div class="d-flex gap-2">
    {{-- Eliminamos el botón de Administración para clientes externos --}}
    <button class="btn btn-primary btn-sm px-3" onclick="shareCatalog()">
        <i class="bi bi-share"></i> Compartir
    </button>
</div>
</div>
</nav>

<div class="px-2">

    {{-- SEARCH & FILTERS --}}
    <div class="mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="position-relative mb-4">
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="🔍 Buscar por nombre, marca o modelo...">
                </div>

                <div class="d-flex flex-wrap justify-content-center">
                    <div class="filter-pill active" data-filter="all">Todos</div>
                    <div class="filter-pill" data-filter="new">🆕 Novedades</div>
                    <div class="filter-pill" data-filter="top">🔥 Más Vendidos</div>
                    <div class="filter-pill" data-filter="promo">⏳ Ofertas</div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRID --}}
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-3" id="productGrid">
        @forelse($products as $product)
            @php
                $mainImg = $product->images->where('is_main', 1)->first() ?? $product->images->first();
                $isNew = $product->created_at->diffInDays(now()) <= ($empresa->config->dias_nuevo ?? 7);
                $isTop = ($product->sales ?? 0) > 5;
                $isPromo = $product->price < ($empresa->config->precio_oferta ?? 5000);
            @endphp

            <div class="col product-item" 
                 data-name="{{ strtolower($product->name) }}"
                 data-new="{{ $isNew ? '1' : '0' }}"
                 data-top="{{ $isTop ? '1' : '0' }}"
                 data-promo="{{ $isPromo ? '1' : '0' }}">
                
                <div class="premium-card">
                    <div class="image-container">
                        @if($isNew) <span class="card-badge" style="background: #2dd4bf;">Nuevo</span> @endif
                        @if($isTop) <span class="card-badge" style="background: #f59e0b; left: auto; right: 12px;">Hot</span> @endif

                        @if($mainImg)
                            <img src="{{ $mainImg->url }}" class="card-img" alt="{{ $product->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="background: #f1f5f9;">
                                📷
                            </div>
                        @endif
                    </div>

                    <div class="content-box">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-price">$ {{ number_format($product->price, 0, ',', '.') }}</div>
                        
                        <div class="mt-auto">
                            <a href="{{ route('catalog.show', [$empresa, $product]) }}" class="btn btn-view btn-action w-100 mb-2">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Aún no hay productos disponibles en el catálogo.</p>
            </div>
        @endforelse
    </div>

    {{-- EMPTY STATE (HIDDEN) --}}
    <div id="emptyState" class="text-center mt-4" style="display: none;">
        <div style="font-size: 4rem;">🧐</div>
        <h4 class="fw-bold mt-3">Sin resultados</h4>
        <p class="text-muted">No encontramos lo que buscas. ¡Prueba con otro nombre!</p>
        <button class="btn btn-primary rounded-pill mt-3" onclick="resetFilters()">Mostrar todos</button>
    </div>

</div>

{{-- FLOATING CART --}}
<a href="{{ route('cart.index') }}" class="cart-float">
    <span style="font-size: 1.8rem;">🛒</span>
    @if($cartCount > 0)
        <span class="cart-badge-float">{{ $cartCount }}</span>
    @endif
</a>

<script>
    // Dinamic Filtering
    const searchInput = document.getElementById('searchInput');
    const filterPills = document.querySelectorAll('.filter-pill');
    const items = document.querySelectorAll('.product-item');
    const emptyState = document.getElementById('emptyState');

    function applyFilters() {
        const query = searchInput.value.toLowerCase();
        const activeFilter = document.querySelector('.filter-pill.active').dataset.filter;
        let visibleCount = 0;

        items.forEach(item => {
            const matchesSearch = item.dataset.name.includes(query);
            const matchesFilter = activeFilter === 'all' || item.dataset[activeFilter] === '1';

            if (matchesSearch && matchesFilter) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', applyFilters);

    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            filterPills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            applyFilters();
        });
    });

    function resetFilters() {
        searchInput.value = '';
        document.querySelector('[data-filter="all"]').click();
    }

    function shareCatalog() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $empresa->nombre_comercial }} - Catálogo',
                text: '¡Mira los productos de {{ $empresa->nombre_comercial }}!',
                url: window.location.href,
            }).catch(console.error);
        } else {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('¡Enlace del catálogo copiado! Ya puedes pegarlo en WhatsApp.');
            });
        }
    }
</script>

@endsection
