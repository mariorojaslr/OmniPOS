@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Gestión de Pagos</h2>
            <p class="text-muted mb-0">Historial de cobranzas / Ingresos de dinero sueltos</p>
        </div>
        <div>
            <a href="{{ route('empresa.pagos.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4 fw-bold">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Pago
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 border-0">Nº Recibo</th>
                            <th class="py-3 border-0">Fecha</th>
                            <th class="py-3 border-0">Cliente</th>
                            <th class="py-3 border-0">Monto Total</th>
                            <th class="py-3 border-0">Composición</th>
                            <th class="py-3 border-0">Registrado por</th>
                            <th class="py-3 border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recibos as $recibo)
                        <tr>
                            <td class="px-4 fw-bold">#{{ $recibo->numero_recibo }}</td>
                            <td>{{ $recibo->fecha ? $recibo->fecha->format('d/m/Y') : $recibo->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('empresa.clientes.show', $recibo->client_id) }}" class="text-decoration-none fw-semibold">
                                    {{ $recibo->client->name ?? 'Cliente Eliminado' }}
                                </a>
                            </td>
                            <td class="fw-bold text-success">${{ number_format($recibo->monto_total, 2, ',', '.') }}</td>
                            <td>
                                @if($recibo->pagos->count() > 0)
                                    <div class="d-flex flex-column gap-1">
                                    @foreach($recibo->pagos as $p)
                                        <span class="badge bg-light text-dark border">
                                            {{ $p->metodo_pago }}: ${{ number_format($p->monto, 2, ',', '.') }}
                                        </span>
                                    @endforeach
                                    </div>
                                @else
                                    <span class="badge bg-light text-dark border">{{ $recibo->metodo_pago }}</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $recibo->user->name ?? 'S/D' }}</small></td>
                            <td class="text-end px-4">
                                <a href="{{ route('empresa.pagos.show', $recibo->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-eye me-1"></i> Ver Detalle
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted">No se encontraron pagos registrados</h5>
                                <p class="small text-muted">Haga clic en "Nuevo Pago" para registrar uno.</p>
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
@endsection
