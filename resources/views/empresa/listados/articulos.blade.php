@extends('layouts.empresa')

@section('page_title', 'Generador de Listados')

@section('content')
<div class="container-fluid py-3 px-4">

    {{-- PANEL DE FILTROS (No se imprime) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 no-print" style="background: #ffffff;">
        <div class="card-body p-3">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                <i class="bi bi-sliders text-primary"></i> Centro de Comando de Listados
            </h6>
            
            <form action="{{ route('empresa.listados.articulos') }}" method="GET" class="row g-2 align-items-end">
                {{-- Filtro por Letra --}}
                <div class="col-md-2">
                    <label class="x-small fw-bold text-muted mb-1">Rango A-Z</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="desde" value="{{ request('desde') }}" class="form-control" placeholder="Desde" maxlength="10">
                        <input type="text" name="hasta" value="{{ request('hasta') }}" class="form-control" placeholder="Hasta" maxlength="10">
                    </div>
                </div>

                {{-- Filtro por Rubro --}}
                <div class="col-md-3">
                    <label class="x-small fw-bold text-muted mb-1">Categorías</label>
                    <select name="rubro_id[]" class="form-select form-select-sm border-primary fw-bold" style="border-radius: 8px;">
                        <option value="">-- TODO EL CATÁLOGO --</option>
                        @foreach($rubros as $r)
                            <option value="{{ $r->id }}" {{ is_array(request('rubro_id')) && in_array($r->id, request('rubro_id')) ? 'selected' : '' }}>
                                {{ $r->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Opciones Checkbox --}}
                <div class="col-md-4 d-flex align-items-center gap-3 pb-1">
                    <div class="form-check form-switch cursor-pointer">
                        <input class="form-check-input" type="checkbox" name="solo_stock" id="solo_stock" value="1" {{ request('solo_stock') ? 'checked' : '' }}>
                        <label class="form-check-label x-small fw-bold" for="solo_stock">Con Stock</label>
                    </div>
                    <div class="form-check form-switch cursor-pointer">
                        <input class="form-check-input" type="checkbox" name="mostrar_fotos" id="mostrar_fotos" value="1" {{ request('mostrar_fotos') ? 'checked' : '' }}>
                        <label class="form-check-label x-small fw-bold text-primary" for="mostrar_fotos"><i class="bi bi-image me-1"></i>Fotos</label>
                    </div>

                    {{-- Selector de Tamaño de Fotos (DROPDOWN COMPACTO) --}}
                    @if(request('mostrar_fotos'))
                    <div class="border-start ps-3 ms-2">
                        <select name="foto_size" class="form-select form-select-sm py-0" style="font-size: 0.7rem; height: 28px;" onchange="this.form.submit()">
                            <option value="38" {{ request('foto_size') == '38' || !request('foto_size') ? 'selected' : '' }}>Mini (1cm)</option>
                            <option value="113" {{ request('foto_size') == '113' ? 'selected' : '' }}>Catálogo (3cm)</option>
                            <option value="189" {{ request('foto_size') == '189' ? 'selected' : '' }}>Grande (5cm)</option>
                        </select>
                    </div>
                    @endif
                </div>

                {{-- Botones de Acción --}}
                <div class="col-md-3 d-flex gap-2 justify-content-end">
                    <a href="{{ route('empresa.listados.articulos') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold border">
                        RESET
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-funnel-fill me-1"></i> APLICAR FILTROS
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- CABECERA DEL LISTADO --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold text-dark mb-0 d-none d-print-block">MultIPOS - Catálogo de Artículos</h2>
            <h5 class="fw-bold text-dark mb-0 no-print"><i class="bi bi-layout-text-sidebar-reverse me-2"></i>Previsualización del Listado</h5>
            <p class="text-muted x-small mb-0 opacity-75">
                {{ $items->count() }} Artículos encontrados • Generado el {{ date('d/m/Y H:i') }}
            </p>
        </div>
        <button onclick="window.print()" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold shadow-sm no-print">
            <i class="bi bi-printer-fill me-2"></i> IMPRIMIR
        </button>
    </div>

    {{-- LISTADO --}}
    <div class="card border-1 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-body p-0">
            <table class="table table-hover table-sm align-middle mb-0 printable-table">
                <thead class="bg-light">
                    <tr class="x-small fw-bold text-muted text-uppercase ls-1" style="height: 45px;">
                        <th class="ps-4 py-2" style="width: 40px;">#</th>
                        @if(request('mostrar_fotos'))
                        <th class="py-2 text-center" style="width: 100px;">Foto</th>
                        @endif
                        <th class="py-2">Producto / Descripción</th>
                        <th class="py-2">Rubro</th>
                        <th class="text-end py-2">Precio Venta</th>
                        <th class="text-end py-2 pe-4" style="width: 130px;">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i)
                    <tr>
                        <td class="ps-4 border-end-0">
                            <div class="check-box border rounded d-inline-block" style="width: 14px; height: 14px;"></div>
                        </td>
                        @if(request('mostrar_fotos'))
                        <td class="text-center">
                            @php 
                                $imgSize = request('foto_size', 38); 
                                // Usamos el fallback de local-media para asegurar que carguen en Hostinger
                                $imgPath = $i->image ? route('local.media', ['path' => $i->image]) : null;
                            @endphp
                            <div class="rounded-3 border overflow-hidden bg-light mx-auto" style="width: {{ $imgSize }}px; height: {{ $imgSize }}px;">
                                @if($i->image)
                                    <img src="{{ $imgPath }}" class="w-100 h-100 object-fit-cover" alt="">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted opacity-25">
                                        <i class="bi bi-image" style="font-size: {{ $imgSize * 0.4 }}px;"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        @endif
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $i->name }}</div>
                            @if($i->barcode)<div class="x-small text-muted font-monospace"><i class="bi bi-upc-scan me-1"></i>{{ $i->barcode }}</div>@endif
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border rounded-pill py-1 px-2" style="font-size: 0.7rem;">
                                {{ $i->rubro->nombre ?? 'GENERAL' }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark fs-6">
                            ${{ number_format($i->price, 2, ',', '.') }}
                        </td>
                        <td class="text-end pe-4">
                            <span class="small fw-bold {{ $i->stock <= 0 ? 'text-danger' : 'text-primary' }}">
                                {{ (float)$i->stock }} <span class="x-small text-muted">{{ $i->unit->abbreviation ?? 'un' }}</span>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <div class="text-muted opacity-50">
                                <i class="bi bi-search fs-1 mb-3 d-block"></i>
                                <span class="fw-bold">No se encontraron productos con estos filtros.</span><br>
                                <small>Intenta resetear la búsqueda para ver todos.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    .x-small { font-size: 0.65rem; }
    .ls-1 { letter-spacing: 1px; }
    .check-box { border: 1px solid #ddd !important; }
    
    .table > :not(caption) > * > * {
        border-bottom-width: 1px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    /* Select2 Dark Support */
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 12px;
        border-color: #dee2e6;
    }

    @media print {
        header.top-bar, #sidebar, .no-print, .sidebar-overlay, #help-trigger {
            display: none !important;
        }
        #main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background: white !important;
        }
        body { 
            background: white !important; 
            padding: 0 !important;
            margin: 0 !important;
        }
        .container-fluid { padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table { width: 100% !important; border: 1px solid #eee !important; }
        .table th { background-color: #f8f9fa !important; border-bottom: 2px solid #000 !important; }
        .table td { border-bottom: 1px solid #eee !important; }
        .object-fit-cover { object-fit: contain !important; }
    }
</style>
@endsection
