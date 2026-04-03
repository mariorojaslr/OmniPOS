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
        background-color: #f8fafc !important;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
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

    .brand-id {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #1e293b;
    }

    .catalog-fluid-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px 100px 40px;
    }

    .search-wrapper {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 40px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .search-input {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .search-input:focus { border-color: var(--catalog-primary); outline: none; }

    .filter-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .filter-pill {
        border-radius: 30px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-pill.active {
        background: var(--catalog-primary);
        color: white;
        border-color: var(--catalog-primary);
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 25px;
    }

    .premium-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .premium-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }

    .image-container { position: relative; padding-top: 100%; background: #f1f5f9; overflow: hidden; }
    .card-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; }

    .content-box { padding: 15px; display: flex; flex-direction: column; flex-grow: 1; }
    .product-name { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 10px; height: 2.4rem; overflow: hidden; }
    .product-price { font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-top: auto; }

    .btn-detail {
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        font-weight: 700;
        padding: 8px;
        border: none;
        margin-top: 15px;
        text-align: center;
        text-decoration: none;
    }
    .btn-detail:hover { background: #e2e8f0; color: #1e293b; }

</style>

<div class="glass-nav d-flex justify-content-between align-items-center">
    <div class="brand-id">
        @if($logo) <img src="{{ $logo }}" height="45" class="me-2 rounded shadow-sm"> @endif
        {{ $empresa->nombre_comercial }}
    </div>
    <a href="{{ route('cart.index') }}" class="btn btn-primary d-flex align-items-center gap-2 rounded-pill px-4 shadow-sm position-relative">
        <i class="bi bi-cart3"></i> Carrito
        @if($cartCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                {{ $cartCount }}
            </span>
        @endif
    </a>
</div>

<div class="catalog-fluid-container">

    <div class="search-wrapper">
        <input type="text" id="searchInput" class="form-control search-input" placeholder="🔍 Buscar por nombre...">
        <div class="filter-pills">
            <div class="filter-pill active" data-filter="all">📦 Todos</div>
            <div class="filter-pill" data-filter="new">🆕 Novedades</div>
            <div class="filter-pill" data-filter="top">🔥 Más Vendidos</div>
            <div class="filter-pill" data-filter="promo">⏳ Ofertas</div>
        </div>
    </div>

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
                
                <div class="premium-card shadow-sm">
                    <div class="image-container">
                        @if($isNew) <span class="badge bg-primary position-absolute m-2 px-2 py-1 fw-bold rounded-pill" style="z-index: 10;">NUEVO</span> @endif
                        @if($mainImg)
                            <img src="{{ $mainImg->url }}" class="card-img" alt="{{ $product->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="background: #f8fafc;">📦</div>
                        @endif
                    </div>

                    <div class="content-box">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-price">$ {{ number_format($product->price, 0, ',', '.') }}</div>
                        <a href="{{ route('catalog.show', [$empresa, $product]) }}" class="btn-detail">Ver Detalle</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Aun no hay productos disponibles.</p>
            </div>
        @endforelse
    </div>

    <div id="emptyState" class="text-center py-5" style="display: none;">
        <h3>Sin resultados</h3>
        <p class="text-muted">Prueba con otro nombre.</p>
        <button class="btn btn-primary rounded-pill px-4 mt-3" onclick="resetFilters()">Mostrar todo</button>
    </div>

</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const filterPills = document.querySelectorAll('.filter-pill');
    const items = document.querySelectorAll('.product-item');
    const emptyState = document.getElementById('emptyState');

    function applyFilters() {
        const query = searchInput.value.toLowerCase();
        if(!query && !document.querySelector('.filter-pill.active').dataset.filter === 'all') return;

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

    window.addEventListener('load', applyFilters);
</script>

@endsection
