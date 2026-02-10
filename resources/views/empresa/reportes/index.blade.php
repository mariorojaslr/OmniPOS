@extends('layouts.empresa')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold">Reportes del negocio</h1>
    <p class="text-muted mb-0">Información comercial para toma de decisiones</p>
</div>

<div class="row g-4">

    {{-- RANKING PRODUCTOS --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <h5 class="fw-bold">Ranking ventas por producto</h5>
                <p class="text-muted">Top artículos más vendidos</p>

                <a href="{{ route('empresa.reportes.ranking.productos') }}"
                   class="btn btn-primary btn-sm">
                   Generar PDF
                </a>
            </div>
        </div>
    </div>

    {{-- RANKING CLIENTES --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <h5 class="fw-bold">Ranking ventas por cliente</h5>
                <p class="text-muted">Clientes que más compran</p>

                <a href="{{ route('empresa.reportes.ranking.clientes') }}"
                   class="btn btn-primary btn-sm">
                   Generar PDF
                </a>
            </div>
        </div>
    </div>

    {{-- VENTAS POR FECHA --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <h5 class="fw-bold">Listado de ventas</h5>
                <p class="text-muted">Filtrar por rango de fechas</p>

                <form method="GET" action="{{ route('empresa.reportes.ventas.form') }}">
                    <button class="btn btn-primary btn-sm">
                        Filtrar y generar PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
