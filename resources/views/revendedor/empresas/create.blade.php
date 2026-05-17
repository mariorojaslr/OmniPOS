@extends('layouts.reseller')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-5">
        <a href="{{ route('revendedor.dashboard') }}" class="text-muted text-decoration-none small fw-bold">
            <i class="bi bi-arrow-left me-1"></i> VOLVER AL PANEL
        </a>
        <h2 class="fw-800 text-white mt-3">Alta de Nueva Empresa</h2>
        <p class="text-muted">Registra un nuevo cliente en tu cartera y configura su acceso inicial.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4" style="background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body p-5">
                    <form action="{{ route('revendedor.empresas.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <!-- Datos de la Empresa -->
                            <div class="col-12 mb-2">
                                <h5 class="text-info fw-bold text-uppercase small" style="letter-spacing: 2px;">Información Comercial</h5>
                                <hr class="opacity-10">
                            </div>
                            
                            <div class="col-md-7">
                                <label class="form-label text-muted small fw-bold">Nombre Comercial de la Empresa</label>
                                <input type="text" name="nombre_comercial" class="form-control bg-dark text-white border-secondary" placeholder="Ej: Clínica Los Alerces" required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label text-muted small fw-bold">Plan Inicial</label>
                                <select name="plan_id" id="plan_select" class="form-select bg-dark text-white border-secondary" required onchange="updateMinPrice()">
                                    <option value="" selected disabled>Selecciona un plan...</option>
                                    @foreach($planes as $plan)
                                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}">{{ $plan->name }} (${{ number_format($plan->price, 0, ',', '.') }}/mes)</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12" id="price_group" style="display: none;">
                                <label class="form-label text-muted small fw-bold">Precio Acordado Mensual ($)</label>
                                <input type="number" name="custom_price" id="custom_price_input" class="form-control bg-dark text-white border-secondary fw-bold text-success fs-5" required>
                                <small class="text-info" id="price_warning" style="font-size: 0.75rem;"></small>
                            </div>

                            <script>
                                function updateMinPrice() {
                                    const select = document.getElementById('plan_select');
                                    const selectedOption = select.options[select.selectedIndex];
                                    const basePrice = selectedOption.getAttribute('data-price');
                                    
                                    if (basePrice) {
                                        document.getElementById('price_group').style.display = 'block';
                                        const priceInput = document.getElementById('custom_price_input');
                                        priceInput.min = basePrice;
                                        priceInput.value = basePrice;
                                        priceInput.placeholder = basePrice;
                                        document.getElementById('price_warning').innerHTML = `<i class="bi bi-info-circle me-1"></i> El precio mínimo permitido para este plan es de <b>$${Number(basePrice).toLocaleString('es-AR')}</b>. Puedes aumentarlo para negociar tu ganancia.`;
                                    }
                                }
                            </script>

                            <!-- Datos del Administrador -->
                            <div class="col-12 mt-5 mb-2">
                                <h5 class="text-warning fw-bold text-uppercase small" style="letter-spacing: 2px;">Cuenta de Administrador</h5>
                                <p class="text-muted small">Se creará un usuario con rol "Empresa" para que el cliente pueda gestionar su panel.</p>
                                <hr class="opacity-10">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Email de Acceso</label>
                                <input type="email" name="email_admin" class="form-control bg-dark text-white border-secondary" placeholder="admin@empresa.com" required>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-shield-lock-fill text-primary fs-3 me-3"></i>
                                        <div class="small text-muted">
                                            La contraseña se generará automáticamente y se mostrará al finalizar el registro.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm">
                                    REGISTRAR EMPRESA Y ACTIVAR CLIENTE
                                </button>
                                <p class="text-center text-muted small mt-3">Al registrar, la empresa quedará vinculada automáticamente a tu liquidación de comisiones.</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
