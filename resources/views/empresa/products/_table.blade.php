<table class="table table-hover align-middle mb-0" id="tablaProductos">
    <thead class="bg-light">
        <tr>
            <th class="ps-4">PRODUCTO</th>
            <th class="text-center">RUBRO</th>
            <th class="text-center">PRECIO</th>
            <th class="text-center">STOCK</th>
            <th class="text-center">ESTADO</th>
            <th class="text-center">MEDIA</th>
            <th class="text-end pe-4">ACCIONES</th>
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
                        <span class="text-muted" style="font-size: 0.65rem;">Min: {{ $product->stock_min }} Ideal: {{ $product->stock_ideal }}</span>
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
                    <button type="button" class="btn btn-sm btn-outline-warning p-1" title="Etiquetas" 
                            onclick="abrirModalEtiquetaRapida({{ json_encode(['id'=>$product->id, 'name'=>$product->name]) }})">
                        <i class="bi bi-tag-fill"></i> 🏷️
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
            <tr><td colspan="7" class="text-center py-5 text-muted fw-bold">No se encontraron artículos con ese nombre en todo el catálogo</td></tr>
        @endforelse
    </tbody>
</table>

<div class="p-3 border-top bg-light paginacion-ajax">
    {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
</div>
