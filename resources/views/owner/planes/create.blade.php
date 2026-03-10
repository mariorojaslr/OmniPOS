@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h4 class="fw-bold mb-4">Crear Nuevo Plan</h4>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('owner.planes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Plan</label>
                        <input type="text" name="name" class="form-control rounded-3" required placeholder="Ej. Básico o Premium" value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción (Opcional)</label>
                        <textarea name="description" class="form-control rounded-3" rows="2" placeholder="Beneficios del plan...">{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Precio Mensual ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3" required value="{{ old('price', '0.00') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Max Usuarios</label>
                            <input type="number" name="max_users" class="form-control rounded-3" required value="{{ old('max_users', '1') }}">
                            <small class="text-muted">0 = Ilimitado</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Max Productos</label>
                            <input type="number" name="max_products" class="form-control rounded-3" required value="{{ old('max_products', '100') }}">
                            <small class="text-muted">0 = Ilimitado</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Almacenamiento (Megabytes)</label>
                            <input type="number" step="0.1" name="max_storage_mb" class="form-control rounded-3" required value="{{ old('max_storage_mb', '100.0') }}">
                            <small class="text-muted">0 = Ilimitado</small>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ old('is_active') || old('_token') === null ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-success" for="isActive">Plan Activo y Visible</label>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('owner.planes.index') }}" class="btn btn-light border-0 shadow-sm rounded-3 px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-3 px-4">Guardar Plan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
