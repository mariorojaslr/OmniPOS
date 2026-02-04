@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Ventas registradas</h1>

    @if($ventas->isEmpty())
        <div class="alert alert-info">
            No hay ventas registradas todavía.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Total sin IVA</th>
                    <th>IVA</th>
                    <th>Total con IVA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $venta->user->name ?? '—' }}</td>
                        <td>${{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
                        <td>${{ number_format($venta->total_iva, 2, ',', '.') }}</td>
                        <td><strong>${{ number_format($venta->total_con_iva, 2, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
