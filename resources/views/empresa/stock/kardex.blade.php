@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   HEADER
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <div>
        <h2 class="fw-bold mb-0">Kardex</h2>
        <small class="text-muted">
            Evolución del stock — {{ $product->name }}
        </small>
    </div>

    <div class="d-flex gap-2">

        {{-- EXPORTAR PDF --}}
        <a href="{{ route('empresa.stock.kardex.pdf',$product->id) }}"
           class="btn btn-danger btn-sm">
            Exportar PDF
        </a>

        {{-- EXPORTAR EXCEL --}}
        <a href="{{ route('empresa.stock.kardex.excel',$product->id) }}"
           class="btn btn-success btn-sm">
            Exportar Excel
        </a>

        {{-- VOLVER --}}
        <a href="{{ route('empresa.stock.index') }}"
           class="btn btn-outline-secondary btn-sm">
            ← Volver
        </a>

    </div>

</div>


{{-- =========================================================
   GRAFICO EVOLUCION STOCK
========================================================= --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <h6 class="mb-3 text-muted">Evolución del Stock</h6>

        <canvas id="graficoStock" height="90"></canvas>

    </div>
</div>


{{-- =========================================================
   RESUMEN STOCK
========================================================= --}}
<div class="row mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <small class="text-muted">Stock Actual</small>
                <h3 class="fw-bold mb-0">
                    {{ number_format($product->stock,2) }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <small class="text-muted">Stock Mínimo</small>
                <h5 class="mb-0">
                    {{ number_format($product->stock_min,2) }}
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <small class="text-muted">Stock Ideal</small>
                <h5 class="mb-0">
                    {{ number_format($product->stock_ideal,2) }}
                </h5>
            </div>
        </div>
    </div>

</div>


{{-- =========================================================
   TABLA KARDEX
========================================================= --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <table class="table table-hover mb-0 align-middle text-center">

            <thead class="table-light">
                <tr>
                    <th width="160">Fecha</th>
                    <th width="120">Tipo</th>
                    <th width="120">Cantidad</th>
                    <th width="150">Stock</th>
                    <th>Origen</th>
                    <th width="120">Usuario</th>
                </tr>
            </thead>

            <tbody>

            @forelse($movimientos as $m)

                @php
                    if($m->tipo === 'entrada') {
                        $badge = ['Entrada','success'];
                        $cantidad = '+' . number_format($m->cantidad,2);
                    }
                    elseif($m->tipo === 'salida') {
                        $badge = ['Salida','danger'];
                        $cantidad = '-' . number_format($m->cantidad,2);
                    }
                    else {
                        $badge = ['Ajuste','warning'];
                        $cantidad = number_format($m->cantidad,2);
                    }
                @endphp

                <tr>

                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>

                    <td>
                        <span class="badge bg-{{ $badge[1] }}">
                            {{ $badge[0] }}
                        </span>
                    </td>

                    <td class="fw-bold">{{ $cantidad }}</td>

                    <td class="fw-bold">
                        {{ number_format($m->stock_resultante,2) }}
                    </td>

                    <td class="text-start">{{ $m->origen }}</td>

                    <td>{{ optional($m->user)->name ?? '-' }}</td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-muted py-4">
                        No hay movimientos registrados
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>
</div>

<div class="mt-3 d-flex justify-content-center">
    {{ $movimientos->links('pagination::bootstrap-5') }}
</div>

@endsection


{{-- =========================================================
   SCRIPT GRAFICO
========================================================= --}}
@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const labels = @json($graficoFechas ?? []);
    const dataStock = @json($graficoStock ?? []);

    const ctx = document.getElementById('graficoStock').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Stock',
                data: dataStock,
                borderWidth: 3,
                tension: 0.2,
                pointRadius: 3,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

});
</script>

@endsection
