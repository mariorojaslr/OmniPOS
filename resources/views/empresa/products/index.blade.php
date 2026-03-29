@extends('layouts.empresa')

@section('content')

<style>
    /* Estética OLED para el listado de productos */
    body { background: #000 !important; }
    .card-oled {
        background: rgba(22, 27, 34, 0.8) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(44, 54, 66, 0.5) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4);
    }
    .table-oled thead th { 
        background: rgba(48, 54, 61, 0.5) !important; 
        color: #8b949e !important; 
        border-bottom: 2px solid #30363d !important;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .table-oled tbody td { 
        color: #e6edf3 !important; 
        border-bottom: 1px solid #21262d !important; 
    }
    .btn-outline-oled { 
        color: #e6edf3 !important; 
        border-color: #30363d !important;
        transition: all 0.3s ease;
    }
    .btn-outline-oled:hover {
        background: rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    .search-oled {
        background: rgba(0, 0, 0, 0.4) !important;
        border: 1px solid #30363d !important;
        color: #fff !important;
        border-radius: 0.75rem;
    }
    .search-oled:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.3);
    }
    .extra-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 1px; }
</style>

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-white">Gestión de Artículos</h2>
            <small class="text-secondary tracking-wider fs-6">Catálogo Profesional OLED</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('empresa.labels.index') }}" class="btn btn-outline-oled d-flex align-items-center gap-2">
                <i class="bi bi-tag"></i> Etiquetas
            </a>
            <a href="{{ route('empresa.products.export') }}" class="btn btn-warning d-flex align-items-center gap-2 text-dark fw-bold shadow-sm">
                <i class="bi bi-download"></i> Planilla
            </a>
            <button type="button" class="btn btn-outline-oled d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload"></i> Importar
            </button>
            <a href="{{ route('empresa.products.create') }}" class="btn btn-primary d-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
        </div>
    </div>

    {{-- MODAL IMPORTACIÓN --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('empresa.products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content card-oled">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-white fw-bold">Importar Artículos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-white">
                        <div class="alert alert-info border-0 bg-primary bg-opacity-10 text-primary small">
                            Suba un archivo CSV con separador <strong>";" (punto y coma)</strong>.
                        </div>
                        <input type="file" name="csv_file" class="form-control search-oled" accept=".csv" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-link text-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Subir y Procesar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- FILTROS OLED --}}
    <div class="card card-oled mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <input type="text" id="buscadorProductos" class="form-control search-oled" placeholder="Buscar artículo en tiempo real...">
                </div>
                <div class="col-md-3 d-flex align-items-center gap-2">
                    <label class="small text-secondary mb-0">Ver</label>
                    <select id="perPageSelect" class="form-select search-oled form-select-sm">
                        @foreach([10,15,25,50,100] as $size)
                            <option value="{{ $size }}" {{ request('per_page',15)==$size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-end small text-secondary">
                    Total: {{ $products->total() }} artículos
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA OLED --}}
    <div class="card card-oled overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-oled align-middle mb-0" id="tablaProductos">
                    <thead>
                        <tr>
                            <th class="ps-4">Artículo</th>
                            <th>Rubro</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    <div class="nombre-producto fw-bold">{{ $product->name }}</div>
                                    @if($product->barcode)
                                        <div class="text-secondary extra-small">GTIN: {{ $product->barcode }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $product->rubro ? 'bg-secondary bg-opacity-25 text-white' : 'text-muted italic' }} border border-secondary shadow-sm">
                                        {{ $product->rubro?->nombre ?? 'Sin rubro' }}
                                    </span>
                                </td>
                                <td class="fw-bold fs-5 text-white text-nowrap">
                                    ${{ number_format($product->price, 2, ',', '.') }}
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold fs-5 {{ $product->stock <= $product->stock_min ? 'text-danger' : 'text-success' }}">
                                            {{ $product->stock }}
                                        </span>
                                        <span class="text-secondary extra-small">Mín: {{ $product->stock_min }}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    <button type="button" class="btn btn-sm btn-outline-oled me-1" title="Etiquetas" 
                                            onclick="abrirModalEtiquetaRapida({{ json_encode(['id'=>$product->id, 'name'=>$product->name]) }})">
                                        🏷️
                                    </button>
                                    <a href="{{ route('empresa.products.edit', $product) }}" class="btn btn-sm btn-outline-oled me-1">Editar</a>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-outline-oled dropdown-toggle" type="button" data-bs-toggle="dropdown">Media</button>
                                        <ul class="dropdown-menu dropdown-menu-dark shadow-lg">
                                            <li><a class="dropdown-item" href="{{ route('empresa.products.images.create', $product) }}">📸 Imágenes</a></li>
                                            <li><a class="dropdown-item" href="{{ route('empresa.products.videos.index', $product) }}">🎬 Videos</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-secondary">No hay artículos cargados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-black bg-opacity-25 border-top border-secondary border-opacity-25">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- MODAL IMPRESIÓN OLED ROLLS-ROYCE --}}
