@extends('layouts.app')

@section('content')
<style>
    /* 🛡️ BLINDAJE ANTI-ICONOS GIGANTES (V7.0) */
    nav svg {
        max-width: 20px !important;
        max-height: 20px !important;
        display: inline-block !important;
        vertical-align: middle;
    }
    
    .pagination-wrapper .relative.inline-flex {
        display: none !important; /* Oculta el texto duplicado de Laravel si aparece */
    }

    /* Estetica Owner Premium */
    .card-owner {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Gestión de Novedades (Log)</h2>
            <small class="text-muted">Control omnisciente del feed de actualizaciones</small>
        </div>
        <a href="{{ route('owner.updates.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">+ Nueva Actualización</a>
    </div>

    <div class="card card-owner shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                            <th class="ps-4">Fecha</th>
                            <th>Título</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($updates as $u)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $u->publish_date->format('d/m/Y') }}</td>
                            <td class="fw-bold text-dark">{{ $u->title }}</td>
                            <td class="text-center">
                                @php
                                    $badge = match($u->type) {
                                        'nuevo' => 'bg-success',
                                        'mejora' => 'bg-info',
                                        'arreglo' => 'bg-warning text-dark',
                                        'tarea' => 'bg-primary',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badge }}" style="font-size: 0.65rem;">{{ strtoupper($u->type) }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('owner.updates.edit', $u) }}" class="btn btn-sm btn-outline-secondary py-1 px-3 rounded-pill" style="font-size: 0.75rem;">Editar</a>
                                <form action="{{ route('owner.updates.destroy', $u) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger py-1 px-2 rounded-circle" onclick="return confirm('¿Eliminar?')">
                                        <i class="bi bi-x-lg" style="font-size: 0.8rem;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted fw-bold">No hay novedades registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- FORZAR PAGINACIÓN BOOTSTRAP 5 --}}
        <div class="p-3 bg-light border-top d-flex justify-content-center pagination-wrapper">
            {{ $updates->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
