@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   ENCABEZADO PROFESIONAL (OLED STYLE)
========================================================= --}}
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('empresa.ventas.index') }}" class="text-decoration-none">Ventas</a></li>
                <li class="breadcrumb-item active">Detalle de Venta</li>
            </ol>
        </nav>
        <h2 class="mb-0 fw-bold">Venta #{{ $venta->id }} — {{ $venta->numero_comprobante ?: 'Interna' }}</h2>
        <div class="d-flex align-items-center gap-2 mt-2">
            <span class="badge bg-dark border">{{ $venta->created_at->format('d/m/Y H:i') }} hs</span>
            @if($venta->es_guarda_pendiente)
                <span class="badge bg-danger shadow-sm border-0 fw-bold px-3 py-2" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                    🛡️ MERCADERÍA EN GUARDA ({{ 100 - $venta->porcentaje_entrega }}% Pendiente)
                </span>
            @else
                <span class="badge bg-success-subtle text-success border-success fw-bold px-3">
                    ✅ ENTREGA COMPLETADA (100%)
                </span>
            @endif
        </div>
    </div>
    <div class="d-flex gap-2">
        @if(!$venta->cae && auth()->user()->empresa->arca_activo)
            <form action="{{ route('empresa.ventas.fiscalizar', $venta->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de emitir la FACTURA ARCA para esta venta? Una vez generada no podrá modificarse.')">
                @csrf
                <button type="submit" class="btn btn-primary shadow-sm fw-bold">
                    🚀 Hacer Factura ARCA
                </button>
            </form>
        @endif
        <a href="{{ route('empresa.ventas.pdf', $venta->id) }}" target="_blank" class="btn btn-outline-danger">
            📄 Factura A4
        </a>
        <a href="{{ route('empresa.ventas.index') }}" class="btn btn-outline-secondary">
            ⬅️ Volver
        </a>
    </div>
</div>

<div class="row g-4">
    
    {{-- =========================================================
       DATOS DEL CLIENTE Y RESUMEN FINANCIERO
    ========================================================= --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white fw-bold border-bottom py-3">
                👤 Información del Cliente
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-1">{{ optional($venta->cliente)->name ?? 'Consumidor Final' }}</h5>
                <p class="text-muted small mb-3">{{ optional($venta->cliente)->email ?? 'Sin email registrado' }}</p>
                
                <hr class="opacity-10">

                <div class="mb-3">
                    <label class="text-muted small d-block">MÉTODO DE PAGO</label>
                    <span class="badge bg-success-subtle text-success border-success text-uppercase">
                        {{ $venta->metodo_pago }}
                    </span>
                </div>

                <div class="bg-light p-3 rounded">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small text-uppercase">Subtotal</span>
                        <span class="fw-bold">$ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small text-uppercase">IVA</span>
                        <span class="fw-bold text-muted">$ {{ number_format($venta->total_iva, 2, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-2 pt-2 border-top border-dark border-opacity-10">
                        <span class="fw-bold fs-5">TOTAL</span>
                        <span class="fw-bold fs-5 text-primary">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
       PANEL LOGÍSTICO (RESUMEN DE ENTREGAS)
    ========================================================= --}}
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold">📦 Control de Entregas (Productos en Guarda)</span>
                @php
                    $totalPendiente = $venta->items->sum('cantidad_pendiente');
                @endphp
                @if($totalPendiente > 0)
                    <a href="{{ route('empresa.ventas.entregar', $venta->id) }}" class="btn btn-sm btn-primary shadow-sm">
                        🚚 Nueva Entrega / Remito
                    </a>
                @else
                    <span class="badge bg-success">✅ Entrega Completada</span>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Producto</th>
                                <th class="text-center">Comprado</th>
                                <th class="text-center">Entregado</th>
                                <th class="text-center">Saldo (En Guarda)</th>
                                <th class="text-center" width="100">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->items as $item)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-bold text-dark">{{ $item->product->name }}</div>
                                    @if($item->variant)
                                        <small class="text-muted">{{ $item->variant->size }} / {{ $item->variant->color }}</small>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ number_format($item->cantidad, 2) }}</td>
                                <td class="text-center text-success fw-bold">{{ number_format($item->cantidad_entregada, 2) }}</td>
                                <td class="text-center">
                                    @if($item->cantidad_pendiente > 0)
                                        <span class="badge bg-warning-subtle text-warning border-warning fs-6">
                                            {{ number_format($item->cantidad_pendiente, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted">——</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->cantidad_pendiente == 0)
                                        <span class="badge bg-success rounded-pill">Completo</span>
                                    @elseif($item->cantidad_entregada > 0)
                                        <span class="badge bg-info rounded-pill">Parcial</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Guarda Total</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- =========================================================
           HISTORIAL DE REMITOS
        ========================================================= --}}
        <h5 class="fw-bold mb-3 mt-4">🗒️ Historial de Remitos de Entrega</h5>
        
        @if($venta->remitos->count() > 0)
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-3" width="150">Fecha / Hora</th>
                            <th width="150">Nº Remito</th>
                            <th>Entregado por</th>
                            <th class="text-center" width="120">Items</th>
                            <th class="text-end pe-3" width="120">Documento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->remitos->sortByDesc('created_at') as $remito)
                        <tr>
                            <td class="ps-3 small">
                                {{ $remito->fecha_entrega->format('d/m/Y') }}<br>
                                {{ $remito->fecha_entrega->format('H:i') }} hs
                            </td>
                            <td class="fw-bold">{{ $remito->numero_remito ?: 'REM-'.$remito->id }}</td>
                            <td>
                                <span class="small">{{ $remito->user->name }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark rounded-pill">{{ $remito->items->count() }} prod.</span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('empresa.remitos.pdf', $remito->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    🖨️ Imprimir
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="alert alert-light border shadow-sm text-center py-4">
            <p class="mb-0 text-muted">Aún no se han generado remitos para esta venta.</p>
        </div>
        @endif
    </div>
</div>

@endsection
