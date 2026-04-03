@extends('layouts.guest')

@php
    $order->load('items.product');
    $config = $empresa->config ?? null;
    $primary = $config->color_primary ?? '#2563eb';
    $cartCount = 0;
    $isCatalog = true;
@endphp

@section('content')
<style>
    :root {
        --catalog-primary: {{ $primary }};
        --bg-light: #f8fafc;
        --card-white: #ffffff;
        --text-slate: #1e293b;
    }

    body {
        background-color: var(--bg-light) !important;
        color: var(--text-slate);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .success-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 50px 20px;
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .success-card {
        background: var(--card-white);
        border: 1px solid #e2e8f0;
        border-radius: 25px;
        padding: 50px;
        width: 100%;
        box-shadow: 0 15px 45px rgba(0,0,0,0.05);
        text-align: center;
    }

    .success-icon {
        font-size: 4rem;
        color: #22c55e;
        margin-bottom: 20px;
    }

    .order-number-badge {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        display: inline-block;
        padding: 10px 30px;
        border-radius: 15px;
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 20px 0;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        max-width: 900px;
        margin: 30px auto;
        text-align: left;
    }

    .summary-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 25px;
        border-radius: 15px;
    }

    .summary-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 800;
        color: #64748b;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .summary-value {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        margin-top: 40px;
    }

    .btn-action {
        flex: 1;
        min-width: 250px;
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
    }

    .btn-whatsapp { background: #22c55e; color: #fff; }
    .btn-whatsapp:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(34, 197, 94, 0.2); color: #fff; }

    .btn-catalog { background: #0f172a; color: #fff; }
    .btn-catalog:hover { transform: translateY(-3px); background: #1e293b; color: #fff; }

</style>

<div class="success-container">
    <div class="success-card">
        
        <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <h1 class="display-5 fw-bold mb-3">¡Pedido Recibido!</h1>
        <p class="fs-5 text-secondary">Gracias por tu compra en <span class="fw-bold text-dark">{{ $empresa->nombre_comercial }}</span>.</p>

        <div class="order-number-badge">
            ORDEN #{{ $order->id }}
        </div>

        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Resumen pedido</div>
                <div class="summary-value">
                    @php $qty = 0; foreach($order->items as $i) $qty += $i->cantidad; @endphp
                    {{ $qty }} {{ $qty == 1 ? 'unidad' : 'unidades' }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Importe Total</div>
                <div class="summary-value" style="color: var(--catalog-primary);">
                    $ {{ number_format($order->total, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Forma de Pago</div>
                <div class="summary-value text-uppercase">
                    {{ $order->metodo_pago }}
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->telefono) }}?text=Hola!%20Acabo%20de%20realizar%20el%20pedido%20%23{{ $order->id }}%20en%20{{ $empresa->nombre_comercial }}" target="_blank" class="btn-action btn-whatsapp shadow-sm">
                <i class="bi bi-whatsapp"></i> WhatsApp
            </a>
            <a href="{{ route('catalog.index', $empresa->slug ?? $empresa->id) }}" class="btn-action btn-catalog shadow-sm">
                 Seguir Comprando
            </a>
        </div>

    </div>
</div>

@endsection
