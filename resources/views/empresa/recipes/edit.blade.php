@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Editor de Receta (BOM)</h2>
            <p class="text-muted small">Cree la estructura interna de sus productos estrella.</p>
        </div>
        <a href="{{ route('empresa.recipes.index') }}" class="btn btn-light border fw-bold shadow-sm px-4">
            <i class="bi bi-check2-circle text-success me-1"></i> FINALIZAR ARMAZÓN
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
                        <i class="bi bi-box-seam fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $recipe->product->name }}</h4>
                        <p class="mb-2 text-muted small pe-md-5">Al vender este producto, el sistema buscará esta receta y descontará los ingredientes proporcionalmente.</p>
                        <div class="d-flex gap-3">
                            <span class="badge bg-light text-dark border"><i class="bi bi-tag-fill text-muted me-1"></i> P. Venta: ${{ number_format($recipe->product->price, 2) }}</span>
                            <span class="badge bg-light text-muted border border-info opacity-75"><i class="bi bi-info-circle me-1"></i> {{ $recipe->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA IZQUIERDA: FORMULARIO AGREGAR --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-white mb-4 position-sticky" style="top: 2rem;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase">Agregar Ingredientes</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('empresa.recipes.addItem', $recipe) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">¿Qué materia prima usarás?</label>
                            <select name="component_product_id" id="ingredient_id" class="form-select border shadow-sm" required>
                                <option value="" selected disabled>Buscar ingrediente...</option>
                                @foreach($ingredients as $i)
                                    <option value="{{ $i->id }}" data-cost="{{ $i->cost }}" data-unit="{{ $i->unit ? $i->unit->short_name : 'U' }}" data-unit-id="{{ $i->unit_id }}">
                                        {{ $i->name }} (Costo: ${{ number_format($i->cost, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-muted">Cantidad</label>
                                <div class="input-group">
                                    <input type="number" name="quantity" id="quantity" class="form-control border shadow-sm" step="0.0001" placeholder="0.00" required>
                                    <span class="input-group-text bg-light text-muted" id="unit_preview">U</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-muted">Unidad</label>
                                <select name="unit_id" id="unit_id" class="form-select border shadow-sm">
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}">{{ $u->short_name }} ({{ $u->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- CÁLCULO DE COSTO EN VIVO --}}
                        <div class="bg-light rounded p-3 mb-4 border d-flex justify-content-between align-items-center">
                            <span class="small fw-bold text-muted">Costo del aporte:</span>
                            <span class="fw-bold text-dark fs-5" id="cost_preview">$0.00</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                            AÑADIR A LA RECETA <i class="bi bi-plus-lg ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: LISTADO Y VALORIZACIÓN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase d-flex align-items-center">
                        <i class="bi bi-list-stars text-primary me-2"></i> Composición de la Receta
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Ingrediente</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Costo Unit.</th>
                                <th class="text-end pe-4">Subtotal (Costo)</th>
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
                                        <small class="text-muted">{{ strtoupper($item->component->usage_type) }}</small>
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
                                            <button class="btn btn-link link-danger p-0">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Empiece agregando ingredientes desde el panel izquierdo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($totalRecipeCost > 0)
                <div class="card-footer bg-light border-top p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 border-end">
                            <div class="d-flex justify-content-between align-items-center px-md-3">
                                <span class="text-muted fw-bold">COSTO TOTAL PRODUCCIÓN:</span>
                                <h3 class="fw-bold mb-0 text-danger">${{ number_format($totalRecipeCost, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center px-md-3">
                                <span class="text-muted fw-bold">MARGEN PRODUCTO (BRUTO):</span>
                                @php 
                                    $margin = $recipe->product->price - $totalRecipeCost;
                                    $marginPercent = $recipe->product->price > 0 ? ($margin / $recipe->product->price) * 100 : 0;
                                @endphp
                                <h3 class="fw-bold mb-0 text-success">${{ number_format($margin, 2) }} <small class="fs-6 opacity-75">({{ number_format($marginPercent, 1) }}%)</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- TOOLTIP / AYUDA TÁCTICA --}}
            <div class="card border-0 shadow-sm bg-light mt-4">
                <div class="card-body border-start border-4 border-primary">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-lightbulb-fill text-primary fs-3 me-3"></i>
                        <h6 class="fw-bold mb-0">Ayuda: El "Descuento en Cascada"</h6>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Cada vez que factures 1 unidad de <strong>{{ $recipe->product->name }}</strong>, el sistema realizará automáticamente los siguientes movimientos de stock:</p>
                    <ul class="text-muted small mt-2 p-0 ps-4">
                        @foreach($recipe->items as $item)
                        <li>Descontará <strong>{{ floatval($item->quantity) }} {{ $item->unit->short_name }}</strong> de {{ $item->component->name }}.</li>
                        @endforeach
                    </ul>
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
        const cost = selected.getAttribute('data-cost');
        const unit = selected.getAttribute('data-unit');
        const unitId = selected.getAttribute('data-unit-id');

        document.getElementById('unit_preview').innerText = unit;
        document.getElementById('unit_id').value = unitId;
        calculateCost();
    });

    document.getElementById('quantity').addEventListener('input', calculateCost);

    function calculateCost() {
        const selected = document.getElementById('ingredient_id').options[document.getElementById('ingredient_id').selectedIndex];
        const cost = parseFloat(selected.getAttribute('data-cost') || 0);
        const qty = parseFloat(document.getElementById('quantity').value || 0);
        const subtotal = cost * qty;
        
        document.getElementById('cost_preview').innerText = '$' + subtotal.toLocaleString('es-AR', {minimumFractionDigits: 2});
    }
</script>
@endsection
