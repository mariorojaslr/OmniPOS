@extends('layouts.owner')

@section('content')
<div class="container-fluid py-4" style="padding-left: 30px; padding-right: 30px; max-width: 800px;">
    <!-- HEADER -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('owner.resellers.index') }}" class="btn btn-outline-light rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold mb-1" style="letter-spacing: -1px;">Editar Revendedor</h2>
            <p class="text-secondary mb-0">Modificando a: {{ $reseller->name }}</p>
        </div>
    </div>

    <!-- FORMULARIO -->
    <div class="card bg-transparent border-0">
        <div class="card-body p-4 rounded-4" style="border: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.02);">
            <form action="{{ route('owner.resellers.update', $reseller) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Nombre del Revendedor <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-black text-white border-secondary border-opacity-25 rounded-3" required value="{{ old('name', $reseller->name) }}">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Email de Acceso <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control bg-black text-white border-secondary border-opacity-25 rounded-3" required value="{{ old('email', $reseller->email) }}">
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <div class="form-check form-switch p-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                            <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" value="1" {{ $reseller->activo ? 'checked' : '' }} style="margin-left: 0; margin-right: 15px;">
                            <label class="form-check-label fw-bold text-white mb-0" for="activo">Cuenta Activa</label>
                            <div class="form-text text-secondary ms-5 mt-1">
                                Si desactivas esta cuenta, el revendedor no podrá ingresar a la plataforma. No afecta el funcionamiento de sus empresas clientes.
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="p-4 rounded-3" style="background: rgba(79, 70, 229, 0.05); border: 1px solid rgba(79, 70, 229, 0.2);">
                            <label class="form-label text-primary small fw-bold text-uppercase mb-3"><i class="bi bi-percent me-1"></i> Comisión Pactada (Marca Blanca)</label>
                            <div class="input-group mb-2" style="max-width: 250px;">
                                <input type="number" step="0.01" min="0" max="100" name="owner_commission_percentage" class="form-control bg-black text-white border-primary border-opacity-25" value="{{ old('owner_commission_percentage', optional($reseller->resellerConfig)->owner_commission_percentage ?? '10.00') }}">
                                <span class="input-group-text bg-primary text-white border-primary border-opacity-25">%</span>
                            </div>
                            <div class="form-text text-secondary">
                                <i class="bi bi-info-circle me-1"></i> Porcentaje del ingreso generado por este revendedor que corresponde al Owner. Este valor se usa para calcular sus liquidaciones.
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-secondary border-opacity-25 my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('owner.resellers.impersonate', $reseller) }}" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Mimetizar
                    </a>
                    
                    <div class="d-flex gap-3">
                        <a href="{{ route('owner.resellers.index') }}" class="btn btn-dark rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="bi bi-check-lg me-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
