@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-3 px-4">

    {{-- CABECERA COMPACTA --}}
    <div class="mb-3 d-flex justify-content-between align-items-end">
        <div>
            <h4 class="fw-bold text-dark mb-0">Centro de Inteligencia</h4>
            <p class="text-muted small mb-0">Análisis estratégico en tiempo real.</p>
        </div>
        <div class="badge bg-light text-dark border p-2">
            <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('d M, Y') }}
        </div>
    </div>

    {{-- BLOQUE 1: KPIs MICRO --}}
    <div class="row g-2 mb-3">
        <!-- Ventas Mes -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-primary text-white">
                <div class="card-body p-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="x-small fw-bold opacity-75">VENTAS MES</span>
                        <i class="fas fa-shopping-cart x-small opacity-50"></i>
                    </div>
                    <h4 class="fw-bold mb-0">${{ number_format($ventasMes, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <!-- Compras Mes -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-danger text-white">
                <div class="card-body p-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="x-small fw-bold opacity-75">COMPRAS MES</span>
                        <i class="fas fa-truck-loading x-small opacity-50"></i>
                    </div>
                    <h4 class="fw-bold mb-0">${{ number_format($comprasMes, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <!-- Deuda Clientes -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-warning border-3">
                <div class="card-body p-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="x-small fw-bold text-muted">A COBRAR</span>
                        <i class="fas fa-user-clock x-small text-warning opacity-50"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">${{ number_format($deudaClientes, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <!-- Deuda Proveedores -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-success border-3">
                <div class="card-body p-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="x-small fw-bold text-muted">A PAGAR</span>
                        <i class="bi bi-bank x-small text-success opacity-50"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">${{ number_format($deudaProveedores, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- BLOQUE 2: GRÁFICAS MEDIANAS (3 POR FILA) --}}
    <div class="row g-3 mb-4">
        <!-- Ventas (Línea) -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-0 pt-3 px-3">
                    <h6 class="fw-bold mb-0"><i class="fas fa-chart-line text-primary me-2"></i> Tendencia (15d)</h6>
                </div>
                <div class="card-body p-2">
                    <canvas id="chartVentas" style="height: 180px;"></canvas>
                </div>
            </div>
        </div>
        <!-- Rubros (Torta) -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-0 pt-3 px-3">
                    <h6 class="fw-bold mb-0"><i class="fas fa-chart-pie text-info me-2"></i> Top Rubros</h6>
                </div>
                <div class="card-body p-2">
                    <canvas id="chartRubros" style="height: 180px;"></canvas>
                </div>
            </div>
        </div>
        <!-- Morosidad (Barra) -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-0 pt-3 px-3">
                    <h6 class="fw-bold mb-0"><i class="fas fa-users-slash text-warning me-2"></i> Morosidad</h6>
                </div>
                <div class="card-body p-2 d-flex flex-column justify-content-center">
                    <div class="text-center">
                        <h2 class="text-warning fw-bold mb-0">{{ $topCategorias->count() }}</h2>
                        <p class="x-small text-muted mb-0">Rubros analizados</p>
                    </div>
                    <hr class="my-2">
                    <div class="small fw-bold text-center">ANÁLISIS DE FLUJO</div>
                    <p class="x-small text-muted text-center italic mt-1">Eficiencia de cobro: 85%</p>
                </div>
            </div>
        </div>
    </div>

    {{-- BLOQUE 3: LISTADOS Y REPORTES (RESTAURACIÓN TOTAL) --}}
    
    <!-- SECCIÓN: ANÁLISIS ESTRATÉGICO -->
    <div class="mb-2 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0 x-small text-uppercase ls-1 opacity-75">Análisis Estratégico & Rankings</h6>
    </div>
    <div class="row g-2 mb-4">
        @php
            $analiticos = [
                ['titulo' => 'Venta por Día', 'ruta' => 'empresa.reportes.ventas_fecha', 'icon' => '🗓️', 'bg' => 'primary'],
                ['titulo' => 'Ranking Productos', 'ruta' => 'empresa.reportes.productos', 'icon' => '📦', 'bg' => 'primary'],
                ['titulo' => 'Ranking Clientes', 'ruta' => 'empresa.reportes.clientes', 'icon' => '👥', 'bg' => 'primary'],
                ['titulo' => 'Ventas por Vendedor', 'ruta' => 'empresa.reportes.vendedores', 'icon' => '👤', 'bg' => 'primary'],
                ['titulo' => 'Rentabilidad', 'ruta' => 'empresa.reportes.rentabilidad', 'icon' => '📈', 'bg' => 'primary'],
                ['titulo' => 'Ventas por Hora', 'ruta' => 'empresa.reportes.por_hora', 'icon' => '⏰', 'bg' => 'primary'],
                ['titulo' => 'Análisis Mensual', 'ruta' => 'empresa.reportes.analisis_mensual', 'icon' => '📅', 'bg' => 'primary'],
                ['titulo' => 'Reporte General', 'ruta' => 'empresa.reportes.empresa', 'icon' => '🏢', 'bg' => 'primary'],
            ];
        @endphp

        @foreach($analiticos as $a)
            <div class="col-md-3">
                <a href="{{ route($a['ruta']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 hover-bg transition-all border-start border-primary border-3">
                        <div class="card-body p-2 px-3 d-flex align-items-center">
                            <div class="me-2">{{ $a['icon'] }}</div>
                            <div class="x-small fw-bold text-dark text-uppercase">{{ $a['titulo'] }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- SECCIÓN: LISTADOS Y AUDITORÍA -->
    <div class="mb-2 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0 x-small text-uppercase ls-1 opacity-75">Listados Maestros & Auditoría</h6>
    </div>
    <div class="row g-2">
        @php
            $listados = [
                ['titulo' => 'Listado de Cheques', 'ruta' => 'empresa.listados.cheques', 'icon' => '📂'],
                ['titulo' => 'Listado Clientes GPS', 'ruta' => 'empresa.listados.clientes', 'icon' => '📍'],
                ['titulo' => 'Listado Artículos', 'ruta' => 'empresa.listados.articulos', 'icon' => '📋'],
                ['titulo' => 'Caja Diaria / Auditoría', 'ruta' => 'empresa.reportes.caja_diaria', 'icon' => '💰'],
            ];
        @endphp

        @foreach($listados as $l)
            <div class="col-md-3">
                <a href="{{ route($l['ruta']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 hover-bg transition-all bg-light">
                        <div class="card-body p-2 px-3 d-flex align-items-center">
                            <div class="me-2">{{ $l['icon'] }}</div>
                            <div class="x-small fw-bold text-muted text-uppercase">{{ $l['titulo'] }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

</div>

<style>
    .x-small { font-size: 0.65rem; }
    .ls-1 { letter-spacing: 1px; }
    .hover-bg:hover { background: #f0f4ff !important; transform: scale(1.02); transition: all 0.2s; }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfica de Líneas: Ventas
    const ctxVentas = document.getElementById('chartVentas').getContext('2d');
    new Chart(ctxVentas, {
        type: 'line',
        data: {
            labels: [ @foreach($ventasLast15 as $v) "{{ date('d/m', strtotime($v->fecha)) }}", @endforeach ],
            datasets: [{
                label: 'Ventas ($)',
                data: [ @foreach($ventasLast15 as $v) {{ $v->total }}, @endforeach ],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // Gráfica de Torta: Rubros
    const ctxRubros = document.getElementById('chartRubros').getContext('2d');
    new Chart(ctxRubros, {
        type: 'doughnut',
        data: {
            labels: [ @foreach($topCategorias as $cat) "{{ $cat->cat }}", @endforeach ],
            datasets: [{
                data: [ @foreach($topCategorias as $cat) {{ $cat->monto }}, @endforeach ],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } }
            },
            cutout: '70%'
        }
    });
</script>
@endsection
