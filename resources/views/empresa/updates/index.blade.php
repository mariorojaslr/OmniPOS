@extends('layouts.empresa')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-6">Línea de Tiempo de Evolución</h2>
        <p class="text-muted">Descubra las últimas mejoras y nuevas funcionalidades implementadas en MultiPOS.</p>
    </div>

    <div class="timeline-wrapper">
        @foreach($updates as $update)
            <div class="timeline-item mb-5">
                <div class="row">
                    <div class="col-md-2 text-md-end mb-3 mb-md-0">
                        <div class="publish-date fw-bold text-primary">{{ $update->publish_date->format('d M, Y') }}</div>
                        <div class="small text-muted">{{ $update->publish_date->diffForHumans() }}</div>
                    </div>
                    <div class="col-md-10 border-start ps-4 position-relative">
                        <div class="timeline-dot"></div>
                        {{-- CARD CLICABLE --}}
                        <div class="glass-panel p-4 shadow-sm update-card" 
                             style="border-left: 5px solid 
                                @if($update->type == 'nuevo') #10b981 @elseif($update->type == 'mejora') #3b82f6 @elseif($update->type == 'arreglo') #f59e0b @else #6366f1 @endif; cursor: pointer;"
                             onclick='showUpdateDetail(@json($update))'>
                            
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h4 class="fw-bold mb-0">{{ $update->title }}</h4>
                                <span class="badge 
                                    @if($update->type == 'nuevo') bg-success 
                                    @elseif($update->type == 'mejora') bg-primary 
                                    @elseif($update->type == 'arreglo') bg-warning text-dark 
                                    @else bg-info @endif">
                                    {{ strtoupper($update->type) }}
                                </span>
                            </div>
                            <div class="update-description text-secondary mb-0">
                                {{ Str::limit($update->description, 180) }}
                                <div class="mt-2 small text-primary fw-bold">Haga clic para ver capturas y detalles →</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- MODAL DE DETALLE (PIZARRA BLANCA / LUXURY) --}}
<div class="modal fade" id="modalUpdateDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: rgba(255,255,255,0.98); backdrop-filter: blur(10px);">
            <div class="modal-header border-0 pb-0 pe-4 pt-4">
                <div class="ps-2">
                    <span id="modalUpdateBadge" class="badge mb-2"></span>
                    <h3 class="modal-title fw-bold" id="modalUpdateTitle"></h3>
                    <small class="text-muted" id="modalUpdateDate"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- DESCRIPCIÓN --}}
                <div id="modalUpdateDescription" class="text-secondary fs-5 mb-4 p-2" style="white-space: pre-wrap; line-height: 1.6;"></div>

                {{-- IMAGEN / CAPTURA --}}
                <div id="modalUpdateImageContainer" class="mb-4 text-center d-none">
                    <h6 class="text-start fw-bold mb-3 border-bottom pb-2">Captura de Pantalla</h6>
                    <img id="modalUpdateImage" src="" class="img-fluid rounded-4 shadow-sm border" style="max-height: 500px; width: 100%; object-fit: contain;">
                </div>

                {{-- VIDEO / TUTORIAL --}}
                <div id="modalUpdateVideoContainer" class="d-none">
                    <h5 class="fw-bold border-top pt-4 mb-3">Tutorial Explicativo</h5>
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm border">
                        <iframe id="modalUpdateVideo" src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar Detalle</button>
            </div>
        </div>
    </div>
</div>

<script>
function showUpdateDetail(update) {
    const modalElement = document.getElementById('modalUpdateDetail');
    const modal = new bootstrap.Modal(modalElement);
    
    // Rellenar Textos
    document.getElementById('modalUpdateTitle').innerText = update.title;
    document.getElementById('modalUpdateDescription').innerText = update.description;
    document.getElementById('modalUpdateDate').innerText = 'Publicado: ' + new Date(update.publish_date).toLocaleDateString();

    // Badge inteligente
    const badge = document.getElementById('modalUpdateBadge');
    badge.innerText = update.type.toUpperCase();
    badge.className = 'badge mb-2';
    if(update.type === 'nuevo') badge.classList.add('bg-success');
    else if(update.type === 'mejora') badge.classList.add('bg-primary');
    else if(update.type === 'arreglo') badge.classList.add('bg-warning', 'text-dark');
    else badge.classList.add('bg-info');

    // Manejo de Imagen
    const imgContainer = document.getElementById('modalUpdateImageContainer');
    const img = document.getElementById('modalUpdateImage');
    if(update.image) {
        img.src = '/storage/' + update.image;
        imgContainer.classList.remove('d-none');
    } else {
        imgContainer.classList.add('d-none');
    }

    // Manejo de Video
    const vidContainer = document.getElementById('modalUpdateVideoContainer');
    const vid = document.getElementById('modalUpdateVideo');
    if(update.link_tutorial) {
        let url = update.link_tutorial;
        if (url.includes('youtube.com/watch?v=')) {
            url = url.replace('watch?v=', 'embed/');
        }
        vid.src = url;
        vidContainer.classList.remove('d-none');
    } else {
        vid.src = '';
        vidContainer.classList.add('d-none');
    }

    modal.show();
}

// Limpiar video al cerrar para que no siga sonando
document.getElementById('modalUpdateDetail').addEventListener('hidden.bs.modal', function() {
    document.getElementById('modalUpdateVideo').src = '';
});
</script>

<style>
.timeline-wrapper {
    max-width: 900px;
    margin: 0 auto;
}
.timeline-dot {
    position: absolute;
    left: -7px;
    top: 5px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #3b82f6;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
.publish-date {
    font-size: 1.1rem;
}
.border-start {
    border-color: #dee2e6 !important;
}
.glass-panel {
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}
.glass-panel:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
    background: #ffffff;
}
</style>
@endsection
