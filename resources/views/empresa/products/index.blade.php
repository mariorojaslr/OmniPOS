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

{{-- MODAL IMPRESIÓN RÁPIDA DE ETIQUETAS --}}
<div class="modal fade" id="modalEtiquetaRapida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <form id="formEtiquetaRapida" action="{{ route('empresa.labels.generate') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="items[]" id="modal_product_id">
                <input type="hidden" name="selected_items[0]" id="modal_product_id_alt"> {{-- Compatibilidad con array asociativo --}}
                
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">🏷️ Imprimir Etiquetas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Configurando impresión para: <strong id="modal_product_name"></strong></p>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">1. Formato de Etiqueta</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="format" id="mFormatSmall" value="small" checked>
                                <label class="btn btn-outline-dark w-100 py-2 rounded-3 d-flex flex-column align-items-center" for="mFormatSmall">
                                    <span class="small fw-bold">CHICA</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="format" id="mFormatMedium" value="medium">
                                <label class="btn btn-outline-dark w-100 py-2 rounded-3 d-flex flex-column align-items-center" for="mFormatMedium">
                                    <span class="small fw-bold">MEDIA</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="format" id="mFormatLarge" value="large">
                                <label class="btn btn-outline-dark w-100 py-2 rounded-3 d-flex flex-column align-items-center" for="mFormatLarge">
                                    <span class="small fw-bold">GRANDE</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">2. Cantidad de Etiquetas</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="qty_mode" id="qtyFull" value="full" checked onchange="toggleQtyInput(this)">
                            <label class="form-check-label" for="qtyFull">
                                Toda la página (llenar hoja A4)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="qty_mode" id="qtySpecific" value="specific" onchange="toggleQtyInput(this)">
                            <label class="form-check-label" for="qtySpecific">
                                Cantidad específica
                            </label>
                        </div>
                        <div id="qtyInputWrapper" style="display:none;" class="mt-2 ps-4">
                             <input type="number" name="dynamic_qty" id="modal_qty" value="1" min="1" max="500" class="form-control w-50">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Generar PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalEtiquetaRapida(data) {
    document.getElementById('modal_product_id').value = data.id;
    document.getElementById('modal_product_id_alt').name = `selected_items[${data.id}]`;
    document.getElementById('modal_product_id_alt').value = "1";
    document.getElementById('modal_product_name').innerText = data.name;
    
    // Reset form
    document.getElementById('modal_qty').name = `quantities[${data.id}]`;
    
    new bootstrap.Modal(document.getElementById('modalEtiquetaRapida')).show();
}

function toggleQtyInput(radio) {
    const wrapper = document.getElementById('qtyInputWrapper');
    const input = document.getElementById('modal_qty');
    if (radio.value === 'specific') {
        wrapper.style.display = 'block';
        input.value = 1;
    } else {
        wrapper.style.display = 'none';
        input.value = 999; // Valor centinela para indicar "Página completa"
    }
}
</script>

