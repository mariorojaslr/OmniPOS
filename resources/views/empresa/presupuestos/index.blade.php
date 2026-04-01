@extends('layouts.empresa')

@section('styles')
<style>
/* ESTILOS COHERENTES CON CONTROL DE STOCK */
.card-indicator {
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 0.75rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.stat-label { 
    font-size: 0.6rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    font-weight: 800; 
    color: #6b7280; 
    margin-bottom: 0.2rem; 
}
.stat-value { 
    font-size: 1.5rem; 
    font-weight: 800; 
    line-height: 1; 
    color: #111827;
}
</style>

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
    
    {{-- HEADER COHERENTE (Igual a Stock) --}}
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <div>
            <h1 class="fw-bold mb-0" style="font-size: 1.6rem; letter-spacing: -0.5px; color: #111827;">
                Gestión de Presupuestos
            </h1>
            <p class="text-muted small mb-0">Seguimiento comercial en tiempo real</p>
        </div>
        <div>
            <a href="{{ route('empresa.presupuestos.create') }}" class="btn btn-success btn-sm px-3 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-1"></i> NUEVA COTIZACIÓN
            </a>
            <a href="#" class="btn btn-light btn-sm px-3 ms-1 border shadow-sm text-dark">
                <i class="bi bi-file-earmark-pdf me-1"></i> REPORTES
            </a>
        </div>
    </div>

    {{-- INDICADORES COMPACTOS (Igual a Stock) --}}
    <div class="row g-2 mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-2 rounded-2 text-center h-100 border-top border-secondary border-3">
                <div style="font-size: 0.6rem; text-transform: uppercase; font-weight: 800; color: #6b7280;">TOTAL EMITIDOS</div>
                <div class="fw-bold text-dark" style="font-size: 1.4rem;">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-2 rounded-2 text-center h-100 border-top border-warning border-3">
                <div style="font-size: 0.6rem; text-transform: uppercase; font-weight: 800; color: #f59e0b;">PENDIENTES</div>
                <div class="fw-bold text-dark" style="font-size: 1.4rem;">{{ $stats['pendientes'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-2 rounded-2 text-center h-100 border-top border-success border-3">
                <div style="font-size: 0.6rem; text-transform: uppercase; font-weight: 800; color: #22c55e;">ACEPTADOS</div>
                <div class="fw-bold text-dark" style="font-size: 1.4rem;">{{ $stats['aceptados'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-2 rounded-2 text-center h-100 border-top border-danger border-3">
                <div style="font-size: 0.6rem; text-transform: uppercase; font-weight: 800; color: #ef4444;">VENCIDOS</div>
                <div class="fw-bold text-dark" style="font-size: 1.4rem;">{{ $stats['vencidos'] }}</div>
            </div>
        </div>
    </div>

    {{-- TABLA UNIFICADA --}}
    <div class="glass-panel p-0 overflow-hidden shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small text-muted" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Ref #</th>
                        <th class="py-3 text-uppercase small text-muted" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Cliente / Prospecto</th>
                        <th class="py-3 text-uppercase small text-muted" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Emisión</th>
                        <th class="py-3 text-uppercase small text-muted" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Vencimiento</th>
                        <th class="py-3 text-uppercase small text-muted text-end" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Total</th>
                        <th class="py-3 text-uppercase small text-muted text-center" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Estado</th>
                        <th class="py-3 text-uppercase small text-muted text-end pe-4" style="letter-spacing: 1px; font-weight: 700; font-size: 0.65rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($presupuestos as $presu)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-light text-dark shadow-sm border">#{{ $presu->numero }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $presu->client->name ?? 'Cliente Ocasional' }}</div>
                            <div class="small text-muted">{{ $presu->client->email ?? '-' }}</div>
                        </td>
                        <td>{{ $presu->fecha ? $presu->fecha->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($presu->vencimiento && $presu->vencimiento < now() && $presu->estado == 'pendiente')
                                <span class="text-danger fw-bold"><i class="bi bi-clock-history me-1"></i> {{ $presu->vencimiento->format('d/m/Y') }}</span>
                            @else
                                {{ $presu->vencimiento ? $presu->vencimiento->format('d/m/Y') : '-' }}
                            @endif
                        </td>
                        <td class="text-end fw-bold fs-5" style="color: var(--color-primario);">$ {{ number_format($presu->total, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $badgeClass = match($presu->estado) {
                                    'pendiente' => 'bg-warning text-dark',
                                    'aceptado' => 'bg-success',
                                    'rechazado' => 'bg-danger',
                                    'convertido' => 'bg-info text-dark',
                                    'vencido' => 'bg-secondary',
                                    default => 'bg-dark'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-3" style="font-size: 0.65rem;">{{ strtoupper($presu->estado) }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-circle shadow-sm" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2 text-primary"></i> Ver Detalle</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i> Imprimir PDF</a></li>
                                    @if($presu->estado == 'pendiente')
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-success" href="#"><i class="bi bi-check-lg me-2"></i> Aceptar</a></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-x-lg me-2"></i> Rechazar</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="opacity-40">
                                <i class="bi bi-file-earmark-text fs-1 mb-3 d-block text-muted"></i>
                                <h5 class="fw-bold text-muted">No hay presupuestos generados</h5>
                                <p class="small mb-0 text-muted">Comience creando su primera cotización profesional.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

