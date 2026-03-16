@extends('layouts.empresa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Actualización Masiva de Precios</h2>
        <p class="text-muted">Aplica cambios de precios de forma inteligente y rápida</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('empresa.products.bulk-price-update.update') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-filter text-primary"></i> 1. Selecciona el Filtro</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Por Rubro Individual</label>
                            <select name="rubro_id" class="form-select">
                                <option value="">-- Todos los Rubros --</option>
                                @foreach($rubros as $rubro)
                                    <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">O por Rango de Rubros (Nombre)</label>
                            <div class="input-group">
                                <select name="rubro_from_id" class="form-select">
                                    <option value="">Desde...</option>
                                    @foreach($rubros as $rubro)
                                        <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                                    @endforeach
                                </select>
                                <select name="rubro_to_id" class="form-select">
                                    <option value="">Hasta...</option>
                                    @foreach($rubros as $rubro)
                                        <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-magic text-primary"></i> 2. Define la Acción</h5>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipo de Cambio</label>
                            <select name="update_type" class="form-select" required>
                                <option value="percentage">Porcentaje (%)</option>
                                <option value="fixed">Monto Fijo ($)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Operación</label>
                            <select name="operation" class="form-select" required>
                                <option value="increase">Aumentar (+)</option>
                                <option value="decrease">Disminuir (-)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Valor</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required placeholder="Ej: 15.50">
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded mb-4">
                        <div class="d-flex align-items-center text-primary">
                            <i class="fas fa-info-circle me-2 fs-4"></i>
                            <span class="small">Esta acción modificará los precios de todos los productos que cumplan con los filtros seleccionados. Esta acción no se puede deshacer de forma automática.</span>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-5 py-2 shadow" onclick="return confirm('¿Estás seguro de que deseas aplicar este cambio masivo de precios?')">
                            Aplicar Actualización de Precios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <h5>Consejos Mágicos</h5>
                <ul class="small mb-0 mt-3 opacity-75">
                    <li class="mb-2">Usa porcentajes para ajustes por inflación.</li>
                    <li class="mb-2">Usa montos fijos para redondeos o cargos de envío.</li>
                    <li class="mb-2">Filtrar por rubro permite ser más preciso con las ofertas.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
