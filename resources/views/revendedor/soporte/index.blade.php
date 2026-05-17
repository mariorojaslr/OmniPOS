@extends('layouts.reseller')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-5">
        <a href="{{ route('revendedor.dashboard') }}" class="text-muted text-decoration-none small fw-bold">
            <i class="bi bi-arrow-left me-1"></i> VOLVER AL PANEL
        </a>
        <h2 class="fw-800 text-white mt-3">Centro de Soporte y Reportes</h2>
        <p class="text-muted">¿Tienes algún problema con un cliente o una consulta técnica? Estamos para ayudarte.</p>
    </div>

    <div class="row g-4">
        <!-- Formulario de Ticket -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-4" style="background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body p-4">
                    <h5 class="text-white fw-bold mb-4">Abrir Nuevo Reporte de Incidencia</h5>
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Asunto / Título del Problema</label>
                            <input type="text" name="subject" class="form-control bg-dark text-white border-secondary" placeholder="Ej: Error en acceso de MedPlus" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Prioridad</label>
                                <select class="form-select bg-dark text-white border-secondary">
                                    <option value="baja">Baja (Consulta general)</option>
                                    <option value="media" selected>Media (Inconveniente funcional)</option>
                                    <option value="alta">Alta (Bloqueo de servicio)</option>
                                    <option value="critica">CRÍTICA (Caída del sistema)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Empresa Afectada</label>
                                <select class="form-select bg-dark text-white border-secondary">
                                    <option value="">Problema General</option>
                                    @foreach(\App\Models\Empresa::where('reseller_id', auth()->id())->get() as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->nombre_comercial }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Descripción Detallada</label>
                            <textarea name="message" class="form-control bg-dark text-white border-secondary" rows="5" placeholder="Explica lo que sucede..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 py-3 rounded-pill fw-bold text-dark shadow-sm">
                            ENVIAR REPORTE AL EQUIPO TÉCNICO
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info de Soporte -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 mb-4 bg-primary bg-opacity-10" style="border: 1px solid rgba(13, 110, 253, 0.2);">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-chat-dots-fill text-primary fs-1 mb-3"></i>
                    <h5 class="text-white fw-bold">Soporte WhatsApp Directo</h5>
                    <p class="text-muted small">Para casos de extrema urgencia, puedes comunicarte por nuestro canal exclusivo para Partners.</p>
                    <a href="https://wa.me/XXXXXXXXXX" target="_blank" class="btn btn-success rounded-pill px-4 fw-bold">
                        <i class="bi bi-whatsapp me-2"></i> HABLAR POR WHATSAPP
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-lg rounded-4" style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body p-4">
                    <h6 class="text-white fw-bold mb-3 small text-uppercase" style="letter-spacing: 1px;">Tus Reportes Recientes</h6>
                    <div class="text-center py-4 opacity-50">
                        <i class="bi bi-journal-check fs-2 text-muted"></i>
                        <p class="small mt-2">No tienes reportes pendientes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
