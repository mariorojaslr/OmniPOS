@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">📁 Ventas por Categoría</h2>
            <p class="text-muted">Distribución de ingresos según los rubros del catálogo.</p>
        </div>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Rubro / Categoría</th>
                        <th class="text-center">Total Facturado</th>
                        <th class="text-center">Porcentaje de Venta</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalGral = $categorias->sum('total'); @endphp
                    @foreach($categorias as $cat)
                        @php $porc = $totalGral > 0 ? ($cat->total / $totalGral) * 100 : 0; @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $cat->cat }}</div>
                            </td>
                            <td class="text-center fw-bold text-primary">
                                ${{ number_format($cat->total, 2, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-info" style="width: {{ $porc }}%"></div>
                                </div>
                                <span class="small fw-bold text-muted">{{ number_format($porc, 1) }}% de la torta</span>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary py-1 px-3" data-bs-toggle="collapse" data-bs-target="#cat-{{ $loop->index }}">
                                    Detalles ↓
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="cat-{{ $loop->index }}">
                            <td colspan="4" class="bg-light bg-opacity-50 p-4 text-center text-muted">
                                <small>Listado de productos asociados próximamente disponible en este desglose.</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
