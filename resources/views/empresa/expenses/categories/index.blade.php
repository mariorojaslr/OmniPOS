@extends('layouts.empresa')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0">Categorías de Gastos</h4>
        <p class="text-muted mb-0">Clasifica tus egresos para reportes más claros.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('empresa.gastos.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Volver a Gastos
        </a>
        <button class="btn btn-primary rounded-pill px-4 ms-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg me-2"></i> Nueva Categoría
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nombre de Categoría</th>
                            <th>Color de Identificación</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $cat->nombre }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle me-3" style="width: 20px; height: 20px; background-color: {{ $cat->color }}; border: 1px solid rgba(0,0,0,0.1);"></div>
                                    <code>{{ $cat->color }}</code>
                                </div>
                            </td>
                            <td>
                                @if($cat->activo)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 small">Activa</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 small">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light rounded-circle shadow-sm me-1" 
                                        onclick="editCategory({{ $cat->id }}, '{{ $cat->nombre }}', '{{ $cat->color }}', {{ $cat->activo }})">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('empresa.gastos_categorias.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No has creado categorías todavía.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form action="{{ route('empresa.gastos_categorias.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold text-dark">Nueva Categoría de Gasto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-control rounded-3 p-2 bg-light border-0" placeholder="Ej: Servicios Públicos" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Color Identificador</label>
                        <input type="color" name="color" class="form-control form-control-color w-100 rounded-3 border-0 bg-light p-1" value="#3b82f6">
                        <small class="text-muted small">Este color se usará en reportes visuales.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Crear Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form id="editCategoryForm" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold text-dark text-uppercase small opacity-75">Editar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control rounded-3 p-2 bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Color Identificador</label>
                        <input type="color" name="color" id="edit_color" class="form-control form-control-color w-100 rounded-3 border-0 bg-light p-1">
                    </div>
                    <div class="mb-0">
                        <div class="form-check form-switch bg-light p-3 rounded-3">
                            <input class="form-check-input ms-0" type="checkbox" name="activo" value="1" id="edit_activo">
                            <label class="form-check-label ms-4 fw-bold" for="edit_activo">Categoría Activa</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold shadow-sm">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategory(id, nombre, color, activo) {
    const form = document.getElementById('editCategoryForm');
    form.action = `/empresa/gastos_categorias/${id}`;
    
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_color').value = color;
    document.getElementById('edit_activo').checked = activo;
    
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}
</script>

@endsection
