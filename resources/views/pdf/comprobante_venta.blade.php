<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante {{ $venta->id }}</title>
    <style>
        @page { margin: 0.5cm; size: A4; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 8.5pt; color: #000; line-height: 1.35; padding: 0; margin: 0; }
        .invoice-box { width: 100%; border: 1.2px solid #000; min-height: 27.5cm; position: relative; background: #fff; }
        
        /* HEADER */
        .header-table { width: 100%; border-bottom: 2.5px solid #000; border-collapse: collapse; }
        .header-table td { vertical-align: top; padding: 12px 10px; }
        
        .header-center { width: 66px; text-align: center; border: 2.5px solid #000; border-top: none; background: #fff; position: absolute; left: 50%; margin-left: -33px; top: 0; height: 60px; z-index: 1000; }
        .header-center .letter { font-size: 36pt; font-weight: bold; line-height: 52px; display: block; }
        .header-center .cod { font-size: 8pt; font-weight: bold; display: block; margin-top: -6px; padding-bottom: 2px; }
        
        .company-name { font-size: 15pt; font-weight: 900; color: #000; text-transform: uppercase; margin-bottom: 3px; }
        .company-data p { margin: 0; font-size: 8.5pt; color: #1a1a1a; line-height: 1.25; }
        
        .doc-title { font-size: 22pt; font-weight: 900; margin: 0 0 8px 0; text-align: right; color: #000; line-height: 1; padding-right: 0.5cm; }
        .doc-num { font-size: 14pt; font-weight: 900; text-align: right; margin-bottom: 8px; padding-right: 0.5cm; }
        .doc-data p { margin: 0 0 5px 0; font-size: 9pt; text-align: right; padding-right: 0.5cm; }

        /* SECCIONES TRANSVERSALES */
        .section-bar { background: #f5f5f5; border-top: 1.2px solid #000; border-bottom: 1.2px solid #000; padding: 8px 15px; margin-top: -1px; }
        .label { font-weight: bold; color: #000; text-transform: uppercase; font-size: 8.5pt; margin-right: 5px; }

        /* TABLA DE ITEMS (SIN RENGLONES) */
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { background: #000; color: #fff; padding: 8px; text-transform: uppercase; font-size: 8.5pt; text-align: center; border: 1px solid #000; }
        .items-table td { padding: 4px 8px; font-size: 8.5pt; color: #000; border: none; }
        
        /* FOOTER COMPACTO */
        .footer-container { position: absolute; bottom: 0; width: 100%; padding: 20px 25px; border-top: 2px solid #000; box-sizing: border-box; }
        
        .arca-box { float: left; width: 40%; padding-top: 10px; }
        .arca-logo { height: 38px; margin-bottom: 12px; }
        .qr-placeholder { border: 1.2px solid #000; padding: 6px; display: inline-block; background: #fff; }
        .qr-img { width: 95px; height: 95px; }
        
        .totals-section { float: left; width: 55%; margin-left: 2%; text-align: right; padding-right: 0.5cm; }
        .totals-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .totals-table td { padding: 3px 0; font-size: 11pt; color: #000; }
        .total-row { font-size: 19pt; font-weight: 900; color: #000; border-top: 2.5px solid #000; border-bottom: 2.5px solid #000; padding: 10px 0; }

        .cae-box { text-align: right; margin-top: 12px; }
        .cae-data { font-weight: 900; font-size: 11.5pt; font-family: 'Courier-Bold', 'Courier', monospace; letter-spacing: 0.5px; }

        .attribution { text-align: center; font-size: 7.5pt; color: #666; margin-top: 35px; border-top: 1px solid #ddd; padding-top: 10px; font-style: italic; }
        .clear { clear: both; }

        /* LOGO CABECERA */
        .company-logo { max-width: 220px; max-height: 85px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>

    @php
        $tipo = strtoupper($venta->tipo_comprobante ?? 'B');
        $hasCae = !empty($venta->cae);
        
        if($tipo == 'TICKET') $tipo = 'T';
        $esA = ($tipo === 'A');
        $isTicket = ($tipo === 'T' || $tipo === 'TICKET');
        
        // LÓGICA DE MODO ADMINISTRATIVO
        if (!$hasCae) {
            $letra = 'X';
            $cod_id = '000';
            $titulo_comprobante = "COMPROBANTE DE GESTIÓN";
        } else {
            $letra = $isTicket ? 'T' : ($esA ? 'A' : 'B');
            $cod_id = $isTicket ? '000' : ($esA ? '001' : '006');
            $titulo_comprobante = $isTicket ? "TICKET" : "FACTURA " . $letra;
        }
        
        $fullNumero = $venta->numero_comprobante ?: (str_pad($empresa->arca_punto_venta ?? '12', 4, '0', STR_PAD_LEFT) . '-' . str_pad($venta->id, 8, '0', STR_PAD_LEFT));
    @endphp

    <div class="invoice-box">
        
        @if(!$hasCae)
            <div style="background: #ff0000; color: #fff; text-align: center; padding: 5px; font-weight: bold; position: absolute; width: 100%; top: -35px; left: 0;">
                DOCUMENTO NO VÁLIDO COMO FACTURA
            </div>
        @endif

        <div class="header-center">
            <span class="letter">{{ $letra }}</span>
            <span class="cod">Cod. {{ $cod_id }}</span>
        </div>

        <table class="header-table">
            <tr>
                <td width="48%">
                    @if(isset($logoBase64) && $logoBase64)
                        <img src="{{ $logoBase64 }}" class="company-logo">
                    @endif
                    <div class="company-name">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
                    <div class="company-data">
                        <p><strong>{{ $empresa->nombre_comercial ?? 'Casa Central' }}</strong></p>
                        <p>{{ $empresa->direccion_fiscal ?? '-' }}</p>
                        <p>Tel: {{ $empresa->telefono ?? '-' }}</p>
                        <p><strong>Cond. IVA:</strong> {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}</p>
                    </div>
                </td>
                <td width="48%" style="text-align: right;">
                    <h1 class="doc-title">{{ $titulo_comprobante }}</h1>
                    <div class="doc-num">{{ $fullNumero }}</div>
                    <div class="doc-data">
                        <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
                        <p><strong>CUIT:</strong> {{ $empresa->cuit }}</p>
                        <p><strong>I.I.B.B.:</strong> {{ $empresa->iibb ?? $empresa->cuit }}</p>
                        <p><strong>Inicio Actividades:</strong> {{ $empresa->inicio_actividad ?? '-' }}</p>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-bar">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="55%"><span class="label">Señor(es):</span> <span style="font-size: 10pt; font-weight: 900;">{{ strtoupper($venta->cliente->name ?? 'CONSUMIDOR FINAL') }}</span></td>
                    <td width="45%"><span class="label">IVA:</span> {{ $venta->cliente->condicion_iva ?? 'Consumidor Final' }}</td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;"><span class="label">Domicilio:</span> {{ $venta->cliente->address ?? '-' }}</td>
                    <td style="padding-top: 5px;"><span class="label">CUIT / DNI:</span> {{ $venta->cliente->document ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <table class="items-table" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th width="12%">Cod.</th>
                    <th width="42%" style="text-align: left;">Descripción / Detalle</th>
                    <th width="10%">Cant.</th>
                    <th width="14%" style="text-align: right;">P. Unit.</th>
                    @if($esA) <th width="10%">IVA</th> @endif
                    <th width="16%" style="text-align: right;">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->items as $item)
                <tr>
                    <td style="text-align: center; color: #444;">{{ $item->product->sku ?? $item->product_id }}</td>
                    <td>
                        <span style="font-weight: bold;">{{ $item->product->name }}</span>
                        @if($item->variant) <small style="color: #666;">[{{ $item->variant->size }}/{{ $item->variant->color }}]</small> @endif
                    </td>
                    <td style="text-align: center;">{{ number_format($item->cantidad, 0) }}</td>
                    <td style="text-align: right;">$ {{ number_format($esA ? $item->precio_unitario_sin_iva : ($item->total_item_con_iva / $item->cantidad), 2, ',', '.') }}</td>
                    @if($esA) <td style="text-align: center;">21%</td> @endif
                    <td style="text-align: right; font-weight: bold;">$ {{ number_format($esA ? $item->subtotal_item_sin_iva : $item->total_item_con_iva, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer-container">
            
            <div class="arca-box">
                @if($hasCae)
                    @if(isset($arcaLogoBase64) && $arcaLogoBase64)
                        <img src="{{ $arcaLogoBase64 }}" class="arca-logo">
                    @endif
                    <br>
                    @if($venta->qr_data)
                        <div class="qr-placeholder">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode('https://www.afip.gob.ar/fe/qr/?p=' . $venta->qr_data) }}" class="qr-img">
                        </div>
                    @endif
                    <div style="display: inline-block; vertical-align: top; margin-left: 15px; width: 180px;">
                        <div style="font-size: 10pt; font-weight: 900;">Comprobante Autorizado</div>
                        <div style="font-size: 7pt; color: #444; line-height: 1.2; margin-top: 5px;">
                            Esta administración federal no se responsabiliza por los datos declarados.
                        </div>
                    </div>
                @else
                    <div style="border: 2px solid #000; padding: 15px; text-align: center; font-weight: bold; font-size: 12pt;">
                        DOCUMENTO NO VÁLIDO COMO FACTURA <br>
                        <span style="font-size: 9pt; font-weight: normal;">Uso Administrativo Interno</span>
                    </div>
                @endif
            </div>

            <div class="totals-section">
                <table class="totals-table">
                    @if($esA)
                        <tr>
                            <td style="font-weight: bold; color: #444;">Subtotal Neto:</td>
                            <td style="text-align: right; font-weight: bold;">$ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; color: #444;">IVA 21%:</td>
                            <td style="text-align: right; font-weight: bold;">$ {{ number_format($venta->total_iva, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td style="text-align: left;">IMPORTE TOTAL:</td>
                        <td style="text-align: right;">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
                    </tr>
                </table>
                
                <div class="cae-box">
                    <div class="cae-data">CAE №: {{ $venta->cae ?? '-' }}</div>
                    <div class="cae-data">Vencimiento CAE: {{ $venta->cae_vencimiento ? \Carbon\Carbon::parse($venta->cae_vencimiento)->format('d/m/Y') : '-' }}</div>
                </div>
            </div>
            
            <div class="clear"></div>

            <div class="attribution">
                MultiPOS SaaS (gentepiola.net) | Emitido Electrónicamente | Pág. 1 de 1
            </div>
        </div>

    </div>

</body>
</html>
