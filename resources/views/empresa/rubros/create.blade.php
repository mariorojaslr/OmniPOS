@extends('layouts.empresa')

@section('content')
<div class="mb-4">
    <a href="{{ route('empresa.rubros.index') }}" class="text-decoration-none text-muted small">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
    <h2 class="mt-2">Nuevo Rubro</h2>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('empresa.rubros.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Rubro</label>
                        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required placeholder="Ej: Bebidas, Limpieza, etc.">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Guardar Rubro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
