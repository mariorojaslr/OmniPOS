<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante {{ $venta->id }}</title>
    <style>
        @page { margin: 0.5cm; size: A4; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 8pt; color: #000; line-height: 1.35; padding: 0; margin: 0; }
        .invoice-box { width: 100%; border: 1px solid #333; min-height: 27cm; position: relative; background: #fff; }
        
        /* HEADER */
        .header-table { width: 100%; border-bottom: 2.5px solid #000; border-collapse: collapse; }
        .header-table td { vertical-align: top; padding: 15px 12px; }
        
        .header-center { width: 64px; text-align: center; border: 2px solid #000; border-top: none; background: #fff; position: absolute; left: 50%; margin-left: -32px; top: 0; height: 64px; z-index: 1000; }
        .header-center .letter { font-size: 34pt; font-weight: bold; line-height: 54px; display: block; }
        .header-center .cod { font-size: 7pt; font-weight: bold; display: block; margin-top: -6px; padding-bottom: 2px; }
        
        .company-name { font-size: 14pt; font-weight: 900; color: #000; text-transform: uppercase; margin-bottom: 4px; }
        .company-data p { margin: 0; font-size: 8pt; color: #1a1a1a; line-height: 1.3; }
        
        .doc-title { font-size: 24pt; font-weight: 900; margin: 0 0 8px 0; text-align: right; color: #000; }
        .doc-data p { margin: 0 0 5px 0; font-size: 9.5pt; text-align: right; }

        /* SECCIONES TRANSVERSALES */
        .section-bar { background: #eee; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px 15px; margin-top: -1px; }
        .label { font-weight: bold; color: #000; text-transform: uppercase; font-size: 8.5pt; margin-right: 5px; }

        /* TABLA DE ITEMS */
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { background: #222; color: #fff; padding: 10px 8px; text-transform: uppercase; font-size: 8.5pt; text-align: center; border: 1px solid #000; }
        .items-table td { padding: 9px 8px; border-bottom: 1px solid #aaa; font-size: 8.5pt; color: #000; border-left: 1px solid #aaa; border-right: 1px solid #aaa; }
        
        /* FOOTER */
        .footer-container { position: absolute; bottom: 0; width: 100%; padding: 25px 20px; border-top: 2px solid #000; box-sizing: border-box; }
        .totals-box { float: right; width: 44%; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 4px 0; font-size: 10.5pt; color: #000; }
        .total-row { font-size: 17pt; font-weight: 900; color: #000; border-top: 2.5px solid #000; padding-top: 8px; margin-top: 5px; }

        .arca-box { float: left; width: 50%; }
        .arca-logo { height: 40px; margin-bottom: 15px; }
        .qr-img { border: 1.5px solid #000; padding: 6px; background: #fff; width: 105px; height: 105px; }
        .cae-data { text-align: right; font-weight: 900; font-size: 11pt; margin-top: 15px; font-family: 'Courier-Bold', 'Courier', monospace; }

        .attribution { text-align: center; font-size: 7.5pt; color: #666; margin-top: 40px; border-top: 1px solid #ddd; padding-top: 12px; font-style: italic; }
        .clear { clear: both; }

        /* LOGO CABECERA */
        .company-logo { max-width: 220px; max-height: 85px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>

@php
    $tipo = strtoupper($venta->tipo_comprobante ?? 'B');
    $esA = ($tipo === 'A' || $tipo === 'FACTURA A');
    $isTicket = ($tipo === 'TICKET');
    $letra = $isTicket ? 'T' : ($esA ? 'A' : 'B');
    $cod_id = $isTicket ? '000' : ($esA ? '001' : '006');
    $titulo_comprobante = $isTicket ? "TICKET" : "FACTURA " . $letra;
    
    $fullNumero = str_pad($empresa->arca_punto_venta ?? '1', 4, '0', STR_PAD_LEFT) . '-' . str_pad($venta->id, 8, '0', STR_PAD_LEFT);
    
    // QR data para ARCA (AFIP)
    $qrData = [
        "ver" => 1,
        "fecha" => $venta->created_at->format('Y-m-d'),
        "cuit" => (int)$empresa->cuit,
        "ptoVta" => (int)($empresa->arca_punto_venta ?? 1),
        "tipoCod" => (int)$cod_id,
        "nroCmp" => (int)$venta->id,
        "importe" => (float)$venta->total_con_iva,
        "moneda" => "PES",
        "ctz" => 1,
        "tipoDocRec" => (int)($venta->cliente->document_type ?? 99),
        "nroDocRec" => (int)($venta->cliente->document ?? 0),
        "tipoCodAut" => "E",
        "codAut" => (int)$venta->cae
    ];
    $qrBase64_afip = base64_encode(json_encode($qrData));
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://www.afip.gob.ar/genericos/comprobante/cae.aspx?p=" . $qrBase64_afip;
@endphp

<div class="invoice-box">
    
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
                <div style="font-weight: bold; font-size: 9pt; margin-bottom: 12px; color: #444; text-transform: uppercase;">Original</div>
                <div class="doc-data">
                    <p><strong>Número:</strong> {{ $fullNumero }}</p>
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
                <td width="55%"><span class="label">Señor(es):</span> <span style="font-size: 10pt; font-weight: 800;">{{ strtoupper($venta->cliente->name ?? 'CONSUMIDOR FINAL') }}</span></td>
                <td width="45%"><span class="label">IVA:</span> {{ $venta->cliente->condicion_iva ?? 'Consumidor Final' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 8px;"><span class="label">Domicilio:</span> {{ $venta->cliente->address ?? '-' }}</td>
                <td style="padding-top: 8px;"><span class="label">CUIT / DNI:</span> {{ $venta->cliente->document ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 8px;"><span class="label">Localidad:</span> {{ $venta->cliente->city ?? '-' }} ({{ $venta->cliente->province ?? '-' }})</td>
                <td style="padding-top: 8px;"><span class="label">Cond. Venta:</span> {{ strtoupper($venta->metodo_pago ?? 'Contado') }}</td>
            </tr>
        </table>
    </div>

    @if($venta->observaciones)
    <div style="padding: 10px 15px; border-bottom: 1px solid #aaa;">
        <span class="label">Observaciones:</span> <span style="font-size: 8.5pt;">{{ $venta->observaciones }}</span>
    </div>
    @endif

    <table class="items-table">
        <thead>
            <tr>
                <th width="12%">Cod.</th>
                <th width="42%" style="text-align: left;">Descripción / Detalle</th>
                <th width="10%">Cant.</th>
                <th width="14%" style="text-align: right;">Precio Unit.</th>
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
                    @if($item->variant) <br><small style="color: #444;">[{{ $item->variant->size }} / {{ $item->variant->color }}]</small> @endif
                </td>
                <td style="text-align: center;">{{ number_format($item->cantidad, 0) }}</td>
                <td style="text-align: right;">$ {{ number_format($esA ? $item->precio_unitario_sin_iva : ($item->total_item_con_iva / $item->cantidad), 2, ',', '.') }}</td>
                @if($esA) <td style="text-align: center;">21,0%</td> @endif
                <td style="text-align: right; font-weight: bold;">$ {{ number_format($esA ? $item->subtotal_item_sin_iva : $item->total_item_con_iva, 2, ',', '.') }}</td>
            </tr>
            @endforeach

            @php $fillLines = max(2, 12 - count($venta->items)); @endphp
            @for($i=0; $i < $fillLines; $i++)
                <tr style="border: none;"><td colspan="{{ $esA ? 6 : 5 }}" style="color: transparent; border: none;">&nbsp;</td></tr>
            @endfor
        </tbody>
    </table>

    <div class="footer-container">
        <div class="arca-box">
            <img src="https://servicioscf.afip.gob.ar/publico/images/arca_logo.png" class="arca-logo" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/e/e0/Logo_ARCA_Argentina.png'"><br>
            <div class="qr-placeholder" style="margin-top: 5px;">
                <img src="{{ $qrUrl }}" class="qr-img">
            </div>
            <div style="display: inline-block; vertical-align: top; margin-left: 20px; padding-top: 25px; width: 250px;">
                <div style="font-size: 11pt; font-weight: 900;">Comprobante Autorizado</div>
                <div style="font-size: 7.5pt; color: #444; line-height: 1.2; margin-top: 5px;">
                    Esta administración federal no se responsabiliza por los datos declarados en este comprobante.
                </div>
            </div>
        </div>

        <div class="totals-box">
            <table class="totals-table">
                @if($esA)
                    <tr>
                        <td style="font-weight: bold; color: #444;">Neto Gravado:</td>
                        <td style="text-align: right; font-weight: bold;">$ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #444;">IVA 21.0%:</td>
                        <td style="text-align: right; font-weight: bold;">$ {{ number_format($venta->total_iva, 2, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td style="text-transform: uppercase;">Importe Total:</td>
                    <td style="text-align: right;">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
                </tr>
            </table>
            
            <div class="cae-data">
                CAE Nº: {{ $venta->cae ?? '74123456789012' }}<br>
                Vto. CAE: {{ $venta->cae_vto ? \Carbon\Carbon::parse($venta->cae_vto)->format('d/m/Y') : '25/12/2026' }}
            </div>
        </div>
        <div class="clear"></div>

        <div class="attribution">
            {{ date('Y') }} — <strong>MultiPOS SaaS</strong> (gentepiola.net) | Comprobante emitido electrónicamente | Pág. 1 de 1
        </div>
    </div>

</div>

</body>
</html>
