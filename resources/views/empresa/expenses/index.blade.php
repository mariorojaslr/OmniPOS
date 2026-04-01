@extends('layouts.empresa')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0">Gestión de Gastos Operativos</h4>
        <p class="text-muted mb-0">Controla los egresos del negocio que no son compras directas.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('empresa.gastos.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Registrar Gasto
        </a>
        <a href="{{ route('empresa.gastos_categorias.index') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">
            <i class="bi bi-tags me-2"></i> Categorías
        </a>
    </div>
</div>

<!-- Filtros y Resumen -->
<div class="row g-3 mb-4">
    <div class="col-md-9">
        <div class="card shadow-sm border-0 rounded-4 p-3 bg-white">
            <form action="{{ route('empresa.gastos.index') }}" method="GET" class="row g-2">
                <div class="col-md-4">
                    <select name="category_id" class="form-select border-0 bg-light rounded-3">
                        <option value="">Todas las Categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control border-0 bg-light rounded-3" placeholder="Desde">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control border-0 bg-light rounded-3" placeholder="Hasta">
                </div>
                <div class="col-md-3">
                    <input type="text" name="search_user" value="{{ request('search_user') }}" class="form-control border-0 bg-light rounded-3" placeholder="Buscar por empleado...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-3">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 rounded-4 p-3 bg-danger text-white">
            <small class="opacity-75 d-block text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.65rem;">TOTAL EGRESOS</small>
            <h3 class="fw-bold mb-0">$ {{ number_format($total, 2, ',', '.') }}</h3>
        </div>
    </div>
</div>

<!-- Listado -->
<div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Fecha</th>
                    <th>Categoría</th>
                    <th>Descripción / Comprobante</th>
                    <th>Monto</th>
                    <th>Responsable</th>
                    <th class="text-end pe-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $gasto)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold">{{ $gasto->date->format('d/m/Y') }}</div>
                        <small class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ $gasto->date->diffForHumans() }}</small>
                    </td>
                    <td>
                        <span class="badge rounded-pill" style="background-color: {{ $gasto->category->color ?? '#6c757d' }};">
                            {{ $gasto->category->nombre ?? 'S/C' }}
                        </span>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 300px;">
                            {{ Str::limit(strip_tags($gasto->description), 50) }}
                        </div>
                        @if($gasto->receipt_url)
                            <a href="#" class="btn-view-image text-primary small fw-bold" data-url="{{ $gasto->receipt_url }}">
                                <i class="bi bi-image me-1"></i> Ver Comprobante
                            </a>
                        @endif
                    </td>
                    <td>
                        <span class="text-danger fw-bold">$ {{ number_format($gasto->amount, 2, ',', '.') }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold me-2 shadow-sm" style="width: 32px; height: 32px; font-size: 0.8rem; border: 1px solid #eee;">
                                {{ substr($gasto->user->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold small">{{ $gasto->user->name ?? 'N/A' }}</div>
                                @if($gasto->asistencia_id)
                                    <span class="badge bg-soft-info text-info border border-info-subtle" style="font-size: 0.6rem;">REGISTRO DE CAMPO</span>
                                @else
                                    <span class="badge bg-soft-secondary text-secondary border border-secondary-subtle" style="font-size: 0.6rem;">MANUAL / DASHBOARD</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3">
                                <li><a class="dropdown-item" href="{{ route('empresa.gastos.edit', $gasto->id) }}"><i class="bi bi-pencil me-2 text-primary"></i> Editar</a></li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <form action="{{ route('empresa.gastos.destroy', $gasto->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro?')">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Eliminar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <img src="https://illustrations.popsy.co/gray/crying-lady.svg" alt="Vacío" style="height: 150px;" class="mb-3">
                        <h5 class="text-muted">No hay gastos registrados en este periodo</h5>
                        <p class="text-secondary small">Hacé clic en "Registrar Gasto" para empezar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $expenses->links() }}
    </div>
    @endif
</div>

<!-- Modal para ver imagen -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 shadow-sm" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 10;">
                <h5 class="modal-title fw-bold">Comprobante de Gasto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center bg-dark d-flex align-items-center justify-content-center" style="min-height: 400px;">
                <img src="" id="modalImage" class="img-fluid shadow-lg" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-view-image').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.dataset.url;
        document.getElementById('modalImage').src = url;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    });
});
</script>
@endpush

@endsection
