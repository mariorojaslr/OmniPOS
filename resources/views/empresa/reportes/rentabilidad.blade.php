@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">📈 Rentabilidad y Margen</h2>
            <p class="text-muted">Análisis comercial de beneficio neto por cada producto vendido.</p>
        </div>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Producto</th>
                        <th class="text-center">Cant. Vendida</th>
                        <th class="text-center">Ingreso Bruto</th>
                        <th class="text-center">Costo Est.</th>
                        <th class="text-center">Beneficio Neto</th>
                        <th class="text-center">Margen %</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $it)
                        @php $margen = $it->subtotal > 0 ? ($it->ganancia / $it->subtotal) * 100 : 0; @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $it->name }}</div>
                            </td>
                            <td class="text-center text-muted">
                                {{ number_format($it->cant, 0, ',', '.') }} un.
                            </td>
                            <td class="text-center fw-bold">
                                ${{ number_format($it->subtotal, 2, ',', '.') }}
                            </td>
                            <td class="text-center text-secondary small">
                                ${{ number_format($it->costo_total, 2, ',', '.') }}
                            </td>
                            <td class="text-center fw-bold text-success">
                                ${{ number_format($it->ganancia, 2, ',', '.') }}
                            </td>
                            <td class="text-center fw-bold text-primary">
                                {{ number_format($margen, 1) }}%
                            </td>
                            <td class="text-end pe-4">
                                <span class="badge {{ $margen > 30 ? 'bg-success' : ($margen > 15 ? 'bg-info' : 'bg-warning') }} rounded-pill px-3">
                                    {{ $margen > 30 ? 'Eficaz' : ($margen > 15 ? 'Bajo' : 'Revisar') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
