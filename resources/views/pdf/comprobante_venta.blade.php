<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Nº {{ $venta->numero_comprobante }}</title>
    <style>
        @page { 
            margin: 1cm; 
            size: A4;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 8.5pt; 
            line-height: 1.1; 
            color: #000; 
            background: #fff;
        }

        .clearfix { clear: both; }

        /* HEADER BOX */
        .header-box { 
            width: 100%; 
            border: 0.8pt solid #000; 
            position: relative; 
            min-height: 140px; 
        }

        .header-left { 
            width: 50%; 
            float: left; 
            padding: 10px 15px; 
            text-align: left;
        }

        .header-right { 
            width: 50%; 
            float: right; 
            padding: 10px 15px; 
            text-align: left; 
            border-left: 0.8pt solid #000;
            min-height: 140px;
        }
        
        /* CUADRO LETRA CENTRAL */
        .letter-container {
            position: absolute;
            left: 50%;
            top: -1px;
            margin-left: -20px;
            width: 40px;
            height: 40px;
            border: 0.8pt solid #000;
            border-top: none;
            background: #fff;
            text-align: center;
            z-index: 100;
        }
        .letter-container .letter { 
            font-size: 24pt; 
            font-weight: bold; 
            display: block; 
            line-height: 1; 
            margin-top: 2px;
        }
        .letter-container .cod { 
            font-size: 6pt; 
            display: block; 
            margin-top: -1px; 
            font-weight: bold;
        }

        /* LOGO / INFO EMISOR */
        .logo { max-width: 140px; max-height: 50px; margin-bottom: 5px; }
        .company-name { font-size: 13pt; font-weight: bold; margin-bottom: 3px; }
        .company-info p { margin-bottom: 1px; font-size: 8.5pt; }

        /* INFO COMPROBANTE */
        .doc-title { 
            font-size: 16pt; 
            font-weight: bold; 
            margin-bottom: 5px; 
            text-align: center; 
            width: 100%;
            margin-top: 10px; /* Separación del cuadro de la letra */
        }
        .doc-info { margin-top: 5px; }
        .doc-info p { margin-bottom: 2px; font-size: 9pt; }

        /* DATOS RECEPTOR */
        .client-box { 
            width: 100%; 
            border: 0.8pt solid #000; 
            border-top: none; 
            padding: 8px 15px; 
        }
        .client-row { width: 100%; margin-bottom: 2px; clear: both; }
        .label { font-weight: bold; margin-right: 3px; }

        /* TABLA ITEMS - TOTALMENTE LIMPIA SIN RENGLONES */
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            border: 0.8pt solid #000; 
            border-top: none; 
            margin-bottom: 10px; 
        }
        .items-table th { 
            border-bottom: 0.8pt solid #000; 
            padding: 5px; 
            background: #eeeeee; 
            font-weight: bold; 
            text-align: center; 
            font-size: 9pt;
        }
        .items-table td { 
            padding: 3px 6px; 
            vertical-align: top; 
            font-size: 8.5pt;
            border: none; /* Sin bordes internos */
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Espaciado para simular columnas sin líneas */
        .col-id { width: 10%; }
        .col-desc { width: 50%; }
        .col-cant { width: 10%; }
        .col-price { width: 15%; }
        .col-desc-pct { width: 5%; }
        .col-subtotal { width: 10%; }

        /* FOOTER / TOTALES */
        .footer-section { width: 100%; margin-top: 5px; }
        .obs-box { 
            width: 60%; 
            float: left; 
            border: 0.8pt solid #000; 
            padding: 10px; 
            min-height: 60px; 
            font-size: 8.5pt; 
        }
        .totals-table { 
            width: 38%; 
            float: right; 
            border: 0.8pt solid #000; 
            border-collapse: collapse; 
        }
        .totals-table td { padding: 4px 10px; font-size: 9pt; }
        .total-row { font-weight: bold; font-size: 11pt; border-top: 0.8pt solid #000; }

        /* AFIP PIE */
        .afip-footer { margin-top: 15px; width: 100%; border-top: 1pt solid #000; padding-top: 10px; }
        .afip-logo-text { font-size: 18pt; font-weight: 900; float: left; margin-right: 15px; color: #333; }
        .afip-info { float: left; font-size: 7.5pt; line-height: 1.2; padding-top: 5px; width: 50%; color: #444; }
        .cae-info { float: right; text-align: right; font-size: 9pt; padding-top: 5px; font-weight: bold; }
        
        .footer-attribution { 
            text-align: center; 
            margin-top: 20px;
            font-size: 7.5pt; 
            color: #777; 
            font-style: italic; 
            width: 100%;
        }

        /* Línea de corte */
        .cut-line {
            border-top: 0.5pt dashed #aaa;
            margin-top: 20px;
            text-align: center;
            font-size: 8pt;
            padding-top: 5px;
            color: #888;
        }
    </style>
</head>
<body>

    @php
        $letra = $venta->tipo_comprobante ?? 'X';
        $letra = strtoupper($letra);
        $es_oficial = in_array($letra, ['A', 'B', 'C', 'M']);
        $titulo_doc = $es_oficial ? 'FACTURA' : 'COMPROBANTE';
        
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

        <div class="header-left">
            @if($logoPath && file_exists($logoPath))
                <img src="{{ $logoPath }}" class="logo">
            @endif
            <div class="company-name">{{ strtoupper($empresa->nombre_comercial ?? $empresa->razon_social) }}</div>
            <div class="company-info">
                <p><strong>Razón Social:</strong> {{ $empresa->razon_social ?? $empresa->nombre_comercial }}</p>
                <p><strong>Domicilio:</strong> {{ $empresa->direccion_fiscal ?? '-' }}</p>
                <p style="font-size: 7.5pt;"><strong>Tel/Email:</strong> {{ $empresa->telefono }} | {{ $empresa->email }}</p>
                <p><strong>IVA:</strong> {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}</p>
            </div>
        </div>

        <div class="header-right">
            <h1 class="doc-title">{{ $titulo_doc }}</h1>
            <div class="doc-info">
                <p><strong>Punto de Venta:</strong> {{ str_pad($empresa->arca_punto_venta ?? '00001', 5, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Comp. Nro:</strong> {{ str_pad($venta->id, 8, '0', STR_PAD_LEFT) }}</p>
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
            <div style="width: 55%; float: left;"><span class="label">Señor/es:</span> {{ strtoupper(optional($venta->cliente)->name ?? 'CONSUMIDOR FINAL') }}</div>
            <div style="width: 45%; float: left;"><span class="label">CUIT/DNI:</span> {{ optional($venta->cliente)->document ?? 'CF' }}</div>
            <div class="clearfix"></div>
        </div>
        <div class="client-row">
            <div style="width: 55%; float: left;"><span class="label">Domicilio:</span> {{ optional($venta->cliente)->address ?? '-' }}</div>
            <div style="width: 45%; float: left;"><span class="label">Condición IVA:</span> {{ optional($venta->cliente)->condicion_iva ?? 'Consumidor Final' }}</div>
            <div class="clearfix"></div>
        </div>
        <div class="client-row">
            <div style="width: 33%; float: left;"><span class="label">Localidad:</span> {{ optional($venta->cliente)->city ?? '-' }}</div>
            <div style="width: 33%; float: left;"><span class="label">Vendedor:</span> {{ optional($venta->user)->name ?? 'Sistema' }}</div>
            <div style="width: 33%; float: left;"><span class="label">Cond. Pago:</span> {{ ucfirst($venta->metodo_pago ?? 'Efectivo') }}</div>
            <div class="clearfix"></div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="col-id">Cód</th>
                <th class="col-desc">Descripción / Concepto</th>
                <th class="col-cant">Cant</th>
                <th class="col-price">P. Unit.</th>
                <th class="col-desc-pct">%</th>
                <th class="col-subtotal">Subtotal</th>
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
                    <td class="text-center">0</td>
                    <td class="text-right">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            
            {{-- Espaciado mínimo para que la factura no se vea vacía si hay pocos items --}}
            @php $minRows = 12 - count($venta->items); @endphp
            @if($minRows > 0)
                @for($i=0; $i < $minRows; $i++)
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>

    <div class="footer-wrap">
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
                <td>Descuento:</td>
                <td class="text-right">$ 0,00</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL:</td>
                <td class="text-right">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
            </tr>
        </table>
        <div class="clearfix"></div>
    </div>

    @if($es_oficial && isset($venta->cae))
        <div class="afip-footer">
            <div class="afip-logo-text">AFIP</div>
            <div class="afip-info">
                <strong>Comprobante Autorizado</strong><br>
                Esta administración general no se responsabiliza por los datos ingresados en el detalle de la operación.
            </div>
            <div class="cae-info">
                CAE Nro.: {{ $venta->cae }}<br>
                Vto. CAE: {{ $venta->cae_vto ? \Carbon\Carbon::parse($venta->cae_vto)->format('d/m/Y') : '-' }}
            </div>
            <div class="clearfix"></div>
        </div>
    @else
        <div class="cut-line">
            DOCUMENTO NO VÁLIDO COMO FACTURA
        </div>
    @endif

    <div class="footer-attribution">
        Desarrollado por MultiPOS SaaS - El Cerebro de tu Negocio - www.gentepiola.net
    </div>

</body>
</html>
