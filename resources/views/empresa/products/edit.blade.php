@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================================================
       ENCABEZADO
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Editar producto</h2>
            <small class="text-muted">{{ $product->name }}</small>
        </div>

        {{-- 🔁 VOLVER AL ORIGEN --}}
        <a href="{{ request('return') ? request('return') : route('empresa.products.index') }}"
           class="btn btn-outline-secondary btn-sm">
            ← Volver
        </a>
    </div>


    <form method="POST"
          action="{{ route('empresa.products.update', $product) }}">
        @csrf
        @method('PUT')


        {{-- =========================================================
           MANTENER URL DE RETORNO
        ========================================================== --}}
        @if(request('return'))
            <input type="hidden"
                   name="return"
                   value="{{ request('return') }}">
        @endif


        {{-- =========================================================
           INFORMACIÓN BÁSICA
        ========================================================== --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                <h6 class="fw-bold mb-3 text-muted">
                    Información básica
                </h6>

                <div class="row g-3">

                    {{-- NOMBRE --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Nombre
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}"
                               required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- PRECIO --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            Precio
                        </label>

                        <input type="number"
                               step="0.01"
                               name="price"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $product->price) }}"
                               required>

                        @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- ESTADO --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            Estado
                        </label>

                        <select name="active" class="form-select">

                            <option value="1"
                                {{ $product->active ? 'selected' : '' }}>
                                Activo
                            </option>

                            <option value="0"
                                {{ !$product->active ? 'selected' : '' }}>
                                Inactivo
                            </option>

                        </select>
                    </div>

                </div>
            </div>
        </div>


        {{-- =========================================================
           CONTENIDO DEL PRODUCTO
        ========================================================== --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                <h6 class="fw-bold mb-3 text-muted">
                    Contenido del producto
                </h6>


                {{-- DESCRIPCIÓN CORTA --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Descripción corta
                    </label>

                    <textarea
                        name="descripcion_corta"
                        class="form-control @error('descripcion_corta') is-invalid @enderror"
                        rows="2"
                        placeholder="Texto breve que se verá en el catálogo...">{{ old('descripcion_corta', $product->descripcion_corta) }}</textarea>

                    @error('descripcion_corta')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- DESCRIPCIÓN LARGA --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Descripción larga
                    </label>

                    <textarea
                        name="descripcion_larga"
                        class="form-control @error('descripcion_larga') is-invalid @enderror"
                        rows="6"
                        placeholder="Descripción detallada del producto...">{{ old('descripcion_larga', $product->descripcion_larga) }}</textarea>

                    @error('descripcion_larga')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>
        </div>


        {{-- =========================================================
           TIPO DE PRODUCTO
        ========================================================== --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                <h6 class="fw-bold mb-3 text-muted">🏷️ Tipo de producto</h6>

                <div class="btn-group mb-3" role="group">
                    <input type="radio" class="btn-check" name="product_type" id="type_normal"
                           value="normal" autocomplete="off"
                           {{ (!$product->has_variants && !$product->is_combo) ? 'checked' : '' }}>
                    <label class="btn btn-outline-secondary" for="type_normal">📦 Normal</label>

                    <input type="radio" class="btn-check" name="product_type" id="type_variants"
                           value="variants" autocomplete="off"
                           {{ $product->has_variants ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="type_variants">👕 Con Talles / Colores</label>

                    <input type="radio" class="btn-check" name="product_type" id="type_combo"
                           value="combo" autocomplete="off"
                           {{ $product->is_combo ? 'checked' : '' }}>
                    <label class="btn btn-outline-success" for="type_combo">🎁 Combo de productos</label>
                </div>

                {{-- ===== PANEL VARIANTES ===== --}}
                <div id="panelVariantes" style="{{ $product->has_variants ? '' : 'display:none' }}">
                    <p class="text-muted small mb-2">Agregá cada combinación de talle y color con su precio y stock.</p>

                    <table class="table table-bordered table-sm align-middle" id="tablaVariantes">
                        <thead class="table-light">
                            <tr>
                                <th>Talle</th>
                                <th>Color</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $v)
                            <tr>
                                <td><input type="text" name="variantes[{{ $v->id }}][size]"  class="form-control form-control-sm" value="{{ $v->size }}"></td>
                                <td><input type="text" name="variantes[{{ $v->id }}][color]" class="form-control form-control-sm" value="{{ $v->color }}"></td>
                                <td><input type="number" step="0.01" name="variantes[{{ $v->id }}][price]" class="form-control form-control-sm" value="{{ $v->price }}"></td>
                                <td><input type="number" name="variantes[{{ $v->id }}][stock]" class="form-control form-control-sm" value="{{ $v->stock }}"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">✕</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarVariante()">
                        + Agregar variante
                    </button>
                    <input type="hidden" name="has_variants" value="1" id="inputHasVariants" style="{{ $product->has_variants ? '' : 'display:none' }}">
                </div>

                {{-- ===== PANEL COMBO ===== --}}
                <div id="panelCombo" style="{{ $product->is_combo ? '' : 'display:none' }}">
                    <p class="text-muted small mb-2">Seleccioná los productos que forman parte de este combo.</p>

                    <table class="table table-bordered table-sm align-middle" id="tablaCombo">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th width="100">Cantidad</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->comboItems as $ci)
                            <tr>
                                <td>
                                    <select name="combo_items[{{ $ci->id }}][child_id]" class="form-select form-select-sm">
                                        @foreach($allProducts as $ap)
                                            <option value="{{ $ap->id }}" {{ $ci->child_product_id == $ap->id ? 'selected' : '' }}>
                                                {{ $ap->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="combo_items[{{ $ci->id }}][quantity]" class="form-control form-control-sm" value="{{ $ci->quantity }}" min="1"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">✕</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-outline-success btn-sm" onclick="agregarComboItem()">
                        + Agregar producto al combo
                    </button>
                    <input type="hidden" name="is_combo" value="1" id="inputIsCombo" style="{{ $product->is_combo ? '' : 'display:none' }}">
                </div>

            </div>
        </div>


        {{-- =========================================================
           ACCIONES
        ========================================================== --}}
        <div class="d-flex justify-content-end gap-2">

            {{-- Guardar (se queda en esta pantalla) --}}
            <button type="submit"
                    name="action"
                    value="save"
                    class="btn btn-outline-primary">
                Guardar
            </button>


            {{-- Guardar y volver al origen --}}
            <button type="submit"
                    name="action"
                    value="save_return"
                    class="btn btn-primary">
                Guardar y volver
            </button>

        </div>

    </form>

</div>

@push('scripts')
<script>
// === TIPO DE PRODUCTO ===
document.querySelectorAll('input[name="product_type"]').forEach(r => {
    r.addEventListener('change', function() {
        document.getElementById('panelVariantes').style.display = 'none';
        document.getElementById('panelCombo').style.display     = 'none';
        document.getElementById('inputHasVariants').style.display = 'none';
        document.getElementById('inputIsCombo').style.display    = 'none';

        if (this.value === 'variants') {
            document.getElementById('panelVariantes').style.display = '';
            document.getElementById('inputHasVariants').style.display = '';
        }
        if (this.value === 'combo') {
            document.getElementById('panelCombo').style.display = '';
            document.getElementById('inputIsCombo').style.display = '';
        }
    });
});

// === AGREGAR VARIANTE ===
let variantIdx = 9000; // Índice temporal para nuevas filas
function agregarVariante() {
    variantIdx++;
    const tbody = document.querySelector('#tablaVariantes tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="variantes[new_${variantIdx}][size]"  class="form-control form-control-sm" placeholder="Ej: XL"></td>
        <td><input type="text" name="variantes[new_${variantIdx}][color]" class="form-control form-control-sm" placeholder="Ej: Azul"></td>
        <td><input type="number" step="0.01" name="variantes[new_${variantIdx}][price]" class="form-control form-control-sm" placeholder="0.00"></td>
        <td><input type="number" name="variantes[new_${variantIdx}][stock]" class="form-control form-control-sm" placeholder="0"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">✕</button></td>
    `;
    tbody.appendChild(tr);
}

// === AGREGAR COMBO ITEM ===
let comboIdx = 9000;
const allProducts = @json($allProducts->pluck('name', 'id'));
function agregarComboItem() {
    comboIdx++;
    const tbody = document.querySelector('#tablaCombo tbody');
    const tr = document.createElement('tr');
    let options = '';
    for (const [id, name] of Object.entries(allProducts)) {
        options += `<option value="${id}">${name}</option>`;
    }
    tr.innerHTML = `
        <td><select name="combo_items[new_${comboIdx}][child_id]" class="form-select form-select-sm">${options}</select></td>
        <td><input type="number" name="combo_items[new_${comboIdx}][quantity]" class="form-control form-control-sm" value="1" min="1"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">✕</button></td>
    `;
    tbody.appendChild(tr);
}
</script>
@endpush
@endsection
