@extends('layouts.empresa')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0">Centro de Soporte</h4>
        <p class="text-muted mb-0">Reporte de incidencias y solicitudes de ayuda.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('empresa.soporte.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            + Nuevo Ticket
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Ticket</th>
                        <th>Asunto</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Fecha Creación</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="ps-4 fw-bold text-secondary">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong class="d-block text-dark">{{ $ticket->subject }}</strong>
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
                            @if($ticket->priority == 'alta')
                                <span class="badge bg-danger">Alta</span>
                            @elseif($ticket->priority == 'media')
                                <span class="badge bg-warning text-dark">Media</span>
                            @else
                                <span class="badge bg-info">Baja</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('empresa.soporte.show', $ticket->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <h5 class="fw-bold text-secondary mb-2">Sin tickets registrados</h5>
                            <p class="mb-0">No has enviado ninguna solicitud de soporte aún.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
