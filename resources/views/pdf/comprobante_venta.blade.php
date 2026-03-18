<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Nº {{ $venta->numero_comprobante }}</title>
    <style>
        @page { margin: 1cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9px; line-height: 1.2; color: #000; background: #fff; }
        .clearfix { clear: both; }

        /* HEADER BOX */
        .header-box { width: 100%; border: 0.8pt solid #000; position: relative; height: 120px; }
        .header-left { width: 45%; float: left; padding: 10px; }
        .header-right { width: 45%; float: right; padding: 10px; text-align: left; }
        
        /* CUADRO LETRA CENTRAL */
        .letter-container {
            position: absolute;
            left: 50%;
            top: -1px;
            margin-left: -25px;
            width: 50px;
            height: 50px;
            border: 0.8pt solid #000;
            background: #fff;
            text-align: center;
            z-index: 100;
        }
        .letter-container .letter { font-size: 32px; font-weight: bold; display: block; line-height: 35px; }
        .letter-container .cod { font-size: 7px; display: block; margin-top: -2px; }

        .letter-divider {
            position: absolute;
            left: 50%;
            top: 49px;
            bottom: -1px;
            width: 0.8pt;
            background: #000;
        }

        /* LOGO / INFO EMISOR */
        .logo { max-width: 140px; max-height: 45px; margin-bottom: 5px; }
        .company-name { font-size: 14px; font-weight: bold; margin-bottom: 2px; }
        .company-info p { margin-bottom: 1px; }

        /* INFO COMPROBANTE */
        .doc-title { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .doc-info p { margin-bottom: 1px; font-weight: normal; }

        /* DATOS RECEPTOR */
        .client-box { width: 100%; border: 0.8pt solid #000; border-top: none; padding: 8px; }
        .client-row { width: 100%; margin-bottom: 3px; }
        .client-col { float: left; }
        .label { font-weight: bold; }

        /* TABLA ITEMS */
        .items-table { width: 100%; border-collapse: collapse; border: 0.8pt solid #000; border-top: none; margin-bottom: 5px; }
        .items-table th { border: 0.5pt solid #000; padding: 4px; background: #eee; font-weight: bold; text-align: center; }
        .items-table td { border: 0.5pt solid #000; padding: 4px; vertical-align: top; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* FOOTER / TOTALES */
        .footer-section { width: 100%; margin-top: 5px; }
        .obs-box { width: 60%; float: left; border: 0.8pt solid #000; padding: 8px; height: 60px; font-size: 8px; }
        .totals-table { width: 35%; float: right; border: 0.8pt solid #000; border-collapse: collapse; }
        .totals-table td { padding: 4px 8px; border: none; }
        .total-row { font-weight: bold; font-size: 11px; }

        /* AFIP PIE */
        .afip-footer { margin-top: 15px; width: 100%; border-top: 1pt solid #000; padding-top: 5px; position: relative; }
        .afip-logo { width: 80px; float: left; margin-right: 15px; }
        .afip-qr { width: 60px; float: left; margin-right: 15px; }
        .afip-info { float: left; font-size: 8px; line-height: 1.4; padding-top: 5px; }
        .cae-info { float: right; text-align: right; font-size: 9px; padding-top: 10px; }
        
        .footer-attribution { text-align: center; padding-top: 10px; font-size: 7px; color: #666; font-style: italic; position: absolute; bottom: 0; width: 100%; }
    </style>
</head>
<body>

    @php
        $letra = $venta->tipo_comprobante ?? 'X';
        $letra = strtoupper($letra);
        $es_oficial = in_array($letra, ['A', 'B', 'C', 'M']);
        $titulo_doc = $es_oficial ? 'FACTURA' : 'COMPROBANTE';
        if($letra != 'X' && !$es_oficial) $titulo_doc = 'TICKET';
        
        $cod_letra = [
            'A' => '01', 'B' => '06', 'C' => '11', 'M' => '51', 'X' => '--'
        ][$letra] ?? '--';

        // Logo handle
        $logoPath = '';
        if ($empresa->config && $empresa->config->logo) {
            $possible = storage_path('app/public/' . $empresa->config->logo);
            if (file_exists($possible)) { $logoPath = $possible; }
        }
    @endphp

    <div class="header-box">
        <div class="letter-container">
            <span class="letter">{{ $letra }}</span>
            <span class="cod">COD. {{ $cod_letra }}</span>
        </div>
        <div class="letter-divider"></div>

        <div class="header-left">
            @if($logoPath && file_exists($logoPath))
                <img src="{{ $logoPath }}" class="logo">
            @endif
            <div class="company-name">{{ strtoupper($empresa->nombre_comercial ?? $empresa->razon_social) }}</div>
            <div class="company-info">
                <p><strong>Razón Social:</strong> {{ $empresa->razon_social ?? $empresa->nombre_comercial }}</p>
                <p><strong>Domicilio:</strong> {{ $empresa->direccion_fiscal ?? '-' }}</p>
                <p><strong>Tel/Email:</strong> {{ $empresa->telefono }} | {{ $empresa->email }}</p>
                <p><strong>Condición IVA:</strong> {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}</p>
            </div>
        </div>

        <div class="header-right">
            <h1 class="doc-title">{{ $titulo_doc }} {{ $letra }}</h1>
            <div class="doc-info">
                <p><strong>Nro:</strong> {{ $venta->numero_comprobante }}</p>
                <p><strong>Fecha Emisión:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
                <p><strong>CUIT:</strong> {{ $empresa->arca_cuit ?? $empresa->cuit }}</p>
                <p><strong>Ing. Brutos:</strong> {{ $empresa->iibb ?? '-' }}</p>
                <p><strong>Inicio Actividad:</strong> {{ $empresa->inicio_actividad ?? '-' }}</p>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="client-box">
        <div class="client-row">
            <div style="width: 60%; float: left;"><span class="label">Nombre:</span> {{ optional($venta->cliente)->name ?? 'CONSUMIDOR FINAL' }}</div>
            <div style="width: 40%; float: left;"><span class="label">CUIT/DNI:</span> {{ optional($venta->cliente)->document ?? 'CF' }}</div>
            <div class="clearfix"></div>
        </div>
        <div class="client-row">
            <div style="width: 60%; float: left;"><span class="label">Domicilio:</span> {{ optional($venta->cliente)->address ?? '-' }}</div>
            <div style="width: 40%; float: left;"><span class="label">Condición IVA:</span> {{ optional($venta->cliente)->condicion_iva ?? 'Consumidor Final' }}</div>
            <div class="clearfix"></div>
        </div>
        <div class="client-row">
            <div style="width: 33%; float: left;"><span class="label">Ciudad:</span> {{ optional($venta->cliente)->city ?? '-' }}</div>
            <div style="width: 33%; float: left;"><span class="label">Vendedor:</span> {{ optional($venta->user)->name ?? 'Sistema' }}</div>
            <div style="width: 33%; float: left;"><span class="label">Cond. Venta:</span> {{ ucfirst($venta->metodo_pago ?? 'Efectivo') }}</div>
            <div class="clearfix"></div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="8%">Cod</th>
                <th width="50%">Concepto</th>
                <th width="10%">Cant</th>
                <th width="12%">P. Unit</th>
                <th width="8%">Bonif.</th>
                <th width="12%">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->items as $item)
                <tr>
                    <td class="text-center">{{ $item->product->id ?? '-' }}</td>
                    <td>
                        {{ $item->product->name }}
                        @if($item->variant)
                            <br><small>({{ $item->variant->size }} / {{ $item->variant->color }})</small>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->cantidad, 2, ',', '.') }}</td>
                    <td class="text-right">$ {{ number_format($item->total_item_con_iva / $item->cantidad, 2, ',', '.') }}</td>
                    <td class="text-center">0%</td>
                    <td class="text-right">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            
            {{-- Filler rows to ensure minimum height as per professional invoices --}}
            @php $fill = max(0, 15 - count($venta->items)); @endphp
            @for($i=0; $i<$fill; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer-section">
        <div class="obs-box">
            <span class="label">Observaciones:</span>
            <p>{{ $venta->observaciones ?? 'Muchas gracias por su compra.' }}</p>
        </div>

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bonificación/Descuento:</td>
                <td class="text-right">$ 0,00</td>
            </tr>
            <tr class="total-row">
                <td>IMPORTE TOTAL:</td>
                <td class="text-right">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
            </tr>
        </table>
        <div class="clearfix"></div>
    </div>

    @if($es_oficial && isset($venta->cae))
        <div class="afip-footer">
            <img src="{{ public_path('images/afip_logo.png') }}" class="afip-logo">
            <div class="afip-info">
                <strong>Comprobante Autorizado</strong><br>
                Esta administración general no se responsabiliza por los datos ingresados en el detalle de la operación.
            </div>
            <div class="cae-info">
                <strong>CAE Nro.:</strong> {{ $venta->cae }}<br>
                <strong>Fecha Vto. CAE:</strong> {{ $venta->cae_vto ? \Carbon\Carbon::parse($venta->cae_vto)->format('d/m/Y') : '-' }}
            </div>
            <div class="clearfix"></div>
        </div>
    @elseif(!$es_oficial)
        <div class="afip-footer" style="border-top: 1pt dashed #ccc; text-align: center; font-size: 8px;">
            <p>DOCUMENTO NO VÁLIDO COMO FACTURA</p>
        </div>
    @endif

    <div class="footer-attribution">
        Sistema MultiPOS SaaS - El Cerebro de tu Negocio - www.gentepiola.net
    </div>

</body>
</html>
