@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="text-white fw-bold">Centro de Comunicaciones SaaS</h2>
            <p class="text-secondary text-uppercase small fw-bold">Gestión de avisos individuales y globales a empresas</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button type="button" class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGlobalNotification">
                <i class="fas fa-bullhorn me-2"></i> Nueva Comunicación Global
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-10 text-success border-success mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card bg-dark border-secondary shadow-lg">
        <div class="card-header bg-transparent border-secondary py-3 d-flex justify-content-between align-items-center">
            <h5 class="text-white mb-0"><i class="fas fa-history me-2"></i> Registro de Avisos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 border-secondary align-middle">
                    <thead class="text-secondary small">
                        <tr>
                            <th class="ps-4">Fecha/Hora</th>
                            <th>Empresa / Destino</th>
                            <th>Título / Asunto</th>
                            <th>Tipo</th>
                            <th>Media</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notif)
                        <tr>
                            <td class="ps-4">
                                <div class="text-white">{{ $notif->created_at->format('d/m/Y') }}</div>
                                <div class="small text-secondary">{{ $notif->created_at->format('H:i') }} hs</div>
                            </td>
                            <td>
                                @if($notif->empresa_id)
                                    <span class="text-white fw-bold">{{ $notif->empresa->nombre_comercial }}</span>
                                @else
                                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary px-3">🌎 GLOBAL (Todas)</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-white fw-bold">{{ $notif->title ?: 'Sin título' }}</div>
                                <div class="small text-secondary text-truncate" style="max-width: 250px;">
                                    {{ Str::limit($notif->message, 80) }}
                                </div>
                            </td>
                            <td>
                                @if($notif->type === 'vencimiento')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">VENCIMIENTO</span>
                                @elseif($notif->type === 'mantenimiento')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">MANTENIMIENTO</span>
                                @elseif($notif->type === 'novedad')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info">NOVEDAD</span>
                                @elseif($notif->type === 'festividad')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">FESTIVIDAD</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">{{ strtoupper($notif->type) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($notif->media_url)
                                    @if($notif->media_type === 'image')
                                        <a href="{{ $notif->media_url }}" target="_blank" class="text-info small">
                                            <i class="fas fa-image me-1"></i> Imagen
                                        </a>
                                    @else
                                        <a href="{{ $notif->media_url }}" target="_blank" class="text-warning small">
                                            <i class="fas fa-video me-1"></i> Video
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted small">Solo texto</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info" title="Editar" 
                                            onclick="editNotification({{ json_encode($notif) }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('owner.notifications.toggle', $notif->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $notif->active ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                                title="{{ $notif->active ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas {{ $notif->active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.notifications.destroy', $notif->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar comunicación permanentemente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="fas fa-paper-plane fa-3x mb-3 d-block opacity-25"></i>
                                No se han enviado comunicaciones recientemente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-secondary py-3">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal: Notificación Global -->
<div class="modal fade" id="modalGlobalNotification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-bullhorn me-2 text-primary"></i> Nueva Comunicación Global</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('owner.notifications.send') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-secondary small mb-4">Este mensaje será visible para <strong>todos los administradores y usuarios</strong> de todas las empresas del sistema en sus respectivos dashboards.</p>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">TÍTULO / ASUNTO</label>
                            <input type="text" name="title" class="form-control bg-black border-secondary text-white" placeholder="Ej: ¡Feliz Día del Padre!" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small fw-bold">TIPO DE AVISO</label>
                            <select name="type" class="form-select bg-black border-secondary text-white" required>
                                <option value="aviso_general">Aviso General</option>
                                <option value="festividad">Festividad</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="novedad">Nueva Función</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small fw-bold">VENCE EL (OPCIONAL)</label>
                            <input type="date" name="expires_at" class="form-control bg-black border-secondary text-white">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">MENSAJE / CONTENIDO</label>
                        <textarea name="message" rows="4" class="form-control bg-black border-secondary text-white" placeholder="Escribe aquí el contenido del mensaje..." required></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-bold">ADJUNTAR FLYER O VIDEO (OPCIONAL)</label>
                        <input type="file" name="media" class="form-control bg-black border-secondary text-white" accept="image/*,video/*">
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">ENVIAR A TODOS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Editar Notificación -->
<div class="modal fade" id="modalEditNotification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-edit me-2 text-info"></i> Editar Comunicación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditNotification" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">TÍTULO / ASUNTO</label>
                            <input type="text" name="title" id="edit-title" class="form-control bg-black border-secondary text-white" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small fw-bold">TIPO DE AVISO</label>
                            <select name="type" id="edit-type" class="form-select bg-black border-secondary text-white" required>
                                <option value="aviso_general">Aviso General</option>
                                <option value="festividad">Festividad</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="novedad">Nueva Función</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small fw-bold">VENCE EL</label>
                            <input type="date" name="expires_at" id="edit-expires" class="form-control bg-black border-secondary text-white">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">MENSAJE / CONTENIDO</label>
                        <textarea name="message" id="edit-message" rows="4" class="form-control bg-black border-secondary text-white" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-info px-4 fw-bold">GUARDAR CAMBIOS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editNotification(notif) {
    document.getElementById('formEditNotification').action = `/owner/notificaciones/${notif.id}`;
    document.getElementById('edit-title').value = notif.title;
    document.getElementById('edit-type').value = notif.type;
    document.getElementById('edit-message').value = notif.message;
    if(notif.expires_at) {
        document.getElementById('edit-expires').value = notif.expires_at.split('T')[0];
    }
    new bootstrap.Modal(document.getElementById('modalEditNotification')).show();
}
</script>

<style>
    .form-control:focus, .form-select:focus {
        background-color: #000 !important;
        border-color: #3b82f6 !important;
        color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
</style>
@endsection
