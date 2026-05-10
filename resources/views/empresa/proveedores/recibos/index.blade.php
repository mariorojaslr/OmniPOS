@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Recibos de Pago (Proveedores)</h2>
            <p class="text-muted mb-0">Historial de pagos realizados a proveedores</p>
        </div>
        <div>
            <a href="{{ route('empresa.recibos-proveedores.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4 fw-bold">
                <i class="fas fa-plus-circle me-2"></i> Generar Recibo
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted text-uppercase small fw-bold">
                            <th class="px-4 py-3 border-0">Nº Recibo</th>
                            <th class="py-3 border-0">Fecha</th>
                            <th class="py-3 border-0">Proveedor</th>
                            <th class="py-3 border-0">Monto Total</th>
                            <th class="py-3 border-0">Medios de Pago</th>
                            <th class="py-3 border-0 text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recibos as $recibo)
                        <tr>
                            <td class="px-4 fw-bold text-primary">#{{ $recibo->numero_orden }}</td>
                            <td>{{ $recibo->fecha ? \Carbon\Carbon::parse($recibo->fecha)->format('d/m/Y') : $recibo->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('empresa.proveedores.show', $recibo->supplier_id) }}" class="text-decoration-none fw-bold text-dark">
                                    {{ $recibo->supplier->name ?? 'Proveedor Eliminado' }}
                                </a>
                            </td>
                            <td class="fw-bold text-danger">${{ number_format($recibo->monto_total, 2, ',', '.') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                @foreach($recibo->pagos as $p)
                                    <span class="badge bg-light text-dark border rounded-pill x-small">
                                        {{ ucfirst(str_replace('_', ' ', $p->metodo_pago)) }}: ${{ number_format($p->monto, 2, ',', '.') }}
                                    </span>
                                @endforeach
                                </div>
                            </td>
                            <td class="text-end px-4">
                                <a href="{{ route('empresa.proveedores.pagos.pdf', $recibo->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 shadow-sm" target="_blank">
                                    <i class="fas fa-print me-1"></i> Imprimir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted fw-bold">No hay recibos registrados</h5>
                                <p class="small text-muted">Haga clic en "Generar Recibo" para empezar.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($recibos->hasPages())
        <div class="card-footer bg-white py-3 px-4 border-0">
            {{ $recibos->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<style>
    .x-small { font-size: 0.7rem; }
    .rounded-4 { border-radius: 1rem !important; }
</style>
@endsection
