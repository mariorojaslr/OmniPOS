@extends('layouts.guest')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-black py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <!-- Logo Header -->
                <div class="text-center mb-5">
                    <img src="{{ asset('images/logo_premium.png') }}" alt="MultiPOS" class="mb-4 shadow-lg rounded-circle" style="width: 80px; height: 80px; border: 2px solid #3b82f6;">
                    <h2 class="text-white fw-bold">Estás a un paso 🚀</h2>
                    <p class="text-secondary">Activa tu cuenta empresarial para comenzar.</p>
                </div>

                <div class="card bg-dark border-0 shadow-lg p-4 p-md-5 rounded-4 overflow-hidden position-relative">
                    <!-- Subtle Glow -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-wallet2 display-1 text-primary"></i>
                    </div>

                    @if($user->status === 'prospecto')
                        <!-- PASO 1: PEDIR PAGO -->
                        <div class="position-relative z-index-2">
                            <h4 class="text-white mb-4">Información de Pago</h4>
                            
                            <div class="bg-black p-4 rounded-3 border border-secondary mb-4">
                                <p class="text-secondary small text-uppercase mb-2 tracking-widest fw-bold">Plan Seleccionado</p>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <h5 class="text-white mb-0">{{ $plan->nombre ?? 'Plan Empresa' }}</h5>
                                    <span class="text-primary fw-bold fs-5">${{ number_format($plan->precio ?? 25000, 0, ',', '.') }}/mes</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <p class="text-white small mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> Realiza la transferencia a esta cuenta:</p>
                                <div class="bg-black p-4 rounded-3 border-start border-4 border-primary">
                                    <p class="text-secondary mb-1 small text-uppercase fw-bold opacity-75">Banco Nación / CBU</p>
                                    <h6 class="text-white fs-6 ls-1 text-break mb-3 font-monospace">0110123456789012345678</h6>
                                    
                                    <p class="text-secondary mb-1 small text-uppercase fw-bold opacity-75">Alias</p>
                                    <h6 class="text-white fs-5 ls-1 mb-0 text-uppercase font-monospace tracking-wide">MULTIPOS.SISTEMAS.OK</h6>
                                </div>
                            </div>

                            <form action="{{ route('register.payment.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label class="text-white small mb-3 fw-bold d-block">Sube tu comprobante (Foto/PDF)</label>
                                    <div class="mb-2">
                                        <input type="file" name="voucher" class="form-control bg-black text-secondary border-secondary rounded-pill p-3" id="inputVoucher" required>
                                    </div>
                                    <small class="text-secondary mt-1 d-block opacity-75"><i class="bi bi-file-image me-1 text-primary"></i> Aceptamos PNG, JPG y PDF.</small>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold text-uppercase letter-spacing-1 shadow mt-2">
                                    Enviar Comprobante
                                </button>
                            </form>
                        </div>

                    @elseif($user->status === 'pendiente_pago')
                        <!-- PASO 2: CONFIRMACIÓN EN PROCESO -->
                        <div class="text-center py-4">
                            <div class="mb-4">
                                <div class="spinner-grow text-primary" style="width: 3.5rem; height: 3.5rem;" role="status"></div>
                            </div>
                            <h4 class="text-white fw-bold mb-3">¡Voucher Recibido!</h4>
                            <p class="text-secondary mb-4 fs-6 px-lg-3">Nuestro equipo administrativo está validando tu transferencia. Recibirás un correo de confirmación una vez activo.</p>
                            
                            <div class="bg-black p-3 rounded-pill mb-4 border border-success border-opacity-50 d-inline-block px-5">
                                <span class="text-success small fw-bold"><i class="bi bi-shield-check me-2"></i> VALIDACIÓN EN CURSO</span>
                            </div>

                            <a href="{{ route('logout.get') }}" class="btn btn-outline-secondary w-100 rounded-pill py-3 fw-bold text-uppercase">
                                Cerrar Sesión
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Footer Support -->
                <div class="text-center mt-5">
                    <p class="text-secondary small">¿Necesitas ayuda inmediata? <br> Envianos un WhatsApp al <a href="https://wa.me/5491100000000" class="text-primary text-decoration-none fw-bold shadow-sm">Atención al Cliente MultiPOS</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .letter-spacing-1 { letter-spacing: 1.5px; }
    .bg-dark { background-color: #121212 !important; }
    .bg-black { background-color: #000000 !important; }
    .z-index-2 { position: relative; z-index: 2; }
    .rounded-4 { border-radius: 1.8rem !important; }
    .form-control { color: #fff !important; }
    .form-control:focus { background-color: #000 !important; border-color: #3b82f6; box-shadow: 0 0 15px rgba(59, 130, 246, 0.2); }
    input[type="file"]::file-selector-button { background: #3b82f6; border: none; border-radius: 50px; color: white; padding: 2px 15px; margin-right: 15px; font-weight: 700; cursor: pointer; }
</style>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .letter-spacing-1 { letter-spacing: 1.5px; }
    .bg-dark { background-color: #121212 !important; }
    .bg-black { background-color: #000000 !important; }
    .z-index-2 { position: relative; z-index: 2; }
    .rounded-4 { border-radius: 1.5rem !important; }
    .form-control:focus { background-color: #000 !important; border-color: #3b82f6; box-shadow: none; color: #fff; }
</style>
@endsection
