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
        --bg-color: #f8fafc;
        --card-bg: #ffffff;
        --text-color: #1e293b;
    }

    body, html {
        background-color: var(--bg-color) !important;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-color);
        overflow-x: hidden;
    }

    /* NAVBAR LIMPIA Y CLARA */
    .glass-nav {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 50px;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .brand-id {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
    }

    /* CONTENEDOR ANCHO COMPLETO */
    .catalog-main {
        width: 100%;
        padding: 40px 50px 100px 50px;
        flex-grow: 1; /* Para que complete el alto de la pantalla si hay pocos productos */
        display: flex;
        flex-direction: column;
    }

    /* BUSCADOR CLARO */
    .search-wrapper {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 50px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        text-align: center;
    }

    .search-input-ultra {
        background: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px;
        padding: 15px 25px;
        font-size: 1.1rem;
        color: #1e293b !important;
        max-width: 800px;
        margin: 0 auto;
    }
    .search-input-ultra:focus { 
        border-color: var(--catalog-primary) !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
    }

    .filter-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-top: 25px;
    }

    .filter-pill {
        border-radius: 30px;
        padding: 10px 24px;
        font-weight: 700;
        font-size: 0.9rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        cursor: pointer;
        transition: 0.3s;
    }
    .filter-pill:hover { border-color: var(--catalog-primary); color: var(--catalog-primary); }
    .filter-pill.active {
        background: var(--catalog-primary);
        color: white;
        border-color: var(--catalog-primary);
    }

    /* GRID PRODUCTOS */
    .product-grid-max {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    .premium-card-ultra {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .premium-card-ultra:hover { 
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: var(--catalog-primary);
    }

    .image-box { position: relative; padding-top: 100%; background: #f8fafc; }
    .card-img-full { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; }

    .content-area { padding: 25px; display: flex; flex-direction: column; flex-grow: 1; text-align: center; }
    .product-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 15px; height: 2.8rem; overflow: hidden; line-height: 1.4; }
    .product-cost { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: auto; }

    .btn-buy-ultra {
        background: var(--catalog-primary);
        color: #fff;
        border-radius: 12px;
        font-weight: 700;
        padding: 12px;
        border: none;
        margin-top: 20px;
        text-align: center;
        text-decoration: none;
    }
    .btn-buy-ultra:hover { filter: brightness(1.1); transform: scale(1.02); color: #fff; }

    /* RESPONSIVE MEDIA QUERIES */
    @media (max-width: 768px) {
        .glass-nav {
            padding: 15px 20px;
        }
        .brand-id {
            font-size: 1.2rem;
        }
        .catalog-main {
            padding: 20px 20px 80px 20px;
        }
        .search-wrapper {
            padding: 20px;
            margin-bottom: 30px;
        }
        .search-input-ultra {
            font-size: 1rem;
            padding: 12px 15px;
        }
        .product-grid-max {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        .content-area {
            padding: 15px;
        }
        .product-title {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .product-cost {
            font-size: 1.1rem;
        }
        .btn-buy-ultra {
            padding: 10px;
            font-size: 0.85rem;
        }
    }

    /* PAGINACIÓN PREMIUM */
    .pagination {
        gap: 8px;
    }
    .page-link {
        border-radius: 10px !important;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        font-weight: 700;
        padding: 10px 18px;
        transition: 0.3s;
    }
    .page-link:hover {
        background-color: var(--catalog-primary);
        color: white;
        border-color: var(--catalog-primary);
    }
    .page-item.active .page-link {
        background-color: var(--catalog-primary);
        border-color: var(--catalog-primary);
    }
    .pagination svg {
        width: 1.5rem !important;
        height: 1.5rem !important;
    }
</style>

<div class="glass-nav">
    <div class="brand-id d-flex align-items-center gap-3">
        @if($logo) <img src="{{ $logo }}" height="40" class="rounded shadow-sm"> @endif
        {{ $empresa->nombre_comercial }}
    </div>
    <a href="{{ route('cart.index') }}" class="btn btn-primary d-flex align-items-center gap-2 rounded-pill px-4 py-2 fw-bold shadow-sm">
        <i class="bi bi-cart3"></i> CARRITO
        @if($cartCount > 0)
            <span class="badge rounded-pill bg-danger border border-white">
                {{ $cartCount }}
            </span>
        @endif
    </a>
</div>

<div class="catalog-main">

    <div class="search-wrapper">
        <input type="text" id="searchInput" class="form-control search-input-ultra" placeholder="🔍 Buscar productos...">
        <div class="filter-pills">
            <div class="filter-pill active" data-filter="all">📦 TODOS</div>
            <div class="filter-pill" data-filter="new">🆕 NOVEDADES</div>
            <div class="filter-pill" data-filter="top">🔥 MÁS VENDIDOS</div>
            <div class="filter-pill" data-filter="promo">⏳ OFERTAS</div>
        </div>
    </div>

    <div class="product-grid-max" id="productGrid">
        @forelse($products as $product)
            @php
                $mainImg = $product->images->where('is_main', 1)->first() ?? $product->images->first();
                $isNew = $product->created_at->diffInDays(now()) <= ($empresa->config->dias_nuevo ?? 7);
            @endphp

            <div class="product-item">
                <div class="premium-card-ultra">
                    <div class="image-box">
                        @if($isNew) <span class="badge bg-success position-absolute m-3 px-3 py-2 fw-bold rounded-pill">NUEVO</span> @endif
                        @if($mainImg) <img src="{{ $mainImg->url }}" class="card-img-full" alt="{{ $product->name }}">
                        @else <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">📦</div> @endif
                    </div>
                    <div class="content-area">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <div class="product-cost">$ {{ number_format($product->price, 0, ',', '.') }}</div>
                        <a href="{{ route('catalog.show', [$empresa, $product]) }}" class="btn-buy-ultra">VER DETALLE</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5" style="grid-column: 1 / -1;">
                <p class="text-secondary fw-bold fs-4">AÚN NO HAY PRODUCTOS DISPONIBLES EN ESTA SECCIÓN.</p>
            </div>
        @endforelse
    </div>

    {{-- PAGINACIÓN PREMIUM --}}
    <div class="mt-5 d-flex justify-content-center">
        {{ $products->links() }}
    </div>

    <div id="emptyState" class="text-center py-5" style="display: none;">
        <h2 class="fw-bold text-slate-800">SIN RESULTADOS</h2>
        <button class="btn btn-outline-primary rounded-pill px-5 mt-4 fw-bold" onclick="location.href='{{ route('catalog.index', $empresa) }}'">MOSTRAR TODO</button>
    </div>

</div>

<script>
    // Los filtros ahora se manejan por URL (Servidor), así que el JS solo se encarga de cambiar la URL
    const filterPills = document.querySelectorAll('.filter-pill');
    
    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            const filter = pill.dataset.filter;
            const url = new URL(window.location.href);
            if (filter === 'all') {
                url.searchParams.delete('filtro');
            } else {
                url.searchParams.set('filtro', filter);
            }
            window.location.href = url.toString();
        });
    });

    // Mantener estado activo del filtro actual
    const currentFilter = "{{ $filtro ?? 'all' }}";
    document.querySelectorAll('.filter-pill').forEach(p => {
        if (p.dataset.filter === currentFilter) p.classList.add('active');
        else p.classList.remove('active');
    });
</script>

@endsection
