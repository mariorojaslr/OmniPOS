@extends('layouts.empresa')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Cuenta Corriente</h3>
        <h4 class="text-success">Saldo: ${{ number_format($saldo,2) }}</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Detalle</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        <th>Saldo</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($movimientos as $m)
                    <tr>
                        <td>{{ $m->created_at->format('d/m/Y') }}</td>
                        <td>{{ $m->detail }}</td>
                        <td>{{ $m->debe ? number_format($m->debe,2) : '' }}</td>
                        <td>{{ $m->haber ? number_format($m->haber,2) : '' }}</td>
                        <td>{{ number_format($m->saldo_acumulado,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-3">
                {{ $movimientos->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>

@endsection
