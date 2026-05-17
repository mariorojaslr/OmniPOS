@extends('layouts.empresa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Planes de Afiliados</h2>
            <p class="text-secondary">Configura los niveles de suscripción para tus pacientes.</p>
        </div>
        <button class="btn btn-primary fw-bold px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalAddPlan">
            <i class="bi bi-plus-lg me-2"></i> Nuevo Plan
        </button>
    </div>

    <div class="row g-4">
        @forelse($plans as $plan)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-800 text-dark mb-0">{{ $plan->name }}</h5>
                        <span class="badge {{ $plan->active ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $plan->active ? 'text-success' : 'text-danger' }} rounded-pill px-3">
                            {{ $plan->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <h2 class="fw-800 text-primary mb-4">${{ number_format($plan->price, 0, ',', '.') }}<small class="text-muted fs-6 fw-normal">/mes</small></h2>
                    <p class="text-secondary small mb-3">{{ $plan->description ?? 'Sin descripción.' }}</p>
                    
                    <div class="mb-4">
                        <label class="text-muted extra-small fw-bold text-uppercase mb-2 d-block">Prestaciones Incluidas:</label>
                        <ul class="list-unstyled mb-0">
                            @forelse($plan->features ?? [] as $feature)
                                <li class="small text-dark mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>{{ $feature }}</li>
                            @empty
                                <li class="small text-muted italic">Sin prestaciones definidas.</li>
                            @endforelse
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2 mt-auto">
                        <button class="btn btn-light fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $plan->id }}">Configurar Plan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalEdit{{ $plan->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('empresa.affiliate_plans.update', $plan) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header border-bottom">
                            <h5 class="modal-title fw-bold text-primary">Configurar: {{ $plan->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label text-muted small fw-bold">Nombre del Plan</label>
                                    <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-bold">Precio ($)</label>
                                    <input type="number" name="price" class="form-control" value="{{ $plan->price }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold">Descripción General</label>
                                    <textarea name="description" class="form-control" rows="2">{{ $plan->description }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold d-flex justify-content-between">
                                        Prestaciones / Beneficios 
                                        <button type="button" class="btn btn-link p-0 small text-decoration-none" onclick="addFeatureRow('features_container_edit_{{ $plan->id }}')">+ Agregar</button>
                                    </label>
                                    <div id="features_container_edit_{{ $plan->id }}">
                                        @foreach($plan->features ?? [] as $feature)
                                            <div class="input-group mb-2">
                                                <input type="text" name="features[]" class="form-control form-control-sm" value="{{ $feature }}">
                                                <button class="btn btn-outline-danger btn-sm" type="button" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Actualizar Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                <i class="bi bi-card-checklist fs-1 text-muted opacity-25 mb-3"></i>
                <h5 class="text-secondary">No has creado planes de afiliados todavía.</h5>
                <p class="text-muted">Los planes te permiten cobrar cuotas mensuales a tus pacientes.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="modalAddPlan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('empresa.affiliate_plans.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Nuevo Plan de Afiliados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-muted small fw-bold">Nombre del Plan</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: Plan Senior" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold">Precio ($)</label>
                            <input type="number" name="price" class="form-control" placeholder="0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Descripción Corta</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Opcional..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold d-flex justify-content-between">
                                Prestaciones Incluidas
                                <button type="button" class="btn btn-link p-0 small text-decoration-none" onclick="addFeatureRow('features_container_add')">+ Agregar</button>
                            </label>
                            <div id="features_container_add">
                                <div class="input-group mb-2">
                                    <input type="text" name="features[]" class="form-control form-control-sm" placeholder="Ej: Consultas 100% bonificadas">
                                    <button class="btn btn-outline-danger btn-sm" type="button" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Crear Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Función para agregar dinámicamente filas de prestaciones/beneficios
    function addFeatureRow(containerId) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" name="features[]" class="form-control form-control-sm" placeholder="Escribe un beneficio...">
            <button class="btn btn-outline-danger btn-sm" type="button" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    // Asegurar que los modales se posicionen directamente en el body al cargar la página.
    // Esto evita conflictos de "stacking context" (segundo plano / sombreado) con el backdrop de Bootstrap.
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.modal').forEach(function(modal) {
            document.body.appendChild(modal);
        });
    });
</script>
@endsection
