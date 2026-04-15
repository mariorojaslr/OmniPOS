@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Flujo de Fondos Proyectado</h2>
            <p class="text-muted small mb-0">Estimación de liquidez basada en vencimientos de cheques a {{ $proyeccionDias }} días.</p>
        </div>
        <div>
            <a href="{{ route('empresa.tesoreria.index') }}" class="btn btn-light px-4 rounded-pill border fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- PANEL RESUMEN --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white mb-3">
                <div class="card-body p-4 text-center">
                    <span class="text-uppercase x-small fw-bold opacity-50 d-block mb-1">Saldo Disponible Hoy</span>
                    <h2 class="fw-bold mb-0 text-info">${{ number_format($saldoInicial, 2, ',', '.') }}</h2>
                </div>
            </div>
            
            <div class="alert alert-info border-0 shadow-sm rounded-4 p-4 small">
                <h6 class="fw-bold"><i class="fas fa-info-circle me-1"></i> Información</h6>
                Este reporte proyecta los ingresos y egresos bancarios basándose exclusivamente en la <strong>fecha de pago</strong> de los cheques en cartera y los cheques propios entregados.
            </div>
        </div>

        {{-- TABLA PROYECCION --}}
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 border-top">
                        <thead class="bg-light">
                            <tr class="x-small fw-bold text-muted text-uppercase">
                                <th class="ps-4">Día / Fecha</th>
                                <th class="text-end">Ingresos (+)</th>
                                <th class="text-end">Egresos (-)</th>
                                <th class="text-end">Balance Diario</th>
                                <th class="text-end pe-4 text-dark">Saldo Proyectado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $saldoAcumulado = $saldoInicial; @endphp
                            @foreach($diario as $fecha => $data)
                                @php 
                                    $balance = $data['ingresos'] - $data['egresos'];
                                    $saldoAcumulado += $balance;
                                @endphp
                                <tr class="{{ $data['ingresos'] > 0 || $data['egresos'] > 0 ? 'bg-light bg-opacity-50 fw-bold' : '' }}">
                                    <td class="ps-4">
                                        <div class="small fw-semibold text-dark">{{ $data['pago']->format('d/m/Y') }}</div>
                                        <div class="x-small text-muted">{{ $data['pago']->translatedFormat('l') }}</div>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        {{ $data['ingresos'] > 0 ? '+' . number_format($data['ingresos'], 2, ',', '.') : '' }}
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ $data['egresos'] > 0 ? '-' . number_format($data['egresos'], 2, ',', '.') : '' }}
                                    </td>
                                    <td class="text-end">
                                        @if($balance != 0)
                                            <span class="badge {{ $balance > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                                ${{ number_format($balance, 2, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-muted opacity-25">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 fw-bold fs-6 {{ $saldoAcumulado < 0 ? 'text-danger' : 'text-dark' }}">
                                        ${{ number_format($saldoAcumulado, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.7rem; }
    .table > :not(caption) > * > * { padding: 0.75rem 0.5rem; }
</style>
@endsection
