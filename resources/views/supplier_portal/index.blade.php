<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Proveedor | {{ $empresa->nombre_comercial }}</title>
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .table-premium { border-radius: 1rem; overflow: hidden; background: white; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); border-radius: 50px; font-weight: 700; padding: 0.6rem 1.5rem; }
        .badge-status { font-size: 0.75rem; font-weight: 700; padding: 0.5rem 1rem; border-radius: 50px; }
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
            <p class="opacity-75 mb-0">Portal para Proveedores</p>
        </div>
    </div>

    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="card card-stats p-4 text-center h-100">
                    <span class="text-muted text-uppercase x-small fw-bold mb-2">Le debemos al día de hoy</span>
                    <h2 class="fw-bold {{ $saldo > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format(abs($saldo), 2, ',', '.') }}
                    </h2>
                    @if($saldo > 0)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill py-1 px-3 mt-2 mx-auto" style="width: fit-content;">DEUDA PENDIENTE</span>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill py-1 px-3 mt-2 mx-auto" style="width: fit-content;">AL DÍA</span>
                    @endif
                </div>
            </div>
            <div class="col-md-12 col-lg-8 d-flex align-items-center justify-content-end no-print">
                <button class="btn btn-primary shadow-sm px-5" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> IMPRIMIR ESTADO
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-white p-4 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list-alt me-2 text-primary"></i> Historial de Movimientos</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="x-small fw-bold text-muted text-uppercase">
                            <th class="ps-4">Fecha</th>
                            <th>Concepto</th>
                            <th class="text-end">Debe (+)</th>
                            <th class="text-end">Haber (-)</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-4 no-print">Acción</th>
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
                            </td>
                            <td class="text-end fw-bold text-danger">
                                {{ $m->type == 'debit' ? '$' . number_format($m->amount, 2, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-bold text-success">
                                {{ $m->type == 'credit' ? '$' . number_format($m->amount, 2, ',', '.') : '-' }}
                            </td>
                            <td class="text-center">
                                @if($m->type == 'debit')
                                    @if($m->paid)
                                        <span class="badge-status bg-success bg-opacity-10 text-success">PAGADO</span>
                                    @else
                                        <span class="badge-status bg-danger bg-opacity-10 text-danger">PENDIENTE</span>
                                    @endif
                                @else
                                    <span class="badge-status bg-info bg-opacity-10 text-info">ORDEN PAGO</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 no-print">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $m->id }}">
                                    VER <i class="fas fa-chevron-down ms-1 small"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="collapse-{{ $m->id }}">
                            <td colspan="6" class="p-0 border-0">
                                <div class="bg-light p-4 shadow-inner">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="x-small fw-bold text-uppercase text-muted mb-3">Vínculos de Pago</h6>
                                            @forelse($m->imputaciones as $imp)
                                                <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 border mb-2 shadow-sm border-start border-4 border-success">
                                                    <div>
                                                        <div class="small fw-bold text-dark">OP #{{ $imp->ordenPago->numero_orden ?? '?' }}</div>
                                                        <div class="x-small text-muted">{{ $imp->created_at->format('d/m/Y') }}</div>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="small fw-bold text-success">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-muted small">No hay pagos aplicados aún.</p>
                                            @endforelse
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
            &copy; {{ date('Y') }} {{ $empresa->nombre_comercial }} | MultiPOS
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
