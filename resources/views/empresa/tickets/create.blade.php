@extends('layouts.empresa')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0">Creato Nuevo Ticket de Soporte</h4>
        <p class="text-muted mb-0">Detalla tu inconveniente o solicitud para que el equipo de soporte te asista a la brevedad.</p>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4 p-4">
    <div class="card-body">
        <form method="POST" action="{{ route('empresa.soporte.store') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label fw-bold">Asunto *</label>
                <input type="text" name="subject" class="form-control rounded-3" placeholder="Ej: No puedo imprimir la factura de un cliente" required autofocus>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Nivel de Prioridad *</label>
                <select name="priority" class="form-select rounded-3 p-2">
                    <option value="baja">Baja (Consultas generales)</option>
                    <option value="media" selected>Media (Inconveniente regular)</option>
                    <option value="alta">Alta (Crítico: El sistema o ventas paralizadas)</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Detalles del problema *</label>
                <textarea name="message" id="messageArea" class="form-control rounded-3" rows="8" 
                          data-upload-url="{{ route('empresa.soporte.uploadMedia') }}"
                          placeholder="Explica detalladamente... TIP: ¡Puedes PEGAR capturas (Ctrl+V) aquí!" required></textarea>
                <div class="form-text mt-2 text-muted">
                    <i class="bi bi-info-circle me-1"></i> Puedes copiar una imagen y pegarla directamente en el cuadro de texto.
                </div>
            </div>

            <div id="livePreview" class="border rounded-3 p-3 bg-light mb-4 shadow-sm" style="display:none; max-height: 400px; overflow-y: auto;">
                <label class="small fw-bold text-primary mb-2 d-block">VISTA PREVIA DE TU MENSAJE:</label>
                <div id="previewContent" class="text-secondary lh-lg"></div>
            </div>

            <div class="d-flex gap-3 pt-3">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                    Enviar Ticket
                </button>
                <a href="{{ route('empresa.soporte.index') }}" class="btn btn-light border rounded-pill px-4">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
function updatePreview() {
    const area = document.getElementById('messageArea');
    const preview = document.getElementById('livePreview');
    const content = document.getElementById('previewContent');
    const text = area.value;

    if (text.trim() === '') {
        preview.style.display = 'none';
        return;
    }

    preview.style.display = 'block';
    let rendered = text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
                       .replace(/\n/g, '<br>')
                       .replace(/!\[.*?\]\((.*?)\)/g, '<img src="$1" class="img-fluid rounded my-2 d-block shadow-sm" style="max-height:300px">');
    content.innerHTML = rendered;
}

document.getElementById('messageArea').addEventListener('input', updatePreview);

document.getElementById('messageArea').addEventListener('paste', function (e) {
    const items = (e.clipboardData || e.originalEvent.clipboardData).items;
    for (let index in items) {
        const item = items[index];
        if (item.kind === 'file' && item.type.includes('image')) {
            const blob = item.getAsFile();
            const textarea = e.target;
            
            // Placeholder
            const cursorPosition = textarea.selectionStart;
            const textBefore = textarea.value.substring(0, cursorPosition);
            const textAfter = textarea.value.substring(cursorPosition);
            const placeholder = "\n![Subiendo imagen...]()\n";
            textarea.value = textBefore + placeholder + textAfter;
            updatePreview();

            const formData = new FormData();
            formData.append('image', blob);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(textarea.dataset.uploadUrl, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.url) {
                    const finalImage = `\n![imagen](${data.url})\n`;
                    textarea.value = textarea.value.replace(placeholder, finalImage);
                    updatePreview();
                }
            })
            .catch(err => {
                textarea.value = textarea.value.replace(placeholder, "\n[Error al subir imagen]\n");
                updatePreview();
            });
        }
    }
});
</script>
@endpush
@endsection
