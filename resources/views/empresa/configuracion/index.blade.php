@extends('layouts.empresa')

@section('content')

<div class="container">

    <h4 class="mb-4">
        ⚙️ Configuración de Empresa
    </h4>

    {{-- =========================
        MENSAJE OK (TOAST VERDE)
    ========================== --}}
    <div id="okBox" class="alert alert-success d-none">
        ✔ Configuración guardada correctamente
    </div>

    <ul class="nav nav-pills mb-4 nav-justified bg-white p-2 rounded shadow-sm border" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">🎨 General y Apariencia</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="fiscal-tab" data-bs-toggle="tab" data-bs-target="#fiscal" type="button" role="tab">📄 Facturación (ARCA)</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="gateways-tab" data-bs-toggle="tab" data-bs-target="#gateways" type="button" role="tab">💳 Pasarelas de Pago</button>
        </li>
    </ul>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4">

            <form id="configForm" enctype="multipart/form-data">
                @csrf

                <div class="tab-content" id="configTabsContent">

                    {{-- PESTAÑA 1: GENERAL --}}
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <h5 class="mb-4 text-primary"><i class="bi bi-palette me-2"></i>Personalización Visual</h5>
                        <div class="row g-4">
                            <div class="col-md-4 text-center">
                                <label class="form-label fw-bold">Logo de Empresa</label>
                                <div class="border rounded-4 p-4 mb-3 bg-light d-flex align-items-center justify-content-center shadow-inner" style="height: 180px;">
                                    @if($config && $config->logo)
                                        <img src="{{ asset('storage/'.$config->logo) }}" class="img-fluid rounded shadow-sm" style="max-height:140px">
                                    @else
                                        <div class="text-muted"><i class="bi bi-image h1"></i><br>Sin logo cargado</div>
                                    @endif
                                </div>
                                <input type="file" name="logo" class="form-control form-control-sm">
                                <small class="text-muted d-block mt-1">PNG o JPG — fondo transparente recomendado</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Colores Institucionales</label>
                                <div class="p-3 border rounded-3 bg-light mb-3">
                                    <div class="mb-3">
                                        <label class="form-label small">Color Primario</label>
                                        <input type="color" name="color_primary" value="{{ $config->color_primary ?? '#1f6feb' }}" class="form-control form-control-color w-100 shadow-sm">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small">Color Secundario</label>
                                        <input type="color" name="color_secondary" value="{{ $config->color_secondary ?? '#0d1117' }}" class="form-control form-control-color w-100 shadow-sm">
                                    </div>
                                </div>
                                
                                <label class="form-label fw-bold">Modo Nocturno</label>
                                <div class="form-check form-switch p-3 border rounded-3 bg-light text-center">
                                    <input class="form-check-input float-none ms-0 mb-2" type="checkbox" id="themeSwitch" name="theme" value="dark" {{ ($config->theme ?? 'light') === 'dark' ? 'checked' : '' }}>
                                    <label class="form-check-label d-block small" for="themeSwitch">Activar Interfaz Oscura Global</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Configuración de Catálogo</label>
                                <div class="p-3 border rounded-3 bg-light">
                                    <label class="form-label small">Días etiqueta "Nuevo"</label>
                                    <input type="number" name="dias_nuevo" value="{{ $config->dias_nuevo ?? 7 }}" min="1" max="365" class="form-control shadow-sm">
                                    <small class="text-muted">Tiempo que permanece el cartel de novedad.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PESTAÑA 2: FISCAL (ARCA) --}}
                    <div class="tab-pane fade" id="fiscal" role="tabpanel">
                        <div class="d-flex align-items-center mb-4">
                            <h5 class="mb-0 text-primary"><i class="bi bi-file-earmark-text me-2"></i>Facturación Electrónica (ARCA / AFIP)</h5>
                            <span class="badge bg-info ms-3">Ex-AFIP</span>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Cuit Titular</label>
                                <input type="text" name="arca_cuit" value="{{ $empresa->arca_cuit ?? $empresa->cuit }}" class="form-control" placeholder="20-XXXXXXXX-X">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Condición IVA</label>
                                <select name="condicion_iva" class="form-select">
                                    <option value="Responsable Inscripto" {{ $empresa->condicion_iva == 'Responsable Inscripto' ? 'selected' : '' }}>Responsable Inscripto</option>
                                    <option value="Monotributista" {{ $empresa->condicion_iva == 'Monotributista' ? 'selected' : '' }}>Monotributista</option>
                                    <option value="Exento" {{ $empresa->condicion_iva == 'Exento' ? 'selected' : '' }}>Exento</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Inscripción IIBB</label>
                                <input type="text" name="iibb" value="{{ $empresa->iibb }}" class="form-control">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Punto de Venta</label>
                                <input type="number" name="arca_punto_venta" value="{{ $empresa->arca_punto_venta ?? $empresa->punto_venta }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Ambiente</label>
                                <select name="arca_ambiente" class="form-select">
                                    <option value="homologacion" {{ ($empresa->arca_ambiente ?? 'homologacion') == 'homologacion' ? 'selected' : '' }}>Homologación (Pruebas)</option>
                                    <option value="produccion" {{ ($empresa->arca_ambiente ?? 'homologacion') == 'produccion' ? 'selected' : '' }}>Producción (Real)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Dirección Fiscal</label>
                                <input type="text" name="direccion_fiscal" value="{{ $empresa->direccion_fiscal }}" class="form-control">
                            </div>

                            <div class="col-12 mt-4">
                                <div class="p-4 border rounded-4 bg-light border-warning">
                                    <h6 class="fw-bold text-warning mb-3"><i class="bi bi-shield-lock me-2"></i>Certificados Digitales</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Certificado (.crt)</label>
                                            <input type="file" name="arca_certificado" class="form-control form-control-sm">
                                            @if($empresa->arca_certificado)
                                                <small class="text-success"><i class="bi bi-check-circle"></i> Certificado cargado</small>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Llave Privada (.key)</label>
                                            <input type="file" name="arca_llave" class="form-control form-control-sm">
                                            @if($empresa->arca_llave)
                                                <small class="text-success"><i class="bi bi-check-circle"></i> Llave privada cargada</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PESTAÑA 3: PASARELAS DE PAGO --}}
                    <div class="tab-pane fade" id="gateways" role="tabpanel">
                        <h5 class="mb-4 text-primary"><i class="bi bi-credit-card me-2"></i>Gestión de Pagos Online</h5>
                        
                        <div class="accordion" id="accordionGateways">
                            
                            {{-- MERCADO PAGO --}}
                            <div class="accordion-item mb-3 border rounded-3 shadow-sm border-0 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#colMP">
                                        <img src="https://logotipous.com/wp-content/uploads/2021/08/Mercado-Pago-Logo.png" height="25" class="me-3">
                                        <span class="fw-bold">Mercado Pago</span>
                                    </button>
                                </h2>
                                <div id="colMP" class="accordion-collapse collapse" data-bs-parent="#accordionGateways">
                                    <div class="accordion-body bg-light">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <h6>Configuración</h6>
                                                <div class="mb-3">
                                                    <label class="form-label small">Access Token</label>
                                                    <input type="password" name="pasarelas[mp_token]" value="{{ $empresa->config_pasarelas['mp_token'] ?? '' }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="pasarelas[mp_enabled]" value="1" {{ isset($empresa->config_pasarelas['mp_enabled']) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Habilitar en Checkout</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card border-info">
                                                    <div class="card-body bg-info bg-opacity-10 p-3">
                                                        <h6 class="text-info fw-bold mb-2">Información de Servicio</h6>
                                                        <ul class="small mb-0 list-unstyled">
                                                            <li><strong>Comisión:</strong> 4.45% + IVA (Acreditación inmediata)</li>
                                                            <li><strong>Acreditación:</strong> En tu cuenta de Mercado Pago.</li>
                                                            <li><strong>Operación:</strong> Tarjetas, Dinero en cuenta y Transferencias.</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- MOBEX --}}
                            <div class="accordion-item mb-3 border rounded-3 shadow-sm border-0 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#colMobex">
                                        <span class="fs-4 me-3">🔵</span>
                                        <span class="fw-bold">Mobex (Pagos Directos)</span>
                                    </button>
                                </h2>
                                <div id="colMobex" class="accordion-collapse collapse" data-bs-parent="#accordionGateways">
                                    <div class="accordion-body bg-light">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <h6>Configuración</h6>
                                                <div class="mb-3">
                                                    <label class="form-label small">API Key</label>
                                                    <input type="text" name="pasarelas[mobex_key]" value="{{ $empresa->config_pasarelas['mobex_key'] ?? '' }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="pasarelas[mobex_enabled]" value="1" {{ isset($empresa->config_pasarelas['mobex_enabled']) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Habilitar en Checkout</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card border-primary">
                                                    <div class="card-body bg-primary bg-opacity-10 p-3">
                                                        <h6 class="text-primary fw-bold mb-2">Información de Servicio</h6>
                                                        <ul class="small mb-0 list-unstyled">
                                                            <li><strong>Comisión:</strong> 2.0% (Transferencia directa)</li>
                                                            <li><strong>Acreditación:</strong> 48hs hábiles.</li>
                                                            <li><strong>Operación:</strong> Cuentas bancarias y billeteras virtuales.</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- STRIPE --}}
                            <div class="accordion-item mb-3 border rounded-3 shadow-sm border-0 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#colStripe">
                                        <span class="fs-4 me-3 text-primary"><i class="bi bi-stripe"></i></span>
                                        <span class="fw-bold">Stripe International</span>
                                    </button>
                                </h2>
                                <div id="colStripe" class="accordion-collapse collapse" data-bs-parent="#accordionGateways">
                                    <div class="accordion-body bg-light">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <h6>Configuración</h6>
                                                <div class="mb-2">
                                                    <label class="form-label small">Public Key</label>
                                                    <input type="text" name="pasarelas[stripe_pk]" value="{{ $empresa->config_pasarelas['stripe_pk'] ?? '' }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small">Secret Key</label>
                                                    <input type="password" name="pasarelas[stripe_sk]" value="{{ $empresa->config_pasarelas['stripe_sk'] ?? '' }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="pasarelas[stripe_enabled]" value="1" {{ isset($empresa->config_pasarelas['stripe_enabled']) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Habilitar en Checkout</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card border-dark">
                                                    <div class="card-body bg-dark bg-opacity-10 p-3">
                                                        <h6 class="text-dark fw-bold mb-2">Información de Servicio</h6>
                                                        <ul class="small mb-0 list-unstyled">
                                                            <li><strong>Comisión:</strong> 2.9% + 0.30 USD</li>
                                                            <li><strong>Acreditación:</strong> 7 días (primer pago), luego 2 días.</li>
                                                            <li><strong>Operación:</strong> Tarjetas internacionales y Apple/Google Pay.</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- PAYPAL --}}
                            <div class="accordion-item mb-0 border rounded-3 shadow-sm border-0 overflow-hidden">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#colPaypal">
                                        <span class="fs-4 me-3 text-primary"><i class="bi bi-paypal"></i></span>
                                        <span class="fw-bold">PayPal</span>
                                    </button>
                                </h2>
                                <div id="colPaypal" class="accordion-collapse collapse" data-bs-parent="#accordionGateways">
                                    <div class="accordion-body bg-light">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <h6>Configuración</h6>
                                                <div class="mb-3">
                                                    <label class="form-label small">Client ID</label>
                                                    <input type="text" name="pasarelas[paypal_id]" value="{{ $empresa->config_pasarelas['paypal_id'] ?? '' }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="pasarelas[paypal_enabled]" value="1" {{ isset($empresa->config_pasarelas['paypal_enabled']) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Habilitar en Checkout</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card border-primary">
                                                    <div class="card-body bg-primary bg-opacity-10 p-3">
                                                        <h6 class="text-primary fw-bold mb-2">Información de Servicio</h6>
                                                        <ul class="small mb-0 list-unstyled">
                                                            <li><strong>Comisión:</strong> 5.4% + 0.30 USD</li>
                                                            <li><strong>Acreditación:</strong> Inmediata en saldo PayPal.</li>
                                                            <li><strong>Operación:</strong> Saldo PayPal y Tarjetas.</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="sticky-bottom bg-white p-3 border-top mt-5 mx-n4 mb-n4 rounded-bottom text-end shadow-sm">
                    <button class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                        <i class="bi bi-save me-2"></i>💾 Guardar Configuración Global
                    </button>
                </div>

            </form>

        </div>
    </div>

            </form>

        </div>
    </div>

</div>

@endsection


{{-- =========================
    SCRIPT AJAX LIMPIO
========================= --}}
@push('scripts')
<script>

document.getElementById('configForm').addEventListener('submit', function(e){
    e.preventDefault();

    let form = this;
    let data = new FormData(form);

    fetch("{{ route('empresa.configuracion.save') }}", {
        method: "POST",
        body: data,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(r => r.json())
    .then(res => {
        if(res.success){
            let box = document.getElementById('okBox');
            box.classList.remove('d-none');
            setTimeout(() => { box.classList.add('d-none'); }, 3000);
        } else {
            alert('Error al guardar: ' + (res.error || 'Desconocido'));
        }
    })
    .catch(err => alert('Error de red: ' + err));
});

</script>
@endpush
