@extends('catalog.layout')

@section('title', 'Finalizar compra')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="mb-4 fw-bold">Finalizar compra</h2>

            @if(session('cart') && count(session('cart')) > 0)

            <div class="row">

                {{-- ================= RESUMEN DEL PEDIDO ================= --}}
                <div class="col-lg-5 mb-4">
                    <div class="card shadow-sm border-0" style="border-radius: 15px;">
                        <div class="card-header bg-white fw-bold py-3 border-bottom">
                            Resumen del pedido
                        </div>
                        <div class="card-body">
                            @php $total = 0; @endphp
                            @foreach(session('cart') as $item)
                                @php
                                    $line = $item['price'] * $item['quantity'];
                                    $total += $line;
                                @endphp
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="me-2">
                                        <span class="fw-bold d-block">{{ $item['name'] }}</span>
                                        <small class="text-muted">Cantidad: {{ $item['quantity'] }}</small>
                                    </div>
                                    <div class="fw-bold">
                                        $ {{ number_format($line,2, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                            <hr class="my-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-5 text-muted">Total a pagar:</span>
                                <span class="fs-3 fw-bold text-primary">$ {{ number_format($total,2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= FORMULARIO INTELIGENTE ================= --}}
                <div class="col-lg-7">
                    <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="empresa_id" id="empresa_id" value="{{ $empresa->id }}">

                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                            <div class="card-header bg-white fw-bold py-3 border-bottom">
                                Datos del cliente
                            </div>
                            <div class="card-body">
                                
                                {{-- ALERTA DE RECONOCIMIENTO --}}
                                <div id="welcomeMessage" class="alert alert-info border-0 shadow-sm mb-4" style="display: none; border-radius: 12px; background: rgba(13, 202, 240, 0.1);">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-2 me-3">👋</div>
                                        <div>
                                            <strong id="clientGreeting">¡Hola!</strong>
                                            <p class="mb-0 small">Te reconocimos. Hemos cargado tus datos para que compres más rápido.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold text-muted">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="ejemplo@correo.com" required>
                                        <small class="text-muted" style="font-size: 0.75rem;">Usamos tu email para reconocerte.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold text-muted">Teléfono</label>
                                        <input type="text" name="telefono" id="phone" class="form-control" placeholder="Ej: +54 9 11 1234 5678" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold text-muted">Nombre</label>
                                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold text-muted">Apellido</label>
                                        <input type="text" name="apellido" id="apellido" class="form-control" required>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <span class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width:24px; height:24px; font-size: 0.8rem;">2</span>
                                    Método de Entrega
                                </h6>
                                <div class="mb-3">
                                    <div class="form-check form-check-inline me-4">
                                        <input class="form-check-input" type="radio" name="metodo_entrega" id="retiro" value="retiro_local" checked>
                                        <label class="form-check-label" for="retiro">Retiro en local</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="metodo_entrega" id="envio" value="envio_domicilio">
                                        <label class="form-check-label" for="envio">Envío a domicilio</label>
                                    </div>
                                </div>

                                <div id="addressSection" class="mt-3 animate__animated animate__fadeIn">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Dirección de Entrega</label>
                                        <input type="text" name="direccion" id="address" class="form-control" placeholder="Calle y número...">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold text-muted">Ciudad</label>
                                            <input type="text" name="ciudad" id="city" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold text-muted">Provincia</label>
                                            <input type="text" name="provincia" id="province" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <span class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width:24px; height:24px; font-size: 0.8rem;">3</span>
                                    Pago
                                </h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="metodo_pago" value="manual" id="pagoManual" checked>
                                    <label class="form-check-label" for="pagoManual">
                                        Pago a coordinar por WhatsApp
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-4 shadow-sm fw-bold py-3" style="border-radius: 12px;">
                                    Confirmar Pedido 🚀
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            @else
                <div class="text-center py-5">
                    <div class="fs-1 mb-3">🛒</div>
                    <h4 class="fw-bold">Tu carrito está vacío</h4>
                    <p class="text-muted">Vuelve al catálogo para elegir tus productos.</p>
                    <a href="{{ route('catalog.index', $empresa->id) }}" class="btn btn-primary mt-3">Ir al Catálogo</a>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const empresaId = document.getElementById('empresa_id').value;
        const welcomeMessage = document.getElementById('welcomeMessage');
        const clientGreeting = document.getElementById('clientGreeting');

        // Función para buscar cliente
        async function checkClient() {
            const email = emailInput.value;
            const phone = phoneInput.value;

            if (email.length < 5 && phone.length < 5) return;

            try {
                const response = await fetch(`{{ route('checkout.search_client') }}?email=${email}&phone=${phone}&empresa_id=${empresaId}`);
                const data = await response.json();

                if (data.found) {
                    const c = data.client;
                    
                    // Saludo y animación
                    clientGreeting.innerText = `¡Hola ${c.nombre}!`;
                    welcomeMessage.style.display = 'block';
                    
                    // Autocompletar (si están vacíos para no molestar si el usuario ya escribió algo distinto)
                    if (!document.getElementById('nombre').value) document.getElementById('nombre').value = c.nombre;
                    if (!document.getElementById('apellido').value) document.getElementById('apellido').value = c.apellido;
                    if (!document.getElementById('address').value) document.getElementById('address').value = c.direccion;
                    if (!document.getElementById('city').value) document.getElementById('city').value = c.ciudad;
                    if (!document.getElementById('province').value) document.getElementById('province').value = c.provincia;

                    // Si ya reconoció al cliente, podemos parar de buscar
                    emailInput.removeEventListener('blur', checkClient);
                    phoneInput.removeEventListener('blur', checkClient);
                }
            } catch (error) {
                console.error('Error buscando cliente:', error);
            }
        }

        // Escuchar cuando el usuario sale del input de email o teléfono
        emailInput.addEventListener('blur', checkClient);
        phoneInput.addEventListener('blur', checkClient);
        
        // Manejo de visibilidad de dirección
        const deliveryRadios = document.querySelectorAll('input[name="metodo_entrega"]');
        const addressSection = document.getElementById('addressSection');
        
        deliveryRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'envio_domicilio') {
                    addressSection.style.display = 'block';
                } else {
                    // Si es retiro en local, ocultamos pero dejamos que el sistema reconozca al cliente igualmente
                    // addressSection.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
