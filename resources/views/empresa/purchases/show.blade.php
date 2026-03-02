@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   CABECERA
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Detalle de Compra</h2>
        <small class="text-muted">Factura registrada</small>
    </div>

    <a href="{{ route('empresa.compras.index') }}" class="btn btn-secondary">
        Volver
    </a>
</div>


{{-- =========================================================
   DATOS FACTURA
========================================================= --}}
<div class="card mb-4">
    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <p class="mb-1"><strong>Proveedor:</strong></p>
                <p>{{ $purchase->supplier->name ?? '-' }}</p>
            </div>

            <div class="col-md-2">
                <p class="mb-1"><strong>Fecha:</strong></p>
                <p>
                    @if($purchase->purchase_date)
                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>

            <div class="col-md-3">
                <p class="mb-1"><strong>Comprobante:</strong></p>
                <p>
                    {{ $purchase->invoice_type ?? '-' }}
                    {{ $purchase->invoice_number ?? '' }}
                </p>
            </div>

            <div class="col-md-3">
                <p class="mb-1"><strong>Estado:</strong></p>
                <p>
                    @if($purchase->payment_type == 'contado')
                        <span class="badge bg-success">Contado</span>
                    @else
                        <span class="badge bg-warning text-dark">Crédito</span>
                    @endif
                </p>
            </div>

        </div>

    </div>
</div>


{{-- =========================================================
   ITEMS
========================================================= --}}
<div class="card mb-4">
    <div class="card-header fw-bold">Detalle de Productos</div>

    <div class="card-body p-0">

        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th width="120">Cantidad</th>
                    <th width="150">Costo Unit.</th>
                    <th width="150">IVA Unit.</th>
                    <th width="160">Total Línea</th>
                </tr>
            </thead>

            <tbody>
            @foreach($purchase->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>

                    <td class="text-end">
                        {{ number_format($item->quantity,2,',','.') }}
                    </td>

                    <td class="text-end">
                        ${{ number_format($item->cost,2,',','.') }}
                    </td>

                    <td class="text-end">
                        ${{ number_format($item->iva,2,',','.') }}
                    </td>

                    <td class="text-end">
                        ${{ number_format($item->subtotal,2,',','.') }}
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

    </div>
</div>


{{-- =========================================================
   TOTALES FACTURA
========================================================= --}}
<div class="row justify-content-end">
    <div class="col-md-4">

        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <strong>
                        ${{ number_format($purchase->subtotal ?? 0,2,',','.') }}
                    </strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>IVA</span>
                    <strong>
                        ${{ number_format($purchase->iva ?? 0,2,',','.') }}
                    </strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5">
                    <span>Total</span>
                    <strong>
                        ${{ number_format($purchase->total ?? 0,2,',','.') }}
                    </strong>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
