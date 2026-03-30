@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">💰 Caja Diaria</h2>
            <p class="text-muted">Balance neto de ingresos por ventas y egresos por gastos.</p>
        </div>
        <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Fecha</th>
                        <th class="text-center">Ingresos (Ventas)</th>
                        <th class="text-center">Egresos (Gastos)</th>
                        <th class="text-center">Balance Neto</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dias as $d)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($d->fecha)->format('d/m/Y') }}</div>
                            </td>
                            <td class="text-center text-success fw-bold">
                                ${{ number_format($d->ventas, 2, ',', '.') }}
                                <span class="badge bg-success bg-opacity-25 text-success ms-2 small">↑</span>
                            </td>
                            <td class="text-center text-danger fw-bold">
                                ${{ number_format($d->gastos, 2, ',', '.') }}
                                <span class="badge bg-danger bg-opacity-25 text-danger ms-2 small">↓</span>
                            </td>
                            <td class="text-center">
                                <span class="fs-6 fw-bold {{ $d->balance >= 0 ? 'text-primary' : 'text-danger' }}">
                                    ${{ number_format($d->balance, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary py-1 px-3" data-bs-toggle="collapse" data-bs-target="#caja-{{ $loop->index }}">
                                    Ver Detalle ↓
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="caja-{{ $loop->index }}">
                            <td colspan="5" class="bg-light bg-opacity-50 p-4">
                                <div class="row g-3">
                                    <div class="col-md-6 border-end">
                                        <h6 class="fw-bold fs-little-bit text-success mb-2 small text-uppercase">Ventas del día</h6>
                                        <p class="small text-muted mb-0">Total bruto ingresado por facturación.</p>
                                    </div>
                                    <div class="col-md-6 ps-4">
                                        <h6 class="fw-bold fs-little-bit text-danger mb-2 small text-uppercase">Gastos operativos</h6>
                                        <p class="small text-muted mb-0">Retiros de caja y otros egresos.</p>
                                    </div>
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
