@extends('catalog.layout')

@section('title', 'Finalizar compra')

@section('content')

<div class="container">

<h3 class="mb-4">Finalizar compra</h3>

@if(session('cart') && count(session('cart')) > 0)

<div class="row">

{{-- ================= RESUMEN DEL PEDIDO ================= --}}
<div class="col-lg-5 mb-4">

<div class="card shadow-sm">
<div class="card-header bg-light fw-bold">
Resumen del pedido
</div>

<div class="card-body">

@php $total = 0; @endphp

@foreach(session('cart') as $item)
    @php
        $line = $item['price'] * $item['quantity'];
        $total += $line;
    @endphp

    <div class="d-flex justify-content-between mb-2">
        <div>
            {{ $item['name'] }}
            <small class="text-muted">x{{ $item['quantity'] }}</small>
        </div>
        <div>
            ${{ number_format($line,2) }}
        </div>
    </div>
@endforeach

<hr>

<div class="d-flex justify-content-between fw-bold fs-5">
    <div>Total</div>
    <div>$ {{ number_format($total,2) }}</div>
</div>

</div>
</div>

</div>

{{-- ================= FORMULARIO ================= --}}
<div class="col-lg-7">

<form method="POST" action="{{ route('checkout.store') }}">
@csrf

<div class="card shadow-sm">
<div class="card-header bg-light fw-bold">
Datos del cliente
</div>

<div class="card-body">

<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Nombre</label>
<input type="text" name="nombre" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Apellido</label>
<input type="text" name="apellido" class="form-control" required>
</div>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Teléfono</label>
<input type="text" name="telefono" class="form-control" required>
</div>

<hr>

<h6 class="fw-bold mb-3">Entrega</h6>

<div class="mb-3">
<select name="metodo_entrega" class="form-select" required>
<option value="">Seleccione método</option>
<option value="retiro_local">Retiro en local</option>
<option value="envio_domicilio">Envío a domicilio</option>
</select>
</div>

<div class="mb-3">
<label class="form-label">Dirección</label>
<input type="text" name="direccion" class="form-control">
</div>

<hr>

<h6 class="fw-bold mb-3">Método de pago</h6>

<div class="form-check">
<input class="form-check-input" type="radio" name="metodo_pago" value="manual" checked>
<label class="form-check-label">
Pago a coordinar con la empresa
</label>
</div>

<div class="form-check text-muted">
<input class="form-check-input" type="radio" disabled>
<label class="form-check-label">
Pago online (próximamente)
</label>
</div>

<button type="submit" class="btn btn-success btn-lg w-100 mt-4">
Confirmar pedido
</button>

</div>
</div>

</form>

</div>

</div>

@else

<div class="alert alert-warning">
No hay productos en el carrito.
</div>

@endif

</div>

@endsection
