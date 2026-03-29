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
function abrirModalEtiquetaRapida(data) {
    document.getElementById('modal_product_id').value = data.id;
    document.getElementById('modal_product_id_alt').name = `selected_items[${data.id}]`;
    document.getElementById('modal_product_id_alt').value = "1";
    document.getElementById('modal_product_name').innerText = data.name;
    document.getElementById('modal_qty_oled').name = `quantities[${data.id}]`;
    new bootstrap.Modal(document.getElementById('modalEtiquetaRapida')).show();
}
</script>
@endsection
