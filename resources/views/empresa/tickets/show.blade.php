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
                <p class="text-secondary mt-3 lh-lg" style="white-space: pre-wrap;">{{ $ticket->message }}</p>

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

        @if($ticket->respuesta_owner)
        <div class="card shadow-sm border-0 rounded-4 border-start border-4 border-success bg-white h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-success mb-3">
                    <i class="me-2 text-success">👤</i> 
                    Respuesta del Equipo de Soporte Central
                </h6>
                <p class="mb-0 text-secondary lh-lg" style="white-space: pre-wrap;">{{ $ticket->respuesta_owner }}</p>
                <div class="mt-4 pt-4 border-top border-light text-end">
                    <small class="text-muted">Actualizado: {{ $ticket->updated_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
        @else
        <div class="card shadow-sm border-0 rounded-4 bg-light text-center p-5 h-100 d-flex flex-column align-items-center justify-content-center">
            <h1 class="text-muted opacity-25 mb-4" style="font-size: 5rem;">⏳</h1>
            <h5 class="fw-bold text-secondary mb-2">Ticket en Revisión</h5>
            <p class="text-muted mb-0">Nuestro equipo está analizando tu solicitud. Recibirás una respuesta aquí pronto.</p>
        </div>
        @endif

    </div>
</div>
@endsection
