@extends('layouts.app')

@section('styles')
<style>
    .presu-header {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.6));
        border-bottom: 2px solid rgba(59, 130, 246, 0.3);
        padding: 3rem 0;
        margin-bottom: 3rem;
        border-radius: 0 0 40px 40px;
    }
    .glass-stat {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    .glass-stat:hover {
        background: rgba(255, 255, 255, 0.07);
        transform: translateY(-5px);
        border-color: rgba(59, 130, 246, 0.5);
    }
    .table-glass {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }
    .table-glass th {
        background: rgba(59, 130, 246, 0.1);
        color: #94a3b8;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 2px;
        border: none;
        padding: 1.2rem;
    }
    .table-glass td {
        padding: 1.2rem;
        color: #f8fafc;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
    }
    .gradient-text-gold {
        background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .gradient-text-blue {
        background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 pb-5">
    
    {{-- HEADER PREMIUM --}}
    <div class="d-flex justify-content-between align-items-end mb-5 mt-4">
        <div>
            <h5 class="stat-label mb-1 text-primary animate__animated animate__fadeInLeft">Módulo Comercial Elite</h5>
            <h1 class="fw-bold text-white mb-0" style="font-size: 3rem; letter-spacing: -2px;">
                Gestión de <span class="gradient-text-blue">Presupuestos</span>
            </h1>
        </div>
        <div class="text-end">
            <a href="{{ route('empresa.presupuestos.create') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg animate-pulse">
                <i class="bi bi-magic me-2"></i> GENERAR NUEVA COTIZACIÓN
            </a>
        </div>
    </div>

    {{-- INDICADORES FLASH --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-stat text-center">
                <div class="stat-label">Total Emitidos</div>
                <div class="fs-2 fw-bold text-white">{{ $stats['total'] }}</div>
                <div class="small text-muted">Histórico Global</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-stat text-center" style="border-bottom: 3px solid #fbbf24;">
                <div class="stat-label">Pendientes</div>
                <div class="fs-2 fw-bold gradient-text-gold">{{ $stats['pendientes'] }}</div>
                <div class="small text-muted">A la espera de cierre</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-stat text-center" style="border-bottom: 3px solid #22c55e;">
                <div class="stat-label">Aceptados</div>
                <div class="fs-2 fw-bold text-success">{{ $stats['aceptados'] }}</div>
                <div class="small text-muted">Exito Comercial</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-stat text-center" style="border-bottom: 3px solid #ef4444;">
                <div class="stat-label">Vencidos</div>
                <div class="fs-2 fw-bold text-danger">{{ $stats['vencidos'] }}</div>
                <div class="small text-muted">Requieren Seguimiento</div>
            </div>
        </div>
    </div>

    {{-- TABLA DE CRISTAL --}}
    <div class="table-glass shadow-2xl">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Cliente / Prospecto</th>
                    <th>Fecha Emisión</th>
                    <th>Vencimiento</th>
                    <th class="text-end">Monto Total</th>
                    <th class="text-center">Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($presupuestos as $presu)
                <tr>
                    <td><span class="badge bg-secondary">#{{ $presu->numero }}</span></td>
                    <td>
                        <div class="fw-bold">{{ $presu->client->name ?? 'Cliente Ocasional' }}</div>
                        <div class="small text-muted">{{ $presu->client->email ?? '-' }}</div>
                    </td>
                    <td>{{ $presu->fecha ? $presu->fecha->format('d/m/Y') : '-' }}</td>
                    <td>{{ $presu->vencimiento ? $presu->vencimiento->format('d/m/Y') : '-' }}</td>
                    <td class="text-end fw-bold fs-5 text-info">$ {{ number_format($presu->total, 2) }}</td>
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
                        <span class="badge {{ $badgeClass }} border border-opacity-10">{{ strtoupper($presu->estado) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light rounded-circle" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-white border-opacity-10">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i> Ver Detalle</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i> Imprimir PDF</a></li>
                                @if($presu->estado == 'pendiente')
                                    <li><hr class="dropdown-divider border-white border-opacity-10"></li>
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
                        <div class="opacity-30">
                            <i class="bi bi-file-earmark-text fs-1 mb-3 d-block"></i>
                            <h5 class="fw-bold">No hay presupuestos generados</h5>
                            <p class="small mb-0">Comience creando su primera cotización profesional.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

