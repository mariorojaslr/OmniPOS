@extends('layouts.empresa')

@section('styles')
<style>
/* CLASES COHERENTES CON DASHBOARD EMPRESA */
.empresa-bg {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: -1;
    background: radial-gradient(circle at 10% 20%, var(--color-primario)10, transparent 40%),
                radial-gradient(circle at 90% 80%, var(--color-secundario)10, transparent 40%);
    animation: bgPulse 15s infinite alternate ease-in-out;
}
@keyframes bgPulse { 0% { transform: scale(1); } 100% { transform: scale(1.05); } }

.glass-panel {
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}
.stat-label { 
    font-size: 0.65rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    font-weight: 700; 
    color: #6b7280; 
    margin-bottom: 0.25rem; 
}
.stat-value { 
    font-size: 1.6rem; 
    font-weight: 800; 
    line-height: 1; 
    color: #111827;
}

.table-glass { border-radius: 20px; overflow: hidden; border: 1px solid rgba(128, 128, 128, 0.1); }
.table-glass.table thead th { 
    background: rgba(var(--color-primario-rgb), 0.08); 
    border-bottom: 2px solid rgba(var(--color-primario-rgb), 0.1); 
    color: #6c757d; font-size: 0.7rem; letter-spacing: 1px; font-weight: 700;
}
</style>
@endsection

@section('content')
<div class="empresa-bg"></div>

<div class="container-fluid px-4 pb-5">

    <div class="row align-items-center mb-5 mt-4">
        <div class="col">
            <h5 class="stat-label mb-1" style="color: var(--color-primario);">Administración Comercial</h5>
            <h1 class="fw-bold mb-0" style="font-size: 2.8rem; letter-spacing: -1.5px;">
                Gastos <span style="color: var(--color-primario);">Operativos</span>
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('empresa.gastos.create') }}" class="btn btn-primary rounded-pill px-4 py-3 fw-bold shadow-lg">
                <i class="bi bi-plus-lg me-2"></i> REGISTRAR GASTO
            </a>
            <a href="{{ route('empresa.gastos_categorias.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-3 ms-2 shadow-sm">
                <i class="bi bi-tags me-2"></i> CATEGORÍAS
            </a>
        </div>
    </div>
    
    <!-- Filtros y Resumen Estilo Premium -->
    <div class="row g-4 mb-5">
        <div class="col-md-9">
            <div class="glass-panel p-3">
                <form action="{{ route('empresa.gastos.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <select name="category_id" class="form-select border-0 bg-light rounded-3">
                            <option value="">Todas las Categorías</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control border-0 bg-light rounded-3">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control border-0 bg-light rounded-3">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search_user" value="{{ request('search_user') }}" class="form-control border-0 bg-light rounded-3" placeholder="Empleado...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark w-100 rounded-3">FILTRAR</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel text-center border-bottom border-danger border-4 shadow-lg bg-danger bg-opacity-10">
                <div class="stat-label text-danger">TOTAL EGRESOS</div>
                <div class="stat-value text-danger">$ {{ number_format($total, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Listado en Panel de Vidrio -->
    <div class="glass-panel p-0 overflow-hidden shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small text-muted" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Fecha</th>
                        <th class="py-3 text-uppercase small text-muted" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Categoría</th>
                        <th class="py-3 text-uppercase small text-muted" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Descripción / Comprobante</th>
                        <th class="py-3 text-uppercase small text-muted text-end" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Monto</th>
                        <th class="py-3 text-uppercase small text-muted" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Responsable</th>
                        <th class="py-3 text-uppercase small text-muted text-end pe-4" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Acciones</th>
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
                            <a href="#" class="btn-view-image text-primary small fw-bold" data-url="{{ route('local.media', ['path' => $gasto->receipt_url]) }}">
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
