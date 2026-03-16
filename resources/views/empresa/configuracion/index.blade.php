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

    <div class="card shadow-sm">
        <div class="card-body">

            <form id="configForm" enctype="multipart/form-data">

                @csrf

                <div class="row">

                    {{-- =========================
                        LOGO
                    ========================== --}}
                    <div class="col-md-4 text-center">

                        <label class="form-label fw-semibold">Logo</label>

                        <div class="border rounded p-3 mb-3 bg-light">

                            @if($config && $config->logo)
                                <img src="{{ asset('storage/'.$config->logo) }}"
                                     style="max-height:120px">
                            @else
                                <div class="text-muted">Sin logo</div>
                            @endif

                        </div>

                        <input type="file"
                               name="logo"
                               class="form-control">

                        <small class="text-muted">
                            PNG o JPG — recomendado fondo transparente
                        </small>

                    </div>

                    {{-- =========================
                        COLORES
                    ========================== --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Color Primario
                        </label>

                        <input type="color"
                               name="color_primary"
                               value="{{ $config->color_primary ?? '#1f6feb' }}"
                               class="form-control form-control-color mb-3">

                        <label class="form-label fw-semibold">
                            Color Secundario
                        </label>

                        <input type="color"
                               name="color_secondary"
                               value="{{ $config->color_secondary ?? '#0d1117' }}"
                               class="form-control form-control-color">

                    </div>

                    {{-- =========================
                        TEMA
                    ========================== --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">Tema</label>

                        <div class="form-check form-switch mt-2">

                            <input class="form-check-input"
                                   type="checkbox"
                                   id="themeSwitch"
                                   name="theme"
                                   value="dark"
                                   {{ ($config->theme ?? 'light') === 'dark' ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Activar Modo Oscuro
                            </label>

                        </div>

                        <small class="text-muted">
                            El modo oscuro reduce el brillo y mejora la experiencia nocturna.
                        </small>

                    </div>

                </div>

                <hr class="my-4">

                <h5 class="mb-3">📄 Datos Fiscales</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">CUIT</label>
                        <input type="text" name="cuit" value="{{ $empresa->cuit }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Condición IVA</label>
                        <select name="condicion_iva" class="form-select">
                            <option value="">Seleccionar...</option>
                            <option value="Responsable Inscripto" {{ $empresa->condicion_iva == 'Responsable Inscripto' ? 'selected' : '' }}>Responsable Inscripto</option>
                            <option value="Monotributista" {{ $empresa->condicion_iva == 'Monotributista' ? 'selected' : '' }}>Monotributista</option>
                            <option value="Exento" {{ $empresa->condicion_iva == 'Exento' ? 'selected' : '' }}>Exento</option>
                            <option value="Consumidor Final" {{ $empresa->condicion_iva == 'Consumidor Final' ? 'selected' : '' }}>Consumidor Final</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Ingresos Brutos (IIBB)</label>
                        <input type="text" name="iibb" value="{{ $empresa->iibb }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Punto de Venta</label>
                        <input type="number" name="punto_venta" value="{{ $empresa->punto_venta ?? 1 }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Próximo Número de Factura</label>
                        <input type="number" name="proximo_numero_factura" value="{{ $empresa->proximo_numero_factura ?? 1 }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Dirección Fiscal</label>
                        <input type="text" name="direccion_fiscal" value="{{ $empresa->direccion_fiscal }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Día de cierre de periodo</label>
                        <input type="number" name="dia_cierre_periodo" value="{{ $empresa->dia_cierre_periodo ?? 0 }}" min="0" max="31" class="form-control">
                        <small class="text-muted">0 = No definido, 1-31 = Día del mes</small>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">💳 Pasarelas de Pago</h5>
                @php
                    $gateways = $empresa->config_pasarelas ?? [];
                @endphp
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pasarelas[mercadopago]" value="1" id="mp" {{ isset($gateways['mercadopago']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mp">Mercado Pago</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pasarelas[mobex]" value="1" id="mobex" {{ isset($gateways['mobex']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mobex">Mobex</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pasarelas[paypal]" value="1" id="paypal" {{ isset($gateways['paypal']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="paypal">PayPal</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pasarelas[stripe]" value="1" id="stripe" {{ isset($gateways['stripe']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="stripe">Stripe</label>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-primary px-4">
                        💾 Guardar Configuración
                    </button>
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
