@extends('layouts.empresa')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Órdenes de Producción</h2>
            <p class="text-muted small">Trazabilidad completa de su fabricación y transformación de productos.</p>
        </div>
        <a href="{{ route('production_orders.create') }}" class="btn btn-primary fw-bold shadow-sm px-4">
            <i class="bi bi-plus-lg me-1"></i> NUEVA ORDEN DE PRODUCCIÓN
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3">Fecha</th>
                        <th>Producto a Fabricar</th>
                        <th class="text-center">Cantidad</th>
                        <th>Autorizado Por</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold d-block text-dark">{{ $o->created_at->format('d/m/Y') }}</span>
                                <small class="text-muted">{{ $o->created_at->format('H:i') }} hs</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="bi bi-gear-wide-connected text-success"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block text-dark">{{ $o->recipe->product->name ?? 'N/A' }}</span>
                                        <small class="text-muted small opacity-75">{{ $o->recipe->name ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border fw-bold px-3 py-2">
                                    {{ number_format($o->quantity, 2) }} {{ $o->recipe->product->unit->short_name ?? 'U' }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted fw-bold">{{ $o->user->name ?? 'Sistema' }}</span>
                            </td>
                            <td class="text-center">
                                @if($o->status === 'completada')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success fw-bold">COMPLETADA</span>
                                @elseif($o->status === 'pendiente')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning fw-bold">PENDIENTE</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary fw-bold">{{ strtoupper($o->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border opacity-75" title="Ver Detalles" disabled>
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-3 opacity-25"></i>
                                No hay órdenes de producción registradas todavía. 
                                <br>Haga clic en el botón superior para crear la primera.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
