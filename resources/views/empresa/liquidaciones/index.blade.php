@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Liquidaciones a Profesionales</h1>
            <p class="text-muted">Historial de cierres administrativos y pagos.</p>
        </div>
        <a href="{{ route('empresa.liquidaciones.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Nueva Liquidación
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="py-3">Profesional</th>
                            <th class="py-3 text-center">Periodo</th>
                            <th class="py-3 text-end">Turnos</th>
                            <th class="py-3 text-end">Total Pagado</th>
                            <th class="py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($liquidaciones as $liq)
                        <tr>
                            <td class="px-4">
                                <span class="fw-bold text-dark">#{{ $liq->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $liq->profesional->name }}</span>
                                        <small class="text-muted">{{ $liq->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border p-2">
                                    {{ \Carbon\Carbon::parse($liq->periodo_desde)->format('d/m') }} - {{ \Carbon\Carbon::parse($liq->periodo_hasta)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">
                                {{ $liq->turnos->count() }}
                            </td>
                            <td class="text-end">
                                <span class="text-success fw-bold">${{ number_format($liq->monto_total, 2, ',', '.') }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 border border-success-subtle">
                                    <i class="fas fa-check-circle me-1"></i>{{ ucfirst($liq->estado) }}
                                </span>
                            </td>
                            <td class="px-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('empresa.liquidaciones.show', $liq->id) }}" class="btn btn-sm btn-light border shadow-sm rounded-3 text-primary p-2" title="Ver Detalle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-eye fs-6"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light border shadow-sm rounded-3 text-danger p-2" 
                                            onclick="confirmDelete('{{ route('empresa.liquidaciones.destroy', $liq->id) }}')" 
                                            title="Anular Liquidación" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-trash-alt fs-6"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-receipt fa-4x text-gray-300 mb-3"></i>
                                    <p class="text-muted fs-5">No hay liquidaciones registradas aún.</p>
                                    <a href="{{ route('empresa.liquidaciones.create') }}" class="btn btn-primary rounded-pill px-4">
                                        Comenzar primer cierre
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($liquidaciones->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $liquidaciones->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de Confirmación de Borrado -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white rounded-top">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Anular Liquidación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-undo-alt fa-3x text-danger mb-3"></i>
                <h5>¿Estás seguro de anular esta liquidación?</h5>
                <p class="text-muted">Esta acción liberará todos los turnos asociados para que puedan ser liquidados nuevamente. Los datos financieros actuales se eliminarán.</p>
            </div>
            <div class="modal-footer bg-light rounded-bottom">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4">Confirmar Anulación</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function confirmDelete(url) {
        document.getElementById('deleteForm').action = url;
        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }
</script>
@endsection
