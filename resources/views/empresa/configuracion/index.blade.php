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
                                        <img src="{{ $config->logo_url }}" class="img-fluid rounded shadow-sm" style="max-height:140px">
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
                                <div class="p-3 border rounded-3 bg-light mb-3">
                                    <label class="form-label small">Días etiqueta "Nuevo"</label>
                                    <input type="number" name="dias_nuevo" value="{{ $config->dias_nuevo ?? 7 }}" min="1" max="365" class="form-control shadow-sm">
                                    <small class="text-muted">Tiempo que permanece el cartel de novedad.</small>
                                </div>

                                <label class="form-label fw-bold">Módulos del Sistema</label>
                                <div class="p-3 border rounded-3 bg-white shadow-sm border-success border-opacity-25">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="modOrdenPedido" name="mod_orden_pedido" value="1" {{ ($config->mod_orden_pedido ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-bold" for="modOrdenPedido">📦 Órdenes de Pedido</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="modOrdenPedidoExtra" name="mod_orden_pedido_extra" value="1" {{ ($config->mod_orden_pedido_extra ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-bold" for="modOrdenPedidoExtra">🛠️ Datos Extra (Constructivos)</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="modTurnos" name="mod_turnos" value="1" {{ ($config->mod_turnos ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-bold" for="modTurnos">📅 Gestión de Turnos</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="modUnidades" name="mod_unidades_medida" value="1" {{ ($config->mod_unidades_medida ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-bold" for="modUnidades">⚖️ Unidades de Medida</label>
                                    </div>
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

                        {{-- MODO ADMINISTRATIVO / FISCAL SWITCH --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm overflow-hidden bg-primary bg-opacity-10">
                                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-white p-3 me-3 shadow-sm">
                                                <i class="bi bi-shield-check text-primary h3 mb-0"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">Conexión con AFIP Activa</h6>
                                                <p class="small text-muted mb-0">Si está desactivado, el sistema emitirá comprobantes de gestión interna (No Válidos como Factura).</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-white text-primary border-primary border px-3 py-2 me-3 fw-bold shadow-sm" style="font-size: 0.9rem;">#{{ $empresa->id }}</span>
                                            <div class="form-check form-switch form-switch-lg">
                                                <input class="form-check-input" type="checkbox" name="arca_activo" value="1" {{ ($empresa->arca_activo ?? false) ? 'checked' : '' }} style="width: 3.5rem; height: 1.8rem;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Cuit Titular</label>
                                <input type="text" name="arca_cuit" value="{{ $empresa->arca_cuit ?? $empresa->cuit }}" class="form-control" placeholder="20-XXXXXXXX-X">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Condición IVA</label>
                                <select name="condicion_iva" class="form-select">
                                    <option value="Responsable Inscripto" {{ $empresa->condicion_iva == 'Responsable Inscripto' ? 'selected' : '' }}>Responsable Inscripto</option>
                                    <option value="Monotributista" {{ $empresa->condicion_iva == 'Monotributista' ? 'selected' : '' }}>Monotributista</option>
                                    <option value="Exento" {{ $empresa->condicion_iva == 'Exento' ? 'selected' : '' }}>Exento</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Inscripción IIBB</label>
                                <input type="text" name="iibb" value="{{ $empresa->iibb }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-danger">Inicio Actividades</label>
                                <input type="date" name="inicio_actividad" value="{{ $empresa->inicio_actividad }}" class="form-control border-danger border-2 shadow-sm">
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
                                        <div class="col-12 mb-3">
                                            <div class="card border-primary bg-primary bg-opacity-10 border-dashed p-3 text-center">
                                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-magic me-2"></i>¿No tienes los certificados?</h6>
                                                <p class="small text-muted mb-3">Podemos generarlos por ti automáticamente. Solo completa unos datos y te daremos los archivos listos.</p>
                                                <button type="button" class="btn btn-primary btn-sm fw-bold px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#wizardCertificados">
                                                    Abrir Asistente de Gestión AFIP
                                                </button>
                                            </div>
                                        </div>
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
                            
                            <!-- BOTON TEST AFIP -->
                            <div class="col-12 mt-3 text-end">
                                <button type="button" id="btnTestAfip" class="btn btn-outline-success fw-bold">
                                    <i class="bi bi-shield-check me-2"></i> Probar Conexión con AFIP
                                </button>
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
                                        <img src="https://img.icons8.com/color/48/mercado-pago.png" height="28" class="me-3">
                                        <span class="fw-bold fs-5">Mercado Pago</span>
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
                                                <div class="card border-primary border-opacity-25 shadow-sm">
                                                    <div class="card-body bg-primary bg-opacity-10 p-3">
                                                        <h6 class="text-primary fw-bold mb-2">Información de Servicio</h6>
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

                </div> <!-- End tab-content -->

                <hr class="mt-5 mb-4 border-secondary opacity-25">
                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-lg shadow fw-bold px-5">
                        <i class="bi bi-floppy me-2"></i> Grabar Configuración
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

{{-- ==========================================
     MODAL: WIZARD DE CERTIFICADOS
=========================================== --}}
<div class="modal fade" id="wizardCertificados" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-magic me-2"></i>Asistente de Certificados AFIP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                {{-- STEP 1: FORMULARIO --}}
                <div id="step1">
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Este asistente generará un archivo <strong>Pedido de Certificado (.csr)</strong> y una <strong>Llave Privada (.key)</strong>.
                    </div>

                    <form id="formWizardCert">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">CUIT del Titular</label>
                                <input type="text" id="wiz_cuit" class="form-control" name="cuit" value="{{ $empresa->arca_cuit ?? $empresa->cuit }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">Nombre Comercial <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_comercial" class="form-control border-2" value="{{ $empresa->nombre_comercial }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">Provincia (Principal para GPS) <span class="text-danger">*</span></label>
                            <select name="provincia" class="form-select border-2" required>
                                <option value="">Seleccionar Provincia...</option>
                                @foreach(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán'] as $prov)
                                    <option value="{{ $prov }}" {{ ($empresa->provincia ?? '') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Localidad / Ciudad</label>
                                <input type="text" id="wiz_localidad" class="form-control" name="localidad" placeholder="Ej: CABA" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Provincia</label>
                                <input type="text" id="wiz_provincia" class="form-control" name="provincia" placeholder="Ej: Buenos Aires" required>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="button" id="btnGenerarWizard" class="btn btn-primary px-5 fw-bold rounded-pill shadow">
                                <i class="bi bi-gear-wide-connected me-2"></i> Generar Archivos
                            </button>
                        </div>
                    </form>
                </div>

                {{-- STEP 2: DESCARGA --}}
                <div id="step2" class="d-none text-center py-4">
                    <div class="mb-4">
                        <div class="display-1 text-success mb-3">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h4 class="fw-bold">¡Archivos Listos!</h4>
                        <p class="text-muted">Descarga ambos archivos y guárdalos en un lugar seguro.</p>
                    </div>

                    <div class="row g-3 justify-content-center mb-5">
                        <div class="col-md-5">
                            <a href="#" id="linkDownloadKey" class="btn btn-outline-primary w-100 py-3 shadow-sm fw-bold">
                                <i class="bi bi-key h3 d-block mb-2"></i>
                                Descargar Llave (.key)
                            </a>
                            <small class="text-danger fw-bold d-block mt-2">¡NUNCA compartas este archivo!</small>
                        </div>
                        <div class="col-md-5">
                            <a href="#" id="linkDownloadCsr" class="btn btn-outline-primary w-100 py-3 shadow-sm fw-bold">
                                <i class="bi bi-file-earmark-arrow-down h3 d-block mb-2"></i>
                                Descargar Pedido (.csr)
                            </a>
                            <small class="text-muted d-block mt-2">Este es el que subes a AFIP.</small>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-4 border">
                        <h6 class="fw-bold"><i class="bi bi-play-btn-fill me-2"></i>¿Qué sigue ahora?</h6>
                        <p class="small text-muted">Mira este video tutorial que explica cómo subir el archivo (.csr) a la página de AFIP para obtener tu Certificado (.crt) final.</p>
                        
                        <div class="ratio ratio-16x9 shadow-sm rounded overflow-hidden">
                            <iframe src="https://www.youtube.com/embed/{{ $afipVideoId }}" title="Tutorial AFIP" allowfullscreen></iframe>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="window.location.reload()">
                                <i class="bi bi-arrow-repeat me-1"></i> Ya tengo el .crt y quiero subirlo
                            </button>
                        </div>
                    </div>
                </div>

            </div>
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

    let btn = this.querySelector('button[type="submit"]');
    let originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

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
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        if(res.success){
            let box = document.getElementById('okBox');
            box.classList.remove('d-none');
            setTimeout(() => { box.classList.add('d-none'); }, 3000);
        } else {
            alert('Error al guardar: ' + (res.error || 'Desconocido'));
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        alert('Error de red: ' + err);
    });
});

document.getElementById('btnTestAfip').addEventListener('click', function() {
    let btn = this;
    let originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Conectando con AFIP...';

    fetch("{{ route('empresa.configuracion.test_afip') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        if(res.success) {
            let msg = res.message;
            if(res.warning) msg += "\n\nOJO: " + res.warning;
            msg += "\n\nEstado de AFIP: AppServer OK.";
            alert(msg);
        } else {
            alert("Error de Conexión AFIP:\n" + res.error);
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        alert('Error de red al intentar probar AFIP: ' + err);
    });
});

// ==========================================
// WIZARD DE CERTIFICADOS (LÓGICA)
// ==========================================
document.getElementById('btnGenerarWizard').addEventListener('click', function() {
    let btn = this;
    let originalHtml = btn.innerHTML;
    let form = document.getElementById('formWizardCert');
    let formData = new FormData(form);

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';

    fetch("{{ route('empresa.configuracion.generate_cert') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        
        if (res.success) {
            document.getElementById('step1').classList.add('d-none');
            document.getElementById('step2').classList.remove('d-none');
            
            document.getElementById('linkDownloadKey').href = res.download_key;
            document.getElementById('linkDownloadCsr').href = res.download_csr;
        } else {
            alert("Error: " + (res.error || "No se pudieron generar los archivos"));
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        alert("Error de red: " + err);
    });
});

</script>
@endpush
