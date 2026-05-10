<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Pago #{{ str_pad($orden->numero_orden, 8, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #fff;
            font-size: 14px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            flex: 1;
        }
        .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .document-type {
            background: #0f172a;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border-radius: 4px;
        }
        .document-type h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }
        .document-type p {
            margin: 0;
            font-size: 12px;
            opacity: 0.8;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }
        .info-block h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #f8fafc;
            text-align: left;
            padding: 12px;
            font-size: 11px;
            text-transform: uppercase;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .text-right { text-align: right; }
        .fw-bold { font-weight: 700; }
        .total-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-left: auto;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total-final {
            border-top: 2px solid #e2e8f0;
            margin-top: 10px;
            padding-top: 10px;
            font-size: 18px;
            font-weight: 800;
        }
        .signatures {
            margin-top: 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 100px;
            text-align: center;
        }
        .sig-line {
            border-top: 1px solid #cbd5e1;
            padding-top: 10px;
            font-size: 12px;
            color: #64748b;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .container { padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f8fafc; padding: 15px; border-bottom: 1px solid #e2e8f0; text-align: center;">
        <button onclick="window.print()" style="background: #0f172a; color: #fff; border: none; padding: 10px 25px; border-radius: 6px; font-weight: 600; cursor: pointer;">
            IMPRIMIR ORDEN DE PAGO
        </button>
    </div>

    <div class="container">
        <div class="header">
            <div class="company-info">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="max-height: 60px; margin-bottom: 15px;">
                @else
                    <div class="company-name">{{ $empresa->nombre_comercial }}</div>
                @endif
                <div style="font-size: 12px; color: #64748b;">
                    {{ $empresa->razon_social }}<br>
                    CUIT: {{ $empresa->cuit }}<br>
                    {{ $empresa->direccion_fiscal }}
                </div>
            </div>
            <div class="document-type">
                <p>ORDEN DE PAGO</p>
                <h2>{{ $orden->numero_orden }}</h2>
                <p>Fecha: {{ $orden->fecha ? \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') : $orden->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-block">
                <h3>Proveedor</h3>
                <div class="fw-bold" style="font-size: 16px;">{{ $orden->supplier->name }}</div>
                <div>CUIT: {{ $orden->supplier->cuit ?? 'N/A' }}</div>
                <div>{{ $orden->supplier->direccion ?? '' }}</div>
            </div>
            <div class="info-block">
                <h3>Detalles de la Operación</h3>
                <div>Cajero: {{ $orden->user->name ?? 'Sistema' }}</div>
                <div>Estado: <span class="fw-bold">COMPLETADO</span></div>
            </div>
        </div>

        <h3>Detalle de Pagos Realizados</h3>
        <table>
            <thead>
                <tr>
                    <th>Método de Pago</th>
                    <th>Referencia / Detalle</th>
                    <th class="text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orden->pagos as $p)
                <tr>
                    <td class="text-uppercase fw-bold">{{ str_replace('_', ' ', $p->metodo_pago) }}</td>
                    <td>
                        {{ $p->referencia ?? 'Pago registrado' }}
                        @if($p->metodo_pago == 'cheque' && $p->cheque)
                            <br><small class="text-muted">Banco: {{ $p->cheque->banco }} | Nº: {{ $p->cheque->numero }} | Vto: {{ $p->cheque->fecha_pago->format('d/m/Y') }}</small>
                        @endif
                    </td>
                    <td class="text-right fw-bold">${{ number_format($p->monto, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($orden->imputaciones->count() > 0)
        <h3>Comprobantes Cancelados / Imputados</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Descripción del Comprobante</th>
                    <th class="text-right">Monto Aplicado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orden->imputaciones as $imp)
                <tr>
                    <td>{{ $imp->created_at->format('d/m/Y') }}</td>
                    <td>{{ $imp->ledger->description ?? 'Compra' }}</td>
                    <td class="text-right fw-bold">${{ number_format($imp->monto_aplicado, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="total-box">
            <div class="total-row">
                <span>Subtotal</span>
                <span>${{ number_format($orden->monto_total, 2, ',', '.') }}</span>
            </div>
            <div class="total-row total-final">
                <span>TOTAL</span>
                <span>${{ number_format($orden->monto_total, 2, ',', '.') }}</span>
            </div>
        </div>

        <div class="signatures">
            <div class="sig-line">Firma y Aclaración Autorizado</div>
            <div class="sig-line">Firma y Aclaración Proveedor</div>
        </div>

        <div class="footer">
            Documento generado internamente por MultiPOS. No válido como factura fiscal.
        </div>
    </div>
</body>
</html>
