@extends('layouts.empresa')

@section('content')

{{-- CDN DE ICONOS REFORZADO --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

    body { background-color: var(--bg-color) !important; color: var(--text-color) !important; transition: all 0.3s ease; }
    
    .card-premium {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    /* BUSCADOR ROLLS-ROYCE GLOBAL */
    .search-group {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .search-ctrl {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-color) !important;
        border-radius: 50px !important;
        padding: 12px 25px 12px 50px !important;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.01);
    }
    
    .search-ctrl:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0.05) !important;
        outline: none;
    }

    .btn-search-icon {
        position: absolute;
        left: 15px;
        background: none;
        border: none;
        color: #3b82f6;
        font-size: 1.2rem;
        z-index: 5;
    }

    #searchLoadingOverlay {
        display: none;
        position: absolute;
        right: 20px;
        z-index: 10;
    }
</style>

<div class="container-fluid px-4 py-3">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="fw-bold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}" style="letter-spacing: -1px;">Gestión de Artículos</h1>
            <div class="d-flex align-items-center gap-2 mt-1">
                <span class="badge bg-primary rounded-pill px-2" style="font-size: 0.6rem;">MOTOR GLOBAL V6.0</span>
                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; opacity: 0.6;">Detección Profunda con Resaltado</small>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap justify-content-end">
            {{-- BOTONES DE EXCEL (PROMINENTES) --}}
            <a href="{{ route('empresa.products.export') }}" class="btn btn-success shadow-sm rounded-pill px-4 d-flex align-items-center gap-2" style="font-size:0.85rem; font-weight:800;">
                <i class="bi bi-file-earmark-excel fs-5"></i> EXPORTAR EXCEL
            </a>
            <button type="button" class="btn btn-info text-white shadow-sm rounded-pill px-4 d-flex align-items-center gap-2" style="font-size:0.85rem; font-weight:800;" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-cloud-arrow-up fs-5"></i> IMPORTAR EXCEL
            </button>

            <div class="vr mx-2 opacity-10"></div>

            {{-- BOTONES DE UTILIDAD --}}
            <a href="{{ route('empresa.inventory_scan') }}" class="btn btn-outline-primary shadow-sm rounded-pill px-3 d-flex align-items-center gap-2" style="font-size:0.8rem; font-weight:700;">
                <i class="bi bi-qr-code-scan"></i> ESCÁNER
            </a>
            <a href="{{ route('empresa.labels.index') }}" class="btn btn-outline-dark shadow-sm rounded-pill px-3 d-flex align-items-center gap-2" style="font-size:0.8rem; font-weight:700;">
                <i class="bi bi-printer"></i> ETIQUETAS
            </a>

            <a href="{{ route('empresa.products.create') }}" class="btn btn-primary shadow-lg rounded-pill px-4 d-flex align-items-center gap-2" style="font-size:0.85rem; font-weight:800; background: linear-gradient(45deg, #2563eb, #1d4ed8);">
                <i class="bi bi-plus-circle-fill"></i> NUEVO PRODUCTO
            </a>
        </div>
    </div>

    {{-- MOTOR DE BÚSQUEDA AJAX --}}
    <div class="row mb-4">
        <div class="col-md-7">
            <div class="search-group">
                <button class="btn-search-icon" onclick="performSearch(1)"><i class="bi bi-search"></i></button>
                <input type="text" id="globalSearchInput" class="form-control search-ctrl" 
                       placeholder="Escriba código, nombre o rubro..." 
                       value="{{ $buscar }}"
                       oninput="liveSearchTrigger()"
                       autocomplete="off">
                <div id="searchLoadingOverlay">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
             <div class="d-flex align-items-center gap-2 h-100 ps-2">
                <span class="small text-muted fw-bold text-uppercase" style="font-size:0.6rem;">Filas</span>
                <select id="perPageSelectAjax" class="form-select border-0 bg-light rounded-pill" style="width: 80px;" onchange="performSearch(1)">
                    @foreach([10,15,25,50,100] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 15)==$size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 text-end d-flex align-items-center justify-content-end">
            <div class="text-muted fw-bold" style="font-size: 0.85rem; background: rgba(0,0,0,0.03); padding: 5px 15px; border-radius: 20px;">
                Carga Total: <span id="resultsCounterTop" class="text-primary">{{ $products->total() }}</span> registros
            </div>
        </div>
    </div>

    {{-- RESULTADOS --}}
    <div class="card card-premium shadow-lg border-0" id="tableWrapper">
        <div id="tableContainer" class="table-responsive">
            @include('empresa.products._table', ['products' => $products, 'buscar' => $buscar])
        </div>
    </div>
</div>

@include('empresa.products._modals')

@endsection

@section('scripts')
<script>
    let searchTimer;
    const searchField = document.getElementById('globalSearchInput');
    const tableArea = document.getElementById('tableContainer');
    const totalNode = document.getElementById('resultsCounterTop');
    const loader = document.getElementById('searchLoadingOverlay');

    function liveSearchTrigger() {
        clearTimeout(searchTimer);
        loader.style.display = 'block';
        searchTimer = setTimeout(() => performSearch(1), 350);
    }

    function performSearch(page = 1) {
        const query = searchField.value;
        const perPageSelection = document.getElementById('perPageSelectAjax').value;
        const urlEndpoint = "{{ route('empresa.products.index') }}";
        
        const fetchParams = new URLSearchParams({
            q: query,
            per_page: perPageSelection,
            page: page
        });

        tableArea.style.opacity = '0.35';

        // BLINDAJE TRIPLE DE CABECERAS
        fetch(`${urlEndpoint}?${fetchParams.toString()}`, {
            method: 'GET',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-Ajax-Search': 'true',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en el servidor');
            return response.json();
        })
        .then(data => {
            if(!data || !data.html) return;
            
            tableArea.innerHTML = data.html;
            totalNode.innerText = data.total;
            
            tableArea.style.opacity = '1';
            loader.style.display = 'none';

            // Vincular paginación capturada
            relinkPagination();

            // Actualizar dirección del navegador
            const finalUrl = query.length > 0 ? `?q=${encodeURIComponent(query)}&per_page=${perPageSelection}&page=${page}` : `?per_page=${perPageSelection}&page=${page}`;
            window.history.pushState({}, '', finalUrl);
        })
        .catch(err => {
            console.error("Falla en Motor AJAX:", err);
            // Si el motor AJAX falla, refrescar página completa como plan B
            if (query.length > 2) {
               // Puedes habilitar esto si prefieres refrescar
               // window.location.href = `?q=${query}`;
            }
            tableArea.style.opacity = '1';
            loader.style.display = 'none';
        });
    }

    function relinkPagination() {
        document.querySelectorAll('.paginacion-ajax a').forEach(tag => {
            tag.addEventListener('click', function(ev) {
                ev.preventDefault();
                const lnk = new URL(this.href);
                performSearch(lnk.searchParams.get('page'));
                window.scrollTo({ top: 0, behavior: 'auto' });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', relinkPagination);

    searchField.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            performSearch(1);
        }
    });

    function abrirModalEtiquetaRapida(obj) {
        if(document.getElementById('modal_product_id')) {
            document.getElementById('modal_product_id').value = obj.id;
            document.getElementById('modal_product_id_alt').name = `selected_items[${obj.id}]`;
            document.getElementById('modal_product_id_alt').value = "1";
            document.getElementById('modal_product_name').innerText = obj.name;
            new bootstrap.Modal(document.getElementById('modalEtiquetaRapida')).show();
        }
    }
</script>
@endsection
