<div class="turno-card mb-3 p-3 border rounded-4 bg-white shadow-sm">
    <div class="d-flex align-items-center gap-3">
        <div class="time-badge bg-primary bg-opacity-10 text-primary fw-bold p-2 rounded-3 text-center" style="min-width: 65px;">
            {{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }}
        </div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <h6 class="fw-bold mb-0 text-dark">
                    {{ $turno->cliente ? $turno->cliente->name : ($turno->cliente_nombre_manual ?? 'Cliente Manual') }}
                </h6>
                <span class="badge rounded-pill x-small bg-{{ $turno->estado == 'finalizado' ? 'success' : ($turno->estado == 'cancelado' ? 'danger' : 'warning') }} bg-opacity-10 text-{{ $turno->estado == 'finalizado' ? 'success' : ($turno->estado == 'cancelado' ? 'danger' : 'warning') }}">
                    {{ strtoupper($turno->estado) }}
                </span>
            </div>
            <div class="text-muted mb-2" style="font-size: 0.8rem;">
                <i class="bi bi-scissors me-1"></i> {{ $turno->servicio ? $turno->servicio->nombre : 'Servicio Gral.' }}
            </div>
            
            <div class="d-flex gap-2">
                @if($turno->cliente && $turno->cliente->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $turno->cliente->phone) }}" class="btn btn-sm btn-outline-success flex-grow-1 fw-bold py-2">
                        <i class="bi bi-whatsapp"></i> WHATSAPP
                    </a>
                @endif

                @if($turno->estado == 'pendiente')
                    <button onclick="finalizarTurno({{ $turno->id }})" class="btn btn-sm btn-primary flex-grow-1 fw-bold py-2">
                        <i class="bi bi-check-lg"></i> FINALIZAR
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
