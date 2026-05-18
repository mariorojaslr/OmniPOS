@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4 pb-5">

    {{-- CABECERA COMPACTA --}}
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">🩺 Rendición Médica & Liquidaciones</h2>
            <p class="text-muted small mb-0">Auditoría financiera de prestaciones, honorarios y saldos de obras sociales.</p>
        </div>
        <div class="badge bg-light text-dark border p-2 shadow-sm">
            <i class="bi bi-calendar3 me-1"></i> Año Fiscal: {{ now()->year }}
        </div>
    </div>

    {{-- KPI WIDGETS --}}
    <div class="row g-3 mb-4">
        <!-- Facturado Clínica -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="x-small fw-bold text-uppercase opacity-75">Total Facturado</span>
                        <i class="bi bi-hospital fs-4 opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1">${{ number_format($facturadoClinica, 2, ',', '.') }}</h3>
                    <p class="x-small mb-0 opacity-75">Consultas médicas procesadas</p>
                </div>
            </div>
        </div>
        <!-- Comisiones Médicas -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="x-small fw-bold text-uppercase opacity-75">Honorarios Médicos</span>
                        <i class="bi bi-person-badge fs-4 opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1">${{ number_format($totalComisiones, 2, ',', '.') }}</h3>
                    <p class="x-small mb-0 opacity-75">Comisión liquidada a profesionales</p>
                </div>
            </div>
        </div>
        <!-- Margen Clínica -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="x-small fw-bold text-uppercase opacity-75">Margen Neto Clínica</span>
                        <i class="bi bi-cash-stack fs-4 opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1">${{ number_format($margenClinica, 2, ',', '.') }}</h3>
                    <p class="x-small mb-0 opacity-75">Rentabilidad real de consultorios</p>
                </div>
            </div>
        </div>
        <!-- Deuda Obras Sociales -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-dark bg-white border-start border-danger border-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="x-small fw-bold text-muted text-uppercase">Cuentas por Cobrar OS</span>
                        <i class="bi bi-file-medical-fill fs-4 text-danger opacity-75"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">${{ number_format($deudaObrasSociales, 2, ',', '.') }}</h3>
                    <p class="x-small text-muted mb-0">Total reclamado a obras sociales</p>
                </div>
            </div>
        </div>
    </div>

    {{-- GRÁFICA MENSUAL --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-bar-chart-fill text-primary me-2"></i> Evolución de Facturación & Comisiones ({{ now()->year }})</h5>
            <p class="text-muted small mb-0">Comparativa mensual de ingresos clínicos frente a egresos por honorarios profesionales.</p>
        </div>
        <div class="card-body px-4 pb-4">
            <div style="height: 280px; position: relative;">
                <canvas id="chartRendimiento"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- TABLA 1: LIQUIDACIONES POR PROFESIONAL --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill text-primary me-2"></i> Rendimiento por Profesional</h5>
                    <p class="text-muted small mb-0">Liquidación y volumen de consultas atendidas por cada médico.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="x-small text-muted text-uppercase">
                                    <th class="ps-4 py-3">Médico / Profesional</th>
                                    <th class="py-3 text-center">Nº Consultas</th>
                                    <th class="py-3 text-end">Total Facturado</th>
                                    <th class="py-3 text-end">A Liquidar</th>
                                    <th class="py-3 text-end pe-4">Margen Clínica</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($liquidacionesProfesionales as $lp)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $lp->profesional_nombre }}</div>
                                            <div class="text-muted x-small font-monospace">{{ $lp->profesional_email }}</div>
                                        </td>
                                        <td class="text-center fw-bold">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                                {{ $lp->cant_turnos }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">${{ number_format($lp->total_bruto, 2, ',', '.') }}</td>
                                        <td class="text-end fw-bold text-warning">${{ number_format($lp->total_comision, 2, ',', '.') }}</td>
                                        <td class="text-end fw-bold text-success pe-4">${{ number_format($lp->margen_clinica, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No hay datos de consultas disponibles para liquidar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLA 2: DEUDAS POR OBRA SOCIAL --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-receipt text-primary me-2"></i> Cuentas a Cobrar Obras Sociales</h5>
                    <p class="text-muted small mb-0">Aranceles pendientes de liquidación por convenios médicos.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="x-small text-muted text-uppercase">
                                    <th class="ps-4 py-3">Obra Social</th>
                                    <th class="py-3 text-center">Nº Prestaciones</th>
                                    <th class="py-3 text-end pe-4">Total Reclamado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deudasObrasSociales as $dos)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $dos->obra_social_nombre }}</div>
                                            <div class="text-muted x-small font-monospace">CUIT: {{ $dos->obra_social_cuit ?: '—' }}</div>
                                        </td>
                                        <td class="text-center fw-bold">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                                {{ $dos->cant_prestaciones }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-danger pe-4">${{ number_format($dos->total_reclamado, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            No hay prestaciones registradas bajo obras sociales.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.7rem; }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('chartRendimiento').getContext('2d');
        
        // Mapear meses en español
        const nombresMeses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
        
        // Rellenar array de 12 meses para evitar saltos en la gráfica si faltan meses en la DB
        const dataTotal = Array(12).fill(0);
        const dataComision = Array(12).fill(0);
        
        @foreach($rendimientoMensual as $rm)
            dataTotal[{{ $rm->mes - 1 }}] = {{ $rm->total }};
            dataComision[{{ $rm->mes - 1 }}] = {{ $rm->comision }};
        @endforeach

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nombresMeses,
                datasets: [
                    {
                        label: 'Facturación Bruta ($)',
                        data: dataTotal,
                        backgroundColor: 'rgba(2, 132, 199, 0.85)',
                        borderColor: '#0284c7',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: 'Comisiones Profesionales ($)',
                        data: dataComision,
                        backgroundColor: 'rgba(245, 158, 11, 0.85)',
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { size: 11, weight: 'bold' },
                            boxWidth: 12
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: {
                            font: { size: 10 },
                            callback: function(value) { return '$' + value.toLocaleString('es-AR'); }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endsection
