@extends('layouts.empresa')

@section('styles')
<style>
    .supplier-card {
        border-left: 5px solid #3b82f6;
        transition: transform 0.3s;
    }
    .supplier-card:hover {
        transform: translateY(-5px);
    }
    .product-row {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 8px;
        margin-bottom: 6px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        transition: all 0.2s;
    }
    .product-row:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(59, 130, 246, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .stock-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.8rem;
        display: inline-block;
    }
    .badge-critical { background: #dc2626; color: white; border: 1px solid #ef4444; }
    .badge-low { background: #d97706; color: white; border: 1px solid #f59e0b; }
    
    .accordion-button::after {
        filter: invert(1);
    }
    .accordion-item {
        background: transparent;
        border: none;
    }
    .activity-item {
        border-left: 2px solid rgba(255, 255, 255, 0.1);
        padding: 4px 0 4px 12px;
        position: relative;
        margin-bottom: 8px;
    }
    .activity-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 0;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #3b82f6;
    }
    .progress-minimal {
        height: 5px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 10px;
    }
    .btn-suggest {
        font-size: 0.75rem;
        padding: 4px 12px;
        border-radius: 6px;
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #60a5fa;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-suggest:hover {
        background: #2563eb;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    {{-- ENCABEZADO "ROLLS ROYCE" --}}
    <div class="glass-card p-3 mb-4 border-0">
        <div class="row align-items-center">
            <div class="col-md-5">
                <h2 class="fw-bold mb-0" style="background: linear-gradient(90deg, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Reposición Inteligente
                </h2>
                <span class="text-muted small">Análisis de {{ $totalFaltantes }} ítems con stock bajo o crítico</span>
            </div>
            <div class="col-md-7">
                <form method="GET" class="row row-cols-lg-auto g-3 align-items-center justify-content-end">
                    <div class="col-12">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="Buscar producto..." style="width: 200px;">
                    </div>
                    <div class="col-12">
                        <select name="filas" class="form-select form-select-sm bg-dark border-secondary text-white" onchange="this.form.submit()">
                            @foreach([10, 20, 50, 100] as $n)
                                <option value="{{ $n }}" @selected(request('filas', 20) == $n)>{{ $n }} filas</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-outline-light">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($totalFaltantes == 0)
        <div class="glass-card p-5 text-center">
            <div class="mb-3 fs-1">💎</div>
            <h3>¡Todo está perfecto!</h3>
            <p class="text-muted">No hay productos por debajo del stock mínimo actualmente.</p>
        </div>
    @else

        {{-- GRUPOS POR PROVEEDOR --}}
        @foreach($porProveedor as $supplierId => $items)
            @php 
                $supplier = ($supplierId === 'sin_proveedor') ? null : $proveedores[$supplierId];
                $groupName = $supplier ? $supplier->name : 'SIN PROVEEDOR ASIGNADO';
            @endphp

            <div class="glass-card mb-4 overflow-hidden border-0">
                <div class="px-3 py-2 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.08);">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                            🚚
                        </div>
                        <div>
                            <span class="fw-bold text-white small">{{ strtoupper($groupName) }}</span>
                            <span class="text-muted ms-2" style="font-size: 0.75rem;">{{ $items->count() }} ítems en falta</span>
                        </div>
                    </div>
                    @if($supplier)
                        <button class="btn btn-sm btn-primary px-3 rounded-pill fw-bold" style="font-size: 0.7rem;" onclick="generarPedido({{ $supplierId }})">
                            GENERAR PEDIDO SUGERIDO
                        </button>
                    @endif
                </div>

                <div class="p-2">
                    <div class="row g-2">
                        @foreach($items as $item)
                            @php 
                                $isCritical = $item->stock <= 0;
                                $suggested = max(0, $item->stock_ideal - $item->stock);
                                $stockPercent = $item->stock_ideal > 0 ? min(100, ($item->stock / $item->stock_ideal) * 100) : 0;
                            @endphp

                            {{-- PRODUCT CARD CON ACORDEÓN PARA ACTIVIDAD --}}
                            <div class="col-12">
                                <div class="product-row px-3 py-2">
                                    <div class="row align-items-center">
                                        <div class="col-md-5">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2 small">{{ $isCritical ? '🚨' : '⚠️' }}</div>
                                                <div>
                                                    <span class="fw-bold small text-white">{{ $item->name }}</span><br>
                                                    <span class="text-muted" style="font-size: 0.75rem;">{{ $item->rubro ? $item->rubro->nombre : 'GENERAL' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="text-muted fw-bold" style="font-size: 0.65rem;">MÉTRICA DE STOCK</span>
                                                <span class="text-white fw-bold" style="font-size: 0.65rem;">{{ $item->stock }} / {{ $item->stock_ideal }}</span>
                                            </div>
                                            <div class="progress progress-minimal">
                                                <div class="progress-bar {{ $isCritical ? 'bg-danger shadow-sm' : 'bg-warning shadow-sm' }}" style="width: {{ $stockPercent }}%"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 text-center">
                                            <span class="stock-badge {{ $isCritical ? 'badge-critical' : 'badge-low' }}">
                                                +{{ $suggested }} {{ ($item->rubro && str_contains(strtolower($item->rubro->nombre), 'peso')) ? 'KG' : 'UN' }}
                                            </span>
                                        </div>

                                        <div class="col-md-2 text-end">
                                            <button class="btn btn-suggest btn-sm" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#activity-{{ $item->id }}" 
                                                    onclick="cargarActividad({{ $item->id }})">
                                                ACTIVIDAD ↓
                                            </button>
                                        </div>
                                    </div>

                                    {{-- ACORDEÓN DE ACTIVIDAD (ROLLS ROYCE STYLE) --}}
                                    <div class="collapse" id="activity-{{ $item->id }}">
                                        <div class="mt-4 pt-3 border-top border-secondary">
                                            <div class="row">
                                                <div class="col-md-6 border-end border-secondary">
                                                    <h6 class="fw-bold text-info mb-3">🕒 Historial Reciente (Kardex)</h6>
                                                    <div id="kardex-list-{{ $item->id }}">
                                                        <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-info"></div></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ps-4">
                                                    <h6 class="fw-bold text-success mb-3">💰 Compras Anteriores</h6>
                                                    <div id="purchases-list-{{ $item->id }}">
                                                        <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-info"></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
    
    // Evitar recargar si ya tiene contenido (opcional)
    if(kardexDiv.innerHTML.includes('activity-item')) return;

    fetch(`/empresa/faltantes/actividad/${productId}`)
        .then(r => r.json())
        .then(data => {
            // Renderizar Kardex
            if(data.movimientos.length === 0) {
                kardexDiv.innerHTML = '<p class="small text-muted">Sin movimientos registrados.</p>';
            } else {
                let html = '';
                data.movimientos.forEach(m => {
                    const color = m.tipo === 'entrada' ? 'text-success' : (m.tipo === 'salida' ? 'text-danger' : 'text-warning');
                    const fecha = new Date(m.created_at).toLocaleDateString('es-AR');
                    html += `
                        <div class="activity-item small">
                            <div class="d-flex justify-content-between">
                                <b class="${color}">${m.tipo.toUpperCase()}</b>
                                <span class="text-muted">${fecha}</span>
                            </div>
                            <div class="text-white">${m.cantidad > 0 ? '+' : ''}${m.cantidad} unidades (${m.origen})</div>
                        </div>
                    `;
                });
                kardexDiv.innerHTML = html;
            }

            // Renderizar Compras
            if(data.compras.length === 0) {
                purchaseDiv.innerHTML = '<p class="small text-muted">Aún no se han registrado compras de este producto.</p>';
            } else {
                let html = '';
                data.compras.forEach(c => {
                    const fecha = new Date(c.purchase.purchase_date).toLocaleDateString('es-AR');
                    const priceFormatted = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' }).format(c.cost);
                    html += `
                        <div class="activity-item small" style="border-left-color: #10b981;">
                            <div class="d-flex justify-content-between">
                                <b class="text-white">${c.purchase.supplier.name}</b>
                                <span class="text-muted">${fecha}</span>
                            </div>
                            <div class="text-success fw-bold">${priceFormatted} <small class="text-muted">x ${c.quantity}u</small></div>
                        </div>
                    `;
                });
                purchaseDiv.innerHTML = html;
            }
        });
}

function generarPedido(supplierId) {
    // Aquí podrías redirigir a una pantalla de checkout de compra o generar un PDF
    alert('Funcionalidad de Generación de Pedido Inteligente: Próximamente disponible. Estamos agrupando los productos para el proveedor.');
}
</script>
@endpush
@endsection
