@extends('layouts.empresa')

@section('content')

<div class="container-fluid">

    {{-- ======================= --}}
    {{-- TITULO + BOTONES --}}
    {{-- ======================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">
            📅 Ventas por Fecha
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


    {{-- ======================= --}}
    {{-- TABLA --}}
    {{-- ======================= --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th class="text-end">Ventas</th>
                        <th class="text-end">Total $</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $totalVentas = 0;
                        $totalDinero = 0;
                    @endphp

                    @foreach($ventas as $v)

                        @php
                            $totalVentas += $v->cantidad;
                            $totalDinero += $v->total;
                        @endphp

                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y') }}
                            </td>

                            <td class="text-end">
                                {{ number_format($v->cantidad, 0, ',', '.') }}
                            </td>

                            <td class="text-end fw-bold">
                                $ {{ number_format($v->total, 2, ',', '.') }}
                            </td>
                        </tr>

                    @endforeach

                </tbody>

                {{-- ======================= --}}
                {{-- TOTALES --}}
                {{-- ======================= --}}
                <tfoot class="table-light">
                    <tr>
                        <th class="text-end">Totales</th>

                        <th class="text-end">
                            {{ number_format($totalVentas, 0, ',', '.') }}
                        </th>

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
