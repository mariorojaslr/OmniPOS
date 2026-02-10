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

            setTimeout(() => {
                box.classList.add('d-none');
            }, 2000);
        }
    });
});

</script>
@endpush
