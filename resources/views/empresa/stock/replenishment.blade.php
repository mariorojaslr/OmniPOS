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

    .progress-minimal {
        height: 6px;
        background: rgba(0,0,0,0.05);
        border-radius: 10px;
    }
</style>

<div class="container-fluid px-4 py-3">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">Reposición Inteligente</h2>
            <small class="text-muted">Análisis de {{ $totalFaltantes }} ítems con stock bajo o crítico</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('empresa.stock.faltantes.export', ['q' => request('q')]) }}" class="btn btn-warning btn-action">Exportar CSV</a>
            <a href="{{ route('empresa.labels.index') }}" class="btn btn-outline-secondary btn-action">Etiquetas</a>
            <button class="btn btn-outline-secondary btn-action" onclick="window.print()">Imprimir Lista</button>
            <a href="{{ route('empresa.products.index') }}" class="btn btn-primary btn-action">Ver Catálogo</a>
        </div>
    </div>

    {{-- FILTROS Y BUSCADOR --}}
    <div class="card card-premium mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control search-ctrl" placeholder="Buscar producto en esta página...">
                </div>
                <div class="col-md-3 d-flex align-items-center gap-2">
                    <span class="small text-muted">Filas</span>
                    <select name="filas" class="form-select form-select-sm search-ctrl" onchange="this.form.submit()" style="width: 70px;">
                        @foreach([10,20,50,100] as $size)
                            <option value="{{ $size }}" @selected(request('filas', 20) == $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary py-1 px-2">Filtrar</button>
                </div>
                <div class="col-md-4 text-end small text-muted">
                    Total: {{ $totalFaltantes }} productos pendientes de reposición
                </div>
            </form>
        </div>
    </div>

    @if($totalFaltantes == 0)
        <div class="card card-premium p-5 text-center">
            <h3 class="fw-bold">SIN ALERTAS DE STOCK</h3>
            <p class="text-muted">Todo el inventario se encuentra en niveles correctos.</p>
        </div>
    @else

        {{-- GRUPOS POR PROVEEDOR --}}
        @foreach($porProveedor as $supplierId => $items)
            @php 
                $supplier = ($supplierId === 'sin_proveedor') ? null : $proveedores[$supplierId];
                $groupName = $supplier ? $supplier->name : 'SIN PROVEEDOR ASIGNADO';
            @endphp

            <div class="card card-premium mb-4 overflow-hidden border-0">
                <div class="px-4 py-2 d-flex justify-content-between align-items-center" style="background: rgba(0,0,0,0.03); border-bottom: 1px solid var(--border-color);">
                    <div class="d-flex align-items-center">
                        <span class="me-2 text-primary">🚚</span>
                        <span class="fw-bold text-dark small">{{ strtoupper($groupName) }}</span>
                        <span class="badge bg-light text-dark ms-2" style="font-size: 0.6rem; border: 1px solid var(--border-color);">
                            {{ $items->count() }} PRODUCTOS
                        </span>
                    </div>
                    @if($supplier)
                        <button class="btn btn-sm btn-primary py-1 px-3 fw-bold" style="font-size: 0.65rem;" onclick="generarPedido({{ $supplierId }})">
                            GENERAR ORDEN DE COMPRA
                        </button>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-premium mb-0" id="tablaReposicion">
                        <thead>
                            <tr class="text-center">
                                <th class="text-start ps-4">Artículo</th>
                                <th>Rubro</th>
                                <th>Métrica de Stock</th>
                                <th>Sugerido</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                @php 
                                    $isCritical = $item->stock <= 0;
                                    $suggested = max(0, $item->stock_ideal - $item->stock);
                                    $stockPercent = $item->stock_ideal > 0 ? min(100, ($item->stock / $item->stock_ideal) * 100) : 0;
                                @endphp
                                <tr class="text-center">
                                    <td class="text-start ps-4">
                                        <div class="fw-bold">{{ $item->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted small border" style="font-size: 0.6rem;">
                                            {{ strtoupper($item->rubro ? $item->rubro->nombre : 'GENERAL') }}
                                        </span>
                                    </td>
                                    <td style="width: 200px;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-muted fw-bold" style="font-size: 0.6rem;">STOCK</span>
                                            <span class="fw-bold" style="font-size: 0.7rem;">{{ $item->stock }} / {{ $item->stock_ideal }}</span>
                                        </div>
                                        <div class="progress progress-minimal">
                                            <div class="progress-bar {{ $isCritical ? 'bg-danger shadow-sm' : 'bg-warning shadow-sm' }}" 
                                                 style="width: {{ $stockPercent }}%"></div>
                                        </div>
                                    </td>
                                    <td class="fw-bold {{ $isCritical ? 'text-danger' : 'text-warning' }} fs-5">
                                        +{{ $suggested }} <span style="font-size: 0.6rem;">{{ ($item->rubro && str_contains(strtolower($item->rubro->nombre), 'peso')) ? 'KG' : 'UN' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-status {{ $isCritical ? 'bg-critico' : 'bg-bajo' }}">
                                            {{ $isCritical ? 'CRÍTICO' : 'BAJO STOCK' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-outline-primary btn-action" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#activity-{{ $item->id }}" 
                                                onclick="cargarActividad({{ $item->id }})">
                                            Ver Actividad ↓
                                        </button>
                                    </td>
                                </tr>
                                {{-- DETALLE DE ACTIVIDAD --}}
                                <tr class="collapse" id="activity-{{ $item->id }}">
                                    <td colspan="6" class="p-0 bg-light bg-opacity-50">
                                        <div class="px-5 py-3 border-start border-4 border-primary">
                                            <div class="row">
                                                <div class="col-md-6 border-end">
                                                    <h6 class="fw-bold small text-primary mb-2">MOVIMIENTOS DE KARDEX</h6>
                                                    <div id="kardex-list-{{ $item->id }}">
                                                        <div class="spinner-border spinner-border-sm text-primary"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ps-4">
                                                    <h6 class="fw-bold small text-success mb-2">COMPRAS ANTERIORES</h6>
                                                    <div id="purchases-list-{{ $item->id }}">
                                                        <div class="spinner-border spinner-border-sm text-success"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        {{-- PAGINACIÓN --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $productos->withQueryString()->links('pagination::bootstrap-5') }}
        </div>

    @endif

</div>

@push('scripts')
<script>
function cargarActividad(productId) {
    const kardexDiv = document.getElementById(`kardex-list-${productId}`);
    const purchaseDiv = document.getElementById(`purchases-list-${productId}`);
    
    if(kardexDiv.innerHTML.includes('row') || kardexDiv.innerHTML.includes('activity-item')) return;

    fetch(`/empresa/faltantes/actividad/${productId}`)
        .then(r => r.json())
        .then(data => {
            // Kardex
            if(data.movimientos.length === 0) {
                kardexDiv.innerHTML = '<p class="small text-muted">Sin movimientos.</p>';
            } else {
                let html = '';
                data.movimientos.forEach(m => {
                    const color = m.tipo === 'entrada' ? 'text-success' : (m.tipo === 'salida' ? 'text-danger' : 'text-warning');
                    const fecha = new Date(m.created_at).toLocaleDateString('es-AR');
                    html += `<div class="mb-1 small border-bottom pb-1">
                        <b class="${color}">${m.tipo.toUpperCase()}</b> | ${fecha} | ${m.cantidad} Un. (${m.origen})
                    </div>`;
                });
                kardexDiv.innerHTML = html;
            }

            // Compras
            if(data.compras.length === 0) {
                purchaseDiv.innerHTML = '<p class="small text-muted">Sin compras registradas.</p>';
            } else {
                let html = '';
                data.compras.forEach(c => {
                    const fecha = new Date(c.purchase.purchase_date).toLocaleDateString('es-AR');
                    html += `<div class="mb-1 small border-bottom pb-1">
                        <b>${c.purchase.supplier.name}</b> | ${fecha} | $${c.cost} x ${c.quantity}u
                    </div>`;
                });
                purchaseDiv.innerHTML = html;
            }
        });
}

function generarPedido(supplierId) {
    alert('Función de Pedido Proyectado próximamente.');
}
</script>
@endpush
@endsection
