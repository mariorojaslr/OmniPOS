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
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        margin-bottom: 10px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.2s;
    }
    .product-row:hover {
        background: rgba(255, 255, 255, 0.07);
        border-color: rgba(255, 255, 255, 0.15);
    }
    .stock-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .badge-critical { background: #ef4444; color: white; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); }
    .badge-low { background: #f59e0b; color: white; box-shadow: 0 0 15px rgba(245, 158, 11, 0.4); }
    
    .accordion-button::after {
        filter: invert(1);
    }
    .accordion-item {
        background: transparent;
        border: none;
    }
    .activity-item {
        border-left: 2px solid rgba(255, 255, 255, 0.1);
        padding-left: 15px;
        position: relative;
        margin-bottom: 15px;
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
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    {{-- ENCABEZADO "ROLLS ROYCE" --}}
    <div class="glass-card p-4 mb-4 border-0 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold mb-1" style="background: linear-gradient(90deg, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Centro de Reposición Inteligente
            </h1>
            <p class="text-muted mb-0">Análisis avanzado de faltantes y sugerencias de compra</p>
        </div>
        <div class="text-end">
            <span class="d-block small text-muted">Items en falta</span>
            <span class="fs-2 fw-bold text-danger">{{ $totalFaltantes }}</span>
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
                $groupName = $supplier ? $supplier->name : 'Sin Proveedor Asignado';
            @endphp

            <div class="glass-card mb-5 overflow-hidden border-0">
                <div class="p-4 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="text-white">🚚</i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-white">{{ $groupName }}</h4>
                            <small class="text-muted">{{ $items->count() }} productos pendientes de reposición</small>
                        </div>
                    </div>
                    @if($supplier)
                        <button class="btn btn-primary px-4 rounded-pill shadow" onclick="generarPedido({{ $supplierId }})">
                            <i class="me-1">📝</i> Generar Pedido Sugerido
                        </button>
                    @else
                        <span class="text-warning small fst-italic">Asigne proveedores para generar pedidos automáticos</span>
                    @endif
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        @foreach($items as $item)
                            @php 
                                $isCritical = $item->stock <= 0;
                                $suggested = max(0, $item->stock_ideal - $item->stock);
                                $stockPercent = $item->stock_ideal > 0 ? min(100, ($item->stock / $item->stock_ideal) * 100) : 0;
                            @endphp

                            {{-- PRODUCT CARD CON ACORDEÓN PARA ACTIVIDAD --}}
                            <div class="col-12">
                                <div class="product-row p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-5">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 fs-4">{{ $isCritical ? '🚨' : '⚠️' }}</div>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-white">{{ $item->name }}</h6>
                                                    <small class="text-muted">{{ $item->rubro ? $item->rubro->nombre : 'Sin rubro' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="small text-muted mb-1">Estado de Stock</div>
                                            <div class="progress progress-minimal mb-2">
                                                <div class="progress-bar {{ $isCritical ? 'bg-danger' : 'bg-warning' }}" style="width: {{ $stockPercent }}%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between small">
                                                <span>Actual: <b>{{ $item->stock }}</b></span>
                                                <span>Ideal: <b>{{ $item->stock_ideal }}</b></span>
                                            </div>
                                        </div>

                                        <div class="col-md-2 text-center">
                                            <div class="small text-muted mb-1">Sugerido</div>
                                            <span class="stock-badge {{ $isCritical ? 'badge-critical' : 'badge-low' }}">
                                                +{{ $suggested }} {{ ($item->rubro && str_contains(strtolower($item->rubro->nombre), 'peso')) ? 'kg' : 'u' }}
                                            </span>
                                        </div>

                                        <div class="col-md-2 text-end">
                                            <button class="btn btn-link text-info text-decoration-none btn-sm" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#activity-{{ $item->id }}" 
                                                    onclick="cargarActividad({{ $item->id }})">
                                                Ver Actividad ↓
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
