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
    
    {{-- HEADER UNIFICADO --}}
    <div class="row align-items-center mb-5 mt-4">
        <div class="col">
            <h5 class="stat-label mb-1" style="color: var(--color-primario);">Módulo Comercial Elite</h5>
            <h1 class="fw-bold mb-0" style="font-size: 2.8rem; letter-spacing: -1.5px;">
                Gestión de <span style="color: var(--color-primario);">Presupuestos</span>
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('empresa.presupuestos.create') }}" class="btn btn-primary px-4 py-3 rounded-pill fw-bold shadow-lg">
                <i class="bi bi-plus-lg me-2"></i> GENERAR NUEVA COTIZACIÓN
            </a>
        </div>
    </div>

    {{-- INDICADORES FLASH (Estilo Dashboard) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-panel text-center">
                <div class="stat-label">Total Emitidos</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="small text-muted mt-2">Histórico Global</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel text-center border-bottom border-warning border-4">
                <div class="stat-label">Pendientes</div>
                <div class="stat-value text-warning">{{ $stats['pendientes'] }}</div>
                <div class="small text-muted mt-2">A la espera de cierre</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel text-center border-bottom border-success border-4" style="border-color: var(--color-primario) !important;">
                <div class="stat-label">Aceptados</div>
                <div class="stat-value" style="color: var(--color-primario);">{{ $stats['aceptados'] }}</div>
                <div class="small text-muted mt-2">Éxito Comercial</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel text-center border-bottom border-danger border-4">
                <div class="stat-label">Vencidos</div>
                <div class="stat-value text-danger">{{ $stats['vencidos'] }}</div>
                <div class="small text-muted mt-2">Requieren Seguimiento</div>
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

