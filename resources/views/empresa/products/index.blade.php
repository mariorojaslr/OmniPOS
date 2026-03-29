@extends('layouts.empresa')

@section('content')

@php
    $modoOscuro = (auth()->user()->empresa?->config?->theme ?? 'light') === 'dark';
@endphp

<style>
    /* ESTÉTICA REPRODUCIDA DE LA CAPTURA DE REFERENCIA (MODO CLARO) */
    :root {
        --bg-color: {{ $modoOscuro ? '#000000' : '#f4f7fa' }};
        --card-bg: {{ $modoOscuro ? '#000000' : '#ffffff' }};
        --text-color: {{ $modoOscuro ? '#ffffff' : '#333333' }};
        --border-color: {{ $modoOscuro ? '#222222' : '#dee2e6' }};
        --table-header-bg: {{ $modoOscuro ? '#0a0a0a' : '#f8f9fa' }};
    }

    body { 
        background-color: var(--bg-color) !important; 
        color: var(--text-color) !important;
    }
    
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

    /* Definición clara de renglones */
    .table-premium tbody tr:hover {
        background: {{ $modoOscuro ? '#111111' : '#f8f9ff' }} !important;
    }

    .btn-action {
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 4px;
        text-transform: uppercase;
    }

    /* Badges tipo captura de referencia */
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

    /* Buscador compacto */
    .search-ctrl {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-color) !important;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid px-4 py-3">

    {{-- CABECERA LIMPIA --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">Gestión de Artículos</h2>
            <small class="text-muted">Inventario y catálogo en tiempo real</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('empresa.labels.index') }}" class="btn btn-outline-secondary btn-action">Etiquetas</a>
            <a href="{{ route('empresa.products.export') }}" class="btn btn-warning btn-action">Bajar Planilla</a>
            <button type="button" class="btn btn-outline-secondary btn-action" data-bs-toggle="modal" data-bs-target="#importModal">Importar</button>
            <a href="{{ route('empresa.products.create') }}" class="btn btn-success btn-action">Nuevo Producto</a>
        </div>
    </div>

    {{-- FILTROS Y BUSCADOR --}}
    <div class="card card-premium mb-3">
        <div class="card-body py-2">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" id="buscadorProductos" class="form-control search-ctrl" placeholder="Buscar producto en esta página...">
                </div>
                <div class="col-md-3 d-flex align-items-center gap-2">
                    <span class="small text-muted">Mostrar</span>
                    <select id="perPageSelect" class="form-select form-select-sm search-ctrl" style="width: 70px;">
                        @foreach([10,15,25,50,100] as $size)
                            <option value="{{ $size }}" {{ request('per_page',15)==$size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span class="small text-muted">filas</span>
                </div>
                <div class="col-md-4 text-end small text-muted">
                    Mostrando 1 a {{ $products->count() }} de {{ $products->total() }} registros
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE ARTÍCULOS --}}
    <div class="card card-premium overflow-hidden">
        <div class="table-responsive">
            <table class="table table-premium mb-0" id="tablaProductos">
                <thead>
                    <tr>
                        <th class="ps-4">Producto</th>
                        <th class="text-center">Rubro</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Media</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <div class="nombre-producto fw-bold">{{ $product->name }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge border text-muted small px-2 py-1 bg-light">
                                    {{ $product->rubro?->nombre ?? 'Sin rubro' }}
                                </span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                ${{ number_format($product->price, 2, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold {{ $product->stock <= $product->stock_min ? 'text-danger' : 'text-dark' }}">
                                        {{ $product->stock }}
                                    </span>
                                    <span class="text-muted" style="font-size: 0.65rem;">Mín: {{ $product->stock_min }}  Ideal: {{ $product->stock_ideal }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $st = $product->stock;
                                    $min = $product->stock_min;
                                    $label = 'OK'; $class = 'bg-ok';
                                    if($st <= 0) { $label = 'CRÍTICO'; $class = 'bg-critico'; }
                                    elseif($st <= $min) { $label = 'BAJO'; $class = 'bg-bajo'; }
                                @endphp
                                <span class="badge-status {{ $class }}">{{ $label }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-white small" style="font-size:0.6rem;">{{ $product->images->count() }} img</span>
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary p-1" title="Etiquetas" 
                                        onclick="abrirModalEtiquetaRapida({{ json_encode(['id'=>$product->id, 'name'=>$product->name]) }})">
                                    🏷️
                                </button>
                                <a href="{{ route('empresa.products.edit', $product) }}" class="btn btn-sm btn-outline-primary py-1 px-2 fw-bold" style="font-size: 0.65rem;">Editar</a>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle py-1 px-2 fw-bold" style="font-size: 0.65rem;" type="button" data-bs-toggle="dropdown">Imágenes</button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-secondary">
                                        <li><a class="dropdown-item small fw-bold" href="{{ route('empresa.products.images.create', $product) }}">📸 Gestionar Fotos</a></li>
                                        <li><a class="dropdown-item small fw-bold" href="{{ route('empresa.products.videos.index', $product) }}">🎬 Gestionar Videos</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted fw-bold">No se encontraron artículos</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top bg-light">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- MODALES REUTILIZADOS --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('empresa.products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Importar Artículos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small">Seleccione su archivo CSV (separador punto y coma).</p>
                    <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Procesar Archivo</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL ETIQUETA RÁPIDA --}}
<div class="modal fade" id="modalEtiquetaRapida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0" style="border-radius: 1rem;">
            <form id="formEtiquetaRapida" action="{{ route('empresa.labels.generate') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="items[]" id="modal_product_id">
                <input type="hidden" name="selected_items[0]" id="modal_product_id_alt">
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Impresión de Etiquetas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="p-3 mb-3 bg-light rounded text-center">
                        <h6 class="fw-bold mb-0" id="modal_product_name"></h6>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Formato</label>
                        <select name="format" class="form-select form-select-sm">
                            <option value="small">Pequeña (A4)</option>
                            <option value="medium" selected>Mediana (A4)</option>
                            <option value="large">Grande (A4)</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-uppercase">Cantidad</label>
                        <input type="number" name="dynamic_qty" id="modal_qty_oled" value="10" min="1" max="999" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow">
                        GENERAR PDF DE ETIQUETAS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function abrirModalEtiquetaRapida(data) {
    document.getElementById('modal_product_id').value = data.id;
    document.getElementById('modal_product_id_alt').name = `selected_items[${data.id}]`;
    document.getElementById('modal_product_id_alt').value = "1";
    document.getElementById('modal_product_name').innerText = data.name;
    document.getElementById('modal_qty_oled').name = `quantities[${data.id}]`;
    
    new bootstrap.Modal(document.getElementById('modalEtiquetaRapida')).show();
}

document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscadorProductos');
    const filas = document.querySelectorAll('#tablaProductos tbody tr');
    buscador.addEventListener('keyup', function() {
        let v = this.value.toLowerCase();
        filas.forEach(f => {
            let n = f.querySelector('.nombre-producto')?.innerText.toLowerCase();
            if(n) f.style.display = n.includes(v) ? '' : 'none';
        });
    });
});
</script>
@endsection
