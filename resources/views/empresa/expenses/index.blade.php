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
<div class="container-fluid px-2 pb-4">

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <div>
            <h1 class="fw-bold mb-0" style="font-size: 1.6rem; letter-spacing: -0.5px; color: #111827;">
                Gastos Operativos
            </h1>
            <p class="text-muted small mb-0">Control de egresos en tiempo real</p>
        </div>
        <div>
            <a href="{{ route('empresa.gastos.create') }}" class="btn btn-success btn-sm px-3 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-1"></i> REGISTRAR GASTO
            </a>
            <a href="{{ route('empresa.gastos_categorias.index') }}" class="btn btn-light btn-sm px-3 ms-1 border shadow-sm text-dark">
                <i class="bi bi-tags me-1"></i> CATEGORÍAS
            </a>
        </div>
    </div>
    
    <!-- Indicadores Estilo Stock (Compactos) -->
    <div class="row g-2 mb-3">
        <div class="col-md-9">
            <div class="card border-0 shadow-sm p-2 rounded-2 h-100">
                <form action="{{ route('empresa.gastos.index') }}" method="GET" class="row g-1 align-items-center">
                    <div class="col-md-3">
                        <select name="category_id" class="form-select form-select-sm border-0 bg-light">
                            <option value="">Todas las Categorías</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm border-0 bg-light">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm border-0 bg-light">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search_user" value="{{ request('search_user') }}" class="form-control form-control-sm border-0 bg-light" placeholder="Empleado...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark btn-sm w-100">FILTRAR</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-2 rounded-2 h-100 border-top border-danger border-3 text-center">
                <div style="font-size: 0.6rem; text-transform: uppercase; font-weight: 800; color: #ef4444;">TOTAL EGRESOS</div>
                <div class="fw-bold text-dark" style="font-size: 1.4rem;">$ {{ number_format($total, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Listado Compacto -->
    <div class="card border-0 shadow-sm overflow-hidden rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="ps-3 py-2 text-uppercase text-muted" style="letter-spacing: 0.5px; font-weight: 700; font-size: 0.65rem;">Fecha / Hora</th>
                        <th class="py-2 text-uppercase text-muted" style="letter-spacing: 0.5px; font-weight: 700; font-size: 0.65rem;">Categoría</th>
                        <th class="py-2 text-uppercase text-muted" style="letter-spacing: 0.5px; font-weight: 700; font-size: 0.65rem;">Descripción / Comprobante</th>
                        <th class="py-2 text-uppercase text-muted text-end" style="letter-spacing: 0.5px; font-weight: 700; font-size: 0.65rem;">Monto</th>
                        <th class="py-2 text-uppercase text-muted" style="letter-spacing: 0.5px; font-weight: 700; font-size: 0.65rem;">Responsable</th>
                        <th class="py-2 text-uppercase text-muted text-end pe-3" style="letter-spacing: 0.5px; font-weight: 700; font-size: 0.65rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($expenses as $gasto)
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold">{{ $gasto->date ? $gasto->date->format('d/m/Y') : $gasto->created_at->format('d/m/Y') }}</div>
                        <div class="small opacity-50">{{ $gasto->created_at->format('H:i') }} hs</div>
                    </td>
                    <td>
                        <span class="badge rounded-pill px-2 py-1" style="background: rgba(var(--color-primario-rgb), 0.1); color: var(--color-primario); font-size: 0.65rem;">
                            {{ $gasto->category->nombre ?? 'General' }}
                        </span>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 250px;">{{ $gasto->description }}</div>
                        @if($gasto->receipt_url)
                            @php
                                $finalUrl = str_starts_with($gasto->receipt_url, 'http') 
                                            ? $gasto->receipt_url 
                                            : route('local.media', ['path' => $gasto->receipt_url]);
                            @endphp
                            <a href="{{ $finalUrl }}" target="_blank" class="small text-primary text-decoration-none">
                                <i class="bi bi-image me-1"></i> Ver Comprobante
                            </a>
                        @endif
                    </td>
                    <td class="text-end fw-bold text-danger">
                        $ {{ number_format($gasto->amount, 2, ',', '.') }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold me-2" style="width: 24px; height: 24px; font-size: 0.6rem; border: 1px solid rgba(var(--color-primario-rgb), 0.2);">
                                {{ strtoupper(substr($gasto->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size: 0.75rem;">{{ $gasto->user->name ?? 'Sistema' }}</div>
                                <div class="small text-muted" style="font-size: 0.6rem; text-transform: uppercase;">{{ $gasto->user->role ?? 'Usuario' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-end pe-3">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm" data-bs-toggle="dropdown" style="width: 28px; height: 28px; padding: 0;">
                                <i class="bi bi-three-dots-vertical" style="font-size: 0.75rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="font-size: 0.8rem;">
                                <li><a class="dropdown-item" href="{{ route('empresa.gastos.edit', $gasto->id) }}"><i class="bi bi-pencil me-2 text-primary"></i> Editar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('empresa.gastos.destroy', $gasto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este gasto? No se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i> Eliminar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No se encontraron gastos con estos criterios.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
