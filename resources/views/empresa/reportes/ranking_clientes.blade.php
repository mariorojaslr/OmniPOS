@extends('layouts.empresa')

@section('content')

<div class="container-fluid">

    {{-- ========================= --}}
    {{-- TITULO + BOTONES --}}
    {{-- ========================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">
            👥 Ranking de Clientes
        </h3>

        <div>
            <a href="{{ route('empresa.reportes.export.pdf') }}"
               class="btn btn-danger btn-sm">PDF</a>

            <a href="{{ route('empresa.reportes.export.excel') }}"
               class="btn btn-success btn-sm">Excel</a>

            <a href="{{ route('empresa.reportes.panel') }}"
               class="btn btn-secondary btn-sm">Volver</a>
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
                        <th>Cliente</th>
                        <th class="text-end">Compras</th>
                        <th class="text-end">Total Gastado</th>
                        <th class="text-end">Promedio Compra</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $totalCompras = 0;
                        $totalDinero = 0;
                    @endphp

                    @foreach($rankingClientes as $i => $item)

                        @php
                            $totalCompras += $item->total_compras;
                            $totalDinero += $item->total_gastado ?? 0;
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>

                            <td>
                                {{ $item->cliente_nombre ?? 'Consumidor final' }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->total_compras, 0, ',', '.') }}
                            </td>

                            <td class="text-end">
                                $ {{ number_format($item->total_gastado ?? 0, 2, ',', '.') }}
                            </td>

                            <td class="text-end">
                                $ {{ number_format($item->promedio_compra ?? 0, 2, ',', '.') }}
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
                            {{ number_format($totalCompras, 0, ',', '.') }}
                        </th>

                        <th class="text-end">
                            $ {{ number_format($totalDinero, 2, ',', '.') }}
                        </th>

                        <th></th>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>

</div>

@endsection
