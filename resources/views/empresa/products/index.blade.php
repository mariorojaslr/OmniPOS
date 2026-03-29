@extends('layouts.empresa')

@section('content')

<div class="container py-4">

    {{-- ======================================================
        CABECERA
    ======================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Productos</h2>
            <small class="text-muted">Gestión profesional del catálogo</small>
        </div>

        <div class="d-flex gap-2">
            {{-- Botón Etiquetas --}}
            <a href="{{ route('empresa.labels.index') }}" 
               class="btn btn-outline-dark d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-tag"></i>
                Etiquetas
            </a>

            {{-- Botón Amarillo de Exportación (Planilla) --}}
            <a href="{{ route('empresa.products.export') }}" 
               class="btn btn-warning d-flex align-items-center gap-2 text-dark fw-bold shadow-sm">
                <i class="bi bi-download"></i>
                Bajar Planilla
            </a>

            {{-- Botón de Importación --}}
            <button type="button" 
                    class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm"
                    data-bs-toggle="modal" 
                    data-bs-target="#importModal">
                <i class="bi bi-upload"></i>
                Importar
            </button>

            <a href="{{ route('empresa.products.create') }}"
               class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-plus-circle"></i>
                Nuevo producto
            </a>
        </div>
    </div>

    {{-- MODAL IMPORTACIÓN --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('empresa.products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Importar Artículos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            Suba un archivo CSV con separador de campos <strong>";" (punto y coma)</strong> para actualizar o crear productos.
                        </div>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Subir y Procesar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    {{-- ======================================================
        FILTROS
    ======================================================= --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">

            <div class="row g-3 align-items-center">

                {{-- Buscador --}}
                <div class="col-md-6">
                    <input type="text"
                           id="buscadorProductos"
                           class="form-control"
                           placeholder="Buscar producto en esta página..."
                           autocomplete="off">
                </div>

                {{-- Selector filas --}}
                <div class="col-md-3 d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Mostrar</label>

                    <select id="perPageSelect"
                            class="form-select form-select-sm">
                        @foreach([10,15,25,50,100] as $size)
                            <option value="{{ $size }}"
                                {{ request('per_page',15)==$size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    <span class="small text-muted">filas</span>
                </div>

                {{-- Info resultados --}}
                <div class="col-md-3 text-end small text-muted">
                    Mostrando {{ $products->firstItem() ?? 0 }}
                    a {{ $products->lastItem() ?? 0 }}
                    de {{ $products->total() }} registros
                </div>

            </div>

        </div>
    </div>



    {{-- ======================================================
        TABLA
    ======================================================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table align-middle mb-0" id="tablaProductos">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Producto</th>
                            <th>Rubro</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Media</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)
                            <tr>

                                {{-- Nombre --}}
                                <td class="ps-4">
                                    <div class="nombre-producto fw-bold">{{ $product->name }}</div>
                                </td>

                                {{-- Rubro --}}
                                <td>
                                    @if($product->rubro)
                                        <span class="badge bg-light text-dark border">{{ $product->rubro->nombre }}</span>
                                    @else
                                        <span class="text-muted small italic">Sin rubro</span>
                                    @endif
                                </td>

                                {{-- Precio --}}
                                <td>
                                    <div class="fw-bold">${{ number_format($product->price, 2, ',', '.') }}</div>
                                    @if($product->barcode)
                                        <small class="text-muted small">Code: {{ $product->barcode }}</small>
                                    @endif
                                </td>

                                {{-- Stock --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold fs-5 {{ $product->stock <= $product->stock_min ? 'text-danger' : 'text-dark' }}">
                                            {{ $product->stock }}
                                        </span>
                                        <div class="d-flex gap-2" style="font-size: 0.75rem;">
                                            <span class="text-muted text-nowrap" title="Stock Mínimo">Mín: {{ $product->stock_min }}</span>
                                            <span class="text-muted text-nowrap" title="Stock Ideal">Ideal: {{ $product->stock_ideal }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Estado --}}
                                <td>
                                    @if($product->active)
                                        <span class="badge bg-success-subtle text-success border border-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary">Inactivo</span>
                                    @endif
                                </td>

                                {{-- Indicadores media --}}
                                <td>

                                    {{-- Imágenes --}}
                                    @if($product->images()->count() > 0)
                                        <span class="badge bg-info">
                                            {{ $product->images()->count() }} img
                                        </span>
                                    @endif

                                    {{-- Videos --}}
                                    @if($product->tieneVideos())
                                        <span class="badge bg-dark">
                                            {{ $product->videos()->count() }} vid
                                        </span>
                                    @endif

                                </td>

                                {{-- Acciones --}}
                                <td class="text-end pe-4">

                                    {{-- Imprimir Etiquetas Rápido --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-dark"
                                            title="Imprimir Etiquetas"
                                            onclick="abrirModalEtiquetaRapida({{ json_encode(['id'=>$product->id, 'name'=>$product->name]) }})">
                                        🏷️
                                    </button>

                                    <a href="{{ route('empresa.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Editar
                                    </a>

                                    <a href="{{ route('empresa.products.images.create', $product) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Imágenes
                                    </a>

                                    <a href="{{ route('empresa.products.videos.index', $product) }}"
                                       class="btn btn-sm btn-outline-dark">
                                        Videos
                                    </a>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No se encontraron productos.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="p-3">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>



{{-- ======================================================
   SCRIPT PROFESIONAL
====================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

    const buscador = document.getElementById('buscadorProductos');
    const filas = document.querySelectorAll('#tablaProductos tbody tr');

    buscador.addEventListener('keyup', function() {

        let valor = this.value.toLowerCase();

        filas.forEach(function(fila) {

            let celdaNombre = fila.querySelector('.nombre-producto');
            let textoOriginal = celdaNombre.innerText;
            let textoLower = textoOriginal.toLowerCase();

            if (textoLower.includes(valor)) {

                fila.style.display = '';

                if (valor.length > 0) {
                    const regex = new RegExp(`(${valor})`, 'gi');
                    celdaNombre.innerHTML = textoOriginal.replace(regex,
                        '<span class="bg-warning text-dark px-1">$1</span>');
                } else {
                    celdaNombre.innerText = textoOriginal;
                }

            } else {
                fila.style.display = 'none';
            }

        });

    });

    // Cambio de cantidad por página
    document.getElementById('perPageSelect')
        .addEventListener('change', function() {

            const params = new URLSearchParams(window.location.search);
            params.set('per_page', this.value);

            window.location.search = params.toString();
        });

});
</script>

{{-- MODAL IMPRESIÓN RÁPIDA DE ETIQUETAS (ESTILO ROLLS-ROYCE OLED) --}}
<div class="modal fade" id="modalEtiquetaRapida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="background: #000; color: #fff; border-radius: 24px;">
            <form id="formEtiquetaRapida" action="{{ route('empresa.labels.generate') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="items[]" id="modal_product_id">
                <input type="hidden" name="selected_items[0]" id="modal_product_id_alt">
                
                <div class="modal-header border-bottom border-light border-opacity-10 py-4 px-4">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <span style="background: #3b82f6; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">🏷️</span>
                        Imprimir Etiquetas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <p class="text-secondary small text-uppercase fw-bold letter-spacing-1 mb-3">Producto Seleccionado</p>
                    <div class="p-3 mb-4 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <strong id="modal_product_name" class="fs-5"></strong>
                    </div>
                    
                    {{-- 1. SELECCIÓN DE TAMAÑO --}}
                    <div class="mb-4">
                        <label class="info-label mb-2">1. Seleccione el Tamaño</label>
                        <div class="row g-2">
                            <div class="col-4 text-center">
                                <input type="radio" class="btn-check" name="format" id="mFormatLarge" value="large">
                                <label class="btn btn-outline-light w-100 py-3 rounded-4 oled-option" for="mFormatLarge">
                                    <div class="fw-bold fs-6">Grande</div>
                                    <div class="small opacity-50">100x50mm</div>
                                </label>
                            </div>
                            <div class="col-4 text-center">
                                <input type="radio" class="btn-check" name="format" id="mFormatMedium" value="medium" checked>
                                <label class="btn btn-outline-light w-100 py-3 rounded-4 oled-option" for="mFormatMedium">
                                    <div class="fw-bold fs-6">Mediana</div>
                                    <div class="small opacity-50">50x40mm</div>
                                </label>
                            </div>
                            <div class="col-4 text-center">
                                <input type="radio" class="btn-check" name="format" id="mFormatSmall" value="small">
                                <label class="btn btn-outline-light w-100 py-3 rounded-4 oled-option" for="mFormatSmall">
                                    <div class="fw-bold fs-6">Chica</div>
                                    <div class="small opacity-50">30x25mm</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- 2. SELECCIÓN DE CANTIDAD --}}
                    <div class="mb-3">
                        <label class="info-label mb-2">2. Cantidad a Imprimir</label>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="qty_mode" id="qtyFull" value="full" checked onchange="toggleQtyInput(this)">
                                <label class="btn btn-outline-light w-100 py-3 rounded-4 oled-option" for="qtyFull">
                                    <div class="fw-bold">Hojas Completas</div>
                                    <div class="small opacity-50">Llenar hojas A4</div>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="qty_mode" id="qtySpecific" value="specific" onchange="toggleQtyInput(this)">
                                <label class="btn btn-outline-light w-100 py-3 rounded-4 oled-option" for="qtySpecific">
                                    <div class="fw-bold">Cantidad Fija</div>
                                    <div class="small opacity-50">Por etiqueta</div>
                                </label>
                            </div>
                        </div>

                        {{-- INPUT DINÁMICO HOJAS --}}
                        <div id="wrapperSheets" class="mt-4 p-3 rounded-4" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2);">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold">¿Cuántas hojas?</div>
                                    <div class="small text-secondary">El sistema las completará</div>
                                </div>
                                <input type="number" name="sheets" value="1" min="1" max="10" class="form-control bg-dark border-0 text-white fw-bold text-center fs-5" style="width: 80px; border-radius: 12px;">
                            </div>
                        </div>

                        {{-- INPUT DINÁMICO CANTIDAD --}}
                        <div id="wrapperSpecific" class="mt-4 p-3 rounded-4" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); display: none;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold">¿Cuántas etiquetas?</div>
                                    <div class="small text-secondary">Cantidad exacta de recortes</div>
                                </div>
                                <input type="number" name="dynamic_qty" id="modal_qty_specific" value="1" min="1" max="500" class="form-control bg-dark border-0 text-white fw-bold text-center fs-5" style="width: 100px; border-radius: 12px;">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-link text-decoration-none text-secondary fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow-lg" style="letter-spacing: 1px; text-transform: uppercase;">GENERAR ETIQUETAS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .info-label { font-size: 0.75rem; text-transform: uppercase; color: #6b7280; font-weight: 800; letter-spacing: 1px; }
    .oled-option { border: 1px solid rgba(255,255,255,0.1); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .oled-option:hover { background: rgba(255,255,255,0.05); }
    .btn-check:checked + .oled-option { 
        background: #3b82f6 !important; 
        border-color: #3b82f6 !important; 
        color: #fff !important; 
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.4); 
        transform: scale(1.02);
    }
</style>

<script>
function abrirModalEtiquetaRapida(data) {
    document.getElementById('modal_product_id').value = data.id;
    document.getElementById('modal_product_id_alt').name = `selected_items[${data.id}]`;
    document.getElementById('modal_product_id_alt').value = "1";
    document.getElementById('modal_product_name').innerText = data.name;

    // Reset modals visuals
    document.getElementById('qtyFull').checked = true;
    toggleQtyInput(document.getElementById('qtyFull'));
    
    new bootstrap.Modal(document.getElementById('modalEtiquetaRapida')).show();
}

function toggleQtyInput(radio) {
    const wSheets = document.getElementById('wrapperSheets');
    const wSpecific = document.getElementById('wrapperSpecific');
    
    if (radio.value === 'specific') {
        wSheets.style.display = 'none';
        wSpecific.style.display = 'block';
    } else {
        wSheets.style.display = 'block';
        wSpecific.style.display = 'none';
    }
}
</script>

