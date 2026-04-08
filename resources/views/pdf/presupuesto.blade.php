<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Presupuesto {{ $presupuesto->numero }}</title>
    <style>
        @page { margin: 0.5cm; size: A4; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 8.5pt; color: #000; line-height: 1.35; padding: 0; margin: 0; background: #fff; }
        .invoice-box { width: 100%; border: 1.2px solid #333; min-height: 27.5cm; position: relative; background: #fff; }
        
        /* HEADER */
        .header-table { width: 100%; border-bottom: 2.5px solid #1f6feb; border-collapse: collapse; }
        .header-table td { vertical-align: top; padding: 15px 12px; }
        
        .header-center { width: 66px; text-align: center; border: 2.5px solid #1f6feb; border-top: none; background: #fff; position: absolute; left: 50%; margin-left: -33px; top: 0; height: 60px; z-index: 1000; }
        .header-center .letter { font-size: 34pt; font-weight: bold; line-height: 52px; display: block; color: #1f6feb; }
        .header-center .cod { font-size: 6.5pt; font-weight: bold; display: block; margin-top: -6px; padding-bottom: 2px; color: #333; }
        
        .company-name { font-size: 16pt; font-weight: 900; color: #1f6feb; text-transform: uppercase; margin-bottom: 3px; }
        .company-data p { margin: 0; font-size: 8.5pt; color: #1a1a1a; line-height: 1.25; }
        
        .doc-title { font-size: 18pt; font-weight: 900; margin: 0 0 5px 0; text-align: right; color: #333; letter-spacing: 1px; }
        .doc-num { font-size: 14pt; font-weight: 900; text-align: right; margin-bottom: 8px; color: #1f6feb; }
        .doc-data p { margin: 0 0 4px 0; font-size: 9pt; text-align: right; }

        /* SECCIONES TRANSVERSALES */
        .section-bar { background: #f8faff; border-top: 1.5px solid #1f6feb; border-bottom: 1.5px solid #1f6feb; padding: 10px 15px; margin-top: -1px; }
        .label { font-weight: bold; color: #1f6feb; text-transform: uppercase; font-size: 8pt; margin-right: 5px; }

        /* TABLA DE ITEMS */
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { background: #1f6feb; color: #fff; padding: 8px; text-transform: uppercase; font-size: 8pt; text-align: center; border: 1px solid #1f6feb; }
        .items-table td { padding: 8px; font-size: 9pt; color: #333; border-bottom: 1px solid #eee; vertical-align: middle; }
        
        .product-img { width: 55px; height: 55px; border-radius: 4px; border: 1px solid #eee; object-fit: cover; }
        .no-img { width: 55px; height: 55px; background: #f5f5f5; border: 1px solid #eee; border-radius: 4px; display: block; font-size: 7pt; color: #999; text-align: center; line-height: 55px; }

        /* TOTALES */
        .totals-container { margin-top: 15px; padding-right: 15px; }
        .totals-table { float: right; width: 35%; border-collapse: collapse; }
        .totals-table td { padding: 6px 8px; font-size: 10pt; }
        .total-row { background: #1f6feb; color: #fff; font-weight: bold; font-size: 12pt !important; }

        /* FOOTER */
        .footer-container { position: absolute; bottom: 0; left: 0; width: 100%; padding: 25px; box-sizing: border-box; }
        .footer-separator { border-top: 1.5px solid #1f6feb; margin-bottom: 15px; width: 100%; }
        .obs-box { float: left; width: 60%; padding: 10px; background: #fcfcfc; border: 1px dashed #1f6feb; min-height: 80px; }
        .bank-box { float: right; width: 35%; padding: 5px; font-size: 8pt; color: #555; }

        .attribution { text-align: center; font-size: 7pt; color: #888; width: 100%; position: absolute; bottom: 8px; font-style: italic; }
        .clear { clear: both; }

        .company-logo { max-width: 200px; max-height: 80px; margin-bottom: 8px; display: block; }
    </style>
</head>
<body>

<div class="invoice-box">
    
    <div class="header-center">
        <span class="letter">P</span>
        <span class="cod">No Válido Fac</span>
    </div>

    <table class="header-table">
        <tr>
            <td width="48%">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" class="company-logo">
                @endif
                <div class="company-name">{{ $empresa->nombre_comercial }}</div>
                <div class="company-data">
                    <p>{{ $empresa->razon_social ?? '' }}</p>
                    <p>{{ $empresa->direccion_fiscal ?? 'Dirección no disponible' }}</p>
                    <p>Tel: {{ $empresa->telefono ?? '-' }}</p>
                    <p>Email: {{ $empresa->email ?? '-' }}</p>
                </div>
            </td>
            <td width="48%" style="text-align: right;">
                <h1 class="doc-title">PRESUPUESTO</h1>
                <div class="doc-num">{{ $presupuesto->numero }}</div>
                <div class="doc-data">
                    <p><strong>Fecha:</strong> {{ $presupuesto->fecha->format('d/m/Y') }}</p>
                    <p><strong>Vencimiento:</strong> {{ $presupuesto->vencimiento->format('d/m/Y') }}</p>
                    <p><strong>Emitido por:</strong> {{ $presupuesto->user->name }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-bar">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="55%"><span class="label">Para:</span> <span style="font-size: 10pt; font-weight: bold;">{{ strtoupper($presupuesto->client->name ?? 'Consumidor Final') }}</span></td>
                <td width="45%"><span class="label">CUIT/DNI:</span> {{ $presupuesto->client->document ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 4px;"><span class="label">Domicilio:</span> {{ $presupuesto->client->address ?? '-' }}</td>
                <td style="padding-top: 4px;"><span class="label">Cond. IVA:</span> {{ $presupuesto->client->condicion_iva ?? 'Consumidor Final' }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="8%">Imagen</th>
                <th width="52%" style="text-align: left;">Descripción del Producto / Servicio</th>
                <th width="12%">Cant.</th>
                <th width="14%">P. Unit.</th>
                <th width="14%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presupuesto->items as $item)
            <tr>
                <td style="text-align: center;">
                    @if($item->image_base64)
                        <img src="{{ $item->image_base64 }}" class="product-img">
                    @else
                        <div class="no-img">Sin Foto</div>
                    @endif
                </td>
                <td>
                    <span style="font-weight: bold; font-size: 10pt;">{{ $item->product->name ?? 'Producto Manual' }}</span><br>
                    <span style="color: #666;">{{ $item->descripcion }}</span>
                </td>
                <td style="text-align: center;">{{ number_format($item->cantidad, 2, ',', '.') }}</td>
                <td style="text-align: right;">$ {{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
                <td style="text-align: right; font-weight: bold;">$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-container">
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td style="text-align: right;">$ {{ number_format($presupuesto->subtotal, 2, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL FINAL</td>
                <td style="text-align: right;">$ {{ number_format($presupuesto->total, 2, ',', '.') }}</td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>

    <div class="footer-container">
        <div class="footer-separator"></div>

        <div class="obs-box">
            <span class="label" style="font-size: 7pt;">CONDICIONES Y OBSERVACIONES:</span><br>
            <div style="font-size: 8.5pt; margin-top: 5px; color: #444;">
                {!! nl2br(e($presupuesto->notas ?: 'Precios sujetos a cambio sin previo aviso. Validez del presupuesto: ' . $presupuesto->fecha->diffInDays($presupuesto->vencimiento) . ' días.')) !!}
            </div>
        </div>

        <div class="bank-box">
            <strong>DATOS DE PAGO:</strong><br>
            @if($empresa->cbu)
                CBU: {{ $empresa->cbu }}<br>
                Alias: {{ $empresa->alias ?? '-' }}<br>
            @endif
            Por favor, informar pagos a: <br>
            {{ $empresa->email ?? 'el contacto comercial' }}
        </div>
        
        <div class="clear"></div>

        <div class="attribution">
            MultiPOS Commercial Suite | Este presupuesto no es válido como factura fiscal.
        </div>
    </div>

</div>

</body>
</html>
