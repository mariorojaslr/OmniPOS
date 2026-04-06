@extends('layouts.empresa')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Editor de Receta (BOM)</h2>
            <p class="text-muted small">Cree la estructura interna de sus productos estrella.</p>
        </div>
        <a href="{{ route('empresa.recipes.index') }}" class="btn btn-light border fw-bold shadow-sm px-4">
            <i class="bi bi-check2-all text-success me-1"></i> FINALIZAR ARMAZÓN
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        
        {{-- CABECERA DEL PRODUCTO TERMINADO --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-light rounded-circle p-4 me-4 border">
                        <i class="bi bi-box-seam fs-2" style="color: var(--color-primario);"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $recipe->product->name }}</h4>
                        <p class="mb-2 text-muted small pe-md-5">Al vender este producto, el sistema descontará los ingredientes proporcionalmente.</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border">P. Venta: ${{ number_format($recipe->product->price, 2) }}</span>
                            <span class="badge bg-light text-muted border opacity-75">{{ $recipe->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA IZQUIERDA: FORMULARIO AGREGAR --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-white mb-4 position-sticky" style="top: 2rem;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase d-flex align-items-center"><i class="bi bi-plus-circle-fill me-2 opacity-50"></i> Agregar Material</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('empresa.recipes.addItem', $recipe) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">Materia Prima / Insumo</label>
                            <select name="component_product_id" id="ingredient_id" class="form-select border shadow-sm rounded-3" required>
                                <option value="" selected disabled>Busque ingrediente...</option>
                                @foreach($ingredients as $i)
                                    <option value="{{ $i->id }}" data-cost="{{ $i->cost }}" data-unit="{{ $i->unit ? $i->unit->short_name : 'U' }}" data-unit-id="{{ $i->unit_id }}">
                                        {{ $i->name }} (Costo: ${{ number_format($i->cost, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Cantidad</label>
                                <div class="input-group">
                                    <input type="number" name="quantity" id="quantity" class="form-control border shadow-sm" step="0.0001" placeholder="0.00" required>
                                    <span class="input-group-text bg-light text-muted" id="unit_preview">U</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Unidad</label>
                                <select name="unit_id" id="unit_id" class="form-select border shadow-sm">
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}">{{ $u->short_name }} ({{ $u->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- CÁLCULO DE COSTO EN VIVO --}}
                        <div class="bg-light rounded p-3 mb-4 border d-flex justify-content-between align-items-center">
                            <span class="small fw-bold text-muted">Costo aporte:</span>
                            <span class="fw-bold text-dark fs-5" id="cost_preview">$0.00</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                            AÑADIR A FÓRMULA <i class="bi bi-chevron-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: LISTADO Y VALORIZACIÓN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase d-flex align-items-center">
                        <i class="bi bi-list-columns-reverse me-2" style="color: var(--color-primario);"></i> Estructura Técnica
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Componente</th>
                                <th class="text-center">Porción</th>
                                <th class="text-end">Costo U.</th>
                                <th class="text-end pe-4">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalRecipeCost = 0; @endphp
                            @forelse($recipe->items as $item)
                                @php 
                                    $itemCost = $item->quantity * $item->component->cost; 
                                    $totalRecipeCost += $itemCost;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold d-block text-dark">{{ $item->component->name }}</span>
                                        <small class="text-muted text-uppercase small">{{ $item->component->usage_type }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border fw-bold px-3 py-2">
                                            {{ floatval($item->quantity) }} {{ $item->unit->short_name }}
                                        </span>
                                    </td>
                                    <td class="text-end text-muted small">
                                        ${{ number_format($item->component->cost, 2) }}
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-dark">
                                        ${{ number_format($itemCost, 2) }}
                                    </td>
                                    <td class="text-center pe-3">
                                        <form action="{{ route('empresa.recipes.removeItem', $item) }}" method="POST" onsubmit="return confirm('¿Remover este ingrediente?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-link link-secondary opacity-50 p-0">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">
                                        <i class="bi bi-inbox fs-2 d-block mb-3 opacity-25"></i>
                                        Agregue ingredientes desde el panel izquierdo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($totalRecipeCost > 0)
                <div class="card-footer bg-light border-top p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-bold small text-uppercase">Costo Producción:</span>
                        <h4 class="fw-bold mb-0" style="color: var(--color-primario);">${{ number_format($totalRecipeCost, 2) }}</h4>
                    </div>
                    <div class="text-end">
                        <span class="text-muted fw-bold small text-uppercase">Márgen Estimado:</span>
                        @php 
                            $margin = $recipe->product->price - $totalRecipeCost;
                        @endphp
                        <h4 class="fw-bold mb-0 text-success">${{ number_format($margin, 2) }}</h4>
                    </div>
                </div>
                @endif
            </div>

            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body border-start border-4 border-info">
                   <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-question-circle-fill text-info fs-5 me-3"></i>
                        <h6 class="fw-bold mb-0 text-dark">Modo de Uso: Descuento en Cascada</h6>
                   </div>
                   <p class="text-muted small mb-0">Cada venta de este producto disparará un descuento proporcional de sus ingredientes en el stock real de forma automática.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.getElementById('ingredient_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const unit = selected.getAttribute('data-unit');
        const unitId = selected.getAttribute('data-unit-id');

        document.getElementById('unit_preview').innerText = unit;
        document.getElementById('unit_id').value = unitId;
        calculateCost();
    });

    document.getElementById('quantity').addEventListener('input', calculateCost);

    function calculateCost() {
        const selected = document.getElementById('ingredient_id').options[document.getElementById('ingredient_id').selectedIndex];
        if(!selected || selected.disabled) return;
        
        const cost = parseFloat(selected.getAttribute('data-cost') || 0);
        const qty = parseFloat(document.getElementById('quantity').value || 0);
        const subtotal = cost * qty;
        
        document.getElementById('cost_preview').innerText = '$' + subtotal.toLocaleString('es-AR', {minimumFractionDigits: 2});
    }
</script>
@endsection
