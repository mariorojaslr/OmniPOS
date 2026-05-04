<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo #{{ str_pad($recibo->numero_recibo, 8, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .receipt-info div {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 40px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            padding: 0 40px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            text-align: center;
            padding-top: 5px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: right; padding: 10px;">
        <button onclick="window.print()" style="padding: 8px 16px; cursor: pointer; background: #007bff; color: #fff; border: none; border-radius: 4px;">Imprimir</button>
        <button onclick="window.close()" style="padding: 8px 16px; cursor: pointer; background: #6c757d; color: #fff; border: none; border-radius: 4px;">Cerrar</button>
    </div>

    <div class="container">
        <div class="header">
            @if(isset($logoBase64) && $logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo" style="max-height: 80px; margin-bottom: 10px;">
            @endif
            <div class="company-name">{{ $empresa->nombre_comercial ?? 'Nuestra Empresa' }}</div>
            <div>{{ $empresa->cuit ?? '' }} | {{ $empresa->condicion_iva ?? '' }}</div>
            <div class="receipt-title">RECIBO OFICIAL X</div>
            <div>Nº: {{ str_pad($recibo->numero_recibo, 8, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="receipt-info">
            <div>
                <strong>Recibimos de:</strong><br>
                {{ $recibo->client->name ?? 'Cliente Desconocido' }}<br>
                DNI/CUIT: {{ $recibo->client->document ?? 'N/A' }}
            </div>
            <div class="text-right">
                <strong>Fecha:</strong> {{ $recibo->fecha ? $recibo->fecha->format('d/m/Y') : $recibo->created_at->format('d/m/Y') }}<br>
                <strong>Cajero:</strong> {{ $recibo->user->name ?? 'Sistema' }}
            </div>
        </div>

        <div>A la suma de <strong>Pesos ${{ number_format($recibo->monto_total, 2, ',', '.') }}</strong>, según el siguiente detalle valorizado:</div>
        <br>

        <table>
            <thead>
                <tr>
                    <th>Método de Pago</th>
                    <th>Referencia</th>
                    <th class="text-right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @if($recibo->pagos->count() > 0)
                    @foreach($recibo->pagos as $p)
                    <tr>
                        <td>{{ $p->metodo_pago }}</td>
                        <td>{{ $p->referencia ?? 'N/A' }}</td>
                        <td class="text-right">${{ number_format($p->monto, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $recibo->metodo_pago }}</td>
                        <td>{{ $recibo->referencia ?? 'N/A' }}</td>
                        <td class="text-right">${{ number_format($recibo->monto_total, 2, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Recibido</td>
                    <td class="text-right">${{ number_format($recibo->monto_total, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="signatures">
            <div class="signature-line">
                Firma Cliente
            </div>
            <div class="signature-line">
                Firma Recipiendario
            </div>
        </div>

        <div class="footer">
            Generado por MultiPOS - Documento no válido como factura
        </div>
    </div>
</body>
</html>
