@extends('layouts.app')

@section('styles')
<style>
    /* FORZAR FONDO NEGRO TOTAL PARA EL OWNER */
    body, #main-content, .navbar-premium {
        background-color: #000 !important;
        background-image: none !important;
    }
    
    .navbar-brand, .nav-link, .btn-profile {
        color: #ffffff !important;
    }

    /* ALTO CONTRASTE PARA LOS TEXTOS */
    .text-white { color: #ffffff !important; }
    .text-secondary, .text-muted, .opacity-75 { color: #ffffff !important; opacity: 1 !important; }
    
    .card {
        background-color: #000 !important;
        border: 1px solid rgba(255,255,255,0.2) !important;
    }

    .table thead th {
        background-color: #1a1a1a !important;
        color: #ffffff !important;
        border-bottom: 2px solid #ffffff !important;
    }

    /* PAGINACIÓN EN NEGRO */
    .pagination .page-link {
        background-color: #000 !important;
        color: #fff !important;
        border-color: rgba(255,255,255,0.2) !important;
    }
    .pagination .page-item.active .page-link {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h2 class="text-white fw-bold mb-1">Centro de Comunicaciones SaaS</h2>
            <p class="text-white opacity-75 text-uppercase small fw-bold tracking-wider">Gestión de avisos individuales y globales a empresas</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button type="button" class="btn btn-primary px-4 shadow-sm fw-bold border-white border-opacity-10" data-bs-toggle="modal" data-bs-target="#modalGlobalNotification">
                <i class="bi bi-megaphone me-2"></i> Nueva Comunicación Global
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-black border-success text-success shadow-lg mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card bg-black border-secondary border-opacity-25 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-dark bg-opacity-50 border-secondary border-opacity-25 py-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="text-white mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Registro de Avisos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="--bs-table-bg: transparent;">
                    <thead class="text-white small text-uppercase" style="background: rgba(255,255,255,0.05);">
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                            <th class="ps-4 py-3 fw-bold">Fecha/Hora</th>
                            <th class="py-3 fw-bold">Empresa / Destino</th>
                            <th class="py-3 fw-bold">Título / Asunto</th>
                            <th class="py-3 fw-bold">Tipo</th>
                            <th class="py-3 fw-bold">Media</th>
                            <th class="text-end pe-4 py-3 fw-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($notifications as $notif)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <td class="ps-4">
                                <div class="text-white fw-bold">{{ $notif->created_at->format('d/m/Y') }}</div>
                                <div class="small text-white opacity-75">{{ $notif->created_at->format('H:i') }} hs</div>
                            </td>
                            <td>
                                @if($notif->empresa_id)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 24px; height: 24px; font-size: 0.7rem; border: 1px solid rgba(255,255,255,0.2);">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <span class="text-white small fw-bold">{{ $notif->empresa->nombre_comercial }}</span>
                                    </div>
                                @else
                                    <span class="badge bg-primary text-white border border-white border-opacity-25 px-3 rounded-pill shadow-sm" style="font-size: 0.7rem; background: #2563eb !important;">
                                        <i class="bi bi-globe me-1 text-white"></i> GLOBAL (Todas)
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="text-white fw-bold small">{{ $notif->title ?: 'Sin título' }}</div>
                                <div class="text-white opacity-75 text-truncate" style="max-width: 250px; font-size: 0.75rem;">
                                    {{ Str::limit($notif->message, 80) }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeStyle = match($notif->type) {
                                        'vencimiento' => 'background: #ef4444; color: white;',
                                        'mantenimiento' => 'background: #f59e0b; color: black;',
                                        'novedad' => 'background: #0ea5e9; color: white;',
                                        'festividad' => 'background: #10b981; color: white;',
                                        default => 'background: #64748b; color: white;'
                                    };
                                @endphp
                                <span class="badge px-2 py-1 rounded-pill shadow-sm fw-bold" style="font-size: 0.65rem; {{ $badgeStyle }}">
                                    {{ strtoupper($notif->type) }}
                                </span>
                            </td>
                            <td>
                                @if($notif->media_url)
                                    <a href="{{ $notif->media_url }}" target="_blank" class="btn btn-sm btn-light py-0 px-2 fw-bold shadow-sm" style="font-size: 0.7rem;">
                                        <i class="bi {{ $notif->media_type === 'image' ? 'bi-image' : 'bi-play-btn' }} me-1"></i> VER MEDIA
                                    </a>
                                @else
                                    <span class="text-white opacity-50 small" style="font-size: 0.7rem;">Solo texto</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button class="btn btn-sm d-flex align-items-center justify-content-center shadow-sm" 
                                            style="width: 32px; height: 32px; border-radius: 8px; background: #3b82f6; border: none;"
                                            title="Editar" onclick="editNotification({{ json_encode($notif) }})">
                                        <i class="bi bi-pencil-square text-white"></i>
                                    </button>
                                    <form action="{{ route('owner.notifications.toggle', $notif->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm d-flex align-items-center justify-content-center shadow-sm" 
                                                style="width: 32px; height: 32px; border-radius: 8px; background: {{ $notif->active ? '#334155' : '#10b981' }}; border: none;"
                                                title="{{ $notif->active ? 'Desactivar' : 'Activar' }}">
                                            <i class="bi {{ $notif->active ? 'bi-eye-slash' : 'bi-eye' }} text-white"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.notifications.destroy', $notif->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar comunicación permanentemente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm d-flex align-items-center justify-content-center shadow-sm" 
                                                style="width: 32px; height: 32px; border-radius: 8px; background: #ef4444; border: none;"
                                                title="Eliminar">
                                            <i class="bi bi-trash text-white"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="bi bi-send fa-3x mb-3 d-block opacity-25"></i>
                                No se han enviado comunicaciones recientemente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-secondary border-opacity-25 py-3">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal: Notificación Global -->
<div class="modal fade" id="modalGlobalNotification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white fw-bold"><i class="bi bi-megaphone me-2 text-primary"></i> Nueva Comunicación Global</h5>
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
                <h5 class="modal-title text-white fw-bold"><i class="bi bi-pencil-square me-2 text-info"></i> Editar Comunicación</h5>
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
