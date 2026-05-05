@extends('layouts.empresa')

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

        <div class="d-flex gap-2">
            {{-- 🏷️ IMPRIMIR ETIQUETA RÁPIDA --}}
            @if($product->barcode)
                <a href="{{ route('empresa.products.labels.single', $product) }}" 
                   target="_blank"
                   class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-tag"></i>
                    Imprimir Etiquetas
                </a>
            @endif

            {{-- 🔁 VOLVER AL ORIGEN --}}
            <a href="{{ request('return') ? request('return') : route('empresa.products.index') }}"
               class="btn btn-outline-secondary btn-sm">
                ← Volver
            </a>
        </div>
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
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nombre del Artículo</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}"
                               required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- PRECIO VENTA --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Precio Venta</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">$</span>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                        </div>
                        @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- COSTO --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Precio Costo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">$</span>
                            <input type="number" step="0.01" name="cost" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost', $product->cost) }}">
                        </div>
                        @error('cost')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- UNIDAD --}}
                    <div class="col-md-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label fw-semibold">Unidad</label>
                            <a href="{{ route('empresa.units.index') }}" class="x-small text-primary text-decoration-none fw-bold" target="_blank" style="font-size: 0.7rem;">+ Nueva</a>
                        </div>
                        <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror">
                            <option value="">-- Unidad --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" @selected(old('unit_id', $product->unit_id) == $unit->id)>
                                    {{ $unit->name }} ({{ $unit->short_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- BARCODE --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Código de Barras</label>
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $product->barcode) }}" placeholder="EAN-13...">
                    </div>

                    {{-- SKU --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">SKU Internacional</label>
                        <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" placeholder="Ej: REM-BLA-L">
                    </div>


                    {{-- RUBRO --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Rubro / Categoría</label>
                        <select name="rubro_id" class="form-select">
                            <option value="">-- Sin Rubro --</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->id }}" @selected(old('rubro_id', $product->rubro_id) == $rubro->id)>
                                    {{ $rubro->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PROVEEDOR --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Proveedor Principal</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">-- Sin Proveedor --</option>
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->id }}" @selected(old('supplier_id', $product->supplier_id) == $prov->id)>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Clasificación Avanzada PROFESIONAL --}}
                    <div class="col-md-12 mt-3">
                        <label class="form-label fw-bold d-block mb-3">¿Para qué se usará este artículo?</label>
                        
                        <div class="row g-3">
                            {{-- OPCIÓN: VENTA --}}
                            <div class="col-md-6">
                                <div class="card h-100 border p-3 selection-card {{ $product->usage_type == 'sell' ? 'active' : '' }}" id="card_sell" onclick="selectUsage('sell')">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio" name="usage_type_main" id="type_sell" value="sell" {{ $product->usage_type == 'sell' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="type_sell">🛍️ Producto para Venta</label>
                                        </div>
                                        <span class="text-primary cursor-pointer" data-bs-toggle="tooltip" title="Producto terminado para la venta directa al cliente final.">
                                            <i class="bi bi-info-circle"></i>
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0">Artículos que forman parte de tu catálogo comercial y generan ingresos directos.</p>
                                </div>
                            </div>

                            {{-- OPCIÓN: OTROS (INSUMOS/MATERIA PRIMA) --}}
                            <div class="col-md-6">
                                <div class="card h-100 border p-3 selection-card {{ $product->usage_type != 'sell' ? 'active' : '' }}" id="card_other" onclick="selectUsage('other')">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio" name="usage_type_main" id="type_other" value="other" {{ $product->usage_type != 'sell' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="type_other">⚙️ Insumo / Operativo</label>
                                        </div>
                                        <span class="text-primary cursor-pointer" data-bs-toggle="tooltip" title="Elementos necesarios para el funcionamiento de la empresa pero que no se venden directamente.">
                                            <i class="bi bi-info-circle"></i>
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0">Materias primas, artículos de limpieza, envases o suministros de oficina.</p>
                                </div>
                            </div>
                        </div>

                        {{-- SUB-NIVEL: DETALLE DE INSUMO --}}
                        <div id="sub_level_other" class="mt-4 p-3 bg-light rounded-3" style="{{ $product->usage_type == 'sell' ? 'display: none;' : '' }}">
                            <label class="form-label fw-bold small text-uppercase text-muted mb-3">Especificar tipo de recurso:</label>
                            
                            <div class="d-flex flex-wrap gap-3">
                                {{-- Materia Prima --}}
                                <div class="usage-option">
                                    <input type="radio" class="btn-check" name="usage_type_sub" id="usage_raw" value="raw_material" autocomplete="off" {{ $product->usage_type == 'raw_material' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-dark d-flex align-items-center gap-2" for="usage_raw">
                                        🧶 Materia Prima
                                        <span class="text-muted" data-bs-toggle="tooltip" title="Elemento que se transforma totalmente durante el proceso productivo. Ejemplo: tela, harina.">
                                            <i class="bi bi-question-circle"></i>
                                        </span>
                                    </label>
                                </div>

                                {{-- Insumo --}}
                                <div class="usage-option">
                                    <input type="radio" class="btn-check" name="usage_type_sub" id="usage_supply" value="supply" autocomplete="off" {{ $product->usage_type == 'supply' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-dark d-flex align-items-center gap-2" for="usage_supply">
                                        📦 Insumo de Proceso
                                        <span class="text-muted" data-bs-toggle="tooltip" title="Elemento para apoyo de procesos productivos o embalaje. Ejemplo: hilos, pegamentos, cajas.">
                                            <i class="bi bi-question-circle"></i>
                                        </span>
                                    </label>
                                </div>

                                {{-- Consumo Interno --}}
                                <div class="usage-option">
                                    <input type="radio" class="btn-check" name="usage_type_sub" id="usage_internal" value="internal" autocomplete="off" {{ $product->usage_type == 'internal' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-dark d-flex align-items-center gap-2" for="usage_internal">
                                        🧹 Consumo Interno
                                        <span class="text-muted" data-bs-toggle="tooltip" title="Gasto operativo para que pase por almacén pero no se vende. Ejemplo: artículos de limpieza.">
                                            <i class="bi bi-question-circle"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Hidden inputs --}}
                        <input type="hidden" name="is_sellable" id="is_sellable_hidden" value="{{ $product->is_sellable ? '1' : '0' }}">
                        <input type="hidden" name="usage_type" id="usage_type_real" value="{{ $product->usage_type }}">

                    </div>

                    {{-- ESTADO --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Visibilidad en catálogo
                        </label>
                        <select name="active" class="form-select">
                            <option value="1" @selected(old('active', $product->active))>Activo / Visible</option>
                            <option value="0" @selected(!old('active', $product->active))>Oculto / Pausado</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>


        {{-- =========================================================
           INVENTARIO
        ========================================================== --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-muted">📊 Inventario</h6>
                <div class="row g-3">
                    {{-- STOCK ACTUAL --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Actual</label>
                        <input type="number" 
                               name="stock" 
                               class="form-control" 
                               value="{{ old('stock', $product->stock) }}" 
                               {{ $product->has_variants ? 'readonly' : '' }}>
                        @if($product->has_variants)
                            <small class="text-muted">Se calcula automáticamente de las variantes.</small>
                        @endif
                    </div>

                    {{-- STOCK MÍNIMO --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Mínimo</label>
                        <input type="number" 
                               name="stock_min" 
                               class="form-control" 
                               value="{{ old('stock_min', $product->stock_min) }}">
                    </div>

                    {{-- STOCK IDEAL --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Ideal</label>
                        <input type="number" 
                               name="stock_ideal" 
                               class="form-control" 
                               value="{{ old('stock_ideal', $product->stock_ideal) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-muted">Contenido del producto</h6>

                {{-- DESCRIPCIÓN CORTA --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción corta</label>
                    <textarea
                        name="descripcion_corta"
                        class="form-control @error('descripcion_corta') is-invalid @enderror"
                        rows="2"
                        placeholder="Texto breve que se verá en el catálogo...">{{ old('descripcion_corta', $product->descripcion_corta) }}</textarea>
                    @error('descripcion_corta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- DESCRIPCIÓN LARGA --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción larga</label>
                    <textarea
                        name="descripcion_larga"
                        class="form-control @error('descripcion_larga') is-invalid @enderror"
                        rows="6"
                        placeholder="Descripción detallada del producto...">{{ old('descripcion_larga', $product->descripcion_larga) }}</textarea>
                    @error('descripcion_larga')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                <th>Código SKU</th>
                                <th>Código Barras</th>
                                <th>Precio (opc)</th>
                                <th>Stock</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $v)
                            <tr>
                                <td><input type="text" name="variantes[{{ $v->id }}][size]"  class="form-control form-control-sm" value="{{ $v->size }}"></td>
                                <td><input type="text" name="variantes[{{ $v->id }}][color]" class="form-control form-control-sm" value="{{ $v->color }}"></td>
                                <td><input type="text" name="variantes[{{ $v->id }}][sku]" class="form-control form-control-sm text-center" value="{{ $v->sku }}" placeholder="SKU..."></td>
                                <td><input type="text" name="variantes[{{ $v->id }}][barcode]" class="form-control form-control-sm text-center" value="{{ $v->barcode }}" placeholder="EAN..."></td>
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

@endsection

@push('scripts')
<style>
    .selection-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border-width: 2px !important;
    }
    .selection-card:hover {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.02);
    }
    .selection-card.active {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05);
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.1);
    }
    .cursor-pointer { cursor: pointer; }
</style>

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
let variantIdx = 9000;
function agregarVariante() {
    variantIdx++;
    const tbody = document.querySelector('#tablaVariantes tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="variantes[new_${variantIdx}][size]"  class="form-control form-control-sm" placeholder="Ej: XL"></td>
        <td><input type="text" name="variantes[new_${variantIdx}][color]" class="form-control form-control-sm" placeholder="Ej: Azul"></td>
        <td><input type="text" name="variantes[new_${variantIdx}][sku]" class="form-control form-control-sm text-center" placeholder="SKU..."></td>
        <td><input type="text" name="variantes[new_${variantIdx}][barcode]" class="form-control form-control-sm text-center" placeholder="EAN..."></td>
        <td><input type="number" step="0.01" name="variantes[new_${variantIdx}][price]" class="form-control form-control-sm" placeholder="Precio"></td>
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

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Lógica de Selección
    window.selectUsage = function(type) {
        const cardSell = document.getElementById('card_sell');
        const cardOther = document.getElementById('card_other');
        const radioSell = document.getElementById('type_sell');
        const radioOther = document.getElementById('type_other');
        const subLevel = document.getElementById('sub_level_other');
        const isSellableHidden = document.getElementById('is_sellable_hidden');
        const usageTypeReal = document.getElementById('usage_type_real');

        if (type === 'sell') {
            cardSell.classList.add('active');
            cardOther.classList.remove('active');
            radioSell.checked = true;
            subLevel.style.display = 'none';
            isSellableHidden.value = "1";
            usageTypeReal.value = "sell";
            
            // Desmarcar sub-opciones
            document.querySelectorAll('input[name="usage_type_sub"]').forEach(r => r.checked = false);
        } else {
            cardOther.classList.add('active');
            cardSell.classList.remove('active');
            radioOther.checked = true;
            subLevel.style.display = 'block';
            isSellableHidden.value = "0";
            
            if (!document.querySelector('input[name="usage_type_sub"]:checked')) {
                document.getElementById('usage_raw').checked = true;
                usageTypeReal.value = "raw_material";
            }
        }
    }

    // Al cambiar sub-opciones
    document.querySelectorAll('input[name="usage_type_sub"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('usage_type_real').value = this.value;
        });
    });
});
</script>
@endpush
