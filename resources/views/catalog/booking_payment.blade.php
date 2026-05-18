@extends('layouts.guest')

@php
    $config = $empresa->config ?? null;
    $primary   = $config->color_primary   ?? '#2563eb';
    $secondary = $config->color_secondary ?? '#16a34a';
    $logo      = $config ? $config->logo_url : asset('images/logo_omnipos.png');
    $onlinePrice = (float) ($empresa->config_pasarelas['online_booking_price'] ?? 0);
@endphp

@section('content')
<style>
    :root {
        --catalog-primary: {{ $primary }};
        --catalog-secondary: {{ $secondary }};
        --bg-color: #f1f5f9;
        --card-bg: #ffffff;
        --text-color: #1e293b;
    }

    body, html {
        background-color: var(--bg-color) !important;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: var(--text-color);
    }

    .glass-nav {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e8f0;
        padding: 15px 50px;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .brand-id {
        font-size: 1.3rem;
        font-weight: 800;
        color: #0f172a;
    }

    .payment-container {
        max-width: 650px;
        margin: 40px auto 100px auto;
        padding: 0 20px;
    }

    .card-payment {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .mercadopago-header {
        background: #009ee3; /* MercadoPago Light Blue */
        padding: 20px;
        color: white;
        text-align: center;
        border-bottom: none;
    }

    .form-control-payment {
        border-radius: 12px;
        padding: 12px 15px;
        border: 1px solid #cbd5e1;
    }

    .form-control-payment:focus {
        border-color: #009ee3;
        box-shadow: 0 0 0 0.25rem rgba(0, 158, 227, 0.2);
    }
</style>

<div class="glass-nav">
    <div class="brand-id d-flex align-items-center gap-3">
        @if($logo) <img src="{{ $logo }}" height="40" class="rounded shadow-sm"> @endif
        {{ $empresa->nombre_comercial }} — Pasarela de Pago
    </div>
</div>

<div class="payment-container">
    
    <div class="card-payment">
        <!-- MercadoPago Header -->
        <div class="mercadopago-header">
            <h5 class="fw-bold mb-0 text-white"><i class="bi bi-shield-lock-fill me-2"></i> PAGO SEGURO DE TURNO</h5>
            <small class="opacity-75">Procesado de forma cifrada por MercadoPago</small>
        </div>

        <div class="p-4">
            <!-- Detalles de Facturación del Turno -->
            <div class="p-3 bg-light rounded-4 border border-secondary border-opacity-10 mb-4">
                <div class="x-small fw-bold text-uppercase text-muted mb-2"><i class="bi bi-receipt me-1"></i> Resumen de Reserva</div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-dark">Paciente:</span>
                    <span class="small text-muted">{{ $turno->cliente?->name }} (Doc: {{ $turno->cliente?->document }})</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-dark">Especialista:</span>
                    <span class="small text-muted">Dr. {{ $turno->user?->name }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-dark">Consulta / Práctica:</span>
                    <span class="small text-muted">{{ $turno->servicio?->nombre }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-dark">Fecha y Hora:</span>
                    <span class="small text-muted">{{ $turno->fecha }} a las {{ $turno->hora_inicio }} hs</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center pt-2">
                    <span class="fw-bold text-primary">Importe Coseguro:</span>
                    <span class="fw-extrabold text-success fs-5">$ {{ number_format($onlinePrice, 2, ',', '.') }}</span>
                </div>
            </div>

            <!-- Formulario de Tarjeta -->
            <form action="{{ route('catalog.booking.payment-process', [$empresa->slug, $turno->id]) }}" method="POST">
                @csrf
                
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-credit-card-2-front text-primary me-2"></i> Datos de Tarjeta de Débito o Crédito</h6>

                <div class="mb-3">
                    <label class="small text-muted fw-bold mb-1">Número de Tarjeta</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-credit-card"></i></span>
                        <input type="text" class="form-control form-control-payment shadow-none" placeholder="XXXX XXXX XXXX XXXX" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted fw-bold mb-1">Nombre en la Tarjeta (Tal como figura)</label>
                    <input type="text" class="form-control form-control-payment shadow-none" placeholder="Ej: JUAN PEREZ" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="small text-muted fw-bold mb-1">Vencimiento</label>
                        <input type="text" class="form-control form-control-payment shadow-none" placeholder="MM/AA" required>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted fw-bold mb-1">Código de Seg. (CVV)</label>
                        <input type="text" class="form-control form-control-payment shadow-none" placeholder="123" required>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-success fw-bold py-3 rounded-4 shadow-sm fs-5" style="background-color: #009ee3; border-color: #009ee3;">
                        <i class="bi bi-shield-check me-2"></i> REALIZAR PAGO Y AGENDAR
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
