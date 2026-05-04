<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Cliente | {{ $empresa->nombre_comercial }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: {{ $empresa->config->color_primario ?? '#3563E9' }};
            --secondary-color: {{ $empresa->config->color_secundario ?? '#2F55D4' }};
        }
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
        .portal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 1rem;
            border-bottom-left-radius: 2rem;
            border-bottom-right-radius: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-stats {
            border: none;
            border-radius: 1.5rem;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .card-stats:hover { transform: translateY(-5px); }
        .table-premium { border-radius: 1rem; overflow: hidden; background: white; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); border-radius: 50px; font-weight: 700; padding: 0.6rem 1.5rem; }
        .btn-primary:hover { background-color: var(--secondary-color); border-color: var(--secondary-color); }
        .badge-status { font-size: 0.75rem; font-weight: 700; padding: 0.5rem 1rem; border-radius: 50px; }
        .imputation-card { border-left: 4px solid #198754; background: #fdfdfd; margin-bottom: 0.5rem; }
        @media print {
            .no-print { display: none !important; }
            .portal-header { border-radius: 0; padding: 1rem; background: white !important; color: black !important; }
        }
    </style>
</head>
<body>

    <div class="portal-header mb-5 no-print">
        <div class="container text-center">
            @if($empresa->config->logo)
                <img src="{{ asset('storage/' . $empresa->config->logo) }}" alt="Logo" class="mb-3" style="max-height: 80px; filter: brightness(0) invert(1);">
            @endif
            <h1 class="fw-bold mb-1">{{ $empresa->nombre_comercial }}</h1>
            <p class="opacity-75 mb-0">Portal de Autogestión para Clientes</p>
        </div>
    </div>

    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="card card-stats p-4 text-center h-100">
                    <span class="text-muted text-uppercase x-small fw-bold mb-2">Mi Saldo Actual</span>
                    <h2 class="fw-bold {{ $saldo > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format(abs($saldo), 2, ',', '.') }}
                    </h2>
                    @if($saldo > 0)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill py-1 px-3 mt-2 mx-auto" style="width: fit-content;">PENDIENTE DE PAGO</span>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill py-1 px-3 mt-2 mx-auto" style="width: fit-content;">AL DÍA</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card card-stats p-4 text-center h-100">
                    <span class="text-muted text-uppercase x-small fw-bold mb-2">Comprobantes Pendientes</span>
                    <h2 class="fw-bold text-dark">{{ $deudas->count() }}</h2>
                    <p class="text-muted small mb-0">Facturas que registran deuda parcial o total</p>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 d-flex align-items-center justify-content-center no-print">
                <button class="btn btn-primary shadow-sm px-5" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> IMPRIMIR ESTADO
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-white p-4 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-history me-2 text-primary"></i> Mis Movimientos Recientes</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="x-small fw-bold text-muted text-uppercase">
                            <th class="ps-4">Fecha</th>
                            <th>Concepto</th>
                            <th class="text-end">Importe</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-4 no-print">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $m)
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $m->created_at->format('d/m/Y') }}</div>
                                <div class="x-small text-muted">{{ $m->created_at->format('H:i') }} hs</div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $m->description }}</span>
                                @if($m->type == 'debit' && $m->imputaciones->count() > 0)
                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 x-small ms-2">
                                        <i class="fas fa-check-double me-1"></i>Con Aplicaciones
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-bold {{ $m->type == 'debit' ? 'text-danger' : 'text-success' }}">
                                {{ $m->type == 'debit' ? '+' : '-' }} ${{ number_format($m->amount, 2, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($m->type == 'debit')
                                    @if($m->paid)
                                        <span class="badge-status bg-success bg-opacity-10 text-success border border-success border-opacity-25">PAGADA</span>
                                    @elseif($m->pending_amount < $m->amount && $m->pending_amount > 0)
                                        <span class="badge-status bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">PAGO PARCIAL</span>
                                    @else
                                        <span class="badge-status bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">IMPAGA</span>
                                    @endif
                                @else
                                    <span class="badge-status bg-info bg-opacity-10 text-info border border-info border-opacity-25">COBRO / RECIBO</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 no-print">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $m->id }}">
                                    VER <i class="fas fa-chevron-down ms-1 small"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="collapse-{{ $m->id }}">
                            <td colspan="5" class="p-0 border-0">
                                <div class="bg-light p-4 shadow-inner">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="x-small fw-bold text-uppercase text-muted mb-3">
                                                {{ $m->type == 'debit' ? 'Detalle de Pagos Aplicados' : 'Facturas que cubrió este cobro' }}
                                            </h6>
                                            @if($m->type == 'debit')
                                                @forelse($m->imputaciones as $imp)
                                                    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 border mb-2 imputation-card shadow-sm">
                                                        <div>
                                                            <div class="small fw-bold text-dark">Recibo #{{ str_pad($imp->recibo->numero_recibo ?? 0, 8, '0', STR_PAD_LEFT) }}</div>
                                                            <div class="x-small text-muted">{{ $imp->recibo->created_at->format('d/m/Y') }}</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="small fw-bold text-success">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-muted small">No hay pagos aplicados aún a este comprobante.</p>
                                                @endforelse
                                            @else
                                                @php $imputacionesRecibo = $m->reference ? $m->reference->imputaciones : collect([]); @endphp
                                                @forelse($imputacionesRecibo as $imp)
                                                    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 border mb-2 shadow-sm">
                                                        <span class="small fw-semibold">{{ $imp->ledger->description }}</span>
                                                        <span class="small fw-bold text-danger">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</span>
                                                    </div>
                                                @empty
                                                    <p class="text-muted small">Este pago aún no ha sido imputado a ninguna factura específica.</p>
                                                @endforelse
                                            @endif
                                        </div>
                                        <div class="col-md-6 text-end">
                                            @if($m->type == 'debit' && !$m->paid)
                                                <div class="alert alert-warning border-0 rounded-4 p-4 text-start shadow-sm mb-0">
                                                    <h6 class="fw-bold mb-2">¿Cómo pagar este saldo?</h6>
                                                    <p class="small mb-0">Comuníquese con nosotros para informarnos su pago o solicitar los datos de transferencia. Saldo pendiente: <strong>${{ number_format($m->pending_amount, 2, ',', '.') }}</strong></p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($movimientos->hasPages())
                <div class="card-footer bg-white p-4 no-print">
                    {{ $movimientos->links() }}
                </div>
            @endif
        </div>

        <div class="text-center text-muted small pb-5 no-print">
            &copy; {{ date('Y') }} {{ $empresa->nombre_comercial }} | Desarrollado por MultiPOS
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
