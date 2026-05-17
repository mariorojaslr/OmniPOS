@extends('layouts.empresa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Obras Sociales y Convenios</h2>
            <p class="text-secondary">Gestiona los convenios corporativos para cobro de turnos.</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Botón para descargar la plantilla o el listado actual en formato Excel/CSV -->
            <a href="{{ route('empresa.obras_sociales.export') }}" class="btn btn-outline-success fw-bold px-3 rounded-pill">
                <i class="bi bi-file-earmark-excel me-1"></i> Plantilla / Exportar Excel
            </a>
            <!-- Botón para abrir el modal de importación masiva -->
            <button class="btn btn-outline-primary fw-bold px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalImportObraSocial">
                <i class="bi bi-cloud-arrow-up me-1"></i> Importar CSV
            </button>
            <button class="btn btn-primary fw-bold px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalAddObraSocial" style="background: linear-gradient(135deg, var(--color-primario, #0d6efd), #3b82f6); border: none;">
                <i class="bi bi-plus-lg me-1"></i> Nueva Obra Social
            </button>
        </div>
    </div>

    <!-- Alertas de validación de Bootstrap en español -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 bg-transparent">
        <div class="card-body p-0">
            <div class="table-responsive rounded-4 border bg-white shadow-sm">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-uppercase small text-secondary fw-bold border-bottom border-secondary border-opacity-25">ID</th>
                            <th class="py-3 text-uppercase small text-secondary fw-bold border-bottom border-secondary border-opacity-25">Obra Social</th>
                            <th class="py-3 text-uppercase small text-secondary fw-bold border-bottom border-secondary border-opacity-25">CUIT</th>
                            <th class="py-3 text-uppercase small text-secondary fw-bold border-bottom border-secondary border-opacity-25">Contacto / Referente</th>
                            <th class="py-3 text-uppercase small text-secondary fw-bold border-bottom border-secondary border-opacity-25">Código Prestador</th>
                            <th class="py-3 text-uppercase small text-secondary fw-bold border-bottom border-secondary border-opacity-25">Estado</th>
                            <th class="px-4 py-3 text-uppercase small text-secondary fw-bold border-bottom border-secondary border-opacity-25 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obrasSociales as $os)
                        <tr>
                            <td class="px-4 py-3 border-bottom text-muted">#{{ $os->id }}</td>
                            <td class="py-3 border-bottom text-dark">
                                <div class="fw-bold">{{ $os->nombre }}</div>
                                @if(!empty($os->config_json['direccion']))
                                    <div class="text-secondary x-small mt-1" style="font-size: 0.72rem;">
                                        <i class="bi bi-geo-alt-fill text-danger opacity-75 me-1"></i>{{ $os->config_json['direccion'] }}
                                    </div>
                                @endif
                                @if(!empty($os->config_json['notas']))
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 mt-1" style="font-size: 0.65rem; cursor: help;" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='text-start'>{{ str_replace('"', '&quot;', $os->config_json['notas']) }}</div>">
                                        <i class="bi bi-journal-text me-1"></i> Ver Notas
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 border-bottom text-muted">{{ $os->cuit ?? 'N/A' }}</td>
                            <td class="py-3 border-bottom text-dark small">
                                @if(!empty($os->config_json['contacto_nombre']))
                                    <div class="fw-bold"><i class="bi bi-person-fill text-secondary me-1"></i>{{ $os->config_json['contacto_nombre'] }}</div>
                                    @if(!empty($os->config_json['telefono']))
                                        <div class="text-secondary x-small"><i class="bi bi-telephone-fill me-1"></i>{{ $os->config_json['telefono'] }}</div>
                                    @endif
                                    @if(!empty($os->config_json['email']))
                                        <div class="text-secondary x-small font-monospace" style="font-size: 0.7rem;"><i class="bi bi-envelope-fill me-1"></i>{{ $os->config_json['email'] }}</div>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3 border-bottom font-monospace text-primary small fw-bold">
                                {{ $os->config_json['codigo_prestador'] ?? '—' }}
                            </td>
                            <td class="py-3 border-bottom">
                                @if($os->activo)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Activa</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">Inactiva</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border-bottom text-end">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $os->id }}">Editar</button>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit{{ $os->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <form action="{{ route('empresa.obras_sociales.update', $os) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title">Editar Obra Social</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-bold">Nombre de la OS *</label>
                                                <input type="text" name="nombre" class="form-control" value="{{ $os->nombre }}" required>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small fw-bold">CUIT (Opcional)</label>
                                                    <input type="text" name="cuit" class="form-control" value="{{ $os->cuit }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small fw-bold">Código de Prestador</label>
                                                    <input type="text" name="codigo_prestador" class="form-control" value="{{ $os->config_json['codigo_prestador'] ?? '' }}" placeholder="Ej: APOS-102">
                                                </div>
                                            </div>
                                            
                                            <hr class="my-3 opacity-10">
                                            <h6 class="fw-bold mb-3 text-primary small text-uppercase"><i class="bi bi-telephone me-2"></i>Contacto y Ubicación</h6>

                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-bold">Dirección de la OS / Sucursal</label>
                                                <input type="text" name="direccion" class="form-control" value="{{ $os->config_json['direccion'] ?? '' }}" placeholder="Ej: Av. Rivadavia 1234, CABA">
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small fw-bold">Persona de Enlace / Contacto</label>
                                                    <input type="text" name="contacto_nombre" class="form-control" value="{{ $os->config_json['contacto_nombre'] ?? '' }}" placeholder="Ej: Dr. Pérez o Auditoría">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small fw-bold">Teléfono de Enlace</label>
                                                    <input type="text" name="telefono" class="form-control" value="{{ $os->config_json['telefono'] ?? '' }}" placeholder="Ej: 11432211 int 5">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-bold">Correo de Contacto</label>
                                                <input type="email" name="email" class="form-control" value="{{ $os->config_json['email'] ?? '' }}" placeholder="Ej: auditoria@obrasocial.org">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-bold">Notas de Convenio y Autorizaciones</label>
                                                <textarea name="notas" class="form-control" rows="3" placeholder="Ingresa anotaciones técnicas, reglas de cobertura, horarios de atención telefónica o datos referenciales importantes...">{{ $os->config_json['notas'] ?? '' }}</textarea>
                                            </div>

                                            <hr class="my-3 opacity-10">

                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-bold">API Access Token (Para integración externa)</label>
                                                <div class="input-group">
                                                    <input type="text" name="api_token" class="form-control font-monospace bg-light small" value="{{ $os->api_token }}" readonly id="token{{ $os->id }}">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('token{{ $os->id }}')"><i class="bi bi-clipboard"></i></button>
                                                </div>
                                                <p class="x-small text-muted mt-1 mb-0">Comparte esta llave con la Obra Social para que consuman reportes de forma automática.</p>
                                            </div>
                                            <div class="form-check form-switch mt-3">
                                                <input class="form-check-input" type="checkbox" name="activo" value="1" {{ $os->activo ? 'checked' : '' }}>
                                                <label class="form-check-label">Convenio Activo</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="bi bi-shield-x fs-1 opacity-25 d-block mb-3"></i>
                                Aún no tienes convenios con obras sociales.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="modalAddObraSocial" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('empresa.obras_sociales.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Nueva Obra Social</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nombre de la OS *</label>
                        <input type="text" name="nombre" class="form-control" required placeholder="Ej: OSDE, Swiss Medical...">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">CUIT (Opcional)</label>
                            <input type="text" name="cuit" class="form-control" placeholder="Para facturación masiva">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Código de Prestador / Homologación</label>
                            <input type="text" name="codigo_prestador" class="form-control" placeholder="Ej: APOS-102">
                        </div>
                    </div>

                    <hr class="my-3 opacity-10">
                    <h6 class="fw-bold mb-3 text-primary small text-uppercase"><i class="bi bi-telephone me-2"></i>Contacto y Ubicación (Opcional)</h6>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Dirección de la OS / Sucursal</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Ej: Av. Rivadavia 1234, CABA">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Persona de Enlace / Contacto</label>
                            <input type="text" name="contacto_nombre" class="form-control" placeholder="Ej: Dr. Pérez o Auditoría">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Teléfono de Enlace</label>
                            <input type="text" name="telefono" class="form-control" placeholder="Ej: 11432211 int 5">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Correo de Contacto</label>
                        <input type="email" name="email" class="form-control" placeholder="Ej: auditoria@obrasocial.org">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Notas de Convenio y Autorizaciones</label>
                        <textarea name="notas" class="form-control" rows="3" placeholder="Anotaciones técnicas, horarios de auditoría u observaciones..."></textarea>
                    </div>

                    <hr class="my-3 opacity-10">

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="activo" value="1" checked>
                        <label class="form-check-label">Convenio Activo</label>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Crear Convenio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="modalImportObraSocial" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('empresa.obras_sociales.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title"><i class="bi bi-file-earmark-excel me-2 text-success"></i>Importar Convenios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">Sube tu archivo de Obras Sociales en formato CSV. El importador soporta las columnas opcionales adicionales para facilitar la carga inicial masiva.</p>
                    <div class="bg-light p-3 rounded-3 mb-3 small font-monospace" style="font-size: 0.72rem;">
                        <strong>Columnas admitidas en orden:</strong><br>
                        Nombre; CUIT; Codigo Prestador; Direccion; Telefono; Email; Contacto Nombre; Notas
                    </div>
                    <input type="file" name="excel_file" class="form-control" required accept=".csv">
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Subir y Cargar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(id) {
        var copyText = document.getElementById(id);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        alert("¡Token copiado al portapapeles!");
    }

    // Solución para evitar que los modales queden en segundo plano / ensombrecidos e inicializar Tooltips
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.modal').forEach(function(modal) {
            document.body.appendChild(modal);
        });

        // Inicializar todos los tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
