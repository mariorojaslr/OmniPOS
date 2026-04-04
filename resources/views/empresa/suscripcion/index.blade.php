@extends('layouts.empresa')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📦 Mi Suscripción MultiPOS</h4>
    </div>

    <div class="row">
        <!-- ESTADO ACTUAL -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h6 class="text-muted text-uppercase fw-bold mb-3">Estado del Plan</h6>
                    
                    @php
                        $isPaid = true;
                        $today = now();
                        $vencimiento = $empresa->fecha_vencimiento ? \Carbon\Carbon::parse($empresa->fecha_vencimiento) : null;
                        
                        if(!$vencimiento) {
                            $isPaid = false;
                        } elseif ($today->copy()->startOfDay()->gt($vencimiento)) {
                            $isPaid = false;
                        }
                    @endphp

                    @if($isPaid)
                        <div class="display-1 text-success mb-2"><i class="bi bi-shield-check"></i></div>
                        <h4 class="text-success fw-bold">Activo y al Día</h4>
                        <p class="text-muted small mt-2">
                            Membresía cubierta hasta el:<br>
                            <span class="fs-5 fw-bold text-dark">{{ $vencimiento ? $vencimiento->format('d/m/Y') : 'N/A' }}</span>
                        </p>
                    @else
                        <div class="display-1 text-danger mb-2"><i class="bi bi-exclamation-triangle"></i></div>
                        <h4 class="text-danger fw-bold">Pago Pendiente</h4>
                        <p class="text-muted small mt-2">
                            El servicio se encuentra vencido desde:<br>
                            <span class="fs-5 fw-bold text-dark">{{ $vencimiento ? $vencimiento->format('d/m/Y') : 'N/A' }}</span>
                        </p>
                        
                        <button class="btn btn-warning fw-bold w-100 mt-2 rounded-pill" onclick="alert('Funcionalidad de pasarela en desarrollo. Por favor contacta al soporte (WhatsApp) para informar transferencia.')">
                            <i class="bi bi-credit-card me-2"></i> ABONAR AHORA
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- HISTORIAL DE PAGOS / FACTURAS -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-receipt me-2"></i>Historial de Pagos y Comprobantes</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Detalles</th>
                                    <th class="text-end">Comprobante</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagos as $pago)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $pago->created_at->format('d/m/Y') }}</span><br>
                                            <small class="text-muted">{{ $pago->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            @if($pago->method == 'cortesia')
                                                <span class="badge bg-info text-dark">Gratis</span>
                                            @else
                                                <span class="fw-bold text-success">${{ number_format($pago->amount, 2, ',', '.') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-uppercase small fw-bold text-muted">{{ $pago->method }}</span>
                                        </td>
                                        <td class="small">
                                            {{ $pago->notes ?? 'Acreditación de Software' }}
                                        </td>
                                        <td class="text-end">
                                            <button onclick="imprimirRecibo('{{ $pago->id }}')" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-printer"></i> Imprimir
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 mb-2 d-block"></i>
                                            Aún no hay registros de pagos en tu historial.
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

<script>
    function imprimirRecibo(id) {
        // Un simple mockup de impresión por ahora (se puede hacer un PDF real despues)
        let w = window.open('', '_blank');
        w.document.write(`
            <html>
            <head>
                <title>Comprobante de Pago Software</title>
                <style>
                    body { font-family: Arial; padding: 40px; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
                    .details { line-height: 1.6; }
                    .total { font-size: 24px; font-weight: bold; margin-top: 30px; border-top: 2px solid #eee; padding-top: 20px; }
                </style>
            </head>
            <body>
                 <div class="header">
                     <h2>RECIBO OFICIAL DE SERVICIO</h2>
                     <p>Software MultiPOS by GentePiola</p>
                 </div>
                 <div class="details">
                    <p><strong>Recibo Nro:</strong> #MP-${id.padStart(5, '0')}</p>
                    <p><strong>Titular (Empresa):</strong> {{ $empresa->nombre_comercial }}</p>
                    <p><strong>Concepto:</strong> Renovación de Suscripción Sistema Gestión SaaS Mensual</p>
                 </div>
                 <div class="total text-center">
                    <p>¡PAGO ACREDITADO CON ÉXITO!</p>
                 </div>
            </body>
            </html>
        `);
        w.document.close();
        w.focus();
        setTimeout(() => { w.print(); }, 500);
    }
</script>

@endsection
