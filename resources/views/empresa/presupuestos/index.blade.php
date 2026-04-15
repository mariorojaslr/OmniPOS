@extends('layouts.empresa')

@section('styles')
<style>
/* REDUCCIÓN DE ESPACIOS SUPERIORES */
.content-wrapper-compact {
    padding-top: 0.5rem !important;
}

/* HEADER COMPACTO */
.page-header-compact {
    margin-bottom: 1.5rem !important;
}
.page-header-compact h2 {
    font-size: 1.5rem;
    margin-bottom: 0 !important;
}

/* TARJETAS DE ESTADÍSTICAS MINI */
.card-stats-mini {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    background: #ffffff;
    transition: all 0.2s;
    border: 1px solid rgba(0,0,0,0.1);
}
.card-stats-mini:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

.stat-icon-mini {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 1.1rem;
}

.stat-label-mini {
    font-size: 0.65rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.stat-value-mini {
    font-size: 1.2rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}

/* TABLA Y DROPDOWN ESTILO VIÑETA */
.table-premium thead th {
    background-color: #f8fafc;
    color: #475569;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    padding: 0.75rem 1rem;
    border-bottom: 2px solid #e2e8f0;
}

/* Menú Estilo Burbuja/Viñeta */
.dropdown-menu-bubble {
    border: none !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    border-radius: 12px !important;
    padding: 0.5rem !important;
    margin-top: 10px !important;
    animation: fadeInScale 0.2s ease-out;
}

.dropdown-menu-bubble::before {
    content: '';
    position: absolute;
    top: -6px;
    right: 20px;
    width: 12px;
    height: 12px;
    background: #ffffff;
    transform: rotate(45deg);
    border-top: 1px solid rgba(0,0,0,0.05);
    border-left: 1px solid rgba(0,0,0,0.05);
}

@keyframes fadeInScale {
    0% { opacity: 0; transform: scale(0.95) translateY(-10px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
}

.dropdown-item {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: #475569;
    transition: all 0.2s;
}
.dropdown-item:hover {
    background-color: #f1f5f9;
    color: #0f172a;
    transform: translateX(5px);
}

/* Prevenir corte en tabla-responsive */
.table-responsive {
    overflow: visible !important;
}
</style>
@endsection

@section('content')
<div class="container-fluid content-wrapper-compact pb-4">
    
    {{-- HEADER COMPACTO --}}
    <div class="d-flex justify-content-between align-items-end page-header-compact">
        <div>
            <h2 class="fw-bold text-dark">Gestión de Presupuestos</h2>
            <p class="text-muted small">Seguimiento comercial y cotizaciones en tiempo real</p>
        </div>
        <div class="d-flex gap-2">
            @if(auth()->user()->role === 'empresa')
                <a href="{{ route('empresa.presupuestos.create') }}" class="btn btn-primary fw-bold rounded-pill px-3 shadow-sm btn-sm d-flex align-items-center">
                    <i class="bi bi-plus-lg me-2"></i> Nueva Cotización
                </a>
            @endif
            <button class="btn btn-outline-secondary fw-bold rounded-pill px-3 shadow-sm btn-sm d-flex align-items-center">
                <i class="bi bi-file-earmark-pdf me-2"></i> Reportes
            </button>
        </div>
    </div>

    {{-- INDICADORES MINI --}}
    <div class="row g-2 mb-4">
        @foreach([
            ['L'=>'Total Emitidos', 'V'=>$stats['total'], 'C'=>'primary', 'I' => 'bi-file-text'],
            ['L'=>'Pendientes', 'V'=>$stats['pendientes'], 'C'=>'warning', 'I' => 'bi-clock-history'],
            ['L'=>'Aceptados', 'V'=>$stats['aceptados'], 'C'=>'success', 'I' => 'bi-check-circle'],
            ['L'=>'Vencidos', 'V'=>$stats['vencidos'], 'C'=>'danger', 'I' => 'bi-exclamation-octagon']
        ] as $s)
        <div class="col-6 col-md-3">
            <div class="card card-stats-mini">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="stat-icon-mini bg-{{ $s['C'] }} bg-opacity-10 text-{{ $s['C'] }} me-2">
                        <i class="bi {{ $s['I'] }}"></i>
                    </div>
                    <div>
                        <div class="stat-label-mini">{{ $s['L'] }}</div>
                        <div class="stat-value-mini">{{ $s['V'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- LISTADO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-premium table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="120">REF #</th>
                        <th>CLIENTE / PROSPECTO</th>
                        <th>EMISIÓN</th>
                        <th>VENCIMIENTO</th>
                        <th class="text-end">TOTAL</th>
                        <th class="text-center">ESTADO</th>
                        <th class="text-end pe-4">GESTIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($presupuestos as $presu)
                    <tr>
                        <td class="fw-bold text-primary">#{{ $presu->numero }}</td>
                        <td>
                            <div class="fw-bold fs-6">{{ $presu->client->name ?? 'Cliente Ocasional' }}</div>
                            <div class="text-muted small">{{ $presu->client->email ?? '-' }}</div>
                        </td>
                        <td class="small">{{ $presu->fecha ? $presu->fecha->format('d/m/Y') : '-' }}</td>
                        <td class="small">
                            @if($presu->vencimiento && $presu->vencimiento < now() && $presu->estado == 'pendiente')
                                <span class="text-danger fw-bold">{{ $presu->vencimiento->format('d/m/Y') }}</span>
                            @else
                                {{ $presu->vencimiento ? $presu->vencimiento->format('d/m/Y') : '-' }}
                            @endif
                        </td>
                        <td class="text-end fw-bold fs-5 text-success">$ {{ number_format($presu->total, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $badgeClass = match($presu->estado) {
                                    'pendiente' => 'bg-warning',
                                    'aceptado'  => 'bg-success',
                                    'vencido'   => 'bg-danger',
                                    default     => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.65rem;">{{ $presu->estado }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('empresa.presupuestos.pdf', $presu->id) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Ver / Imprimir PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 ms-1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" style="min-width: 220px;">
                                    {{-- VER / IMPRIMIR --}}
                                    <li>
                                        <a class="dropdown-item" href="{{ route('empresa.presupuestos.pdf', $presu->id) }}" target="_blank">
                                            <i class="bi bi-printer me-2 text-primary"></i> Imprimir PDF
                                        </a>
                                    </li>
                                    
                                    @if(auth()->user()->role === 'empresa')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('empresa.presupuestos.edit', $presu->id) }}">
                                                <i class="bi bi-pencil me-2 text-warning"></i> Editar
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        {{-- CLONAR --}}
                                        <li>
                                            <form action="{{ route('empresa.presupuestos.clone', $presu->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('¿Clonar el presupuesto {{ $presu->numero }}? Se creará uno nuevo con los mismos ítems para que pueda editarlo.')">
                                                    <i class="bi bi-copy me-2 text-info"></i> Clonar como nuevo
                                                </button>
                                            </form>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        {{-- CONVERTIR EN FACTURA --}}
                                        <li>
                                            <form action="{{ route('empresa.presupuestos.convertir_factura', $presu->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success fw-bold"
                                                    onclick="return confirm('¿Convertir {{ $presu->numero }} en Factura?\n\nSe abrirá el facturador manual con los ítems pre-cargados. El presupuesto quedará marcado como ACEPTADO.')">
                                                    <i class="bi bi-receipt-cutoff me-2"></i> Convertir en Factura
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox-fill fs-1 opacity-25"></i>
                                <p class="mt-2 fw-bold">No hay presupuestos emitidos hasta el momento</p>
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

