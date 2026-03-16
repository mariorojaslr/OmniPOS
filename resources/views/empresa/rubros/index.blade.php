@extends('layouts.empresa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Rubros</h2>
        <p class="text-muted">Gestiona las categorías de tus productos</p>
    </div>
    <a href="{{ route('empresa.rubros.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Rubro
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nombre</th>
                        <th>Estado</th>
                        <th>Productos</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rubros as $rubro)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $rubro->nombre }}</td>
                            <td>
                                @if($rubro->activo)
                                    <span class="badge bg-success-soft text-success px-3">Activo</span>
                                @else
                                    <span class="badge bg-danger-soft text-danger px-3">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                {{ $rubro->products()->count() }} productos
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('empresa.rubros.edit', $rubro) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('empresa.rubros.destroy', $rubro) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este rubro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                No se encontraron rubros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
