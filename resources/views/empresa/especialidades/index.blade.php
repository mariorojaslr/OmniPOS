@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4 pb-5">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">🏥 Especialidades Médicas</h2>
            <p class="text-muted small mb-0">Gestione la nómina de especialidades clínicas de su centro de salud.</p>
        </div>
        <button class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none;" data-bs-toggle="modal" data-bs-target="#modalEspecialidad">
            <i class="bi bi-plus-lg me-2"></i> NUEVA ESPECIALIDAD
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr class="x-small text-muted text-uppercase">
                        <th class="ps-4 py-3">Especialidad</th>
                        <th class="py-3">Descripción</th>
                        <th class="py-3 text-center">Nº de Prácticas</th>
                        <th class="py-3 text-center">Nº de Profesionales</th>
                        <th class="py-3 text-center">Estado</th>
                        <th class="py-3 text-center" width="80">Editar</th>
                        <th class="py-3 text-center pe-4" width="80">Borrar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($especialidadesData as $esp)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="p-2 rounded bg-info bg-opacity-10 text-info d-inline-flex">
                                        <i class="bi bi-heart-pulse-fill" style="font-size: 1.1rem;"></i>
                                    </span>
                                    {{ $esp->nombre }}
                                </span>
                            </td>
                            <td class="text-muted small text-truncate" style="max-width: 300px;">
                                {{ $esp->descripcion ?: 'Sin descripción' }}
                            </td>
                            <td class="text-center fw-bold">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                    {{ $esp->cant_servicios }}
                                </span>
                            </td>
                            <td class="text-center fw-bold">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    {{ $esp->cant_profesionales }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($esp->activo)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        ACTIVO
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        INACTIVO
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm text-dark border-0 animate-hover" onclick="editarEspecialidad({{ json_encode($esp) }})" title="Editar">
                                    <i class="bi bi-pencil-square fs-5 text-primary"></i>
                                </button>
                            </td>
                            <td class="text-center pe-4">
                                <form action="{{ route('empresa.especialidades.destroy', $esp->id) }}" method="POST" class="d-inline m-0">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm text-danger border-0 animate-hover" onclick="return confirm('¿Está seguro de eliminar esta especialidad?')" title="Borrar">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-medical fs-1 opacity-25 d-block mb-3"></i>
                                No hay especialidades configuradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREAR/EDITAR --}}
<div class="modal fade" id="modalEspecialidad" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEspecialidad" action="{{ route('empresa.especialidades.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf
            <div style="height: 6px; background: linear-gradient(90deg, #0284c7, #10b981);"></div>
            <div id="methodField"></div>
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="tituloModal">Nueva Especialidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Nombre de la Especialidad</label>
                    <input type="text" name="nombre" id="nombre" class="form-control border-slate rounded-3" placeholder="Ej: Cardiología, Pediatría, Traumatología" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control border-slate rounded-3" placeholder="Detalle informativo sobre la especialidad o consultorios..."></textarea>
                </div>
                <div class="mb-3" id="estadoContainer" style="display: none;">
                    <label class="form-label fw-bold small text-secondary">Estado Operativo</label>
                    <select name="activo" id="activo" class="form-select border-slate rounded-3">
                        <option value="1">Activo (Habilitado)</option>
                        <option value="0">Inactivo (Suspendido)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none;">GUARDAR ESPECIALIDAD</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editarEspecialidad(esp) {
        const form = document.getElementById('formEspecialidad');
        form.action = `/empresa/especialidades/${esp.id}`;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('tituloModal').innerText = 'Editar Especialidad';
        
        document.getElementById('nombre').value = esp.nombre;
        document.getElementById('descripcion').value = esp.descripcion || '';
        document.getElementById('activo').value = esp.activo ? "1" : "0";
        document.getElementById('estadoContainer').style.display = 'block';
        
        new bootstrap.Modal(document.getElementById('modalEspecialidad')).show();
    }

    // Resetear formulario al cerrar modal
    document.getElementById('modalEspecialidad').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('formEspecialidad');
        form.action = "{{ route('empresa.especialidades.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('tituloModal').innerText = 'Nueva Especialidad';
        form.reset();
        document.getElementById('estadoContainer').style.display = 'none';
    });
</script>

<style>
    .border-slate {
        border: 1px solid #cbd5e1;
    }
    .border-slate:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }
    .animate-hover:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }
</style>
@endsection
