@extends('layouts.guest')

@php
    $config = $empresa->config ?? null;
    $primary = $config->color_primary ?? '#3b82f6';
@endphp

@section('content')
<style>
    :root {
        --oled-bg: #000000;
        --oled-card: #0a0a0a;
        --oled-border: rgba(255, 255, 255, 0.1);
        --accent-glow: rgba(59, 130, 246, 0.3);
    }

    body {
        background-color: var(--oled-bg) !important;
        color: #e5e7eb;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .success-container {
        max-width: 600px;
        margin: 60px auto;
        padding: 20px;
    }

    .oled-card {
        background: var(--oled-card);
        border: 1px solid var(--oled-border);
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 10px 50px rgba(0,0,0,0.8);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid rgba(16, 185, 129, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        color: #10b981;
        font-size: 3rem;
        box-shadow: 0 0 30px rgba(16, 185, 129, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .order-id {
        display: inline-block;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--oled-border);
        padding: 10px 25px;
        border-radius: 12px;
        font-weight: 800;
        letter-spacing: 2px;
        color: #fff;
        margin-bottom: 25px;
        font-size: 1.2rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .btn-return {
        background: #fff;
        color: #000;
        padding: 15px 40px;
        border-radius: 100px;
        font-weight: 800;
        text-decoration: none;
        display: inline-block;
        margin-top: 40px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-return:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(255,255,255,0.2);
        background: #f8f9fa;
    }

    .whatsapp-btn {
        background: #25D366;
        color: white;
        padding: 15px 30px;
        border-radius: 100px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .whatsapp-btn:hover {
        transform: translateY(-3px);
        filter: brightness(1.1);
        color: white;
    }

    .celebration {
        position: absolute;
        top: -50px;
        right: -50px;
        opacity: 0.05;
        font-size: 10rem;
        transform: rotate(15deg);
    }
</style>

<div class="success-container">
    <div class="oled-card">
        <div class="celebration">🛒</div>
        
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1 class="display-6 fw-extrabold text-white mb-2">¡Pedido Recibido!</h1>
        <p class="text-secondary mb-4">Gracias por tu compra en <b>{{ $empresa->nombre_comercial }}</b>. <br>Nos pondremos en contacto contigo pronto.</p>

        <div class="order-id">ORDEN #{{ $order->id }}</div>

        <div class="mt-4 text-start bg-dark bg-opacity-25 p-4 rounded-4" style="border: 1px solid var(--oled-border)">
            <h6 class="text-secondary text-uppercase fw-bold small letter-spacing-1 mb-3">Resumen de tu pedido</h6>
            
            <div class="summary-item">
                <span class="text-secondary">Productos:</span>
                <span class="text-white fw-bold">{{ $order->items->sum('cantidad') }} unidades</span>
            </div>
            
            <div class="summary-item">
                <span class="text-secondary">Importe Total:</span>
                <span class="text-white fw-bold">$ {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            <div class="summary-item border-0">
                <span class="text-secondary">Forma de Pago:</span>
                <span class="text-info fw-bold text-uppercase small">{{ $order->metodo_pago }}</span>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->telefono ?? '') }}?text=Hola!%20Acabo%20de%20realizar%20un%20pedido%20(%23{{ $order->id }}).%20" 
               class="whatsapp-btn">
                <i class="fab fa-whatsapp fs-4"></i> Consultar por WhatsApp
            </a>
            
            <a href="{{ route('catalog.index', $empresa->id) }}" class="btn-return">
                Seguir Comprando
            </a>
        </div>

    </div>

    <div class="text-center mt-5">
        <p class="small text-secondary fw-bold text-uppercase letter-spacing-1">
            Multipro - Soluciones Digitales
        </p>
    </div>
</div>
@endsection
