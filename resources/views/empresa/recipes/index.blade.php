@extends('layouts.empresa')

@section('content')
<div class="container py-4">

    {{-- CABECERA INSTITUCIONAL --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Módulo de Producción</h2>
            <p class="text-muted small">Gestión de Recetas y fórmulas de fabricación.</p>
        </div>
        <a href="{{ route('empresa.recipes.create') }}" class="btn btn-primary fw-bold shadow-sm d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i> NUEVA RECETA
        </a>
    </div>

    @if($recipes->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5 bg-white">
            <div class="card-body">
                <i class="bi bi-mortarboard fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold text-dark">Aún no tiene recetas creadas</h5>
                <p class="text-muted">Consolide sus ingredientes y automatice su stock de producción.</p>
                <a href="{{ route('empresa.recipes.create') }}" class="btn btn-outline-primary fw-bold mt-3 px-4">CREAR MI PRIMERA RECETA</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($recipes as $recipe)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 bg-white border-top border-4" style="border-top-color: var(--color-primario) !important;">
                        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-muted border small text-uppercase">Venta Directa</span>
                            <div class="dropdown">
                                <button class="btn btn-link link-secondary p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="{{ route('empresa.recipes.edit', $recipe) }}">Editar Receta</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item text-danger" onclick="confirmDelete('{{ $recipe->id }}')">Eliminar</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-box-seam fs-3" style="color: var(--color-primario);"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h5 class="fw-bold mb-1 text-dark text-truncate">{{ $recipe->product->name ?? 'Producto no encontrado' }}</h5>
                                    <small class="text-muted d-block text-truncate">{{ $recipe->name }}</small>
                                </div>
                            </div>
                            
                            <hr class="opacity-10 my-3">
                            
                            <div class="d-flex justify-content-between align-items-center small mb-2">
                                <span class="text-muted">Ingredientes vinculados:</span>
                                <span class="badge bg-primary rounded-pill">{{ $recipe->items->count() }}</span>
                            </div>

                            <a href="{{ route('empresa.recipes.edit', $recipe) }}" class="btn btn-light border w-100 fw-bold mt-3 transition-all">
                                <i class="bi bi-gear-fill me-2"></i> CONFIGURAR FÓRMULA
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

{{-- MODAL ELIMINAR --}}
<form id="deleteForm" method="POST" action="">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('¿Seguro que desea eliminar esta receta?')) {
            let form = document.getElementById('deleteForm');
            form.action = '/empresa/recipes/' + id;
            form.submit();
        }
    }
</script>
<style>
    .transition-all { transition: all 0.2s ease; }
    .transition-all:hover { opacity: 0.8; transform: translateY(-2px); }
</style>
@endsection
