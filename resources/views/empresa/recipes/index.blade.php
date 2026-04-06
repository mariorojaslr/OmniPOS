@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Gestión de Recetas</h2>
            <p class="text-muted small">Vincule sus productos terminados con sus materias primas e insumos.</p>
        </div>
        <a href="{{ route('empresa.recipes.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> NUEVA RECETA
        </a>
    </div>

    @if($recipes->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5 bg-white">
            <div class="card-body">
                <i class="bi bi-mortarboard fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold text-dark">Aún no tiene recetas creadas</h5>
                <p class="text-muted">Empiece creando una receta para sus productos de venta que requieran transformación.</p>
                <a href="{{ route('empresa.recipes.create') }}" class="btn btn-outline-primary fw-bold mt-3">CREAR MI PRIMERA RECETA</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($recipes as $recipe)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 bg-white">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-primary border border-primary opacity-75">Venta Directa</span>
                            <div class="dropdown">
                                <button class="btn btn-link link-dark text-decoration-none dropdown-toggle p-0" data-bs-toggle="dropdown">
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
                                <i class="bi bi-box-seam fs-3 text-muted me-3"></i>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $recipe->product->name }}</h5>
                                    <small class="text-muted">{{ $recipe->name }}</small>
                                </div>
                            </div>
                            
                            <hr class="opacity-10 my-3">
                            
                            <div class="d-flex justify-content-between align-items-center small mb-2">
                                <span class="text-muted">Componentes:</span>
                                <span class="fw-bold text-dark">{{ $recipe->items->count() }} ingredientes</span>
                            </div>

                            <a href="{{ route('empresa.recipes.edit', $recipe) }}" class="btn btn-light border w-100 fw-bold mt-3">
                                CONFIGURAR ARMAZÓN
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
        if (confirm('¿Seguro que desea eliminar esta receta? Los ingredientes dejarán de descontarse automáticamente.')) {
            let form = document.getElementById('deleteForm');
            form.action = '/empresa/recipes/' + id;
            form.submit();
        }
    }
</script>
@endsection
