@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0">Sistema de Facturación</h4>
        <p class="text-muted mb-0">Historial de pagos y suscripciones de las empresas.</p>
    </div>
    <div class="col-auto">
        {{-- <a href="{{ route('owner.facturacion.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            + Registrar Pago
        </a> --}}
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Fecha</th>
                        <th>Empresa</th>
                        <th>Plan</th>
                        <th>Método</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Comprobante</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $pago)
                    <tr>
                        <td class="ps-4">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                        <td>
                            <strong class="d-block text-dark">{{ $pago->empresa->nombre_comercial }}</strong>
                            <small class="text-muted">{{ $pago->empresa->status }}</small>
                        </td>
                        <td>
                            @if($pago->plan)
                                <span class="badge bg-secondary">{{ $pago->plan->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-uppercase" style="font-size: 0.85rem;">{{ $pago->metodo }}</span>
                        </td>
                        <td>
                            <strong class="text-success">${{ number_format($pago->monto, 2) }}</strong>
                        </td>
                        <td>
                            @if($pago->estado == 'aprobado')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1 pb-1 rounded-pill">Aprobado</span>
                            @elseif($pago->estado == 'pendiente')
                                <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25 px-2 py-1 pb-1 rounded-pill">Pendiente</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-2 py-1 pb-1 rounded-pill">{{ ucfirst($pago->estado) }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $pago->nro_comprobante ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No hay pagos registrados aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
