@extends('layouts.empresa')

@section('content')

<div class="container-fluid">

    {{-- ============================= --}}
    {{-- TITULO --}}
    {{-- ============================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">
            📊 Reporte General de la Empresa
        </h3>

        <div>
            <a href="{{ route('empresa.reportes.export.pdf') }}"
               class="btn btn-danger btn-sm">
                PDF
            </a>

            <a href="{{ route('empresa.reportes.export.excel') }}"
               class="btn btn-success btn-sm">
                Excel
            </a>

            <a href="{{ route('empresa.reportes.panel') }}"
               class="btn btn-secondary btn-sm">
                Volver
            </a>
        </div>

    </div>


    {{-- ============================= --}}
    {{-- RANKING PRODUCTOS --}}
    {{-- ============================= --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">
            🛒 Ranking de Productos
        </div>

        <div class="card-body">

            <table class="table table-sm table-striped">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>

                <tbody>

                @php
                    $totalCantidad = 0;
                    $totalDinero = 0;
                @endphp

                @foreach($rankingProductos as $i => $item)

                    @php
                        $linea = $item->total * $item->price;
                        $totalCantidad += $item->total;
                        $totalDinero += $linea;
                    @endphp

                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->producto_nombre }}</td>

                        <td class="text-end">
                            {{ number_format($item->total,0,',','.') }}
                        </td>

                        <td class="text-end">
                            $ {{ number_format($item->price,2,',','.') }}
                        </td>

                        <td class="text-end fw-bold">
                            $ {{ number_format($linea,2,',','.') }}
                        </td>
                    </tr>

                @endforeach

                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="2" class="text-end">Totales</th>

                        <th class="text-end">
                            {{ number_format($totalCantidad,0,',','.') }}
                        </th>

                        <th></th>

                        <th class="text-end">
                            $ {{ number_format($totalDinero,2,',','.') }}
                        </th>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>


    {{-- ============================= --}}
    {{-- RANKING CLIENTES --}}
    {{-- ============================= --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            👥 Ranking de Clientes
        </div>

        <div class="card-body">

            <table class="table table-sm table-striped">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th class="text-end">Compras</th>
                        <th class="text-end">Total Gastado</th>
                        <th class="text-end">Promedio</th>
                    </tr>
                </thead>

                <tbody>

                @php
                    $totalCompras = 0;
                    $totalGastado = 0;
                @endphp

                @foreach($rankingClientes as $i => $item)

                    @php
                        $totalCompras += $item->total_compras;
                        $totalGastado += $item->total_gastado;
                    @endphp

                    <tr>
                        <td>{{ $i + 1 }}</td>

                        <td>
                            {{ $item->cliente_nombre ?? 'Consumidor final' }}
                        </td>

                        <td class="text-end">
                            {{ number_format($item->total_compras,0,',','.') }}
                        </td>

                        <td class="text-end fw-bold">
                            $ {{ number_format($item->total_gastado,2,',','.') }}
                        </td>

                        <td class="text-end">
                            $ {{ number_format($item->promedio_compra,2,',','.') }}
                        </td>
                    </tr>

                @endforeach

                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="2" class="text-end">Totales</th>

                        <th class="text-end">
                            {{ number_format($totalCompras,0,',','.') }}
                        </th>

                        <th class="text-end">
                            $ {{ number_format($totalGastado,2,',','.') }}
                        </th>

                        <th></th>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>

</div>

@endsection
