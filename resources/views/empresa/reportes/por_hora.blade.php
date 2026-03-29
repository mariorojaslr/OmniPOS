@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">⏰ Ventas por Hora</h2>
            <p class="text-muted">Distribución horaria para detectar picos de demanda y optimizar personal.</p>
        </div>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-start">Franja Horaria</th>
                        <th class="text-center">Operaciones Realizadas</th>
                        <th class="text-center">Total Recaudado</th>
                        <th class="text-center">Tendencia de Turno</th>
                        <th class="text-end pe-4">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @php $maxCant = $horas->max('cant') ?: 1; @endphp
                    @foreach($horas as $h)
                        @php $porc = ($h->cant / $maxCant) * 100; @endphp
                        <tr>
                            <td class="ps-4 text-start">
                                <div class="fw-bold text-dark">{{ str_pad($h->hora, 2, '0', STR_PAD_LEFT) }}:00 hs</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary px-3 rounded-pill">{{ $h->cant }}</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                ${{ number_format($h->total, 2, ',', '.') }}
                            </td>
                            <td style="min-width: 150px;">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-info" style="width: {{ $porc }}%"></div>
                                </div>
                                <span class="small text-muted">{{ number_format($porc, 0) }}% del pico máximo</span>
                            </td>
                            <td class="text-end pe-4">
                                <span class="badge {{ $porc >= 80 ? 'bg-danger' : ($porc >= 40 ? 'bg-success' : 'bg-secondary') }} px-3 rounded-pill">
                                    {{ $porc >= 80 ? 'CRÍTICO' : ($porc >= 40 ? 'ALTO' : 'BAJO') }}
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
