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
        body { background: #fdfdfd; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1e293b; font-size: 0.85rem; }
        
        .portal-header {
            background: #fff;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 2px solid #f1f5f9;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-logo { max-height: 30px; margin-right: 1rem; }
        .header-info { border-left: 1px solid #e2e8f0; padding-left: 1rem; }
        .header-title { font-size: 0.9rem; font-weight: 800; margin: 0; color: #000; }
        .header-subtitle { font-size: 0.7rem; color: #64748b; margin: 0; font-weight: 600; }

        .card-stats {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.6rem 1rem !important;
            background: #fff;
        }
        .stat-label { font-size: 0.6rem; text-transform: uppercase; font-weight: 700; color: #64748b; display: block; }
        .stat-value { font-size: 1.1rem; font-weight: 800; margin: 0; }
        
        .table-report { width: 100%; border-collapse: collapse; background: white; }
        .table-report thead th { 
            background: #f8fafc; 
            color: #475569; 
            font-size: 0.65rem; 
            font-weight: 800; 
            padding: 0.5rem 0.75rem; 
            border: 1px solid #e2e8f0;
            text-transform: uppercase;
        }
        .table-report tbody td { 
            padding: 0.4rem 0.75rem; 
            border: 1px solid #f1f5f9;
            font-size: 0.8rem;
        }
        .table-report tfoot td {
            background: #f8fafc;
            font-weight: 800;
            padding: 0.6rem 0.75rem;
            border-top: 2px solid #cbd5e1;
        }
        
        .btn-mini { font-size: 0.65rem; padding: 0.2rem 0.6rem; border-radius: 4px; font-weight: 700; }
        .badge-status { font-size: 0.6rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 3px; }

        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .table-report { border: 1px solid #000; }
            .table-report th, .table-report td { border: 1px solid #000; }
        }
    </style>
</head>
<body>

    <div class="portal-header no-print">
        <div class="d-flex align-items-center w-100">
            @if($empresa->config->logo)
                <img src="{{ asset('storage/' . $empresa->config->logo) }}" alt="Logo" class="header-logo">
            @endif
            <div class="header-info me-auto">
                <h1 class="header-title">{{ $empresa->nombre_comercial }}</h1>
                <p class="header-subtitle text-uppercase">Portal del Proveedor: <span class="text-dark fw-bold">{{ $supplier->name }}</span></p>
            </div>
            <div class="no-print">
                <button class="btn btn-sm btn-dark fw-bold px-3" onclick="window.print()" style="font-size: 0.7rem;">
                    <i class="fas fa-print me-1"></i> IMPRIMIR ESTADO
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 px-lg-4">
        <div class="row g-2 mb-3">
            <div class="col-md-2">
                <div class="card card-stats">
                    <span class="stat-label">Le Debemos</span>
                    <div class="stat-value {{ $saldo > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format(abs($saldo), 2, ',', '.') }}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-stats">
                    <span class="stat-label">Comprobantes</span>
                    <div class="stat-value text-dark">{{ $movimientos->total() }}</div>
                </div>
            </div>
        </div>

        <div class="border rounded-2 overflow-hidden shadow-sm bg-white">
            <div class="bg-light p-2 border-bottom px-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="font-size: 0.75rem;">DETALLE DE COMPRAS Y COMPOSICIÓN DE PAGOS</h6>
            </div>
            <div class="table-responsive">
                <table class="table-report align-middle">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Fecha</th>
                            <th>Descripción</th>
                            <th class="text-end" style="width: 120px;">Total</th>
                            <th class="text-end" style="width: 120px;">Pagado</th>
                            <th class="text-end" style="width: 120px;">Saldo</th>
                            <th class="text-center" style="width: 100px;">Estado</th>
                            <th class="text-end pe-4 no-print" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $sumTotal = 0;
                            $sumPagado = 0;
                            $sumSaldo = 0;
                        @endphp
                        @foreach($movimientos as $m)
                        @php 
                            $pagado = $m->amount - $m->pending_amount;
                            $sumTotal += (float)$m->amount;
                            $sumPagado += (float)$pagado;
                            $sumSaldo += (float)$m->pending_amount;
                        @endphp
                        <tr>
                            <td>{{ $m->created_at ? $m->created_at->format('d/m/Y') : '-' }}</td>
                            <td><span class="fw-bold">{{ $m->description }}</span></td>
                            <td class="text-end fw-bold text-secondary">${{ number_format($m->amount, 2, ',', '.') }}</td>
                            <td class="text-end text-success fw-bold">${{ number_format($pagado, 2, ',', '.') }}</td>
                            <td class="text-end text-danger fw-bold">${{ number_format($m->pending_amount, 2, ',', '.') }}</td>
                            <td class="text-center">
                                @if($m->paid)
                                    <span class="badge-status bg-success-subtle text-success border">CUBIERTO</span>
                                @elseif($pagado > 0)
                                    <span class="badge-status bg-warning-subtle text-warning border">PARCIAL</span>
                                @else
                                    <span class="badge-status bg-danger-subtle text-danger border">PENDIENTE</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 no-print">
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="btn btn-outline-secondary btn-mini" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $m->id }}">PAGOS</button>
                                    @if($m->reference_type == 'App\Models\Purchase')
                                    <a href="{{ route('supplier.portal.invoice.pdf', ['token' => request()->route('token'), 'id' => $m->reference_id]) }}" class="btn btn-dark btn-mini" target="_blank">FACTURA</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr class="collapse" id="collapse-{{ $m->id }}">
                            <td colspan="7" class="p-0 bg-light">
                                <div class="p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="x-small fw-bold text-muted text-uppercase mb-2">Órdenes de Pago vinculadas:</div>
                                            @forelse($m->imputaciones as $imp)
                                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom x-small">
                                                    <span>OP #{{ optional($imp->ordenPago)->numero_orden ? str_pad($imp->ordenPago->numero_orden, 6, '0', STR_PAD_LEFT) : '???' }} ({{ $imp->created_at ? $imp->created_at->format('d/m/Y') : '-' }})</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold text-success">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</span>
                                                        @if($imp->orden_pago_id)
                                                        <a href="{{ route('supplier.portal.payment.pdf', ['token' => request()->route('token'), 'id' => $imp->orden_pago_id]) }}" target="_blank" class="text-dark no-print"><i class="fas fa-print"></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-muted x-small italic">Sin pagos vinculados aún.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end">TOTALES DEL REPORTE:</td>
                            <td class="text-end">${{ number_format($sumTotal, 2, ',', '.') }}</td>
                            <td class="text-end text-success">${{ number_format($sumPagado, 2, ',', '.') }}</td>
                            <td class="text-end text-danger">${{ number_format($sumSaldo, 2, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if($movimientos->hasPages())
                <div class="p-2 no-print border-top">{{ $movimientos->links() }}</div>
            @endif
        </div>
        <div class="text-center text-muted x-small py-4 no-print">&copy; {{ date('Y') }} {{ $empresa->nombre_comercial }} | MultiPOS</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
