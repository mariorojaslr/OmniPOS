<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Presupuesto {{ $presupuesto->numero }}</title>
    <style>
        @page { margin: 1cm; size: A4; }
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

        /* Letra P Central */
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
        .doc-type-code { font-size: 5pt; font-weight: bold; margin-top: -5px; display: block; }

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
        .text-center { text-align: center; }
        .total-row { font-size: 14pt; font-weight: bold; color: #000; border-top: 1px solid #333; }

        .footer-container { position: absolute; bottom: 0; width: 100%; border-top: 2px solid #000; padding-top: 10px; }
        .obs-box { font-size: 8pt; color: #666; padding: 10px; background: #f9f9f9; border: 1px dashed #ccc; }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="doc-type-box">
        <span class="doc-type-letter">P</span>
        <span class="doc-type-code">PRESUPUESTO</span>
    </div>

    <table class="header-table">
        <tr>
            <td width="48%">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" style="max-height: 80px; margin-bottom: 5px;">
                @else
                    <div class="company-name">{{ $empresa->nombre_comercial ?? $empresa->razon_social }}</div>
                @endif
                <div class="company-data">
                    <p>{{ $empresa->direccion_fiscal ?? '-' }}</p>
                    <p>Tel: {{ $empresa->telefono ?? '-' }}</p>
                    <p>CUIT: {{ $empresa->arca_cuit ?? $empresa->cuit }}</p>
                </div>
            </td>
            <td width="4%"></td>
            <td width="48%" style="text-align: right;">
                <h1 class="doc-title">PRESUPUESTO</h1>
                <div class="doc-num">N&deg; {{ $presupuesto->numero }}</div>
                <div class="company-data">
                    <p><strong>Fecha:</strong> {{ $presupuesto->fecha->format('d/m/Y') }}</p>
                    <p><strong>Vencimiento:</strong> {{ $presupuesto->vencimiento->format('d/m/Y') }}</p>
                    <p><strong>Emitido por:</strong> {{ $presupuesto->user->name }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-bar">Datos del Cliente</div>
    <div style="padding: 10px;">
        <table width="100%">
            <tr>
                <td><strong>Señor(es):</strong> {{ $presupuesto->client->name ?? 'Consumidor Final' }}</td>
                <td style="text-align: right;"><strong>CUIT/DNI:</strong> {{ $presupuesto->client->document ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Domicilio:</strong> {{ $presupuesto->client->address ?? '-' }}</td>
                <td style="text-align: right;"><strong>Cond. IVA:</strong> {{ $presupuesto->client->condicion_iva ?? 'Consumidor Final' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-bar">Detalle de Cotización</div>
    <table class="items-table">
        <thead>
            <tr>
                <th width="10%" class="text-center">Cant.</th>
                <th width="60%">Descripción</th>
                <th width="15%" class="text-right">Unitario</th>
                <th width="15%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presupuesto->items as $item)
            <tr>
                <td class="text-center">{{ number_format($item->cantidad, 2, ',', '.') }}</td>
                <td>
                    <strong>{{ $item->product->name ?? 'Producto Manual' }}</strong><br>
                    <span style="font-size: 8pt; color: #666;">{{ $item->descripcion }}</span>
                </td>
                <td class="text-right">${{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
                <td class="text-right">${{ number_format($item->subtotal, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="float: right; width: 35%; margin-top: 20px;">
        <table width="100%">
            <tr>
                <td style="padding-bottom: 5px;">Subtotal:</td>
                <td style="text-align: right;">${{ number_format($presupuesto->subtotal, 2, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td style="padding-top: 5px;">TOTAL:</td>
                <td style="text-align: right; padding-top: 5px;">${{ number_format($presupuesto->total, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer-container">
        <div class="obs-box">
            <strong>OBSERVACIONES:</strong><br>
            {!! nl2br(e($presupuesto->notas ?: 'Precios sujetos a cambio sin previo aviso.')) !!}
        </div>
        <div style="font-size: 8pt; color: #888; text-align: center; margin-top: 15px;">
            MultiPOS Commercial Suite | DOCUMENTO NO VÁLIDO COMO FACTURA FISCAL
        </div>
    </div>
</div>

</body>
</html>
