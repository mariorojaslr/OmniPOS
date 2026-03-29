@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4 h-100 d-flex flex-column">
    
    {{-- CABECERA PROFESIONAL --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 d-flex align-items-center">
                <span class="badge bg-primary me-2" style="font-size: 0.6em; vertical-align: middle;">PRO</span>
                Hub de Etiquetas e Impresión
            </h2>
            <p class="text-muted mb-0">Gestiona la señalética y códigos de barras de tus productos con precisión.</p>
        </div>
        <div>
            <a href="{{ route('empresa.products.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                ← Volver al Catálogo
            </a>
        </div>
    </div>

    {{-- BARRA DE FILTROS INTELIGENTES --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);">
        <div class="card-body py-3">
            <form action="{{ route('empresa.labels.index') }}" method="GET" class="row align-items-end g-2">
                
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Filtro rápido</label>
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('empresa.labels.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">Todos</a>
                        <a href="{{ route('empresa.labels.index', ['filter' => 'nuevas']) }}" class="btn btn-sm btn-outline-primary {{ request('filter') == 'nuevas' ? 'active' : '' }}">Nuevos</a>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Por Rubro</label>
                    <select name="rubro_id" class="form-select form-select-sm rounded-pill font-monospace" onchange="this.form.submit()">
                        <option value="">-- Todos los rubros --</option>
                        @foreach($rubros as $r)
                            <option value="{{ $r->id }}" {{ request('rubro_id') == $r->id ? 'selected' : '' }}>{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Por Compra Reciente</label>
                    <select name="purchase_id" class="form-select form-select-sm rounded-pill font-monospace" onchange="this.form.submit()">
                        <option value="">-- Compras recientes --</option>
                        @foreach($compras as $c)
                            <option value="{{ $c->id }}" {{ request('purchase_id') == $c->id ? 'selected' : '' }}>
                                #{{ $c->id }} - {{ $c->supplier->name }} ({{ date('d/m/y', strtotime($c->purchase_date)) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill px-4">Filtrar</button>
                    @if(request()->anyFilled(['filter', 'rubro_id', 'purchase_id']))
                        <a href="{{ route('empresa.labels.index') }}" class="btn btn-link btn-sm text-danger">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('empresa.labels.generate') }}" method="POST" target="_blank" id="formLabels">
        @csrf
        
        <div class="row g-4 flex-grow-1">
            
            {{-- LISTADO DE PRODUCTOS A SELECCIONAR --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">1. Artículos para imprimir</h6>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-start-pill px-3" onclick="selectAll(true)">Seleccionar todos</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-end-pill px-3" onclick="selectAll(false)">Ninguno</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light sticky-top shadow-sm" style="z-index: 5;">
                                    <tr class="small text-muted text-uppercase">
                                        <th width="40" class="ps-4">✓</th>
                                        <th>Producto</th>
                                        <th width="120">Cod. Barra</th>
                                        <th width="100" class="text-center">Copias</th>
                                        <th class="text-end pe-4">Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $p)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="form-check">
                                                    <input type="checkbox" name="selected_items[{{ $p->id }}]" value="1" class="form-check-input item-check" {{ request('filter') == 'nuevas' || request('purchase_id') ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($p->created_at >= now()->subHours(48))
                                                        <span class="badge bg-warning text-dark me-2" style="font-size: 0.7em;">NUEVO</span>
                                                    @endif
                                                    <span class="fw-bold">{{ $p->name }}</span>
                                                </div>
                                                <small class="text-muted">{{ $p->rubro->nombre ?? 'Sin rubro' }}</small>
                                            </td>
                                            <td><code class="text-primary">{{ $p->barcode }}</code></td>
                                            <td class="text-center">
                                                <input type="number" name="quantities[{{ $p->id }}]" value="1" min="1" max="500" class="form-control form-control-sm text-center mx-auto" style="width: 70px; border-radius: 8px;">
                                            </td>
                                            <td class="text-end pe-4 fw-bold">$ {{ number_format($p->price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted py-5">
                                                    <i class="bi bi-box-seam fs-1 d-block mb-3 opacity-25"></i>
                                                    No se encontraron productos con el filtro aplicado.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONFIGURACIÓN DEL TRABAJO DE IMPRESIÓN --}}
            <div class="col-md-4">
                <div class="card shadow border-0 rounded-4 sticky-top" style="top: 20px; background: #fff;">
                    <div class="card-header bg-dark text-white py-3 border-0 rounded-top-4">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-gear-fill me-2"></i> Configuración de Salida</h6>
                    </div>
                    <div class="card-body py-4">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Formato de Etiquetas</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="format" id="formatSmall" value="small" checked>
                                    <label class="btn btn-outline-dark w-100 py-3 rounded-4 d-flex flex-column align-items-center" for="formatSmall">
                                        <i class="bi bi-grid-3x3-gap mb-2 fs-4"></i>
                                        <span class="small fw-bold">CHICA</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="format" id="formatMedium" value="medium">
                                    <label class="btn btn-outline-dark w-100 py-3 rounded-4 d-flex flex-column align-items-center" for="formatMedium">
                                        <i class="bi bi-grid-3x2 mb-2 fs-4"></i>
                                        <span class="small fw-bold">MEDIA</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="format" id="formatLarge" value="large">
                                    <label class="btn btn-outline-dark w-100 py-3 rounded-4 d-flex flex-column align-items-center" for="formatLarge">
                                        <i class="bi bi-view-stacked mb-2 fs-4"></i>
                                        <span class="small fw-bold">GRANDE</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 p-3 rounded-4 bg-light border">
                            <h6 class="fw-bold small mb-2"><i class="bi bi-info-circle me-1"></i> Recomendación</h6>
                            <p class="small text-muted mb-0">Para una impresión perfecta, asegúrate de que la escala en tu navegador esté al 100% y los márgenes en "Ninguno".</p>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg">
                            <i class="bi bi-printer-fill me-2"></i> GENERAR PDF PROFESIONAL
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">Optimizada para hojas A4</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function selectAll(state) {
    document.querySelectorAll('.item-check').forEach(c => c.checked = state);
}
</script>

<style>
/* Estilos premium OLED */
.main-fluid { background: #f0f2f5; }
.card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.05); }
.form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
.btn-outline-dark:hover { background-color: #000; color: #fff; }

/* Sticky table adjustments */
.sticky-top { top: -1px; background: #fff; }
</style>
@endsection
