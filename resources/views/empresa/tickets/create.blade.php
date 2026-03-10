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
                <textarea name="message" class="form-control rounded-3" rows="6" placeholder="Explica detalladamente qué estabas haciendo y qué error apareció..." required></textarea>
                <div class="form-text mt-2 text-muted">
                    Cuanta más información nos proveas, más rápido podremos ayudarte.
                </div>
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
@endsection
