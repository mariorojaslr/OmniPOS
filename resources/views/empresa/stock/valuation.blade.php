@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Valorización de Inventario</h2>
            <p class="text-muted small">Capital inmovilizado en stock (Productos, Materias Primas e Insumos)</p>
        </div>
        <div class="text-end">
            <h3 class="fw-black text-primary mb-0">${{ number_format($totalGeneral, 2) }}</h3>
            <span class="badge bg-light text-dark">{{ $totalItems }} artículos valorizados</span>
        </div>
    </div>

    {{-- RESUMEN POR CATEGORÍA --}}
    <div class="row g-3 mb-5">
        @foreach($valuationData as $data)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm overflow-hidden h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-uppercase fw-black text-muted small letter-spacing-1">
                                @switch($data->usage_type)
                                    @case('sell') 🛍️ Venta @break
                                    @case('raw_material') 🧶 Mat. Prima @break
                                    @case('supply') 📦 Insumos @break
                                    @case('internal') 🧹 Consumo @break
                                    @default Otro
                                @endswitch
                            </span>
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">{{ $data->count }}</span>
                        </div>
                        <h4 class="fw-bold mb-0">${{ number_format($data->total_value, 2) }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- TABLA TOP VALORIZACIÓN --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0">Top 15 Artículos con mayor valor inmovilizado</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Producto / Recurso</th>
                        <th class="text-center">Uso</th>
                        <th class="text-end">Stock Actual</th>
                        <th class="text-end">Costo Unitario</th>
                        <th class="text-end pe-4">Valor Total</th>
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
                                <span class="badge bg-light text-muted fw-bold">
                                    {{ strtoupper($p->usage_type) }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">
                                {{ floatval($p->stock) }} 
                                <small class="text-muted">{{ $p->unit ? $p->unit->short_name : 'U' }}</small>
                            </td>
                            <td class="text-end text-muted">
                                ${{ number_format($p->cost, 2) }}
                            </td>
                            <td class="text-end pe-4 fw-black text-primary">
                                ${{ number_format($p->valuation, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-info-circle fs-1 text-muted d-block mb-2"></i>
                                No hay datos de valorización disponibles.<br>
                                Asegúrate de cargar <b>Costo</b> y <b>Stock</b> en tus productos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($totalGeneral > 0)
            <div class="card-footer bg-light border-0 py-3 text-center">
                <span class="text-muted small">Este reporte ayuda a optimizar las compras y evitar sobre-stock de recursos ociosos.</span>
            </div>
        @endif
    </div>

</div>

<style>
    .letter-spacing-1 { letter-spacing: 1px; }
    .fw-black { font-weight: 900; }
</style>
@endsection
