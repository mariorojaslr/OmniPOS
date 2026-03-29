@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">🏷️ Margen por Producto</h2>
            <p class="text-muted">Análisis detallado de ganancia neta por unidad vendida.</p>
        </div>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Producto</th>
                        <th class="text-center">Margen % Individual</th>
                        <th class="text-center">Beneficio Neto</th>
                        <th class="text-center">Precio de Venta</th>
                        <th class="text-center">Costo Est.</th>
                        <th class="text-end pe-4">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $it)
                        @php $margen = $it->subtotal > 0 ? ($it->ganancia / $it->subtotal) * 100 : 0; @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $it->name }}</div>
                            </td>
                            <td class="text-center">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar {{ $margen > 30 ? 'bg-success' : 'bg-info' }}" style="width: {{ $margen }}%"></div>
                                </div>
                                <span class="small fw-bold">{{ number_format($margen, 1) }}%</span>
                            </td>
                            <td class="text-center fw-bold text-success">
                                ${{ number_format($it->ganancia, 2, ',', '.') }}
                            </td>
                            <td class="text-center text-dark">
                                ${{ number_format($it->subtotal / max($it->cant, 1), 2, ',', '.') }}
                            </td>
                            <td class="text-center text-muted">
                                ${{ number_format($it->costo_total / max($it->cant, 1), 2, ',', '.') }}
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('empresa.stock.index', ['q' => $it->name]) }}" class="btn btn-sm btn-outline-secondary p-1">🔎</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
