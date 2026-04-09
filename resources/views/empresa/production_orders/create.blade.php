@extends('layouts.empresa')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Generar Lote de Producción</h2>
            <p class="text-muted small">Simule y transforme materia prima en producto terminado.</p>
        </div>
        <a href="{{ route('empresa.production_orders.index') }}" class="btn btn-light border fw-bold shadow-sm px-4">
            CANCELAR
        </a>
    </div>

    @if(session('info'))
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
            <i class="bi bi-copy fs-4 me-3"></i>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4">
            <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- COLUMNA CONFIGURACIÓN --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase"><i class="bi bi-gear-fill me-2 opacity-50"></i> Configuración del Lote</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('empresa.production_orders.create') }}" method="GET" id="simulationForm">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">Producto a Fabricar (Receta)</label>
                            <select name="recipe_id" class="form-select form-select-lg border shadow-sm rounded-3" onchange="this.form.submit()" required>
                                <option value="" selected disabled>Seleccione receta...</option>
                                @foreach($recipes as $r)
                                    <option value="{{ $r->id }}" {{ $r->id == request('recipe_id') ? 'selected' : '' }}>
                                        {{ $r->product->name }} ({{ $r->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">Cantidad de unidades</label>
                            <div class="input-group input-group-lg">
                                <input type="number" name="quantity" class="form-control border shadow-sm" step="0.01" min="0.01" value="{{ $quantity }}" required>
                                <span class="input-group-text bg-light text-muted fw-bold">
                                    {{ $selectedRecipe ? ($selectedRecipe->product->unit->short_name ?? 'U') : 'U' }}
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-3 mb-2">
                            <i class="bi bi-search me-2"></i> ANALIZAR FACTIBILIDAD
                        </button>
                    </form>
                </div>
            </div>

            @if($simulation)
            <div class="card border-0 shadow-sm {{ $simulation['can_produce'] ? 'bg-success bg-opacity-10 text-success border-success' : 'bg-danger bg-opacity-10 text-danger border-danger' }} border-start border-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase mb-2">Resultado del Análisis</h6>
                    @if($simulation['can_produce'])
                        <p class="mb-0 fw-bold"><i class="bi bi-check-circle-fill me-2"></i> Stock suficiente para producir el 100%.</p>
                    @else
                        <p class="mb-2 fw-bold"><i class="bi bi-x-circle-fill me-2"></i> Stock Insuficiente.</p>
                        <p class="small mb-0">Con el inventario actual solo puede fabricar un máximo de <strong>{{ $simulation['max_possible'] }}</strong> unidades.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- COLUMNA EXPLOSIÓN DE MATERIALES (SIMULADOR) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase"><i class="bi bi-list-check me-2 opacity-50"></i> Explosión de Materiales (BOM)</h6>
                    @if($selectedRecipe)
                        <span class="badge bg-light text-dark border fw-bold px-3 py-2">TOTAL SOLICITADO: {{ $quantity }} {{ $selectedRecipe->product->unit->short_name ?? 'U' }} </span>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Ingrediente / Insumo</th>
                                <th class="text-center">Req. Unitario</th>
                                <th class="text-center">Total Req.</th>
                                <th class="text-center">Stock Actual</th>
                                <th class="text-end pe-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($simulation)
                                @foreach($simulation['items'] as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark d-block">{{ $item['product'] }}</span>
                                        </td>
                                        <td class="text-center small">
                                            {{ number_format($item['needed'] / $quantity, 4) }} {{ $item['unit'] }}
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold">{{ number_format($item['needed'], 2) }}</span> {{ $item['unit'] }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $item['available'] >= $item['needed'] ? 'bg-light text-dark border' : 'bg-danger' }}">
                                                {{ number_format($item['available'], 2) }} {{ $item['unit'] }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            @if($item['status'] === 'OK')
                                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                            @else
                                                <span class="text-danger fw-bold small">- {{ number_format($item['shortage'], 2) }} {{ $item['unit'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">
                                        <i class="bi bi-calculator fs-2 d-block mb-3 opacity-25"></i>
                                        Seleccione una receta e indique la cantidad para simular la producción.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($simulation && $simulation['can_produce'])
                    <div class="card-footer bg-light border-top p-4">
                        <form action="{{ route('empresa.production_orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="recipe_id" value="{{ request('recipe_id') }}">
                            <input type="hidden" name="quantity" value="{{ $quantity }}">
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Observaciones / Notas del Lote (Opcional)</label>
                                <textarea name="notes" class="form-control border-0 shadow-sm" rows="2" placeholder="Ej: Lote Mañana - Personal: Juancito"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm py-3" style="font-size: 1.1rem;">
                                <i class="bi bi-gear-wide-connected me-2"></i> CONFIRMAR Y EJECUTAR TRANSFORMACIÓN
                            </button>
                            <p class="text-center small text-muted mt-3 mb-0">Al confirmar, el sistema restará automáticamente los insumos y sumará el producto final al inventario.</p>
                        </form>
                    </div>
                @elseif($simulation)
                    <div class="card-footer bg-light border-top p-4 text-center">
                        <div class="alert alert-warning border-0 shadow-sm mb-0">
                            <i class="bi bi-info-circle me-2"></i> Debe contar con el stock total solicitado para ejecutar esta orden. Considere producir una cantidad menor (Ej: <strong>{{ $simulation['max_possible'] }}</strong> unidades) o reponer faltantes.
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
