@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="row align-items-center mb-4">
        <div class="col">
            <h4 class="fw-bold mb-0 text-dark">Ticket #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</h4>
            <small class="text-muted">Resolución de Incidentes</small>
        </div>
        <div class="col-auto">
            <a href="{{ route('owner.soporte.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                Volver
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge {{ $ticket->priority == 'alta' ? 'bg-danger' : ($ticket->priority == 'media' ? 'bg-warning text-dark' : 'bg-info') }}">
                            Prioridad {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>

                    <h5 class="fw-bold text-dark">{{ $ticket->subject }}</h5>
                    <p class="text-secondary mt-3 lh-lg" style="white-space: pre-wrap;">{{ $ticket->message }}</p>

                    <hr class="my-4 text-muted">

                    <div class="d-flex align-items-center mb-0">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-5 me-3" style="width: 45px; height: 45px;">
                            {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <strong class="d-block text-dark">{{ $ticket->user->name ?? 'Usuario' }}</strong>
                            <small class="text-muted">{{ $ticket->empresa->nombre_comercial ?? 'Empresa N/A' }} | {{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            @if($ticket->respuesta_owner)
            <div class="card shadow-sm border-0 rounded-4 border-start border-4 border-success bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-success mb-3"><i class="bi bi-headset me-2"></i>Respuesta de Soporte Técnico</h6>
                    <p class="mb-0 text-secondary lh-lg" style="white-space: pre-wrap;">{{ $ticket->respuesta_owner }}</p>
                </div>
            </div>
            @endif

        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white p-4 border-0 pb-0">
                    <h5 class="fw-bold text-dark mb-0">Gestionar Ticket</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('owner.soporte.update', $ticket->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Estado del Ticket</label>
                            <select name="status" class="form-select rounded-3 p-2 bg-light">
                                <option value="abierto" {{ $ticket->status == 'abierto' ? 'selected' : '' }}>🔴 Abierto (Pendiente)</option>
                                <option value="en_proceso" {{ $ticket->status == 'en_proceso' ? 'selected' : '' }}>🟡 En Proceso (Analizando)</option>
                                <option value="cerrado" {{ $ticket->status == 'cerrado' ? 'selected' : '' }}>🟢 Cerrado (Resuelto)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Respuesta Oficial (Soporte)</label>
                            <textarea name="respuesta_owner" class="form-control rounded-3 bg-light" rows="6" placeholder="Escribe aquí la respuesta oficial que verá el cliente..." required>{{ old('respuesta_owner', $ticket->respuesta_owner) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                            Guardar Cambios y Responder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
