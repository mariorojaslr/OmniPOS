@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ============================= --}}
    {{-- ENCABEZADO --}}
    {{-- ============================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Nuevo producto</h2>
            <small class="text-muted">
                Cargá la información básica del producto
            </small>
        </div>

        <a href="{{ route('empresa.products.index') }}"
           class="btn btn-outline-secondary btn-sm">
            ← Volver
        </a>
    </div>

    <form method="POST" action="{{ route('empresa.products.store') }}">
        @csrf

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
                               placeholder="Ej: Helado de vainilla 1kg"
                               value="{{ old('name') }}"
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
                               placeholder="0.00"
                               value="{{ old('price') }}"
                               required>

                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rubro --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Rubro</label>
                        <select name="rubro_id" class="form-select">
                            <option value="">-- Sin Rubro --</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->id }}" @selected(old('rubro_id') == $rubro->id)>
                                    {{ $rubro->nombre }}
                                </option>
                            @endforeach
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
                        placeholder="Texto breve que se verá en el catálogo...">{{ old('descripcion_corta') }}</textarea>

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
                        placeholder="Descripción detallada del producto, características, usos, beneficios...">{{ old('descripcion_larga') }}</textarea>

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
