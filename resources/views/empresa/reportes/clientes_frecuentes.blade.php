@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">⭐ Clientes Frecuentes</h2>
            <p class="text-muted">Ranking de clientes basado en frecuencia de visitas y facturación total.</p>
        </div>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4"># Ranking</th>
                        <th>Cliente</th>
                        <th class="text-center">Visitas</th>
                        <th class="text-center">Total Gastado</th>
                        <th class="text-center">Ticket Promedio</th>
                        <th class="text-end pe-4">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $i => $c)
                        @php $promedio = $c->visitas > 0 ? $c->total / $c->visitas : 0; @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-dark rounded-pill px-3">{{ $i + 1 }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $c->cliente_nombre ?? 'Consumidor Final' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary px-3 rounded-pill">{{ $c->visitas }}</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                ${{ number_format($c->total, 2, ',', '.') }}
                            </td>
                            <td class="text-center text-muted">
                                ${{ number_format($promedio, 2, ',', '.') }}
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary p-1">👤</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
