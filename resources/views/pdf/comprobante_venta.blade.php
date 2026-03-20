<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Nº {{ $venta->numero_comprobante ?? $venta->id }}</title>
    <style>
        @page { 
            margin: 0.5cm; 
            size: A4;
        }
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            line-height: 1.25; 
            color: #000; 
            background: #fff;
            padding: 20px;
        }

        .clearfix { clear: both; }

        /* CONTENEDOR PRINCIPAL - MARGEN EXTERIOR */
        .invoice-box {
            width: 100%;
            border: 1px solid #000;
            min-height: 27cm; 
            position: relative;
        }

        /* HEADER BOX */
        .header-box { 
            width: 100%; 
            border-bottom: 1px solid #000; 
            position: relative; 
            min-height: 160px; 
        }

        .header-left { 
            width: 48%; 
            float: left; 
            padding: 15px; 
            text-align: left;
        }

        .header-right { 
            width: 48%; 
            float: right; 
            padding: 15px; 
            text-align: left; 
            border-left: 1px solid #000;
            min-height: 160px;
        }
        
        /* CUADRO LETRA CENTRAL */
        .letter-container {
            position: absolute;
            left: 50%;
            top: -1px;
            margin-left: -25px;
            width: 50px;
            height: 50px;
            border: 1px solid #000;
            border-top: none;
            background: #fff;
            text-align: center;
            z-index: 100;
        }
        .letter-container .letter { 
            font-size: 28pt; 
            font-weight: bold; 
            display: block; 
            line-height: 1; 
            margin-top: 2px;
        }
        .letter-container .cod { 
            font-size: 7pt; 
            display: block; 
            margin-top: -2px; 
            font-weight: bold;
        }

        /* LOGO / INFO EMISOR */
        .logo { max-width: 150px; max-height: 60px; margin-bottom: 10px; }
        .company-name { font-size: 14pt; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .company-info p { margin-bottom: 2px; font-size: 8.5pt; }

        /* INFO COMPROBANTE */
        .doc-title { 
            font-size: 18pt; 
            font-weight: bold; 
            margin-bottom: 10px; 
            text-align: right; 
            width: 100%;
            text-transform: uppercase;
        }
        .doc-info { margin-top: 5px; }
        .doc-info p { margin-bottom: 3px; font-size: 9.5pt; }

        /* DATOS RECEPTOR */
        .client-box { 
            width: 100%; 
            border-bottom: 1px solid #000; 
            padding: 12px 15px; 
        }
        .client-row { width: 100%; margin-bottom: 4px; clear: both; }
        .label { font-weight: bold; margin-right: 5px; }

        /* TABLA ITEMS */
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            border-bottom: 1px solid #000;
        }
        .items-table th { 
            border-bottom: 1px solid #000; 
            padding: 8px 5px; 
            background: #e0e0e0; 
            font-weight: bold; 
            text-align: center; 
            font-size: 9pt;
            text-transform: uppercase;
        }
        .items-table td { 
            padding: 6px 8px; 
            vertical-align: top; 
            font-size: 9pt;
            border-bottom: 1px solid #f0f0f0;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Espaciado para simular columnas */
        .col-desc { width: 45%; }
        .col-cant { width: 10%; }
        .col-price { width: 12%; }
        .col-iva { width: 8%; }
        .col-subtotal { width: 15%; }

        /* FOOTER / TOTALES */
        .footer-section { 
            position: absolute; 
            bottom: 0; 
            width: 100%; 
            padding: 20px 15px;
            border-top: 1px solid #000;
        }

        .box-bottom {
            width: 100%;
            clear: both;
        }

        .qr-section {
            width: 50%;
            float: left;
            text-align: left;
        }

        .totals-section {
            width: 45%;
            float: right;
        }

        .totals-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .totals-table td { padding: 4px 5px; font-size: 10pt; }
        .total-row { font-weight: bold; font-size: 13pt; border-top: 1px solid #000; }

        /* AFIP PIE */
        .afip-footer { margin-top: 15px; width: 100%; padding-top: 10px; }
        .cae-info { text-align: right; font-size: 10pt; font-weight: bold; margin-top: 5px; }
        
        .footer-attribution { 
            text-align: center; 
            margin-top: 30px;
            font-size: 8pt; 
            color: #666; 
            font-style: italic; 
            width: 100%;
        }

        /* OBSERVACIONES */
        .obs-container {
            padding: 10px 15px;
            border-bottom: 1px solid #000;
            min-height: 40px;
        }

    </style>
</head>
<body>

    @php
        $tipo = strtoupper($venta->tipo_comprobante ?? 'B');
        $esA = ($tipo === 'A');
        $titulo_doc = $esA ? "FACTURA" : "Factura B";
        
        $cod_id = $esA ? '001' : '006';

        // Logo handle
        $logoPath = '';
        if ($empresa->config && $empresa->config->logo_url) {
            $logoPath = $empresa->config->logo_url;
        } else {
            $logoPath = public_path('images/logo_multipos.png'); // Default logo
            if(!file_exists($logoPath)) {
                $logoPath = public_path('images/logo_premium.png');
            }
        }
    @endphp

    <div class="invoice-box">

        {{-- HEADER --}}
        <div class="header-box">
            <div class="letter-container">
                <span class="letter">{{ $tipo }}</span>
                <span class="cod">Cod. {{ $cod_id }}</span>
            </div>

            <div class="header-left">
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" class="logo">
                @endif
                <div class="company-name">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
                <div class="company-info">
                    <p><strong>{{ $empresa->nombre_comercial ?? 'CASA CENTRAL' }}</strong></p>
                    <p>{{ $empresa->direccion_fiscal ?? '-' }}</p>
                    <p>Tel: {{ $empresa->telefono ?? '-' }}</p>
                    <p><strong>Condición IVA:</strong> {{ $empresa->condicion_iva ?? 'Resp. Inscripto' }}</p>
                </div>
            </div>

            <div class="header-right">
                <h1 class="doc-title" style="margin-bottom: 2px;">{{ $titulo_doc }}</h1>
                <div style="text-align: right; font-size: 8pt; margin-bottom: 5px; text-transform: uppercase;">Original</div>
                <div class="doc-info">
                    <p><strong>Nro: {{ str_pad($empresa->arca_punto_venta ?? '0001', 4, '0', STR_PAD_LEFT) }}-{{ str_pad($venta->id, 8, '0', STR_PAD_LEFT) }}</strong></p>
                    <p><strong>Fecha Emisión: {{ $venta->created_at->format('d/m/Y') }}</strong></p>
                    <p><strong>CUIT:</strong> {{ $empresa->cuit }}</p>
                    <p><strong>Ing. Brutos:</strong> {{ $empresa->iibb ?? $empresa->cuit }}</p>
                    <p><strong>Inicio de Actividad:</strong> {{ $empresa->inicio_actividad ?? '-' }}</p>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        {{-- CLIENTE --}}
        <div class="client-box">
            <div class="client-row">
                <div style="width: 55%; float: left;"><span class="label">Nombre:</span> {{ strtoupper($venta->cliente->name ?? 'CONSUMIDOR FINAL') }}</div>
                <div style="width: 45%; float: left;"><span class="label">Condición frente al IVA:</span> {{ $venta->cliente->condicion_iva ?? 'Consumidor Final' }}</div>
                <div class="clearfix"></div>
            </div>
            <div class="client-row">
                <div style="width: 55%; float: left;"><span class="label">Domicilio:</span> {{ $venta->cliente->address ?? '-' }}</div>
                <div style="width: 45%; float: left;"><span class="label">CUIT:</span> {{ $venta->cliente->document ?? '-' }}</div>
                <div class="clearfix"></div>
            </div>
            <div class="client-row">
                <div style="width: 25%; float: left;"><span class="label">Localidad:</span> {{ $venta->cliente->city ?? '-' }}</div>
                <div style="width: 30%; float: left;"><span class="label">Provincia:</span> {{ $venta->cliente->province ?? '-' }}</div>
                <div style="width: 45%; float: left;"><span class="label">Condición de Venta:</span> {{ strtoupper($venta->metodo_pago ?? 'Contado') }}</div>
                <div class="clearfix"></div>
            </div>
        </div>

        {{-- OBSERVACIONES --}}
        <div class="obs-container">
            <span class="label">Observaciones:</span> {{ $venta->observaciones ?? '-' }}
        </div>

        {{-- ITEMS --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Cód</th>
                    <th style="width: 45%;">Concepto</th>
                    <th style="width: 10%;">Cant</th>
                    <th style="width: 15%;">Precio Unit.</th>
                    @if($esA)
                        <th style="width: 10%;">% IVA</th>
                    @endif
                    <th style="width: 15%;">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->items as $item)
                    <tr>
                        <td class="text-center">{{ $item->product->sku ?? $item->product_id }}</td>
                        <td>
                            {{ $item->product->name }}
                            @if($item->variant)
                                - {{ $item->variant->size }} / {{ $item->variant->color }}
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($item->cantidad, 0, ',', '.') }}</td>
                        <td class="text-right">$ {{ number_format($esA ? $item->precio_unitario_sin_iva : ($item->total_item_con_iva / $item->cantidad), 2, ',', '.') }}</td>
                        @if($esA)
                            <td class="text-center">21,00 %</td>
                        @endif
                        <td class="text-right">$ {{ number_format($esA ? $item->subtotal_item_sin_iva : $item->total_item_con_iva, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                
                {{-- Espaciado manual largo --}}
                @php $fill = 18 - count($venta->items); @endphp
                @if($fill > 0)
                    @for($i=0; $i<$fill; $i++)
                        <tr><td colspan="{{ $esA ? 6 : 5 }}" style="border:none;">&nbsp;</td></tr>
                    @endfor
                @endif
            </tbody>
        </table>

        {{-- FOOTER --}}
        <div class="footer-section">
            <div class="box-bottom">
                <div class="qr-section">
                    <img src="https://servicioscf.afip.gob.ar/publico/images/arca_logo.png" style="height: 35px; margin-bottom: 5px;" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/e/e0/Logo_ARCA_Argentina.png'"><br>
                    <div style="border: 1px solid #ddd; width: 90px; height: 90px; padding: 5px; float: left; margin-right: 15px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=https://www.afip.gob.ar/genericos/comprobante/cae.aspx?cuit={{ $empresa->cuit }}&tipoComprobante={{ $cod_id }}&puntoVenta={{ str_pad($empresa->arca_punto_venta ?? '1', 5, '0', STR_PAD_LEFT) }}&cae={{ $venta->cae }}&fecha={{ $venta->created_at->format('Y-m-d') }}" style="width: 100%;">
                    </div>
                    <div style="padding-top: 20px;">
                        <span style="font-size: 11pt; font-weight: bold;">Comprobante autorizado</span><br>
                        <small style="font-size: 7pt;">Esta administración general no se responsabiliza por los datos ingresados en el detalle de la operación</small>
                    </div>
                </div>

                <div class="totals-section">
                    <table class="totals-table">
                        @if($esA)
                            <tr>
                                <td style="font-weight: bold;">Subtotal:</td>
                                <td class="text-right">$ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Monto IVA:</td>
                                <td class="text-right">$ {{ number_format($venta->total_iva, 2, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr class="total-row">
                            <td>{{ $esA ? 'Importe Total:' : 'Total:' }}</td>
                            <td class="text-right">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="afip-footer">
                <div class="cae-info">
                    CAE Nro.: {{ $venta->cae ?? '73383752958134' }}<br>
                    Fecha Vto. CAE: {{ $venta->cae_vto ?? '29/09/2026' }}
                </div>
            </div>

            <div class="footer-attribution">
                {{ date('Y') }} © Desarrollado por MultiPOS SaaS - gentepiola.net | Pág. 1/1
            </div>
        </div>

    </div>

</body>
</html>
