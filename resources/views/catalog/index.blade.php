@extends('layouts.guest')
@php
    $isCatalog = true;
    $config = $empresa->config ?? null;
    $primary   = $config->color_primary   ?? '#2563eb';
    $secondary = $config->color_secondary ?? '#16a34a';
    $cartCount = session('cart') ? count(session('cart')) : 0;
    $logo      = $config ? $config->logo_url : asset('images/logo_premium.png');
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

    /* GLOBAL CONTAINER FOR FLUID OVERRIDE */
    .catalog-fluid-container {
        width: 100%;
        max-width: 100vw;
        overflow-x: hidden;
        margin: 0;
        padding: 0 40px 100px 40px; /* Padding balanceado */
    }

    /* GLASS NAVBAR */
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

    .brand-id {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #1e293b;
        text-transform: none;
    }

    /* SEARCH & FILTERS PREMIUM */
    .search-wrapper {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 50px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .search-input {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 15px 25px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    /* PRODUCT CARDS */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 30px;
        width: 100% !important;
    }

    @media (max-width: 768px) {
        .catalog-fluid-container { padding: 0 15px 80px 15px; }
        .glass-nav { padding: 10px 15px; }
        .product-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; }
        .search-wrapper { padding: 20px; border-radius: 15px; }
    }
    .filter-pill {
        border-radius: 30px;
        padding: 10px 25px;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-right: 12px;
        margin-bottom: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-pill:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .filter-pill.active {
        background: var(--catalog-primary);
        color: white;
        border-color: var(--catalog-primary);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.25);
    }

    .premium-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.03);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .premium-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px -12px rgba(0,0,0,0.12);
    }

    .image-container {
        position: relative;
        padding-top: 100%;
        background: #f8fafc;
        overflow: hidden;
    }

    .card-img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }

    .premium-card:hover .card-img {
        transform: scale(1.15);
    }

    .card-badge {
        position: absolute;
        top: 15px; left: 15px;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        z-index: 10;
        color: white;
        backdrop-filter: blur(4px);
    }

    .content-box {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.4;
        color: #1e293b;
        height: 2.8rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-price {
        font-size: 1.3rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 20px;
    }

    .btn-action {
        border-radius: 14px;
        font-weight: 700;
        padding: 12px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
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
</style>

<div class="glass-nav w-100 d-flex justify-content-between align-items-center">
    <div class="brand-id">
        @if($logo)
            <img src="{{ $logo }}" height="40" class="me-2 rounded-2">
        @endif
        {{ $empresa->nombre_comercial }}
    </div>

    <div class="d-flex gap-3">
        <button class="btn btn-light btn-sm rounded-pill px-4 shadow-sm border" onclick="shareCatalog()">
            <i class="bi bi-share"></i> Compartir
        </button>
        <a href="{{ route('cart.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm position-relative">
            <i class="bi bi-cart-fill"></i> Carrito
            @if($cartCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
    </div>
</div>

<div class="catalog-fluid-container">

    {{-- SEARCH & FILTERS --}}
    <div class="search-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="position-relative mb-4">
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="🔍 Buscar por nombre, marca o modelo...">
                </div>

                <div class="d-flex flex-wrap justify-content-center">
                    <div class="filter-pill active" data-filter="all">📦 Todos</div>
                    <div class="filter-pill" data-filter="new">🆕 Novedades</div>
                    <div class="filter-pill" data-filter="top">🔥 Más Vendidos</div>
                    <div class="filter-pill" data-filter="promo">⏳ Ofertas</div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRID FLUIDO INTELIGENTE --}}
    <div class="product-grid" id="productGrid">
        @forelse($products as $product)
            @php
                $mainImg = $product->images->where('is_main', 1)->first() ?? $product->images->first();
                $isNew = $product->created_at->diffInDays(now()) <= ($empresa->config->dias_nuevo ?? 7);
                $isTop = ($product->sales ?? 0) > 5;
                $isPromo = $product->price < ($empresa->config->precio_oferta ?? 5000);
            @endphp

            <div class="product-item" 
                 data-name="{{ strtolower($product->name) }}"
                 data-new="{{ $isNew ? '1' : '0' }}"
                 data-top="{{ $isTop ? '1' : '0' }}"
                 data-promo="{{ $isPromo ? '1' : '0' }}">
                
                <div class="premium-card shadow-sm h-100">
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
