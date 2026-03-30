@extends('layouts.empresa')

@section('content')

@php
    $modoOscuro = (auth()->user()->empresa?->config?->theme ?? 'light') === 'dark';
@endphp

<style>
    :root {
        --bg-color: {{ $modoOscuro ? '#000000' : '#f4f7fa' }};
        --card-bg: {{ $modoOscuro ? '#000000' : '#ffffff' }};
        --text-color: {{ $modoOscuro ? '#ffffff' : '#333333' }};
        --border-color: {{ $modoOscuro ? '#222222' : '#dee2e6' }};
        --table-header-bg: {{ $modoOscuro ? '#0a0a0a' : '#f8f9fa' }};
    }

    body { background-color: var(--bg-color) !important; color: var(--text-color) !important; }
    
    .card-premium {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .table-premium thead th {
        background: var(--table-header-bg) !important;
        color: {{ $modoOscuro ? '#ffffff' : '#666' }} !important;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        border-bottom: 2px solid var(--border-color) !important;
        padding: 10px 15px;
    }

    .table-premium tbody td {
        background: transparent !important;
        color: var(--text-color) !important;
        border-bottom: 1px solid var(--border-color) !important;
        padding: 8px 15px;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .table-premium tbody tr:hover { background: {{ $modoOscuro ? '#111111' : '#f8f9ff' }} !important; }

    .btn-action {
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .badge-status {
        font-size: 0.65rem;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 800;
        text-transform: uppercase;
    }
    
    .bg-critico { background: #dc3545; color: white; }
    .bg-bajo { background: #ffc107; color: #000; }
    .bg-ok { background: #198754; color: white; }

    .search-ctrl {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-color) !important;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    /* RESALTADO EN AMARILLO */
    mark.highlight {
        background-color: #ffeb3b;
        color: #000;
        padding: 0;
        border-radius: 2px;
    }
</style>

<div class="container-fluid px-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">Gestión de Artículos</h2>
            <small class="text-muted">Inventario y catálogo en tiempo real (Búsqueda Global Activa)</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('empresa.labels.index') }}" class="btn btn-outline-secondary btn-action">Etiquetas</a>
            <a href="{{ route('empresa.products.export') }}" class="btn btn-warning btn-action">Bajar Planilla</a>
            <button type="button" class="btn btn-outline-secondary btn-action" data-bs-toggle="modal" data-bs-target="#importModal">Importar</button>
            <a href="{{ route('empresa.products.create') }}" class="btn btn-success btn-action">Nuevo Producto</a>
        </div>
    </div>

    {{-- FILTROS CON BÚSQUEDA AJAX --}}
    <div class="card card-premium mb-3">
        <div class="card-body py-2">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" id="globalSearchInput" class="form-control search-ctrl" placeholder="Escribe para buscar en todo el catálogo..." autocomplete="off">
                </div>
                <div class="col-md-3 d-flex align-items-center gap-2">
                    <span class="small text-muted">Mostrar</span>
                    <select id="perPageSelectAjax" class="form-select form-select-sm search-ctrl" style="width: 70px;">
                        @foreach([10,15,25,50,100] as $size)
                            <option value="{{ $size }}" {{ request('per_page',15)==$size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span class="small text-muted">filas</span>
                </div>
                <div class="col-md-4 text-end small text-muted">
                    <span id="resultsCounter">Total: {{ $products->total() }} registros encontrados</span>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENEDOR DE TABLA RESPONSIVE EXTRAÍDO --}}
    <div class="card card-premium overflow-hidden" id="tableContainer">
        <div class="table-responsive">
            @include('empresa.products._table', ['products' => $products])
        </div>
    </div>
</div>

{{-- MODALES (Importar y Etiquetas) --}}
@include('empresa.products._modals')

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('globalSearchInput');
    const perPageSelect = document.getElementById('perPageSelectAjax');
    const tableContainer = document.getElementById('tableContainer');
    let searchTimeout;

    // Función principal de búsqueda AJAX
    function performSearch() {
        const query = searchInput.value;
        const perPage = perPageSelect.value;
        
        // Petición AJAX
        fetch(`{{ route('empresa.products.index') }}?q=${encodeURIComponent(query)}&per_page=${perPage}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            tableContainer.innerHTML = data.html;
            if(query.length >= 2) {
                highlightText(query);
            }
            bindPagination(); // Re-vincular eventos de paginación
        });
    }

    // Debounce para no saturar el servidor
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });

    perPageSelect.addEventListener('change', performSearch);

    // Resaltado en amarillo
    function highlightText(text) {
        if (!text) return;
        const nameElements = document.querySelectorAll('.nombre-producto');
        const regex = new RegExp(`(${text})`, 'gi');

        nameElements.forEach(el => {
            el.innerHTML = el.innerText.replace(regex, '<mark class="highlight">$1</mark>');
        });
    }

<<<<<<< HEAD
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
=======
    // Paginación vía AJAX para no perder el filtro ni el resaltado
    function bindPagination() {
        const links = document.querySelectorAll('.paginacion-ajax a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.href;
                
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    tableContainer.innerHTML = data.html;
                    highlightText(searchInput.value);
                    bindPagination();
                    window.scrollTo(0, 0);
                });
            });
        });
    }

    bindPagination();
});

// Función para el modal de etiquetas (fuera del DOMContentLoaded)
>>>>>>> staging
function abrirModalEtiquetaRapida(data) {
    document.getElementById('modal_product_id').value = data.id;
    document.getElementById('modal_product_id_alt').name = `selected_items[${data.id}]`;
    document.getElementById('modal_product_id_alt').value = "1";
    document.getElementById('modal_product_name').innerText = data.name;
<<<<<<< HEAD

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
=======
    document.getElementById('modal_qty_oled').name = `quantities[${data.id}]`;
    new bootstrap.Modal(document.getElementById('modalEtiquetaRapida')).show();
}
>>>>>>> staging
</script>
@endsection
