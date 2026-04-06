@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ENCABEZADO INSTITUCIONAL --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Valorización de Inventario</h2>
            <p class="text-muted small">Capital inmovilizado en stock (Productos, Materias Primas e Insumos)</p>
        </div>
        <div class="text-end">
            <h3 class="fw-bold mb-0" style="color: var(--color-primario);">${{ number_format($totalGeneral, 2) }}</h3>
            <span class="badge bg-secondary opacity-75">{{ $totalItems }} artículos valorizados</span>
        </div>
    </div>

    {{-- RESUMEN POR CATEGORÍA (TARJETAS CLARAS) --}}
    <div class="row g-3 mb-5">
        @foreach($valuationData as $data)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body border-start border-4" style="border-color: var(--color-primario) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-uppercase fw-bold text-muted small">
                                @switch($data->usage_type)
                                    @case('sell') 🛍️ Venta @break
                                    @case('raw_material') 🧶 Mat. Prima @break
                                    @case('supply') 📦 Insumos @break
                                    @case('internal') 🧹 Consumo @break
                                    @default Otro
                                @endswitch
                            </span>
                            <span class="badge rounded-pill bg-light text-dark border">{{ $data->count }}</span>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">${{ number_format($data->total_value, 2) }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- TABLA TOP VALORIZACIÓN (ESTILO INSTITUCIONAL) --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="fw-bold mb-0 text-dark">Top 15 Artículos con mayor valor inmovilizado</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small text-uppercase">Producto / Recurso</th>
                        <th class="text-center text-muted small text-uppercase">Uso</th>
                        <th class="text-end text-muted small text-uppercase">Stock Actual</th>
                        <th class="text-end text-muted small text-uppercase">Costo Unitario</th>
                        <th class="text-end pe-4 text-muted small text-uppercase">Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topValuation as $p)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold d-block text-dark">{{ $p->name }}</span>
                                <small class="text-muted">{{ $p->barcode ?? 'Sin código' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-muted border">
                                    {{ strtoupper($p->usage_type) }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                {{ floatval($p->stock) }} 
                                <small class="text-muted">{{ $p->unit ? $p->unit->short_name : 'U' }}</small>
                            </td>
                            <td class="text-end text-muted">
                                ${{ number_format($p->cost, 2) }}
                            </td>
                            <td class="text-end pe-4 fw-bold" style="color: var(--color-primario);">
                                ${{ number_format($p->valuation, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-info-circle fs-2 text-muted d-block mb-2"></i>
                                No hay datos de valorización disponibles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($totalGeneral > 0)
            <div class="card-footer bg-white border-top py-3 text-center">
                <span class="text-muted small">Reporte basado en conteo real de stock por precio de costo.</span>
            </div>
        @endif
    </div>

</div>
@endsection
