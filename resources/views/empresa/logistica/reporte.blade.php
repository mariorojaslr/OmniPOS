@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   ENCABEZADO ESTRATÉGICO
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0 fw-bold text-dark">📊 Tablero de Compromisos Logísticos</h2>
        <p class="text-muted small">Estado global de productos en guarda y deuda física con clientes</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-dark btn-sm">
            🖨️ Imprimir Reporte
        </button>
    </div>
</div>

<div class="row g-4">
    
    {{-- =========================================================
       KPIs GLOBALES
    ========================================================= --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-top border-primary border-4">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1 text-uppercase fw-bold">Compromiso Total</small>
                <h3 class="mb-0 fw-bold text-primary">{{ number_format($registros->sum('compromiso_total'), 2) }}</h3>
                <small class="text-muted">Unidades pendientes de entrega</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-top border-success border-4">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1 text-uppercase fw-bold">Cumplimiento</small>
                @php
                    $totalV = $registros->sum('total_vendido');
                    $totalE = $registros->sum('total_entregado');
                    $porcentaje = $totalV > 0 ? ($totalE / $totalV) * 100 : 100;
                @endphp
                <h3 class="mb-0 fw-bold text-success">{{ number_format($porcentaje, 1) }}%</h3>
                <small class="text-muted">Mercadería ya retirada</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    
    {{-- =========================================================
       TABLA DE PRODUCTOS EN GUARDA
    ========================================================= --}}
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="ps-4 py-3">Producto / Variante</th>
                                <th class="text-center" width="120">Vendidos</th>
                                <th class="text-center" width="120">Entregados</th>
                                <th class="text-center" width="150" style="background: #fff8e1;">Saldo en Guarda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registros as $reg)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $reg->product->name }}</div>
                                    @if($reg->variant)
                                        <small class="text-muted">{{ $reg->variant->size }} / {{ $reg->variant->color }}</small>
                                    @endif
                                </td>
                                <td class="text-center text-muted fw-bold">{{ number_format($reg->total_vendido, 2) }}</td>
                                <td class="text-center text-success fw-bold">{{ number_format($reg->total_entregado, 2) }}</td>
                                <td class="text-center fw-extrabold text-dark fs-5" style="background: #fffdf5;">
                                    <span class="badge bg-warning text-dark px-3">{{ number_format($reg->compromiso_total, 2) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    No hay mercadería pendiente de entrega en el sistema. ✨
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
       SIDEBAR: CLIENTES DEUDORES
    ========================================================= --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 fw-bold border-bottom">
                👥 Clientes con Mayor Saldo en Guarda
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($clientesDeudores as $cliente)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <div class="fw-bold">{{ $cliente->name }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 0.65rem;">{{ $cliente->document ?: 'Sin Documento' }}</small>
                        </div>
                        <span class="badge bg-dark rounded-pill">{{ number_format($cliente->saldo_guarda, 2) }} UN</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center py-4 text-muted small">
                        Sin deuda física registrada.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="alert alert-info mt-4 border-0 shadow-sm">
            <h6 class="fw-bold"><i class="bi bi-lightbulb me-2"></i>Estrategia Logística</h6>
            <p class="small mb-0">
                Utiliza este tablero para planificar tus compras. Los "Saldos en Guarda" representan tu compromiso de entrega a futuro.
            </p>
        </div>
    </div>

</div>

@endsection
