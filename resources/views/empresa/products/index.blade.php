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
                            <th>Código</th>
                            <th>Precio</th>
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
                                    @if($product->rubro)
                                        <small class="text-muted">{{ $product->rubro->nombre }}</small>
                                    @endif
                                </td>

                                {{-- Código --}}
                                <td>
                                    @if($product->barcode)
                                        <code class="text-dark bg-light px-2 py-1 rounded small border">{{ $product->barcode }}</code>
                                    @else
                                        <span class="text-muted small italic">S/C</span>
                                    @endif
                                </td>

                                {{-- Precio --}}
                                <td>
                                    ${{ number_format($product->price, 2, ',', '.') }}
                                </td>

                                {{-- Estado --}}
                                <td>
                                    @if($product->active)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
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
                                    <a href="{{ route('empresa.products.labels.single', $product) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-dark"
                                       title="Imprimir Etiquetas">
                                        🏷️
                                    </a>

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
                                <td colspan="5" class="text-center py-4 text-muted">
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

@endsection
