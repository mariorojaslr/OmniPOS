@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold mb-0">Gestión de Planes de Suscripción</h3>
        <p class="text-secondary mb-0">Crea bandas de precios y establece límites de recursos para tus clientes.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('owner.planes.create') }}" class="btn btn-primary fw-bold px-4">
            + Nuevo Plan
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Plan (Nombre)</th>
                        <th>Precio</th>
                        <th>Límite Usuarios</th>
                        <th>Límite Productos</th>
                        <th>Almacenamiento</th>
                        <th>Estado</th>
                        <th>Clientes</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($planes as $plan)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold">{{ $plan->name }}</span>
                        </td>
                        <td>
                            <span class="text-success fw-bold">${{ number_format($plan->price, 2) }}</span>
                        </td>
                        <td>{{ $plan->max_users == 0 ? 'Ilimitado' : $plan->max_users }}</td>
                        <td>{{ $plan->max_products == 0 ? 'Ilimitado' : $plan->max_products }}</td>
                        <td>{{ $plan->max_storage_mb == 0 ? 'Ilimitado' : $plan->max_storage_mb }} MB</td>
                        <td>
                            @if($plan->is_active)
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1 pb-1 rounded-pill">Activo</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-25 px-2 py-1 pb-1 rounded-pill">Oculto</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary rounded-pill">{{ $plan->empresas_count }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('owner.planes.edit', $plan) }}" class="btn btn-sm btn-outline-secondary rounded-3">Editar</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div style="font-size: 3rem; opacity: 0.5;">👑</div>
                            Aún no has creado ningún plan de suscripción.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
