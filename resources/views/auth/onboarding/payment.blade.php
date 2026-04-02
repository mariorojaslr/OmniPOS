@php $isBroad = true; @endphp
@extends('layouts.guest')

@section('content')
<div class="card bg-dark border-0 shadow-lg p-3 p-md-5 rounded-4 overflow-hidden position-relative">
    <!-- Subtle Glow -->
    <div class="position-absolute top-0 end-0 p-3 opacity-10">
        <i class="bi bi-wallet2 display-2 text-primary"></i>
    </div>

    <!-- Header -->
    <div class="text-center text-md-start mb-5">
        <div class="d-flex align-items-center gap-3 mb-3 justify-content-center justify-content-md-start">
            <img src="{{ asset('images/logo_premium.png') }}" alt="MultiPOS" class="shadow-lg rounded-circle" style="width: 50px; height: 50px; border: 1px solid #3b82f6;">
            <h2 class="text-white fw-bold mb-0">Estás a un paso 🚀</h2>
        </div>
        <p class="text-secondary">Activa tu cuenta comercial para comenzar a facturar.</p>
    </div>

    @if($user->status === 'prospecto')
        <div class="row g-4">
            <!-- COLUMNA 1: INFO DE PAGO -->
            <div class="col-lg-6 border-end border-secondary border-opacity-25 pe-lg-4">
                <h4 class="text-white mb-4 fs-5">1. Información de la Cuenta</h4>
                
                <div class="bg-black p-4 rounded-3 border border-secondary mb-4 shadow-sm">
                    <p class="text-secondary small text-uppercase mb-2 tracking-widest fw-bold">Plan Seleccionado</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-white mb-0">{{ $plan->nombre ?? 'Plan Empresa' }}</h5>
                        <span class="text-primary fw-bold fs-5">${{ number_format($plan->precio ?? 25000, 0, ',', '.') }}/mes</span>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-white small mb-3"><i class="bi bi-bank me-2 text-primary"></i> Transfiere el total a esta cuenta:</p>
                    <div class="bg-black p-4 rounded-3 border-start border-4 border-primary">
                        <div class="mb-3">
                            <p class="text-secondary mb-1 x-small text-uppercase fw-bold opacity-75">Proveedor de Servicios de Pago - Garpa S.A.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 id="cbu_text" class="text-white fs-6 ls-1 text-break mb-0 font-monospace">00000069700207937938884</h6>
                                <button onclick="copyToClipboard('cbu_text', event)" class="btn btn-sm btn-outline-primary rounded-circle" title="Copiar CVU">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-secondary mb-1 x-small text-uppercase fw-bold opacity-75">Alias</p>
                            <div class="d-flex justify-content-between align-items-center text-break">
                                <h6 id="alias_text" class="text-white fs-5 ls-1 mb-0 text-uppercase font-monospace tracking-wide">cenizo.bolisa.arq</h6>
                                <button onclick="copyToClipboard('alias_text', event)" class="btn btn-sm btn-outline-primary rounded-circle" title="Copiar Alias">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLUMNA 2: COMPROBANTE -->
            <div class="col-lg-6 ps-lg-4">
                <h4 class="text-white mb-4 fs-5">2. Confirmación de Pago</h4>
                <p class="text-secondary small mb-4">Sube un comprobante de la transferencia (Foto, captura o PDF) para habilitar tu acceso.</p>
                
                <form action="{{ route('register.payment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <div class="mb-2">
                            <input type="file" name="voucher" class="form-control bg-black text-secondary border-secondary rounded-4 p-3" id="inputVoucher" required>
                        </div>
                        <small class="text-secondary mt-1 d-block opacity-75"><i class="bi bi-file-image me-1 text-primary"></i> Formatos aceptados: PNG, JPG, PDF.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold text-uppercase ls-1 shadow-lg mt-2">
                        <i class="bi bi-cloud-upload me-2"></i> Enviar Comprobante
                    </button>
                    
                    <p class="text-center mt-4 small">
                        <a href="{{ route('logout.get') }}" class="text-secondary text-decoration-none opacity-50 hover-opacity-100">Cerrar Sesión / Salir</a>
                    </p>
                </form>
            </div>
        </div>

    @elseif($user->status === 'pendiente_pago')
        <div class="text-center py-5">
            <div class="mb-5 position-relative">
                <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status text-primary"></div>
                <div class="position-absolute top-50 start-50 translate-middle">
                    <i class="bi bi-check-lg text-primary fs-3"></i>
                </div>
            </div>
            <h4 class="text-white fw-bold mb-3">Voucher en Validación</h4>
            <p class="text-secondary mb-4 fs-6 col-lg-8 mx-auto">Hola {{ auth()->user()->name }}. Ya recibimos tu comprobante. Nuestro equipo lo está revisando para activar tu empresa hoy mismo.</p>
            
            <div class="bg-black p-3 rounded-pill mb-5 border border-primary border-opacity-25 d-inline-block px-5">
                <span class="text-primary small fw-bold"><i class="bi bi-shield-fill-check me-2"></i> TRÁMITE SEGURO EN CURSO</span>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ route('logout.get') }}" class="btn btn-outline-secondary rounded-pill px-5 py-3">Cerrar Sesión</a>
                <a href="https://wa.me/5491100000000" class="btn btn-success rounded-pill px-5 py-3"><i class="bi bi-whatsapp me-2"></i> Soporte VIP</a>
            </div>
        </div>
    @endif
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .x-small { font-size: 0.7rem; }
    .bg-dark { background-color: #121212 !important; }
    .bg-black { background-color: #000000 !important; }
    .form-control { color: #fff !important; }
    .form-control:focus { background-color: #000 !important; border-color: #3b82f6; box-shadow: 0 0 15px rgba(59, 130, 246, 0.2); }
    input[type="file"]::file-selector-button { background: #3b82f6; border: none; border-radius: 50px; color: white; padding: 5px 20px; margin-right: 15px; font-weight: 700; cursor: pointer; }
    .hover-opacity-100:hover { opacity: 1 !important; color: #fff !important; }
</style>

<script>
    function copyToClipboard(id, event) {
        const text = document.getElementById(id).innerText.trim();
        navigator.clipboard.writeText(text).then(() => {
            const btn = event.currentTarget;
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            btn.classList.replace('btn-outline-primary', 'btn-success');
            setTimeout(() => {
                btn.innerHTML = originalIcon;
                btn.classList.replace('btn-success', 'btn-outline-primary');
            }, 2000);
        });
    }
</script>
@endsection
