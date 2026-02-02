@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Encabezado --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-0">Nuevo producto</h2>
        <small class="text-muted">
            Cargá la información básica del producto
        </small>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ route('empresa.products.store') }}">
                @csrf

                {{-- Nombre --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del producto</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Ej: Helado de vainilla 1kg"
                           value="{{ old('name') }}"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Precio --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Precio</label>
                    <input type="number"
                           step="0.01"
                           name="price"
                           class="form-control @error('price') is-invalid @enderror"
                           placeholder="0.00"
                           value="{{ old('price') }}"
                           required>

                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Acciones --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('empresa.products.index') }}"
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                    <button class="btn btn-primary">
                        Guardar producto
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
