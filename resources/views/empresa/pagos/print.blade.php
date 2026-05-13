<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo #{{ str_pad($recibo->numero_recibo, 8, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 1cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            color: #333; 
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        .invoice-box { width: 100%; position: relative; min-height: 27cm; }

        /* Encabezado */
        .header-table { width: 100%; border-bottom: 1px solid #000; margin-bottom: 10px; table-layout: fixed; }
        .header-table td { vertical-align: top; padding: 5px; }
        
        .company-name { font-size: 16pt; font-weight: bold; color: #000; margin-bottom: 2px; }
        .company-data { font-size: 8pt; color: #444; }

        /* Letra X Central */
        .doc-type-box {
            position: absolute;
            top: 0;
            left: 50%;
            margin-left: -20px;
            width: 40px;
            height: 40px;
            border: 1px solid #000;
            background: #fff;
            text-align: center;
            z-index: 10;
        }
        .doc-type-letter { font-size: 24pt; font-weight: bold; line-height: 35px; }
        .doc-type-code { font-size: 6pt; font-weight: bold; margin-top: -5px; display: block; }

        .doc-title { font-size: 14pt; font-weight: bold; margin: 0; text-transform: uppercase; }
        .doc-num { font-size: 11pt; font-weight: bold; margin: 5px 0; }

        .section-bar { 
            background: #f0f0f0; 
            padding: 5px 10px; 
            border: 1px solid #ccc; 
            font-weight: bold; 
            font-size: 9pt; 
            margin: 10px 0;
            text-transform: uppercase;
        }

        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { background: #333; color: #fff; text-align: left; padding: 7px 10px; font-size: 8.5pt; }
        .items-table td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 9pt; }
        
        .text-right { text-align: right; }
        .total-row { font-size: 14pt; font-weight: bold; color: #000; }

        .footer-container { position: absolute; bottom: 0; width: 100%; border-top: 2px solid #000; padding-top: 10px; text-align: center; }
        .signatures { margin-top: 50px; margin-bottom: 50px; }
        .sig-box { display: inline-block; width: 40%; border-top: 1px solid #000; margin: 0 4%; padding-top: 5px; font-size: 8pt; }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="doc-type-box">
        <span class="doc-type-letter">X</span>
        <span class="doc-type-code">RECIBO</span>
    </div>

    <table class="header-table">
        <tr>
            <td width="48%">
                <div class="company-name">{{ $recibo->empresa->nombre_comercial ?? $recibo->empresa->razon_social }}</div>
                <div class="company-data">
                    <p>{{ $recibo->empresa->direccion_fiscal ?? '-' }}</p>
                    <p>CUIT: {{ $recibo->empresa->arca_cuit ?? $recibo->empresa->cuit }}</p>
                    <p>Cond. IVA: {{ $recibo->empresa->condicion_iva ?? 'Responsable Inscripto' }}</p>
                </div>
            </td>
            <td width="4%"></td>
            <td width="48%" style="text-align: right;">
                <h1 class="doc-title">RECIBO DE PAGO</h1>
                <div class="doc-num">N&deg; {{ str_pad($recibo->numero_recibo, 8, '0', STR_PAD_LEFT) }}</div>
                <div class="company-data">
                    <p><strong>Fecha:</strong> {{ $recibo->fecha ? $recibo->fecha->format('d/m/Y') : $recibo->created_at->format('d/m/Y') }}</p>
                    <p><strong>Cajero:</strong> {{ $recibo->user->name ?? 'Sistema' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-bar">Datos del Cliente</div>
    <div style="padding: 10px;">
        <strong>Recibimos de:</strong> {{ $recibo->client->name ?? 'Cliente Desconocido' }}<br>
        <strong>DNI/CUIT:</strong> {{ $recibo->client->document ?? 'N/A' }}<br>
        <strong>Concepto:</strong> Pago de cuenta corriente / Facturas varias.
    </div>

    <div class="section-bar">Detalle de Valores</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Método de Pago</th>
                <th>Referencia / Banco</th>
                <th class="text-right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recibo->pagos as $p)
            <tr>
                <td>{{ $p->metodo_pago }}</td>
                <td>{{ $p->referencia ?? 'N/A' }} {{ $p->banco ? '- '.$p->banco : '' }}</td>
                <td class="text-right">${{ number_format($p->monto, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right" style="padding-top:15px"><strong>TOTAL RECIBIDO:</strong></td>
                <td class="text-right total-row" style="padding-top:15px">${{ number_format($recibo->monto_total, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div class="sig-box">Firma del Cliente</div>
        <div class="sig-box">Firma y Sello Comercial</div>
    </div>

    <div class="footer-container">
        <div style="font-size: 8pt; color: #666;">
            MultiPOS - Sistema de Gestión Comercial | Documento no válido como factura
        </div>
    </div>
</div>

</body>
</html>
