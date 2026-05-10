@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4 pb-5">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">Configuración de Servicios</h2>
            <p class="text-muted small mb-0">Define tus especialidades, precios y comisiones por trabajo.</p>
        </div>
        <button class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalServicio">
            <i class="bi bi-plus-lg me-2"></i> NUEVO SERVICIO
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr class="x-small text-muted text-uppercase">
                        <th class="ps-4 py-3">Categoría</th>
                        <th class="py-3">Servicio</th>
                        <th class="py-3 text-center">Duración</th>
                        <th class="py-3 text-end">Precio</th>
                        <th class="py-3 text-center">Comisión</th>
                        <th class="py-3 text-center" width="80">Editar</th>
                        <th class="py-3 text-center pe-4" width="80">Borrar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $s)
                        <tr>
                            <td class="ps-4">
                                @if($s->categoria)
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        {{ strtoupper($s->categoria) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        SIN CATEGORÍA
                                    </span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $s->nombre }}</td>
                            <td class="text-center small">{{ $s->duracion_minutos }} min</td>
                            <td class="text-end fw-bold text-success">$ {{ number_format($s->precio, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="fw-bold text-muted small">{{ number_format($s->comision_porcentaje, 1) }}%</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm text-dark border-0" onclick="editarServicio({{ $s }})" title="Editar">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>
                            </td>
                            <td class="text-center pe-4">
                                <form action="{{ route('empresa.servicios.destroy', $s->id) }}" method="POST" class="d-inline m-0">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm text-danger border-0" onclick="return confirm('¿Eliminar servicio?')" title="Borrar">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">No hay servicios configurados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREAR/EDITAR --}}
<div class="modal fade" id="modalServicio" tabindex="-1">
    <div class="modal-dialog">
        <form id="formServicio" action="{{ route('empresa.servicios.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div id="methodField"></div>
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold" id="tituloModal">Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Categoría / Especialidad</label>
                    <input type="text" name="categoria" id="categoria" class="form-control" placeholder="Ej: Manicura, Masajes" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Nombre del Servicio</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Esmaltado Semipermanente" required>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Precio de Venta ($)</label>
                        <input type="number" name="precio" id="precio" step="0.01" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small">Duración (min)</label>
                        <input type="number" name="duracion_minutos" id="duracion_minutos" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">Comisión para el Profesional (%)</label>
                        <div class="input-group">
                            <input type="number" name="comision_porcentaje" id="comision_porcentaje" step="0.1" class="form-control" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">GUARDAR SERVICIO</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editarServicio(servicio) {
        const form = document.getElementById('formServicio');
        form.action = `/empresa/servicios/${servicio.id}`;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('tituloModal').innerText = 'Editar Servicio';
        
        document.getElementById('categoria').value = servicio.categoria;
        document.getElementById('nombre').value = servicio.nombre;
        document.getElementById('precio').value = servicio.precio;
        document.getElementById('duracion_minutos').value = servicio.duracion_minutos;
        document.getElementById('comision_porcentaje').value = servicio.comision_porcentaje;
        
        new bootstrap.Modal(document.getElementById('modalServicio')).show();
    }
</script>
@endsection
