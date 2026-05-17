@extends('layouts.empresa') <!-- Asumiendo que el revendedor usa un layout similar o uno propio. Ajustar si es layouts.owner o un layout específico -->

@section('content')
<div class="container-fluid py-4" style="padding-left: 30px; padding-right: 30px; max-width: 800px;">
    <!-- HEADER -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="letter-spacing: -1px;">Configuración de Marca Blanca</h2>
            <p class="text-secondary mb-0">Personaliza la plataforma para tus clientes.</p>
        </div>
    </div>

    <!-- FORMULARIO -->
    <div class="card bg-transparent border-0">
        <div class="card-body p-4 rounded-4" style="border: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.02);">
            <form action="{{ route('revendedor.white_label.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Nombre de la Marca</label>
                        <input type="text" name="brand_name" class="form-control bg-black text-white border-secondary border-opacity-25 rounded-3" value="{{ old('brand_name', $config->brand_name) }}" placeholder="Ej: MiMarca POS">
                        <div class="form-text text-secondary mt-1">Este nombre reemplazará a "OmniPOS" en los correos y en la interfaz.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Color Principal (Hex)</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color bg-black border-secondary border-opacity-25 p-1" name="primary_color" value="{{ old('primary_color', $config->primary_color ?? '#4f46e5') }}" title="Elige tu color institucional">
                            <input type="text" class="form-control bg-black text-white border-secondary border-opacity-25" value="{{ old('primary_color', $config->primary_color ?? '#4f46e5') }}" disabled>
                        </div>
                        <div class="form-text text-secondary mt-1">Se usará en la barra lateral y botones principales.</div>
                    </div>

                    <div class="col-12 mt-4">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Logo Institucional</label>
                        
                        @if($config->logo_path)
                        <div class="mb-3 p-3 bg-black rounded border border-secondary border-opacity-25 d-inline-block">
                            <img src="{{ asset('storage/' . $config->logo_path) }}" alt="Logo" style="max-height: 50px;">
                        </div>
                        @endif
                        
                        <input type="file" name="logo" class="form-control bg-black text-white border-secondary border-opacity-25 rounded-3" accept="image/*">
                        <div class="form-text text-secondary mt-2">
                            <i class="bi bi-info-circle me-1"></i> Formatos: PNG, JPG, WEBP o SVG. Altura recomendada: 60px. El fondo debería ser transparente.
                        </div>
                        @error('logo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr class="border-secondary border-opacity-25 my-4">

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-check-lg me-2"></i> Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
