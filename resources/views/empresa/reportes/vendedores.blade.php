@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">👤 Ventas por Vendedor</h2>
            <p class="text-muted">Desempeño comercial por cada cajero y empleado.</p>
        </div>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Vendedor</th>
                        <th class="text-center">Ventas Realizadas</th>
                        <th class="text-center">Total Recaudado</th>
                        <th class="text-center">Ticket Promedio</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendedores as $v)
                        @php $promedio = $v->total_ventas > 0 ? $v->total_monto / $v->total_ventas : 0; @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $v->name }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill px-3">{{ $v->total_ventas }}</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                ${{ number_format($v->total_monto, 2, ',', '.') }}
                            </td>
                            <td class="text-center text-muted">
                                ${{ number_format($promedio, 2, ',', '.') }}
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary py-1 px-3" data-bs-toggle="collapse" data-bs-target="#vendedor-{{ $loop->index }}">
                                    Detalles ↓
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="vendedor-{{ $loop->index }}">
                            <td colspan="5" class="bg-light bg-opacity-50 p-4">
                                <div class="p-3 border rounded bg-white">
                                    <h6 class="fw-bold mb-3 small text-uppercase text-primary">Análisis de Desempeño</h6>
                                    <p class="small text-muted mb-0">Este vendedor representa el <b>{{ number_format(($v->total_monto / max($vendedores->sum('total_monto'), 1)) * 100, 1) }}%</b> de la facturación total.</p>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
