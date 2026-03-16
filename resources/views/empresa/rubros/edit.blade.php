@extends('layouts.empresa')

@section('content')
<div class="mb-4">
    <a href="{{ route('empresa.rubros.index') }}" class="text-decoration-none text-muted small">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
    <h2 class="mt-2">Editar Rubro</h2>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('empresa.rubros.update', $rubro) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Rubro</label>
                        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $rubro->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1" @if(old('activo', $rubro->activo)) checked @endif>
                            <label class="form-check-label" for="activo">Rubro Activo</label>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Actualizar Rubro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
