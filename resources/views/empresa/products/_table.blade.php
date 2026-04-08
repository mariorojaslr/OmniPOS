@php
    // Función auxiliar para resaltar texto en amarillo (Case Insensitive)
    if (!function_exists('highlight')) {
        function highlight($text, $query) {
            if (empty($query)) return $text;
            $safeQuery = preg_quote($query, '/');
            return preg_replace('/(' . $safeQuery . ')/i', '<mark class="p-0" style="background-color: #ffeb3b; color: #000; border-radius:3px; padding: 0 2px !important;">$1</mark>', $text);
        }
    }
@endphp

<table class="table table-hover align-middle mb-0" id="tablaProductos">
    <thead class="bg-light">
        <tr>
            <th class="ps-4" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">PRODUCTO</th>
            <th class="text-center">MEDIA</th> {{-- NUEVA POSICIÓN PARA MINIATURA --}}
            <th class="text-center">RUBRO</th>
            <th class="text-center">PRECIO</th>
            <th class="text-center">STOCK</th>
            <th class="text-center text-nowrap">ESTADO</th>
            <th class="text-end pe-4">ACCIONES</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr>
                <td class="ps-4">
                    <div class="nombre-producto fw-bold" style="font-size: 0.95rem;">
                        {!! highlight($product->name, $buscar) !!}
                        @if($product->usage_type !== 'sell')
                            <span class="badge bg-secondary ms-1" style="font-size: 0.6rem; vertical-align: middle; text-transform: uppercase;">
                                {{ $product->usage_type === 'raw_material' ? 'M. Prima' : ($product->usage_type === 'supply' ? 'Insumo' : 'Gasto') }}
                            </span>
                        @endif
                        @if(!$product->is_sellable)
                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.6rem; vertical-align: middle;">NO VENTA</span>
                        @endif
                    </div>
                    @if($product->barcode || $product->sku)
                        <div class="small text-muted" style="font-size: 0.75rem;">
                            @if($product->barcode)
                                <i class="bi bi-barcode me-1"></i> {!! highlight($product->barcode, $buscar) !!}
                            @endif
                            @if($product->sku)
                                <span class="ms-2">
                                    <i class="bi bi-tag me-1"></i> SKU: {!! highlight($product->sku, $buscar) !!}
                                </span>
                            @endif
                        </div>
                    @endif
                </td>
                
                {{-- COLUMNA DE MEDIA CON FOTO REAL --}}
                <td class="text-center">
                    @if($product->images->count() > 0)
                        <div class="position-relative d-inline-block">
                            <img src="{{ $product->images->first()->url }}" 
                                 class="rounded-3 shadow-sm border" 
                                 style="width: 45px; height: 45px; object-fit: cover;"
                                 onerror="this.src='https://placehold.co/45x45?text=Img'">
                            <span class="position-absolute top-100 start-100 translate-middle badge rounded-pill bg-dark border border-light" style="font-size: 0.55rem;">
                                {{ $product->images->count() }}
                            </span>
                        </div>
                    @else
                        <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center text-muted mx-auto" style="width: 45px; height: 45px;">
                            <i class="bi bi-image" style="font-size: 1.2rem; opacity: 0.2;"></i>
                        </div>
                    @endif
                </td>

                <td class="text-center">
                    <span class="badge border text-muted small px-2 py-1 bg-light">
                        {!! highlight($product->rubro?->nombre ?? 'Sin rubro', $buscar) !!}
                    </span>
                </td>
                <td class="text-center fw-bold text-dark">
                    ${{ number_format($product->price, 2, ',', '.') }}
                </td>
                <td class="text-center">
                    <div class="d-flex flex-column align-items-center">
                        <span class="fw-bold {{ $product->stock <= $product->stock_min ? 'text-danger' : 'text-dark' }}">
                            {{ number_format($product->stock, 0) }}
                        </span>
                        <div class="text-muted" style="font-size: 0.6rem; opacity: 0.7;">MIN: {{ $product->stock_min }} / OPT: {{ $product->stock_ideal }}</div>
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
                <td class="text-end pe-4 text-nowrap">
                    <button type="button" class="btn btn-sm btn-outline-warning p-1" title="Etiquetas" 
                            onclick="abrirModalEtiquetaRapida({{ json_encode(['id'=>$product->id, 'name'=>$product->name]) }})">
                        🏷️
                    </button>
                    <a href="{{ route('empresa.products.edit', $product) }}" class="btn btn-sm btn-outline-primary py-1 px-2 fw-bold" style="font-size: 0.65rem;">Editar</a>
                    
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle py-1 px-2 fw-bold" style="font-size: 0.65rem;" type="button" data-bs-toggle="dropdown">Media</button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-secondary">
                            <li><a class="dropdown-item small fw-bold" href="{{ route('empresa.products.images.create', $product) }}">📸 Gestionar Fotos</a></li>
                            <li><a class="dropdown-item small fw-bold" href="{{ route('empresa.products.videos.index', $product) }}">🎬 Gestionar Videos</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted fw-bold italic fs-4 shadow-inner" style="background: rgba(0,0,0,0.01);">
                    <div class="mb-2">⚠️ No se encontraron resultados</div>
                    <div class="small fw-normal">Intente con otro término o revise el rubro.</div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="p-3 border-top bg-light d-flex justify-content-between align-items-center paginacion-ajax">
    <div class="small fw-bold text-muted">Mostrando {{ $products->firstItem() ?? 0 }} a {{ $products->lastItem() ?? 0 }} de un total de {{ $products->total() }} artículos encontrados</div>
    {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
</div>
