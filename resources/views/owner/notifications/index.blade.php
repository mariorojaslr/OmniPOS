@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-white fw-bold">Centro de Comunicaciones SaaS</h2>
            <p class="text-secondary text-uppercase small fw-bold">Historial de notificaciones enviadas a empresas</p>
        </div>
    </div>

    <div class="card bg-dark border-secondary shadow-lg">
        <div class="card-header bg-transparent border-secondary py-3">
            <h5 class="text-white mb-0"><i class="fas fa-history me-2"></i> Registro de Avisos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 border-secondary">
                    <thead class="text-secondary small">
                        <tr>
                            <th class="ps-4">Fecha/Hora</th>
                            <th>Empresa</th>
                            <th>Tipo</th>
                            <th>Mensaje</th>
                            <th>Canal</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notif)
                        <tr>
                            <td class="ps-4">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold text-white">{{ $notif->empresa->name }}</td>
                            <td>
                                @if($notif->type === 'vencimiento')
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger">VENCIMIENTO</span>
                                @elseif($notif->type === 'aviso_general')
                                    <span class="badge bg-info bg-opacity-25 text-info border border-info">AVISO GENERAL</span>
                                @else
                                    <span class="badge bg-secondary">{{ strtoupper($notif->type) }}</span>
                                @endif
                            </td>
                            <td class="small text-secondary" style="max-width: 300px;">
                                {{ Str::limit($notif->message, 100) }}
                            </td>
                            <td>
                                <i class="fas fa-desktop me-1"></i> Dashboard
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary" title="Reenviar">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="fas fa-paper-plane fa-3x mb-3 d-block opacity-25"></i>
                                No se han enviado notificaciones recientemente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-secondary py-3">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
