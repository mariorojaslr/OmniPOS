@extends('layouts.empresa')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0 text-dark">Detalle de Ticket #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</h4>
        <small class="text-muted">Estado y seguimiento de tu solicitud.</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('empresa.soporte.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        
        <div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge {{ $ticket->status == 'abierto' ? 'bg-danger-subtle text-danger' : ($ticket->status == 'en_proceso' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success') }} px-3 py-2 rounded-pill border">
                        Estado: {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    <span class="badge {{ $ticket->priority == 'alta' ? 'bg-danger' : ($ticket->priority == 'media' ? 'bg-warning text-dark' : 'bg-info') }}">
                        Prioridad {{ ucfirst($ticket->priority) }}
                    </span>
                </div>

                <h5 class="fw-bold text-dark">{{ $ticket->subject }}</h5>
                <div class="text-secondary mt-3 lh-lg" style="white-space: pre-wrap;">{!! preg_replace('/!\[.*?\]\((.*?)\)/', '<img src="$1" class="img-fluid rounded shadow-sm my-3 d-block" style="max-height: 500px">', e($ticket->message)) !!}</div>

                <hr class="my-4 border-light">

                <div class="d-flex align-items-center mb-0">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold fs-5 me-3 shadow-sm" style="width: 45px; height: 45px;">
                        {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <strong class="d-block text-dark">{{ $ticket->user->name ?? 'Tú' }}</strong>
                        <small class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-5">

        {{-- BLOQUE DE RESPUESTA OFICIAL --}}
        @if($ticket->respuesta_owner)
        <div class="card shadow-sm border-0 rounded-4 border-start border-4 border-success bg-white mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-success mb-3">
                    <i class="me-2 text-success">👤</i> 
                    Respuesta del Equipo de Soporte Central
                </h6>
                <div class="mb-0 text-secondary lh-lg" style="white-space: pre-wrap;">{!! preg_replace('/!\[.*?\]\((.*?)\)/', '<img src="$1" class="img-fluid rounded shadow-sm my-3 d-block" style="max-height: 500px">', e($ticket->respuesta_owner)) !!}</div>
                <div class="mt-4 pt-4 border-top border-light text-end">
                    <small class="text-muted">Actualizado: {{ $ticket->updated_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
        @else
        <div class="card shadow-sm border-0 rounded-4 bg-light text-center p-5 mb-4 d-flex flex-column align-items-center justify-content-center">
            <h1 class="text-muted opacity-25 mb-4" style="font-size: 5rem;">⏳</h1>
            <h5 class="fw-bold text-secondary mb-2">Ticket en Revisión</h5>
            <p class="text-muted mb-0">Nuestro equipo está analizando tu solicitud.</p>
        </div>
        @endif

        {{-- PANEL ADMINISTRATIVO (SOLO VISIBLE PARA EL OWNER) --}}
        @if(auth()->user()->role === 'owner')
        <div class="card shadow-sm border-0 rounded-4 border-top border-4 border-primary bg-white">
            <div class="card-header bg-white p-4 border-0 pb-0">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock me-2"></i>Control de Administrador</h5>
                <small class="text-muted">Como Owner, puedes gestionar este ticket desde aquí.</small>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.soporte.update', $ticket->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Estado del Ticket</label>
                        <select name="status" class="form-select border-2">
                            <option value="abierto" {{ $ticket->status == 'abierto' ? 'selected' : '' }}>🔴 Abierto (Pendiente)</option>
                            <option value="en_proceso" {{ $ticket->status == 'en_proceso' ? 'selected' : '' }}>🟡 En Proceso (Analizando)</option>
                            <option value="cerrado" {{ $ticket->status == 'cerrado' ? 'selected' : '' }}>🟢 Cerrado (Resuelto)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Prioridad</label>
                        <select name="priority" class="form-select border-2">
                            <option value="baja" {{ $ticket->priority == 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ $ticket->priority == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ $ticket->priority == 'alta' ? 'selected' : '' }}>Alta 🔥</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Tu Respuesta (Pega imágenes aquí)</label>
                        <textarea name="respuesta_owner" id="replyArea" class="form-control border-2" rows="6" 
                                  data-upload-url="{{ route('owner.soporte.uploadMedia') }}"
                                  placeholder="Escribe tu respuesta técnica o pega una captura con Ctrl+V...">{{ old('respuesta_owner', $ticket->respuesta_owner) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-2 shadow-sm">
                        ACTUALIZAR Y ENVIAR RESPUESTA
                    </button>
                    <input type="hidden" name="redirect_back" value="1">
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- SCRIPT PARA PEGAR IMÁGENES (COPIADO DEL OWNER) --}}
@if(auth()->user()->role === 'owner')
<script>
document.getElementById('replyArea')?.addEventListener('paste', function (e) {
    const items = (e.clipboardData || e.originalEvent.clipboardData).items;
    for (let index in items) {
        const item = items[index];
        if (item.kind === 'file' && item.type.includes('image')) {
            const blob = item.getAsFile();
            const textarea = e.target;
            const placeholder = "\n![Subiendo imagen...]()\n";
            textarea.value += placeholder;

            const formData = new FormData();
            formData.append('image', blob);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(textarea.dataset.uploadUrl, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.url) {
                    textarea.value = textarea.value.replace(placeholder, `\n![imagen](${data.url})\n`);
                }
            })
            .catch(err => {
                textarea.value = textarea.value.replace(placeholder, "\n[Error al subir imagen]\n");
            });
        }
    }
});
</script>
@endif
@endsection
