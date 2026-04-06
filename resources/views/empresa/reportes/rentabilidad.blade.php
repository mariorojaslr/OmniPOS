@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4 py-4">
    
    {{-- CABECERA ESTRATÉGICA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Análisis de Rentabilidad Real</h2>
            <p class="text-muted small">Cálculo basado en Recetas (BOM) y Costos de Insumos Neto de IVA.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-light border fw-bold shadow-sm">
                <i class="bi bi-chevron-left me-1"></i> VOLVER AL PANEL
            </a>
        </div>
    </div>

    {{-- ALERTAS / AYUDA TÁCTICA --}}
    <div class="alert bg-white border-start border-4 border-info shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
            <div>
                <h6 class="fw-bold mb-1 text-dark">¿Cómo leemos este reporte?</h6>
                <p class="text-muted small mb-0">
                    El <strong>Precio Neto</strong> es su venta real (restando el 21% de IVA). La <strong>Ganancia</strong> es lo que queda tras restar el <strong>Costo Total</strong> (insumos o base). 
                    Los productos con <span class="badge bg-light text-primary border border-primary small">RECETA</span> son cálculos exactos basados en sus ingredientes.
                </p>
            </div>
        </div>
    </div>

    {{-- TABLA DE RENTABILIDAD --}}
    <div class="card border-0 shadow-sm overflow-hidden bg-white mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Producto / Servicio</th>
                        <th class="text-center">Método Costo</th>
                        <th class="text-end">Precio Venta (con IVA)</th>
                        <th class="text-end">Precio Neto (Sin IVA)</th>
                        <th class="text-end">Costo Producción</th>
                        <th class="text-end">Ganancia Neta</th>
                        <th class="text-center pe-4">Margen %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        @php 
                            $statusClass = $it->margen > 30 ? 'text-success' : ($it->margen > 15 ? 'text-warning' : 'text-danger');
                            $bgClass = $it->margen > 30 ? 'bg-success' : ($it->margen > 15 ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold d-block text-dark">{{ $it->nombre }}</span>
                                <small class="text-muted">ID: #{{ $it->id }}</small>
                            </td>
                            <td class="text-center">
                                @if($it->tiene_r)
                                    <span class="badge bg-light text-primary border border-primary opacity-75 fw-bold" style="font-size: 0.7rem;">
                                        <i class="bi bi-mortarboard-fill me-1"></i> RECETA (BOM)
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border small fw-normal" style="font-size: 0.7rem;">
                                        <i class="bi bi-pencil-square me-1"></i> MANUAL
                                    </span>
                                @endif
                            </td>
                            <td class="text-end text-muted small">
                                ${{ number_format($it->precio_v, 2) }}
                            </td>
                            <td class="text-end fw-bold text-dark">
                                ${{ number_format($it->precio_n, 2) }}
                            </td>
                            <td class="text-end text-danger fw-bold">
                                ${{ number_format($it->costo, 2) }}
                            </td>
                            <td class="text-end fw-bold {{ $statusClass }}">
                                ${{ number_format($it->ganancia, 2) }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-inline-block text-center" style="width: 60px;">
                                    <span class="fw-bold d-block {{ $statusClass }}">{{ number_format($it->margen, 1) }}%</span>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar {{ $bgClass }}" role="progressbar" style="width: {{ min(100, max(0, $it->margen)) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-graph-down fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <span class="text-muted">No hay productos activos configurados para la venta.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- LEYENDA TÁCTICA --}}
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded-circle p-2 me-3" style="width: 12px; height: 12px;"></div>
                    <span class="small fw-bold text-muted">EFICAZ (>30%): Genera capital sólido.</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning rounded-circle p-2 me-3" style="width: 12px; height: 12px;"></div>
                    <span class="small fw-bold text-muted">BAJO (15%-30%): Margen operativo ajustado.</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-danger rounded-circle p-2 me-3" style="width: 12px; height: 12px;"></div>
                    <span class="small fw-bold text-muted">CRÍTICO (<15%): Riesgo de pérdida financiera.</span>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .progress { background-color: rgba(0,0,0,0.05); }
</style>
@endsection
