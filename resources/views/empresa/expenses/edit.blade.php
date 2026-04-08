@extends('layouts.empresa')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0">Editar Registro de Gasto</h4>
        <p class="text-muted mb-0">Corregí montos, categorías o actualizá el comprobante.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('empresa.gastos.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Volver al Listado
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('empresa.gastos.update', $gasto->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha del Gasto *</label>
                            <input type="date" name="date" class="form-control rounded-3 p-2 bg-light border-0" value="{{ $gasto->date->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Categorización *</label>
                            <select name="category_id" class="form-select rounded-3 p-2 bg-light border-0" required>
                                <option value="">-- Seleccionar Categoría --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $gasto->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Monto ($) *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-3 fw-bold">$</span>
                            <input type="number" step="0.01" name="amount" class="form-control rounded-end-3 p-2 bg-light border-0" value="{{ $gasto->amount }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Descripción / Notas / Foto *</label>
                        <textarea name="description" id="expenseArea" 
                                  data-upload-url="{{ route('empresa.gastos.uploadMedia') }}"
                                  class="form-control rounded-4 p-3 bg-light border-0" rows="8" 
                                  placeholder="Explica en qué se gastó... TIP: ¡Pega aquí la foto del ticket (Ctrl+V)!" required>{{ $gasto->description }}</textarea>
                        
                        <div id="livePreview" class="mt-3 p-3 border rounded-4 bg-white shadow-sm" style="{{ $gasto->description ? '' : 'display:none;' }} max-height: 400px; overflow-y: auto;">
                            <label class="small fw-bold text-primary mb-2 d-block text-uppercase">Vista Previa:</label>
                            <div id="previewContent" class="text-secondary small overflow-hidden"></div>
                        </div>
                        
                        <div class="form-text mt-2 text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Describí el gasto. Si pegás una imagen, se guardará como comprobante visual.
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                            Actualizar Registro de Gasto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-sm border-0 rounded-4 bg-primary text-white p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i>Edición Segura</h5>
            <div class="small lh-lg">
                <p class="mb-3">Los cambios que realices aquí impactarán inmediatamente en tus reportes financieros y cierres de caja.</p>
                <p class="mb-3">Si cambias la categoría, el gasto se reagrupará automáticamente en el gráfico de torta de la próxima auditoría.</p>
                <hr class="bg-white opacity-25">
                <p class="fst-italic opacity-75">MultiPOS mantiene la integridad de tus datos contables en tiempo real.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updatePreview() {
    const area = document.getElementById('expenseArea');
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

// Inicializar vista previa
document.addEventListener('DOMContentLoaded', updatePreview);

document.getElementById('expenseArea').addEventListener('input', updatePreview);

document.getElementById('expenseArea').addEventListener('paste', function (e) {
    const items = (e.clipboardData || e.originalEvent.clipboardData).items;
    for (let index in items) {
        const item = items[index];
        if (item.kind === 'file' && item.type.includes('image')) {
            const blob = item.getAsFile();
            const textarea = e.target;
            
            const cursorPosition = textarea.selectionStart;
            const textBefore = textarea.value.substring(0, cursorPosition);
            const textAfter = textarea.value.substring(cursorPosition);
            const placeholder = "\n![Subiendo comprobante...]()\n";
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
                    const finalImage = `\n![comprobante](${data.url})\n`;
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
