@extends('layouts.empresa')

@section('content')
<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('empresa.production_orders.index') }}" class="text-muted text-decoration-none">Órdenes de Producción</a></li>
                    <li class="breadcrumb-item active">Detalle #{{ $production_order->id }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Detalle de Orden</h2>
            <p class="text-muted small">Trazabilidad completa del lote de producción.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.production_orders.edit', $production_order) }}" class="btn btn-warning fw-bold shadow-sm px-4">
                <i class="bi bi-pencil-square me-1"></i> Editar
            </a>
            <form action="{{ route('empresa.production_orders.clone', $production_order) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success fw-bold shadow-sm px-4"
                    onclick="return confirm('¿Desea clonar esta orden y generar un nuevo lote?')">
                    <i class="bi bi-copy me-1"></i> Clonar Orden
                </button>
            </form>
            <a href="{{ route('empresa.production_orders.index') }}" class="btn btn-light border fw-bold shadow-sm px-4">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="row g-4">

        {{-- COLUMNA IZQUIERDA: INFO PRINCIPAL --}}
        <div class="col-lg-4">

            {{-- Card: Estado y Metadata --}}
            <div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase">
                        <i class="bi bi-info-circle me-2 opacity-50"></i> Información General
                    </h6>
                </div>
                <div class="card-body p-4">

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Estado</label>
                        @if($production_order->status === 'completada')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success fw-bold fs-6 px-3 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i> COMPLETADA
                            </span>
                        @elseif($production_order->status === 'pendiente')
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning fw-bold fs-6 px-3 py-2">
                                <i class="bi bi-clock-fill me-1"></i> PENDIENTE
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary fw-bold fs-6 px-3 py-2">
                                <i class="bi bi-x-circle-fill me-1"></i> {{ strtoupper($production_order->status) }}
                            </span>
                        @endif
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Producto Fabricado</label>
                        <span class="fw-bold text-dark fs-5">{{ $production_order->recipe->product->name ?? 'N/A' }}</span>
                        <small class="text-muted d-block">{{ $production_order->recipe->name ?? '' }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Cantidad Producida</label>
                        <span class="fw-bold text-dark fs-4">
                            {{ number_format($production_order->quantity, 2) }}
                            <small class="fs-6 text-muted">{{ $production_order->recipe->product->unit->short_name ?? 'U' }}</small>
                        </span>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Autorizado por</label>
                        <span class="fw-bold text-dark">{{ $production_order->user->name ?? 'Sistema' }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Fecha de Creación</label>
                        <span class="text-dark">{{ $production_order->created_at->format('d/m/Y \a\l\a\s H:i') }} hs</span>
                    </div>

                    @if($production_order->completed_at)
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Fecha de Completado</label>
                        <span class="text-success fw-bold">{{ \Carbon\Carbon::parse($production_order->completed_at)->format('d/m/Y H:i') }} hs</span>
                    </div>
                    @endif

                    @if($production_order->notes)
                    <hr class="my-3">
                    <div>
                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Notas del Lote</label>
                        <p class="text-dark mb-0 fst-italic">{{ $production_order->notes }}</p>
                    </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- COLUMNA DERECHA: MATERIALES CONSUMIDOS --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-white overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase">
                        <i class="bi bi-list-check me-2 opacity-50"></i> Materiales Consumidos (BOM)
                    </h6>
                    <span class="badge bg-light text-dark border fw-bold px-3 py-2">
                        LOTE: {{ number_format($production_order->quantity, 2) }} {{ $production_order->recipe->product->unit->short_name ?? 'U' }}
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase fw-bold">
                            <tr>
                                <th class="ps-4 py-3">Insumo / Ingrediente</th>
                                <th class="text-center">Req. x Unidad</th>
                                <th class="text-center">Total Consumido</th>
                                <th class="text-end pe-4">Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($production_order->recipe->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block">{{ $item->component->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-muted small">{{ number_format($item->quantity, 4) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-dark">
                                            {{ number_format($item->quantity * $production_order->quantity, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-light text-dark border">
                                            {{ $item->unit->short_name ?? ($item->component->unit->short_name ?? 'U') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                                        No hay ingredientes registrados en esta receta.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-light border-top">
                            <tr>
                                <td colspan="4" class="ps-4 py-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Los insumos fueron descontados automáticamente del inventario al ejecutar el lote.
                                        El producto <strong>{{ $production_order->recipe->product->name ?? '' }}</strong>
                                        se incrementó en <strong>{{ number_format($production_order->quantity, 2) }} {{ $production_order->recipe->product->unit->short_name ?? 'U' }}</strong>.
                                    </small>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
