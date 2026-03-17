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
                        <div class="glass-panel p-4 shadow-sm" style="border-left: 5px solid 
                            @if($update->type == 'nuevo') #10b981 @elseif($update->type == 'mejora') #3b82f6 @elseif($update->type == 'arreglo') #f59e0b @else #6366f1 @endif">
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
                            <div class="update-description text-secondary mb-3">
                                {!! nl2br(e($update->description)) !!}
                            </div>
                            @if($update->link_tutorial)
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm btn-video" 
                                            onclick="openVideo(this, '{{ $update->link_tutorial }}')">
                                        <i class="me-2">▶</i> Ver Tutorial / Ayuda
                                    </button>
                                </div>
                                <div class="video-container-wrapper mt-3" style="display: none; opacity: 0; transform: scale(0.95); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                                    <div class="glass-video-panel p-2 rounded-4 shadow-lg position-relative overflow-hidden" 
                                         style="background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2);">
                                        <div class="ratio ratio-16x9">
                                            <iframe src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="rounded-3"></iframe>
                                        </div>
                                        <button class="btn btn-sm btn-dark position-absolute top-0 end-0 m-3 rounded-circle" 
                                                style="width: 32px; height: 32px; z-index: 10;"
                                                onclick="closeVideo(this)">✕</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
function openVideo(btn, url) {
    const wrapper = btn.closest('.glass-panel').querySelector('.video-container-wrapper');
    const iframe = wrapper.querySelector('iframe');
    
    // Si ya está abierto, lo cerramos
    if (wrapper.style.display === 'block') {
        closeVideo(wrapper.querySelector('button'));
        return;
    }

    // Set URL
    if (url.includes('youtube.com/watch?v=')) {
        url = url.replace('watch?v=', 'embed/');
    }
    iframe.src = url;

    // Show with animation
    wrapper.style.display = 'block';
    setTimeout(() => {
        wrapper.style.opacity = '1';
        wrapper.style.transform = 'scale(1)';
    }, 10);

    btn.innerHTML = '<i class="me-2">✕</i> Cerrar Video';
    btn.classList.replace('btn-primary', 'btn-dark');
}

function closeVideo(closeBtn) {
    const wrapper = closeBtn.closest('.video-container-wrapper');
    const panel = closeBtn.closest('.glass-panel');
    const btn = panel.querySelector('.btn-video');
    const iframe = wrapper.querySelector('iframe');

    wrapper.style.opacity = '0';
    wrapper.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        wrapper.style.display = 'none';
        iframe.src = '';
    }, 400);

    btn.innerHTML = '<i class="me-2">▶</i> Ver Tutorial / Ayuda';
    btn.classList.replace('btn-dark', 'btn-primary');
}
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
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
}
.publish-date {
    font-size: 1.1rem;
}
.border-start {
    border-color: #dee2e6 !important;
}
.glass-panel {
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}
.btn-video {
    transition: all 0.3s ease;
}
.btn-video:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
}
</style>
@endsection
