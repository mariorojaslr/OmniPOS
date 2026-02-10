@extends('layouts.empresa')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">📊 Panel de Reportes</h2>
    <p class="text-muted">Elegí qué información querés analizar</p>

    <div class="row g-4">

        {{-- ================= RANKING PRODUCTOS ================= --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h5>Ranking Productos</h5>
                    <p class="small text-muted">Ventas por producto</p>

                    <a href="{{ route('empresa.reportes.productos') }}"
                       class="btn btn-primary btn-sm">
                        Ver
                    </a>
                </div>
            </div>
        </div>

        {{-- ================= RANKING CLIENTES ================= --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h5>Ranking Clientes</h5>
                    <p class="small text-muted">Clientes que más compran</p>

                    <a href="{{ route('empresa.reportes.clientes') }}"
                       class="btn btn-success btn-sm">
                        Ver
                    </a>
                </div>
            </div>
        </div>

        {{-- ================= VENTAS POR FECHA ================= --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h5>Ventas por Fecha</h5>
                    <p class="small text-muted">Filtrado por período</p>

                    <a href="{{ route('empresa.reportes.ventas_fecha') }}"
                       class="btn btn-dark btn-sm">
                        Ver
                    </a>
                </div>
            </div>
        </div>

        {{-- ================= REPORTE EMPRESA ================= --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <h5>Reporte General</h5>
                    <p class="small text-muted">Vista completa</p>

                    <a href="{{ route('empresa.reportes.empresa') }}"
                       class="btn btn-warning btn-sm">
                        Ver
                    </a>
                </div>
            </div>
        </div>

    </div>

    <hr class="my-4">

    {{-- ================= FUTUROS REPORTES ================= --}}
    <h5 class="text-muted mb-3">Próximamente</h5>

    <div class="row g-3">

        @foreach([
            'Ventas por vendedor',
            'Caja diaria',
            'Rentabilidad',
            'Margen por producto',
            'Ventas por categoría',
            'Clientes frecuentes',
            'Ventas por hora',
            'Análisis mensual'
        ] as $futuro)

            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">{{ $futuro }}</small>
                        <div class="badge bg-secondary">Próximamente</div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>

</div>

@endsection
