@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Gestión de Novedades (Log)</h2>
        <a href="{{ route('owner.updates.create') }}" class="btn btn-primary">+ Nueva Actualización</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($updates as $u)
                    <tr>
                        <td>{{ $u->publish_date->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ $u->title }}</td>
                        <td>
                            @php
                                $badge = match($u->type) {
                                    'nuevo' => 'bg-success',
                                    'mejora' => 'bg-info',
                                    'arreglo' => 'bg-warning text-dark',
                                    'tarea' => 'bg-primary',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ strtoupper($u->type) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('owner.updates.edit', $u) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                            <form action="{{ route('owner.updates.destroy', $u) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar?')">X</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $updates->links() }}
        </div>
    </div>
</div>
@endsection