<div class="modal fade" id="modalEtiquetaRapida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="background: #000; border: 1px solid rgba(59, 130, 246, 0.5); border-radius: 1.5rem; overflow: hidden;">
            <form id="formEtiquetaRapida" action="{{ route('empresa.labels.generate') }}" method="POST" target="_blank">
                @csrf
                {{-- Campos ocultos para el controlador --}}
                <input type="hidden" name="items[]" id="modal_product_id">
                <input type="hidden" name="selected_items[0]" id="modal_product_id_alt">
                
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                        <span style="display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: rgba(59, 130, 246, 0.2); border-radius: 10px; color: #3b82f6;">
                            🏷️
                        </span>
                        Impresión de Etiquetas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <p class="text-secondary small mb-3">Configurando impresión para:</p>
                    <div class="p-3 mb-4 rounded-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <h5 class="text-white fw-bold mb-0" id="modal_product_name"></h5>
                    </div>
                    
                    {{-- 1. SELECCIÓN DE TAMAÑO --}}
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold text-uppercase mb-2 tracking-wider">1. Formato de Etiqueta</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="format" id="mFormatSmall" value="small">
                                <label class="btn btn-outline-primary w-100 py-3 rounded-4" for="mFormatSmall">
                                    <span class="d-block fw-bold small">CHICA</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="format" id="mFormatMedium" value="medium" checked>
                                <label class="btn btn-outline-primary w-100 py-3 rounded-4" for="mFormatMedium">
                                    <span class="d-block fw-bold small">MEDIA</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="format" id="mFormatLarge" value="large">
                                <label class="btn btn-outline-primary w-100 py-3 rounded-4" for="mFormatLarge">
                                    <span class="d-block fw-bold small">GRANDE</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- 2. MODO DE CANTIDAD --}}
                    <div class="mb-2">
                        <label class="form-label text-secondary small fw-bold text-uppercase mb-2 tracking-wider">2. Cantidad</label>
                        
                        <div class="d-flex flex-column gap-3">
                            {{-- Hojas completas --}}
                            <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1);">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="qty_mode" id="qtyFull" value="full" checked onchange="toggleQtyInputOLED(this)">
                                    <label class="form-check-label text-white fw-bold" for="qtyFull">Llenar hojas A4</label>
                                    <div id="sheetsWrap" class="mt-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" name="sheets" value="1" min="1" max="50" class="form-control bg-black text-white border-secondary w-25">
                                            <span class="text-secondary small">página(s) completas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Cantidad específica --}}
                            <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1);">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="qty_mode" id="qtySpecific" value="specific" onchange="toggleQtyInputOLED(this)">
                                    <label class="form-check-label text-white fw-bold" for="qtySpecific">Cantidad específica</label>
                                    <div id="unitsWrap" style="display:none;" class="mt-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" name="dynamic_qty" id="modal_qty_oled" value="10" min="1" max="999" class="form-control bg-black text-white border-secondary w-25">
                                            <span class="text-secondary small">unidades en total</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg" style="background: #3b82f6; border: none;">
                        GENERAR PDF PROFESIONAL 🚀
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

function toggleQtyInputOLED(radio) {
    document.getElementById('sheetsWrap').style.display = (radio.value==='full')?'block':'none';
    document.getElementById('unitsWrap').style.display = (radio.value==='specific')?'block':'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscadorProductos');
    const filas = document.querySelectorAll('#tablaProductos tbody tr');
    buscador.addEventListener('keyup', function() {
        let v = this.value.toLowerCase();
        filas.forEach(f => {
            let n = f.querySelector('.nombre-producto').innerText.toLowerCase();
            f.style.display = n.includes(v) ? '' : 'none';
        });
    });
});
</script>
@endsection

@endsection
