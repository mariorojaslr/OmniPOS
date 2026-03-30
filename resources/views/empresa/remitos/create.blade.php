@extends('layouts.empresa')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0 fw-bold">Generar Entrega (Remito)</h2>
        <p class="text-muted small">Registrando salida física para la Venta #{{ $venta->id }}</p>
    </div>
    <a href="{{ route('empresa.ventas.show', $venta->id) }}" class="btn btn-outline-secondary">
        Cancelar
    </a>
</div>

<form action="{{ route('empresa.ventas.entregar.store', $venta->id) }}" method="POST">
    @csrf

    <div class="row g-4">
        
        {{-- PANEL IZQUIERDO: SELECCIÓN DE PRODUCTOS --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 border-top border-primary border-4">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold">📦 Productos Pendientes de Entrega</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Producto / Variante</th>
                                    <th class="text-center" width="120">En Guarda</th>
                                    <th class="text-center" width="200">Entregar Ahora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemsPendientes as $item)
                                <tr>
                                    <td class="ps-3 py-3">
                                        <div class="fw-bold">{{ $item->product->name }}</div>
                                        @if($item->variant)
                                            <small class="text-muted">{{ $item->variant->size }} / {{ $item->variant->color }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark fs-6">{{ number_format($item->cantidad_pendiente, 2) }}</span>
                                    </td>
                                    <td class="text-center pe-3">
                                        <div class="input-group">
                                            <input type="number" 
                                                   name="items[{{ $item->id }}][cantidad]" 
                                                   class="form-control fw-bold text-center border-primary" 
                                                   step="0.01" 
                                                   min="0" 
                                                   max="{{ $item->cantidad_pendiente }}" 
                                                   value="{{ $item->cantidad_pendiente }}"
                                                   onfocus="this.select()">
                                            <span class="input-group-text bg-primary text-white">UNID</span>
                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL DERECHO: DATOS DEL REMITO --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3 fw-bold border-bottom">
                    📝 Datos del Documento
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NÚMERO DE REMITO (Opcional)</label>
                        <input type="text" name="numero_remito" class="form-control" placeholder="Ej: 0001-00004562">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">QUIÉN RETIRA / OBSERVACIONES</label>
                        <textarea name="observaciones" class="form-control" rows="4" placeholder="Ej: Retira el chofer de Juan Pérez en camión propio..."></textarea>
                    </div>

                    <hr class="my-4 opacity-10">

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow">
                        🚚 Confirmar Entrega y Crear Remito
                    </button>
                    
                    <p class="text-muted small text-center mt-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Esta acción descontará el saldo en guarda de la venta y generará un historial de retiro.
                    </p>
                </div>
            </div>
        </div>

    </div>
</form>

@endsection
