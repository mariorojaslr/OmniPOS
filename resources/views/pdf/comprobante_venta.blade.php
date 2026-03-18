<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Nº {{ $venta->numero_comprobante }}</title>
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            line-height: 1.1; 
            color: #000; 
            background: #fff;
            padding: 1cm; /* Aire de 1cm a la vuelta requested by USER */
        }

        .clearfix { clear: both; }

        /* BORDE EXTERIOR OPCIONAL SI SE QUIERE ENCERRAR TODO COMO EN LAS IMAGENES */
        .main-container {
            width: 100%;
            /* border: 1pt solid #000; */ /* Si quieres un borde a todo el documento descomenta esto */
        }

        /* HEADER BOX */
        .header-box { 
            width: 100%; 
            border: 1pt solid #000; 
            position: relative; 
            min-height: 160px; 
        }

        .header-left { 
            width: 50%; 
            float: left; 
            padding: 15px 20px; 
        }

        .header-right { 
            width: 50%; 
            float: right; 
            padding: 15px 20px; 
            text-align: left; 
            border-left: 1pt solid #000;
            min-height: 160px;
        }
        
        /* CUADRO LETRA CENTRAL */
        .letter-container {
            position: absolute;
            left: 50%;
            top: -1px;
            margin-left: -30px;
            width: 60px;
            height: 55px;
            border: 1pt solid #000;
            border-top: none;
            background: #fff;
            text-align: center;
            z-index: 100;
        }
        .letter-container .letter { font-size: 32pt; font-weight: bold; display: block; line-height: 38px; margin-top: 2px; }
        .letter-container .cod { font-size: 7.5pt; display: block; margin-top: 1px; font-weight: bold; }

        /* LOGO / INFO EMISOR */
        .logo { max-width: 160px; max-height: 60px; margin-bottom: 10px; }
        .company-name { font-size: 16pt; font-weight: bold; margin-bottom: 5px; }
        .company-info p { margin-bottom: 3px; font-size: 9.5pt; }

        /* INFO COMPROBANTE */
        .doc-title { font-size: 24pt; font-weight: bold; margin-bottom: 10px; text-align: center; letter-spacing: 1.5pt; }
        .doc-info p { margin-bottom: 4px; font-size: 10pt; }

        /* DATOS RECEPTOR */
        .client-box { 
            width: 100%; 
            border: 1pt solid #000; 
            border-top: none; 
            padding: 12px 20px; 
        }
        .client-row { width: 100%; margin-bottom: 6px; clear: both; }
        .label { font-weight: bold; margin-right: 5px; }

        /* TABLA ITEMS - SIN RENGLONES INTERNOS */
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            border: 1pt solid #000; 
            border-top: none; 
            margin-bottom: 15px; 
        }
        .items-table th { 
            border-bottom: 1pt solid #000; 
            border-right: 1pt solid #000;
            padding: 8px 6px; 
            background: #eee; 
            font-weight: bold; 
            text-align: center; 
            font-size: 9.5pt;
        }
        .items-table th:last-child { border-right: none; }

        .items-table td { 
            padding: 4px 10px; 
            vertical-align: top; 
            border-right: 1pt solid #000;
            font-size: 9.5pt;
            /* SE ELIMINA EL BORDER-BOTTOM POR PEDIDO DEL USUARIO (SIN RENGLONES) */
        }
        .items-table td:last-child { border-right: none; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* FOOTER / TOTALES */
        .footer-section { width: 100%; margin-top: 10px; border-top: 1pt solid #000; }
        .obs-box { 
            width: 62%; 
            float: left; 
            padding: 15px; 
            min-height: 100px; 
            font-size: 9.5pt; 
            border-right: 1pt solid #000;
            border-bottom: 1pt solid #000;
            border-left: 1pt solid #000;
        }
        .totals-table { 
            width: 38%; 
            float: right; 
            border: 1pt solid #000; 
            border-top: none;
            border-collapse: collapse; 
        }
        .totals-table td { padding: 8px 15px; font-size: 11pt; }
        .total-row { font-weight: bold; font-size: 14pt; border-top: 1pt solid #000; }

        /* AFIP PIE */
        .afip-footer { margin-top: 25px; width: 100%; border-top: 1.2pt solid #000; padding-top: 15px; }
        .afip-logo { width: 120px; float: left; margin-right: 25px; }
        .afip-qr { width: 70px; float: left; margin-right: 20px; }
        .afip-info { float: left; font-size: 8.5pt; line-height: 1.4; padding-top: 8px; width: 50%; }
        .cae-info { float: right; text-align: right; font-size: 10.5pt; padding-top: 10px; font-weight: bold; }
        
        .footer-attribution { 
            text-align: center; 
            margin-top: 40px;
            font-size: 8.5pt; 
            color: #666; 
            font-style: italic; 
            width: 100%;
        }

        /* Línea de corte para comprobantes no válidos */
        .cut-line {
            border-top: 1pt dashed #aaa;
            margin-top: 25px;
            text-align: center;
            font-size: 9pt;
            padding-top: 10px;
            color: #666;
            font-weight: bold;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

<div class="main-container">

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
                <p><strong>Tel/Email:</strong> {{ $empresa->telefono }} | {{ $empresa->email }}</p>
                <p><strong>Cond. IVA:</strong> {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}</p>
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
            <div style="width: 58%; float: left;"><span class="label">Señor/es:</span> {{ strtoupper(optional($venta->cliente)->name ?? 'CONSUMIDOR FINAL') }}</div>
            <div style="width: 42%; float: left;"><span class="label">CUIT/DNI:</span> {{ optional($venta->cliente)->document ?? 'CF' }}</div>
            <div class="clearfix"></div>
        </div>
        <div class="client-row">
            <div style="width: 58%; float: left;"><span class="label">Domicilio:</span> {{ optional($venta->cliente)->address ?? '-' }}</div>
            <div style="width: 42%; float: left;"><span class="label">Condición IVA:</span> {{ optional($venta->cliente)->condicion_iva ?? 'Consumidor Final' }}</div>
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
                <th width="10%">Cód</th>
                <th width="45%">Descripción / Concepto</th>
                <th width="10%">Cant</th>
                <th width="15%">P. Unit.</th>
                <th width="8%">% Desc</th>
                <th width="12%">Subtotal</th>
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
                    <td class="text-center">0,00</td>
                    <td class="text-right">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            
            {{-- SE QUITAN RENGLONES VACÍOS PARA AHORRAR ESPACIO SI ES LARGA --}}
            {{-- Solo si es muy corta agregamos un poco de espacio --}}
            @if(count($venta->items) < 10)
                @for($i=0; $i < (10 - count($venta->items)); $i++)
                    <tr>
                        <td style="border-right: 1pt solid #000;">&nbsp;</td>
                        <td style="border-right: 1pt solid #000;">&nbsp;</td>
                        <td style="border-right: 1pt solid #000;">&nbsp;</td>
                        <td style="border-right: 1pt solid #000;">&nbsp;</td>
                        <td style="border-right: 1pt solid #000;">&nbsp;</td>
                        <td>&nbsp;</td>
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
            <div style="float: left; width: 150px;">
                {{-- Logo AFIP --}}
                <div style="font-size: 20pt; font-weight: 900; color: #333; letter-spacing: -2px;">AFIP</div>
                <div style="font-size: 8pt; font-weight: bold; margin-top: -5px;">Comprobante autorizado</div>
            </div>
            
            <div class="afip-info">
                Esta administración general no se responsabiliza por los datos ingresados en el detalle de la operación.
            </div>
            
            <div class="cae-info">
                CAE Nro.: {{ $venta->cae }}<br>
                Fecha Vto. CAE: {{ $venta->cae_vto ? \Carbon\Carbon::parse($venta->cae_vto)->format('d/m/Y') : '-' }}
            </div>
            <div class="clearfix"></div>
        </div>
    @else
        <div class="cut-line">
            DOCUMENTO NO VÁLIDO COMO FACTURA
        </div>
    @endif

    <div class="footer-attribution">
        Sistema MultiPOS SaaS - "El Cerebro de tu Negocio" - www.gentepiola.net
    </div>

</div> <!-- main-container -->

</body>
</html>
