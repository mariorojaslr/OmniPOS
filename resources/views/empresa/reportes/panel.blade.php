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

    {{-- ================= REPORTES OPERATIVOS ================= --}}
    <h5 class="text-secondary fw-bold mb-3">Informes Operativos de Lujo</h5>

    <div class="row g-3">
        @php
            $informes = [
                ['titulo' => 'Ventas por Vendedor', 'ruta' => 'empresa.reportes.vendedores', 'icon' => '👤', 'color' => 'primary'],
                ['titulo' => 'Caja Diaria', 'ruta' => 'empresa.reportes.caja_diaria', 'icon' => '💰', 'color' => 'success'],
                ['titulo' => 'Rentabilidad', 'ruta' => 'empresa.reportes.rentabilidad', 'icon' => '📈', 'color' => 'info'],
                ['titulo' => 'Margen por Producto', 'ruta' => 'empresa.reportes.margen', 'icon' => '🏷️', 'color' => 'warning'],
                ['titulo' => 'Ventas por Categoría', 'ruta' => 'empresa.reportes.categorias', 'icon' => '📁', 'color' => 'danger'],
                ['titulo' => 'Clientes Frecuentes', 'ruta' => 'empresa.reportes.clientes_frecuentes', 'icon' => '⭐', 'color' => 'primary'],
                ['titulo' => 'Ventas por Hora', 'ruta' => 'empresa.reportes.por_hora', 'icon' => '⏰', 'color' => 'secondary'],
                ['titulo' => 'Análisis Mensual', 'ruta' => 'empresa.reportes.analisis_mensual', 'icon' => '📅', 'color' => 'dark'],
            ];
        @endphp

        @foreach($informes as $inf)
            <div class="col-md-3">
                <a href="{{ route($inf['ruta']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center h-100 hover-luxury">
                        <div class="card-body">
                            <div class="fs-2 mb-2">{{ $inf['icon'] }}</div>
                            <h6 class="fw-bold text-dark mb-0">{{ $inf['titulo'] }}</h6>
                            <div class="small text-primary mt-2">VER INFORME →</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <style>
        .hover-luxury { transition: all 0.3s; }
        .hover-luxury:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; 
            background: #f8f9ff !important;
        }
    </style>

</div>

@endsection
