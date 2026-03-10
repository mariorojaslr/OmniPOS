@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h4 class="fw-bold mb-4">Editar Plan</h4>

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

                <form action="{{ route('owner.planes.update', $plan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Plan</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ $plan->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción (Opcional)</label>
                        <textarea name="description" class="form-control rounded-3" rows="2">{{ $plan->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Precio Mensual ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3" value="{{ $plan->price }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Max Usuarios</label>
                            <input type="number" name="max_users" class="form-control rounded-3" value="{{ $plan->max_users }}" required>
                            <small class="text-muted">0 = Ilimitado</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Max Productos</label>
                            <input type="number" name="max_products" class="form-control rounded-3" value="{{ $plan->max_products }}" required>
                            <small class="text-muted">0 = Ilimitado</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Almacenamiento (Megabytes)</label>
                            <input type="number" step="0.1" name="max_storage_mb" class="form-control rounded-3" value="{{ $plan->max_storage_mb }}" required>
                            <small class="text-muted">0 = Ilimitado</small>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ $plan->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-success" for="isActive">Plan Activo y Visible</label>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('owner.planes.index') }}" class="btn btn-light border-0 shadow-sm rounded-3 px-4">Volver</a>
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-3 px-4">Actualizar Plan</button>
                    </div>
                </form>

            </div>
        </div>

        <!-- FORMULARIO ELIMINAR -->
        <form action="{{ route('owner.planes.destroy', $plan) }}" method="POST" class="mt-4 text-end">
            @csrf
            @method('DELETE')
            <p class="text-muted small mb-1">Zona de peligro, eliminar permanentemente este plan (solo si no tiene clientes).</p>
            <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill" onclick="return confirm('¿Seguro de borrar este plan?')">🗑 Eliminar Plan</button>
        </form>

    </div>
</div>
@endsection
