@extends('layouts.empresa')

@section('content')

<div class="container-fluid">

    {{-- ========================= --}}
    {{-- TITULO + BOTONES --}}
    {{-- ========================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">
            📊 Ranking de Productos
        </h3>

        <div>

            {{-- PDF --}}
            <a href="{{ route('empresa.reportes.export.pdf') }}"
               class="btn btn-danger btn-sm">
                PDF
            </a>

            {{-- EXCEL --}}
            <a href="{{ route('empresa.reportes.export.excel') }}"
               class="btn btn-success btn-sm">
                Excel
            </a>

            {{-- VOLVER --}}
            <a href="{{ route('empresa.reportes.panel') }}"
               class="btn btn-secondary btn-sm">
                Volver
            </a>

        </div>
    </div>


    {{-- ========================= --}}
    {{-- TABLA --}}
    {{-- ========================= --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Total $</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $totalCantidad = 0;
                        $totalDinero = 0;
                    @endphp

                    @foreach($rankingProductos as $i => $item)

                        @php
                            $totalLinea = $item->total * $item->price;
                            $totalCantidad += $item->total;
                            $totalDinero += $totalLinea;
                        @endphp

                        <tr>
                            {{-- Ranking --}}
                            <td>{{ $i + 1 }}</td>

                            {{-- Nombre producto --}}
                            <td>{{ $item->producto_nombre }}</td>

                            {{-- Cantidad --}}
                            <td class="text-end">
                                {{ number_format($item->total, 0, ',', '.') }}
                            </td>

                            {{-- Precio --}}
                            <td class="text-end">
                                $ {{ number_format($item->price, 2, ',', '.') }}
                            </td>

                            {{-- Total por producto --}}
                            <td class="text-end fw-bold">
                                $ {{ number_format($totalLinea, 2, ',', '.') }}
                            </td>
                        </tr>

                    @endforeach

                </tbody>

                {{-- ========================= --}}
                {{-- TOTALES --}}
                {{-- ========================= --}}
                <tfoot class="table-light">
                    <tr>
                        <th colspan="2" class="text-end">Totales</th>

                        <th class="text-end">
                            {{ number_format($totalCantidad, 0, ',', '.') }}
                        </th>

                        <th></th>

                        <th class="text-end">
                            $ {{ number_format($totalDinero, 2, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>

</div>

@endsection
