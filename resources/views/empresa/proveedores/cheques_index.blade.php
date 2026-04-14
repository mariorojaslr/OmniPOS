@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Cartera de Cheques</h3>
            <p class="text-muted small">Administración de valores recibidos de clientes y entregados a terceros.</p>
        </div>
        <div>
            <div class="dropdown">
                <button class="btn btn-light btn-sm border rounded-pill px-3 dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i> Filtrar Estado
                </button>
                <ul class="dropdown-menu border-0 shadow">
                    <li><a class="dropdown-item" href="#">Todos</a></li>
                    <li><a class="dropdown-item" href="#">En Cartera</a></li>
                    <li><a class="dropdown-item" href="#">Entregados</a></li>
                    <li><a class="dropdown-item" href="#">Depositados</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 h-100">
                <span class="text-muted small fw-bold text-uppercase">En Cartera</span>
                <h3 class="fw-bold text-primary mb-0">${{ number_format($cheques->where('estado', 'en_cartera')->sum('monto'), 2, ',', '.') }}</h3>
                <small class="text-muted">{{ $cheques->where('estado', 'en_cartera')->count() }} valores</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 h-100">
                <span class="text-muted small fw-bold text-uppercase">Vencen en 7 días</span>
                @php 
                    $proximos = $cheques->where('estado', 'en_cartera')->filter(function($c) {
                        return $c->fecha_pago <= now()->addDays(7) && $c->fecha_pago >= now();
                    });
                @endphp
                <h3 class="fw-bold text-warning mb-0">${{ number_format($proximos->sum('monto'), 2, ',', '.') }}</h3>
                <small class="text-muted">{{ $proximos->count() }} valores</small>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted text-uppercase fw-bold">
                        <th class="ps-4">Nro / Banco</th>
                        <th>Emisor / Cliente</th>
                        <th>Monto</th>
                        <th>Fecha de Cobro</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cheques as $c)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold d-block text-dark">#{{ $c->numero }}</span>
                            <small class="text-muted">{{ $c->banco }}</small>
                        </td>
                        <td>
                            @if($c->client)
                                <a href="{{ route('empresa.clientes.show', $c->client_id) }}" class="text-decoration-none fw-semibold">
                                    {{ $c->client->name }}
                                </a>
                            @else
                                <span class="text-muted">Carga Manual / Desconocido</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold fs-6 text-dark">${{ number_format($c->monto, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold {{ $c->fecha_pago <= now() && $c->estado == 'en_cartera' ? 'text-danger' : '' }}">
                                {{ $c->fecha_pago->format('d/m/Y') }}
                            </div>
                            @if($c->fecha_pago <= now() && $c->estado == 'en_cartera')
                                <span class="badge bg-danger rounded-pill" style="font-size: 10px;">VENCIDO / COBRABLE</span>
                            @endif
                        </td>
                        <td>
                            @switch($c->estado)
                                @case('en_cartera')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 border border-success">En Cartera</span>
                                    @break
                                @case('entregado')
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 border border-info">Entregado a {{ $c->supplier->name ?? 'Proveedor' }}</span>
                                    @break
                                @case('depositado')
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 border border-primary">Depositado</span>
                                    @break
                                @case('rechazado')
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 border border-danger">Rechazado</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                                    Gestionar
                                </button>
                                <ul class="dropdown-menu shadow border-0">
                                    @if($c->estado == 'en_cartera')
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-university me-2 text-primary"></i> Depositar</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-money-bill-wave me-2 text-success"></i> Cobrar por ventanilla</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-times-circle me-2 text-danger"></i> Rechazado</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-muted" href="#"><i class="fas fa-history me-2"></i> Ver Historial</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted opacity-50">
                            <i class="fas fa-money-check fa-4x mb-3 d-block opacity-25"></i>
                            No hay cheques registrados en el sistema.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 px-4 py-3">
            {{ $cheques->links() }}
        </div>
    </div>
</div>
@endsection
