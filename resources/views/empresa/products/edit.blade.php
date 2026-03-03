@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ============================= --}}
    {{-- ENCABEZADO --}}
    {{-- ============================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Editar producto</h2>
            <small class="text-muted">{{ $product->name }}</small>
        </div>

        <a href="{{ route('empresa.products.index') }}"
           class="btn btn-outline-secondary btn-sm">
            ← Volver
        </a>
    </div>

    <form method="POST"
          action="{{ route('empresa.products.update', $product) }}">
        @csrf
        @method('PUT')

        {{-- 🔁 Mantener URL de retorno --}}
        @if(request('return'))
            <input type="hidden" name="return" value="{{ request('return') }}">
        @endif

        {{-- ============================= --}}
        {{-- INFORMACIÓN BÁSICA --}}
        {{-- ============================= --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-muted">Información básica</h6>

                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}"
                               required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Precio --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Precio</label>
                        <input type="number"
                               step="0.01"
                               name="price"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $product->price) }}"
                               required>

                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Estado</label>
                        <select name="active" class="form-select">
                            <option value="1" {{ $product->active ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="0" {{ !$product->active ? 'selected' : '' }}>
                                Inactivo
                            </option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        {{-- ============================= --}}
        {{-- CONTENIDO DEL PRODUCTO --}}
        {{-- ============================= --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-muted">Contenido del producto</h6>

                {{-- Descripción corta --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción corta</label>
                    <textarea
                        name="descripcion_corta"
                        class="form-control @error('descripcion_corta') is-invalid @enderror"
                        rows="2"
                        placeholder="Texto breve que se verá en el catálogo...">{{ old('descripcion_corta', $product->descripcion_corta) }}</textarea>

                    @error('descripcion_corta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Descripción larga --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción larga</label>
                    <textarea
                        name="descripcion_larga"
                        class="form-control @error('descripcion_larga') is-invalid @enderror"
                        rows="6"
                        placeholder="Descripción detallada del producto...">{{ old('descripcion_larga', $product->descripcion_larga) }}</textarea>

                    @error('descripcion_larga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ============================= --}}
        {{-- ACCIONES --}}
        {{-- ============================= --}}
        <div class="d-flex justify-content-end gap-2">

            <button type="submit" name="action" value="save"
                    class="btn btn-outline-primary">
                Guardar
            </button>

            <button type="submit" name="action" value="save_return"
                    class="btn btn-primary">
                Guardar y volver
            </button>

        </div>

    </form>

</div>
@endsection
