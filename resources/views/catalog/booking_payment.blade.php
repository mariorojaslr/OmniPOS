@extends('layouts.guest')

@php
    $config = $empresa->config ?? null;
    $primary   = '#3b82f6';
    $secondary = '#10b981';
      if ($config) {
          $primary = $config->color_primary ?? '#3b82f6';
          $secondary = $config->color_secondary ?? '#10b981';
      }
    $logo      = $config ? $config->logo_url : asset('images/logo_omnipos.png');
    $onlinePrice = (float) ($empresa->config_pasarelas['online_booking_price'] ?? 0);
@endphp

@section('content')
<style>
    :root {
        --bg-color: #030712;
        --card-bg: #0b0f19;
        --border-color: rgba(255, 255, 255, 0.08);
        --text-white: #ffffff;
        --text-secondary: #94a3b8;
        --clinical-blue: {{ $primary }};
        --clinical-green: {{ $secondary }};
    }

    body, html {
        background-color: var(--bg-color) !important;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: var(--text-white);
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .glass-nav {
        background: rgba(11, 15, 25, 0.85);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid var(--border-color);
        padding: 15px 40px;
        position: sticky;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .brand-id {
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .payment-container {
        max-width: 650px;
        margin: 40px auto 100px auto;
        padding: 0 20px;
    }

    .card-payment {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.65);
        overflow: hidden;
    }

    .mercadopago-header {
        background: linear-gradient(135deg, #009ee3, #005a87);
        padding: 25px;
        color: white;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }

    .form-control-payment {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid var(--border-color) !important;
        color: white !important;
        border-radius: 14px !important;
        padding: 14px 16px !important;
        transition: all 0.3s ease;
    }

    .form-control-payment:focus {
        border-color: #009ee3 !important;
        box-shadow: 0 0 10px rgba(0, 158, 227, 0.2) !important;
    }

    .form-control-payment::placeholder {
        color: rgba(255, 255, 255, 0.3) !important;
    }

    /* TARJETA VIRTUAL INTERACTIVA */
    .virtual-card {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 25px;
        position: relative;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        overflow: hidden;
        margin: 10px auto 30px auto;
        max-width: 380px;
        transition: all 0.3s ease;
    }

    .virtual-card-chip {
        width: 48px;
        height: 36px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 8px;
    }
</style>

<div class="glass-nav">
    <div class="brand-id d-flex align-items-center gap-3">
        @if($logo) <img src="{{ $logo }}" height="38" class="rounded shadow-sm"> @endif
        <span class="text-white">{{ $empresa->nombre_comercial }} — Pago Seguro</span>
    </div>
    <span class="badge bg-info bg-opacity-25 text-info rounded-pill px-3 py-2 fw-bold font-monospace border border-info border-opacity-25 d-none d-sm-inline-block">
        ● Conexión Cifrada SSL
    </span>
</div>

<div class="payment-container">
    
    <div class="card-payment">
        <!-- MercadoPago Header -->
        <div class="mercadopago-header">
            <h5 class="fw-bold mb-0 text-white"><i class="bi bi-shield-lock-fill me-2"></i> PROCESAMIENTO DE PAGO SEGURO</h5>
            <small class="opacity-75">Transacción cifrada y gestionada por la pasarela de pagos</small>
        </div>

        <div class="p-4 p-md-5">
            <!-- Detalles de Facturación del Turno -->
            <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-4 bg-dark bg-opacity-40">
                <div class="x-small fw-bold text-uppercase text-muted mb-2"><i class="bi bi-receipt me-1"></i> Resumen de Reserva</div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-secondary">Paciente:</span>
                    <span class="small text-white fw-bold">{{ $turno->cliente?->name }} (DNI: {{ $turno->cliente?->document }})</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-secondary">Especialista:</span>
                    <span class="small text-white fw-bold">Dr. {{ $turno->user?->name }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-secondary">Consulta / Práctica:</span>
                    <span class="small text-white fw-bold">{{ $turno->servicio?->nombre }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-secondary">Fecha y Hora:</span>
                    <span class="small text-white fw-bold">{{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }} a las {{ $turno->hora_inicio }} hs</span>
                </div>
                <hr class="border-secondary border-opacity-25 my-2">
                <div class="d-flex justify-content-between align-items-center pt-2">
                    <span class="fw-bold text-primary">Importe Coseguro:</span>
                    <span class="fw-extrabold text-success fs-4">$ {{ number_format($onlinePrice, 2, ',', '.') }}</span>
                </div>
            </div>

            <!-- TARJETA VIRTUAL INTERACTIVA -->
            <div class="virtual-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="virtual-card-chip"></div>
                    <div class="text-white fw-extrabold fs-4 italic">VISA</div>
                </div>
                
                <div class="text-white fs-4 fw-bold font-monospace tracking-widest mb-4 text-center" id="vcard-number">•••• •••• •••• ••••</div>
                
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div class="text-start">
                        <div class="x-small text-muted text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">Titular de la Tarjeta</div>
                        <div class="small fw-bold text-white text-uppercase tracking-wider" id="vcard-name">JUAN PEREZ</div>
                    </div>
                    <div class="text-end">
                        <div class="x-small text-muted text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">Vence</div>
                        <div class="small fw-bold text-white font-monospace" id="vcard-expiry">MM/AA</div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Tarjeta -->
            <form action="{{ route('catalog.booking.payment-process', [$empresa->slug, $turno->id]) }}" method="POST" id="payment-gateway-form">
                @csrf
                
                <h6 class="fw-bold text-white mb-3 border-bottom border-secondary border-opacity-10 pb-2"><i class="bi bi-credit-card-2-front text-primary me-2"></i> Datos de Tarjeta de Débito o Crédito</h6>

                <div class="mb-3">
                    <label class="small text-secondary fw-bold mb-1">Número de Tarjeta</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary border-opacity-25 text-muted"><i class="bi bi-credit-card"></i></span>
                        <input type="text" id="card-number-input" maxlength="19" class="form-control form-control-payment shadow-none" placeholder="XXXX XXXX XXXX XXXX" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary fw-bold mb-1">Nombre Impreso en la Tarjeta</label>
                    <input type="text" id="card-name-input" class="form-control form-control-payment shadow-none" placeholder="Ej: JUAN PEREZ" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="small text-secondary fw-bold mb-1">Vencimiento</label>
                        <input type="text" id="card-expiry-input" maxlength="5" class="form-control form-control-payment shadow-none" placeholder="MM/AA" required>
                    </div>
                    <div class="col-6">
                        <label class="small text-secondary fw-bold mb-1">Cód. Seguridad (CVV)</label>
                        <input type="text" maxlength="4" class="form-control form-control-payment shadow-none" placeholder="123" required>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="button" class="btn btn-lg py-3 rounded-pill fw-bold text-white fs-5" style="background-color: #009ee3; border-color: #009ee3;" onclick="processPaymentSimulation()">
                        <i class="bi bi-shield-check me-2"></i> PROCESAR PAGO Y CONFIRMAR TURNO
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Dinámica interactiva de la tarjeta virtual
    document.getElementById('card-number-input').addEventListener('input', function(e) {
        let val = e.target.value.replace(/\D/g, '');
        // Agrupar de a 4
        let formatted = '';
        for (let i = 0; i < val.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formatted += ' ';
            }
            formatted += val[i];
        }
        e.target.value = formatted;
        document.getElementById('vcard-number').innerText = formatted || '•••• •••• •••• ••••';
    });

    document.getElementById('card-name-input').addEventListener('input', function(e) {
        document.getElementById('vcard-name').innerText = e.target.value.toUpperCase() || 'JUAN PEREZ';
    });

    document.getElementById('card-expiry-input').addEventListener('input', function(e) {
        let val = e.target.value.replace(/\D/g, '');
        if (val.length > 2) {
            val = val.substring(0, 2) + '/' + val.substring(2, 4);
        }
        e.target.value = val;
        document.getElementById('vcard-expiry').innerText = val || 'MM/AA';
    });

    function processPaymentSimulation() {
        // Validar campos nativos
        const form = document.getElementById('payment-gateway-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: '⚡ Procesando Transacción...',
            html: 'Autorizando fondos de coseguro de forma segura con el banco emisor.<br><div class="progress mt-3 bg-dark" style="height: 6px;"><div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 100%"></div></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            background: '#0b0f19',
            color: '#fff',
            timer: 2500,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                icon: 'success',
                title: '¡Pago Aprobado!',
                text: 'Su comprobante fiscal ha sido enviado y el turno se ha agendado exitosamente.',
                background: '#0b0f19',
                color: '#fff',
                confirmButtonColor: '#009ee3',
                timer: 1800,
                showConfirmButton: false
            }).then(() => {
                form.submit();
            });
        });
    }
</script>
@endsection
