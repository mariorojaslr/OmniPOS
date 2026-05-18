@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4 print-container">
    
    <!-- ACCIONES DE CABECERA (Se ocultan al imprimir) -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap no-print">
        <div>
            <h4 class="mb-1 text-primary fw-bold"><i class="bi bi-receipt-cutoff me-2"></i> Detalle de Liquidación Renglón por Renglón</h4>
            <p class="text-muted small mb-0">Detalle de costos facturables para presentación a la obra social.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.hospitalization.billing') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver a Liquidaciones
            </a>
            <a href="{{ route('empresa.hospitalization.billing.excel', $hospitalization->id) }}" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-file-earmark-excel me-2"></i> Exportar Excel
            </a>
            <button onclick="window.print()" class="btn btn-primary fw-bold shadow-sm">
                <i class="bi bi-printer me-2"></i> Imprimir Liquidación
            </button>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL DE LIQUIDACIÓN -->
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-5 bg-white">
        <!-- Cabecera de la Clínica -->
        <div class="card-header bg-gradient-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
            <div class="row align-items-center">
                <div class="col-md-6 col-sm-12">
                    <h3 class="fw-bold mb-0 text-white tracking-wide">MEDPLUS HOSPITAL</h3>
                    <p class="x-small mb-0 text-success opacity-75 fw-bold text-uppercase">Facturación de Prestaciones de Internación</p>
                </div>
                <div class="col-md-6 col-sm-12 text-md-end text-sm-start mt-md-0 mt-3">
                    <h5 class="fw-bold mb-1 text-white">LIQUIDACIÓN N° #INT-{{ str_pad($hospitalization->id, 6, '0', STR_PAD_LEFT) }}</h5>
                    <p class="x-small mb-0 text-muted">Fecha Emisión: {{ now()->format('d/m/Y H:i') }} hs</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Información Paciente y Obra Social -->
            <div class="row g-4 mb-4">
                <div class="col-md-4 col-sm-12">
                    <div class="p-3 bg-light rounded-3 h-100 border border-secondary border-opacity-10">
                        <div class="x-small fw-bold text-uppercase text-muted mb-2"><i class="bi bi-person-fill text-primary me-1"></i> Paciente Internado</div>
                        <h6 class="fw-bold text-dark mb-1">{{ $hospitalization->patient?->name }}</h6>
                        <div class="small text-muted">DNI: {{ $hospitalization->patient?->document ?? 'Sin DNI' }}</div>
                        <div class="small text-muted">Email: {{ $hospitalization->patient?->email ?? 'Sin Email' }}</div>
                        <div class="small text-muted">Tel: {{ $hospitalization->patient?->phone ?? 'Sin Teléfono' }}</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="p-3 bg-light rounded-3 h-100 border border-secondary border-opacity-10">
                        <div class="x-small fw-bold text-uppercase text-muted mb-2"><i class="bi bi-shield-plus text-success me-1"></i> Obra Social / Cobertura</div>
                        <h6 class="fw-bold text-dark mb-1">{{ $hospitalization->patient?->obraSocial?->nombre ?? 'Particular / Sin Cobertura' }}</h6>
                        <div class="small text-muted">N° Afiliado: {{ $hospitalization->patient?->nro_afiliado ?? 'N/A' }}</div>
                        <div class="small text-muted">Plan: {{ $hospitalization->patient?->plan_obra_social ?? 'Básico' }}</div>
                        <div class="small text-muted">Estado: <span class="badge bg-success bg-opacity-10 text-success fw-bold small">Activo</span></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="p-3 bg-light rounded-3 h-100 border border-secondary border-opacity-10">
                        <div class="x-small fw-bold text-uppercase text-muted mb-2"><i class="bi bi-calendar-check text-warning me-1"></i> Periodo de Internación</div>
                        <div class="small text-muted"><strong>Fecha Ingreso:</strong> {{ $hospitalization->fecha_ingreso }}</div>
                        <div class="small text-muted"><strong>Fecha Egreso:</strong> {{ $hospitalization->fecha_egreso ?? 'Internación Activa' }}</div>
                        <div class="small text-muted"><strong>Estado Clínico:</strong> 
                            @if($hospitalization->estado === 'activo')
                                <span class="badge bg-danger bg-opacity-10 text-danger fw-bold small">Internado</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold small">Alta Médica</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Renglones -->
            <h5 class="fw-bold text-dark mb-3 mt-4 text-uppercase tracking-wider fs-6 border-bottom pb-2"><i class="bi bi-list-columns-reverse text-primary me-2"></i> Prestaciones, Insumos y Medicaciones Valoradas</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-4">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="py-3 px-3 small fw-bold">Fecha</th>
                            <th class="py-3 small fw-bold">Descripción Prestación / Concepto</th>
                            <th class="py-3 small fw-bold text-center">Tipo</th>
                            <th class="py-3 small fw-bold text-center">Cantidad</th>
                            <th class="py-3 small fw-bold text-end">Valor Unitario ($)</th>
                            <th class="py-3 px-3 small fw-bold text-end">Subtotal ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lineasFacturacion as $linea)
                            <tr>
                                <td class="py-3 px-3 small text-muted">{{ $linea['fecha'] }}</td>
                                <td class="py-3 text-dark fw-bold">{{ $linea['concepto'] }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-secondary bg-opacity-10 text-dark small px-3 py-1 rounded-pill">
                                        {{ $linea['tipo'] }}
                                    </span>
                                </td>
                                <td class="py-3 text-center fw-bold">{{ $linea['cantidad'] }}</td>
                                <td class="py-3 text-end text-muted">${{ number_format($linea['precio_unitario'], 2, ',', '.') }}</td>
                                <td class="py-3 px-3 text-end fw-extrabold text-dark">${{ number_format($linea['subtotal'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cierre de Facturación / Totales -->
            <div class="row justify-content-end mt-4">
                <div class="col-md-5 col-sm-12">
                    <div class="p-3 bg-light rounded-4 border border-secondary border-opacity-10">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Subtotal Clínico:</span>
                            <span class="fw-bold text-dark">${{ number_format($totalGeneral, 2, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Cargos Adicionales / IVA (0%):</span>
                            <span class="fw-bold text-dark">$0,00</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span class="fw-bold text-primary fs-6">TOTAL GENERAL:</span>
                            <span class="fw-extrabold text-success fs-4">${{ number_format($totalGeneral, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Firma y Aclaración Clínico -->
            <div class="row mt-5 pt-5 sign-area" style="display: none;">
                <div class="col-6 text-center">
                    <div style="border-top: 1px solid #000; width: 60%; margin: 0 auto; padding-top: 5px;" class="small">
                        <strong>Firma y Sello del Médico</strong>
                    </div>
                </div>
                <div class="col-6 text-center">
                    <div style="border-top: 1px solid #000; width: 60%; margin: 0 auto; padding-top: 5px;" class="small">
                        <strong>Firma del Administrador / Auditor</strong>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ESTILOS EXCLUSIVOS PARA IMPRESIÓN (PRINT CSS) -->
<style>
    @media print {
        /* Ocultar barra de navegación, menús de la app y botones */
        .sidebar, .navbar, .no-print, header, footer, .bottom-nav, .btn, .main-header, .alert, .card-header .btn {
            display: none !important;
        }

        /* Ajustar contenido para ocupar el ancho total */
        body {
            background-color: #fff !important;
            color: #000 !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 11pt !important;
        }

        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
            margin-bottom: 0 !important;
        }

        .card-header {
            background: #fff !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
            padding: 0 0 10px 0 !important;
        }

        .card-header * {
            color: #000 !important;
        }

        .bg-light {
            background-color: #fff !important;
            border: 1px solid #ddd !important;
        }

        .table-light {
            background-color: #f8f9fa !important;
        }

        .sign-area {
            display: flex !important;
        }
    }
</style>
@endsection
