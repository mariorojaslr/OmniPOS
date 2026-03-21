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

    .catalog-fluid-container {
        width: 100%;
        max-width: 1000px;
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

    .cart-wrapper {
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(10px);
        padding: 40px;
        border-radius: 32px;
        border: 1px solid rgba(255,255,255,0.4);
        box-shadow: 0 20px 50px rgba(0,0,0,0.05);
    }

    .table { margin-bottom: 0; }
    .table thead th { border: none; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; padding: 15px; }
    .table tbody td { padding: 20px 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

    .btn-checkout {
        background: var(--catalog-primary);
        color: white;
        border-radius: 16px;
        padding: 15px 30px;
        font-weight: 800;
        text-transform: uppercase;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-checkout:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        color: white;
    }
</style>

<div class="glass-nav w-100 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <a href="{{ url()->previous() }}" class="btn btn-light rounded-circle me-3 shadow-sm border">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="brand-id h5 m-0 fw-bold">Mi Carrito</div>
    </div>
</div>

<div class="catalog-fluid-container mt-4">
    <div class="cart-wrapper">
        @if(session('cart') && count(session('cart')) > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-end">Precio</th>
                            <th class="text-end">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $id => $item)
                            @php
                                $lineTotal = $item['price'] * $item['quantity'];
                                $total += $lineTotal;
                            @endphp
                            <tr data-price="{{ $item['price'] }}">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if(!empty($item['image']))
                                            <img src="{{ $item['image'] }}" width="60" height="60" class="rounded-3 shadow-sm" style="object-fit: cover;">
                                        @endif
                                        <div class="fw-bold text-dark">{{ $item['name'] }}</div>
                                    </div>
                                </td>
                                <td width="120">
                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                               min="1" max="{{ $item['stock'] ?? 9999 }}" 
                                               class="form-control form-control-sm text-center fw-bold rounded-pill quantity-input"
                                               onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="text-end text-muted">$ {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-dark line-total">$ {{ number_format($lineTotal, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-link text-danger p-0"><i class="bi bi-trash3-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5 d-flex flex-column align-items-end">
                <div class="text-muted mb-1">Costo Total</div>
                <div class="h2 fw-bold text-dark mb-4">$ <span id="cartTotal">{{ number_format($total, 0, ',', '.') }}</span></div>
                
                <div class="d-flex gap-3">
                    <a href="{{ route('catalog.index', $empresa ?? '') }}" class="btn btn-light rounded-pill px-4">Seguir comprando</a>
                    <a href="{{ route('checkout.index') }}" class="btn btn-checkout">Finalizar Compra <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="fs-1 mb-3">🛒</div>
                <h4 class="fw-bold">Tu carrito está vacío</h4>
                <p class="text-muted">Parece que aún no has agregado productos.</p>
                <a href="{{ route('catalog.index', $empresa ?? '') }}" class="btn btn-primary rounded-pill px-5 mt-3">Ver catálogo</a>
            </div>
        @endif
    </div>
</div>

@endsection

