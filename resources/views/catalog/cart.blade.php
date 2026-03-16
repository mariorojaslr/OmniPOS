@extends('catalog.layout')

@section('title', 'Carrito')

@section('content')

<h3 class="mb-4">Tu carrito</h3>

@if(session('cart') && count(session('cart')) > 0)

<table class="table align-middle bg-white shadow-sm">
    <thead class="table-light">
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th width="160">Cantidad</th>
            <th>Total</th>
            <th width="80"></th>
        </tr>
    </thead>
    <tbody>

    @php $total = 0; @endphp

    @foreach(session('cart') as $id => $item)
        @php
            $lineTotal = $item['price'] * $item['quantity'];
            $total += $lineTotal;
        @endphp

        <tr data-price="{{ $item['price'] }}">
            <td>
                <div class="d-flex align-items-center gap-3">

                    @if(!empty($item['image']))
                        <img src="{{ $item['image'] }}"
                             width="60"
                             height="60"
                             style="object-fit:cover; border-radius:8px;">
                    @endif

                    <div>
                        <div class="fw-bold">{{ $item['name'] }}</div>
                    </div>

                </div>
            </td>

            <td>
                ${{ number_format($item['price'],2) }}
            </td>

            <td>
                <form action="{{ route('cart.update',$id) }}" method="POST" class="d-flex gap-2 align-items-center">
                    @csrf
                    @method('PATCH')

                    <input type="number"
                           name="quantity"
                           value="{{ $item['quantity'] }}"
                           min="1"
                           max="{{ $item['stock'] ?? 9999 }}"
                           class="form-control form-control-sm quantity-input text-center"
                           style="width:80px;">

                    <button class="btn btn-sm btn-outline-primary">
                        ✓
                    </button>
                </form>
            </td>

            <td class="fw-bold line-total">
                ${{ number_format($lineTotal,2) }}
            </td>

            <td>
                <form action="{{ route('cart.remove',$id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        X
                    </button>
                </form>
            </td>
        </tr>

    @endforeach

    </tbody>
</table>

<div class="d-flex justify-content-end mt-4">
    <h4>Total: $<span id="cartTotal">{{ number_format($total,2) }}</span></h4>
</div>

<div class="d-flex justify-content-end mt-3">
    <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg shadow">
        Finalizar compra
    </a>
</div>

@else
<div class="alert alert-info">
    No hay productos en el carrito.
</div>
@endif


{{-- ================== SCRIPT TOTAL DINÁMICO ================== --}}
@if(session('cart') && count(session('cart')) > 0)
<script>
document.querySelectorAll('.quantity-input').forEach(input => {

    input.addEventListener('input', function(){

        const row = this.closest('tr');
        const price = parseFloat(row.dataset.price);
        const quantity = parseInt(this.value) || 0;

        const lineTotalCell = row.querySelector('.line-total');
        const newLineTotal = price * quantity;

        lineTotalCell.innerText = "$" + newLineTotal.toLocaleString('es-AR', {minimumFractionDigits:2});

        // Recalcular total general
        let grandTotal = 0;

        document.querySelectorAll('tbody tr').forEach(r => {
            const p = parseFloat(r.dataset.price);
            const q = parseInt(r.querySelector('.quantity-input').value) || 0;
            grandTotal += p * q;
        });

        document.getElementById('cartTotal').innerText =
            grandTotal.toLocaleString('es-AR', {minimumFractionDigits:2});

    });

});
</script>
@endif

@endsection
