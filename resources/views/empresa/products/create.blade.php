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
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nombre del Artículo</label>
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

                    {{-- Precio Venta --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Precio Venta</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">$</span>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="0.00" value="{{ old('price') }}" required>
                        </div>
                        @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Costo --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Precio Costo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">$</span>
                            <input type="number" step="0.01" name="cost" class="form-control @error('cost') is-invalid @enderror" placeholder="0.00" value="{{ old('cost') }}">
                        </div>
                        @error('cost')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Unidad de Medida --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Unidad</label>
                        <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror">
                            <option value="">-- Unidad --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" @selected(old('unit_id') == $unit->id)>
                                    {{ $unit->name }} ({{ $unit->short_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Código de Barras --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Código de Barras</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">🏷️</span>
                            <input type="text"
                                   name="barcode"
                                   class="form-control @error('barcode') is-invalid @enderror"
                                   placeholder="EAN, UPC..."
                                   value="{{ old('barcode') }}">
                        </div>
                        @error('barcode')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SKU --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">SKU (Interno)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">🆔</span>
                            <input type="text"
                                   name="sku"
                                   class="form-control @error('sku') is-invalid @enderror"
                                   placeholder="Ej: REM-BLA-L"
                                   value="{{ old('sku') }}">
                        </div>
                        @error('sku')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rubro --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Rubro / Categoría</label>
                        <select name="rubro_id" class="form-select @error('rubro_id') is-invalid @enderror">
                            <option value="">-- Sin Rubro --</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->id }}" @selected(old('rubro_id') == $rubro->id)>
                                    {{ $rubro->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('rubro_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Proveedor --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Proveedor Principal</label>
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                            <option value="">-- Sin Proveedor --</option>
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->id }}" @selected(old('supplier_id') == $prov->id)>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Clasificación Avanzada --}}
                    <div class="col-md-12 mt-3">
                        <label class="form-label fw-bold d-block mb-3">¿Para qué se usará este artículo?</label>
                        
                        <div class="row g-3">
                            {{-- OPCIÓN: VENTA --}}
                            <div class="col-md-6">
                                <div class="card h-100 border p-3 selection-card" id="card_sell" onclick="selectUsage('sell')">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio" name="usage_type_main" id="type_sell" value="sell" checked>
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
                                <div class="card h-100 border p-3 selection-card" id="card_other" onclick="selectUsage('other')">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio" name="usage_type_main" id="type_other" value="other">
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

                        {{-- SUB-NIVEL: DETALLE DE INSUMO (Oculto por defecto) --}}
                        <div id="sub_level_other" class="mt-4 p-3 bg-light rounded-3" style="display: none;">
                            <label class="form-label fw-bold small text-uppercase text-muted mb-3">Especificar tipo de recurso:</label>
                            
                            <div class="d-flex flex-wrap gap-3">
                                {{-- Materia Prima --}}
                                <div class="usage-option">
                                    <input type="radio" class="btn-check" name="usage_type_sub" id="usage_raw" value="raw_material" autocomplete="off">
                                    <label class="btn btn-outline-dark d-flex align-items-center gap-2" for="usage_raw">
                                        🧶 Materia Prima
                                        <span class="text-muted" data-bs-toggle="tooltip" title="Elemento que se transforma totalmente durante el proceso productivo. Ejemplo: tela, harina.">
                                            <i class="bi bi-question-circle"></i>
                                        </span>
                                    </label>
                                </div>

                                {{-- Insumo --}}
                                <div class="usage-option">
                                    <input type="radio" class="btn-check" name="usage_type_sub" id="usage_supply" value="supply" autocomplete="off">
                                    <label class="btn btn-outline-dark d-flex align-items-center gap-2" for="usage_supply">
                                        📦 Insumo de Proceso
                                        <span class="text-muted" data-bs-toggle="tooltip" title="Elemento para apoyo de procesos productivos o embalaje. Ejemplo: hilos, pegamentos, cajas.">
                                            <i class="bi bi-question-circle"></i>
                                        </span>
                                    </label>
                                </div>

                                {{-- Consumo Interno --}}
                                <div class="usage-option">
                                    <input type="radio" class="btn-check" name="usage_type_sub" id="usage_internal" value="internal" autocomplete="off">
                                    <label class="btn btn-outline-dark d-flex align-items-center gap-2" for="usage_internal">
                                        🧹 Consumo Interno
                                        <span class="text-muted" data-bs-toggle="tooltip" title="Gasto operativo para que pase por almacén pero no se vende. Ejemplo: artículos de limpieza.">
                                            <i class="bi bi-question-circle"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Hidden inputs para compatibilidad con el backend --}}
                        <input type="hidden" name="is_sellable" id="is_sellable_hidden" value="1">
                        <input type="hidden" name="usage_type" id="usage_type_real" value="sell">

                    </div>

                </div>
            </div>
        </div>

        {{-- ============================= --}}
        {{-- INVENTARIO --}}
        {{-- ============================= --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-muted">📊 Configuración de Stock</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Inicial</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Mínimo</label>
                        <input type="number" name="stock_min" class="form-control" value="{{ old('stock_min', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Ideal</label>
                        <input type="number" name="stock_ideal" class="form-control" value="{{ old('stock_ideal', 0) }}">
                    </div>
                </div>
            </div>
        </div>
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
            
            // Desmarcar sub-opciones si estaban marcadas
            document.querySelectorAll('input[name="usage_type_sub"]').forEach(r => r.checked = false);
        } else {
            cardOther.classList.add('active');
            cardSell.classList.remove('active');
            radioOther.checked = true;
            subLevel.style.display = 'block';
            isSellableHidden.value = "0";
            
            // Si no hay ninguna sub-opción marcada, marcar la primera por defecto
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

    // Ejecutar inicialización (por si hay old input)
    const currentMain = document.querySelector('input[name="usage_type_main"]:checked').value;
    selectUsage(currentMain);
});
</script>
@endpush
