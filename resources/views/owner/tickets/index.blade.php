@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0">Tickets de Soporte</h4>
        <p class="text-muted mb-0">Revisión y respuesta a los requerimientos de las empresas clientes.</p>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Ticket ID</th>
                        <th>Empresa / Usuario</th>
                        <th>Asunto</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong class="d-block text-dark">{{ $ticket->empresa->nombre_comercial ?? 'N/A' }}</strong>
                            <small class="text-muted">{{ $ticket->user->name ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <strong class="d-block text-truncate" style="max-width: 250px;">{{ $ticket->subject }}</strong>
                        </td>
                        <td>
                            @if($ticket->status == 'abierto')
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">Abierto</span>
                            @elseif($ticket->status == 'en_proceso')
                                <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill">En Proceso</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">Cerrado</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->priority == 'critica')
                                <span class="badge bg-black text-white border border-danger border-opacity-75 px-3 py-2 shadow-sm animate-pulse-red" style="font-size: 0.7rem; letter-spacing: 1px;">🚨 CRÍTICA</span>
                            @elseif($ticket->priority == 'alta')
                                <span class="badge bg-danger">Alta</span>
                            @elseif($ticket->priority == 'media')
                                <span class="badge bg-warning text-dark">Media</span>
                            @else
                                <span class="badge bg-info">Baja</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('owner.soporte.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Responder
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No hay tickets de soporte creados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('styles')
<style>
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); border-color: rgba(220, 38, 38, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); border-color: rgba(220, 38, 38, 1); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); border-color: rgba(220, 38, 38, 0.4); }
    }
    .animate-pulse-red {
        animation: pulse-red 2s infinite;
    }
</style>
@endsection
@endsection
