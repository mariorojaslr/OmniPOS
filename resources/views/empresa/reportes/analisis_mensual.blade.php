@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">📅 Análisis Mensual</h2>
            <p class="text-muted">Crecimiento histórico y comparativa de facturación por mes.</p>
        </div>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Mes / Año</th>
                        <th class="text-center">Ventas Operadas</th>
                        <th class="text-center">Total Recaudado</th>
                        <th class="text-center">Promedio Facturación</th>
                        <th class="text-end pe-4">Visual</th>
                    </tr>
                </thead>
                <tbody>
                    @php $maxTotal = $meses->max('total') ?: 1; @endphp
                    @foreach($meses as $m)
                        @php $porc = ($m->total / $maxTotal) * 100; @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($m->mes . '-01')->translatedFormat('F Y') }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary px-3 rounded-pill">{{ $m->ventas }}</span>
                            </td>
                            <td class="text-center fw-bold text-dark text-success h5">
                                ${{ number_format($m->total, 2, ',', '.') }}
                            </td>
                            <td class="text-center text-muted">
                                ${{ number_format($m->total / max($m->ventas, 1), 2, ',', '.') }}
                            </td>
                            <td class="text-end pe-4" style="min-width: 200px;">
                                <div class="progress" style="height: 12px; border-radius: 10px;">
                                    <div class="progress-bar bg-success rounded-pill" style="width: {{ $porc }}%"></div>
                                </div>
                                <span class="small text-muted">{{ number_format($porc, 0) }}% respecto al récord mensual</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
