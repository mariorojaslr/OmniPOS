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
                                <a href="{{ $update->link_tutorial }}" target="_blank" class="btn btn-sm btn-outline-primary">Ver Tutorial / Ayuda</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

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
    background: var(--mp-primary);
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
}
.publish-date {
    font-size: 1.1rem;
}
.border-start {
    border-color: #dee2e6 !important;
}
</style>
@endsection
