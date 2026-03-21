<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante {{ $venta->numero_comprobante ?? $venta->id }}</title>
    <style>
        @page { margin: 0.5cm; size: A4; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 8.5pt; color: #1a1a1a; line-height: 1.4; padding: 0; margin: 0; }
        .invoice-box { width: 100%; border: 1px solid #dcdcdc; min-height: 27.5cm; position: relative; background: #fff; }
        
        .main-header { width: 100%; border-bottom: 2px solid #333; border-collapse: collapse; }
        .main-header td { vertical-align: top; padding: 20px 15px; }
        
        .header-type { width: 60px; text-align: center; border: 2px solid #333; border-top: none; background: #fff; position: relative; padding: 0 !important; z-index: 10; }
        .header-type .letter { font-size: 34pt; font-weight: bold; line-height: 1; display: block; margin-top: 2px; }
        .header-type .cod { font-size: 7.5pt; font-weight: bold; display: block; margin-top: -4px; padding-bottom: 5px; }
        
        .company-name { font-size: 16pt; font-weight: 900; color: #000; text-transform: uppercase; margin-bottom: 5px; }
        .company-data p { margin: 0; font-size: 8.5pt; color: #444; }
        
        .doc-title { font-size: 22pt; font-weight: 900; margin: 0 0 5px 0; text-align: right; color: #333; }
        .doc-data p { margin: 0 0 3px 0; font-size: 9.5pt; text-align: right; }

        .section-bar { background: #f8f9fa; border-top: 1px solid #333; border-bottom: 1px solid #333; padding: 10px 15px; }
        .label { font-weight: bold; color: #000; text-transform: uppercase; font-size: 8pt; margin-right: 5px; }

        .items-table { width: 100%; border-collapse: collapse; margin-top: 0; }
        .items-table th { background: #333; color: #fff; padding: 12px 10px; text-transform: uppercase; font-size: 8.5pt; letter-spacing: 0.5px; border: 1px solid #333; }
        .items-table td { padding: 10px 10px; border-bottom: 1px solid #eee; font-size: 8.5pt; color: #333; border-left: 1px solid #eee; border-right: 1px solid #eee; }
        .row-even { background: #fafafa; }
        
        .footer-container { position: absolute; bottom: 0; width: 100%; padding: 25px 20px; border-top: 1px solid #333; box-sizing: border-box; }
        .totals-box { float: right; width: 42%; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 5px 0; font-size: 10pt; color: #333; }
        .total-amount { font-size: 16pt; font-weight: 900; color: #000; border-top: 2px solid #333; padding-top: 8px; margin-top: 5px; }

        .arca-box { float: left; width: 55%; }
        .arca-logo { height: 40px; margin-bottom: 15px; }
        .qr-placeholder { border: 1px solid #eee; padding: 8px; display: inline-block; background: #fff; }
        .cae-data { text-align: right; font-weight: bold; font-size: 11pt; margin-top: 12px; font-family: 'Courier New', monospace; letter-spacing: 0.5px; }

        .attribution { text-align: center; font-size: 7.5pt; color: #999; margin-top: 45px; border-top: 1px solid #eee; padding-top: 15px; font-style: italic; }
        .clear { clear: both; }
    </style>
</head>
<body>

@php
    $tipo = strtoupper($venta->tipo_comprobante ?? 'B');
    $esA = ($tipo === 'A' || $tipo === 'FACTURA A');
    $letra = $esA ? 'A' : 'B';
    $cod_id = $esA ? '001' : '006';
    $puntoVenta = str_pad($empresa->arca_punto_venta ?? '1', 5, '0', STR_PAD_LEFT);
    $nroComp = str_pad($venta->id, 8, '0', STR_PAD_LEFT);
    
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
    $qrBase64 = base64_encode(json_encode($qrData));
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://www.afip.gob.ar/genericos/comprobante/cae.aspx?p=" . $qrBase64;
@endphp

<div class="invoice-box">
    
    <table class="main-header">
        <tr>
            <td width="45%">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" style="max-width: 190px; max-height: 85px; margin-bottom: 12px; display: block;">
                @endif
                <div class="company-name">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
                <div class="company-data">
                    <p><strong>{{ $empresa->nombre_comercial ?? 'Casa Central' }}</strong></p>
                    <p>{{ $empresa->direccion_fiscal ?? '-' }}</p>
                    <p>CP: {{ $empresa->codigo_postal ?? '-' }} | Tel: {{ $empresa->telefono ?? '-' }}</p>
                    <p><strong>Cond. IVA:</strong> {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}</p>
                </div>
            </td>
            <td class="header-type">
                <span class="letter">{{ $letra }}</span>
                <span class="cod">Cod. {{ $cod_id }}</span>
            </td>
            <td width="45%" style="text-align: right;">
                <h1 class="doc-title">FACTURA</h1>
                <div style="font-weight: 800; font-size: 9pt; margin-bottom: 8px; color: #666; text-transform: uppercase;">Original</div>
                <div class="doc-data">
                    <p><strong>Punto de Venta:</strong> {{ $puntoVenta }} &nbsp;&nbsp; <strong>Número:</strong> {{ $nroComp }}</p>
                    <p><strong>Fecha de Emisión:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
                    <p><strong>CUIT:</strong> {{ $empresa->cuit }}</p>
                    <p><strong>Ingresos Brutos:</strong> {{ $empresa->iibb ?? $empresa->cuit }}</p>
                    <p><strong>Inicio de Actividad:</strong> {{ $empresa->inicio_actividad ?? '-' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-bar">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="55%"><span class="label">Apellido y Nombre / Razón Social:</span> <span style="font-size: 10pt; font-weight: bold;">{{ strtoupper($venta->cliente->name ?? 'CONSUMIDOR FINAL') }}</span></td>
                <td width="45%"><span class="label">Cond. IVA:</span> {{ $venta->cliente->condicion_iva ?? 'Consumidor Final' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 8px;"><span class="label">Domicilio:</span> {{ $venta->cliente->address ?? '-' }}</td>
                <td style="padding-top: 8px;"><span class="label">CUIT / DNI:</span> {{ $venta->cliente->document ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 8px;"><span class="label">Localidad / Provincia:</span> {{ $venta->cliente->city ?? '-' }} ({{ $venta->cliente->province ?? '-' }})</td>
                <td style="padding-top: 8px;"><span class="label">Condición de Venta:</span> {{ strtoupper($venta->metodo_pago ?? 'Contado') }}</td>
            </tr>
        </table>
    </div>

    @if($venta->observaciones)
    <div style="padding: 10px 15px; border-bottom: 1px solid #eee;">
        <span class="label">OBSERVACIONES:</span> <span style="font-size: 8.5pt; color: #555;">{{ $venta->observaciones }}</span>
    </div>
    @endif

    <table class="items-table">
        <thead>
            <tr>
                <th width="12%">Código</th>
                <th width="40%" style="text-align: left;">Descripción del Producto / Servicio</th>
                <th width="10%" style="text-align: center;">Cant.</th>
                <th width="12%" style="text-align: right;">Unitario</th>
                @if($esA) <th width="10%" style="text-align: center;">% IVA</th> @endif
                <th width="16%" style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->items as $index => $item)
            <tr class="{{ $index % 2 == 0 ? '' : 'row-even' }}">
                <td style="text-align: center; color: #666;">{{ $item->product->sku ?? $item->product_id }}</td>
                <td>
                    <span style="font-weight: bold;">{{ $item->product->name }}</span>
                    @if($item->variant) <br><small style="color: #666;">[{{ $item->variant->size }} / {{ $item->variant->color }}]</small> @endif
                </td>
                <td style="text-align: center;">{{ number_format($item->cantidad, 0) }}</td>
                <td style="text-align: right;">$ {{ number_format($esA ? $item->precio_unitario_sin_iva : ($item->total_item_con_iva / $item->cantidad), 2, ',', '.') }}</td>
                @if($esA) <td style="text-align: center;">21,00%</td> @endif
                <td style="text-align: right; font-weight: bold;">$ {{ number_format($esA ? $item->subtotal_item_sin_iva : $item->total_item_con_iva, 2, ',', '.') }}</td>
            </tr>
            @endforeach

            @php $fillLines = max(2, 14 - count($venta->items)); @endphp
            @for($i=0; $i < $fillLines; $i++)
                <tr style="border: none;"><td colspan="{{ $esA ? 6 : 5 }}" style="color: transparent; border: none;">&nbsp;</td></tr>
            @endfor
        </tbody>
    </table>

    <div class="footer-container">
        <div class="arca-box">
            <img src="https://servicioscf.afip.gob.ar/publico/images/arca_logo.png" class="arca-logo" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/e/e0/Logo_ARCA_Argentina.png'"><br>
            <div class="qr-placeholder" style="margin-top: 5px;">
                <img src="{{ $qrUrl }}" style="width: 110px; height: 110px;">
            </div>
            <div style="display: inline-block; vertical-align: top; margin-left: 20px; padding-top: 25px; width: 280px;">
                <div style="font-size: 11pt; font-weight: 900; color: #000;">Comprobante Autorizado</div>
                <div style="font-size: 7.5pt; color: #666; line-height: 1.2; margin-top: 5px;">
                    Esta administración federal no se responsabiliza por los datos declarados en el detalle del comprobante.
                </div>
            </div>
        </div>

        <div class="totals-box">
            <table class="totals-table">
                @if($esA)
                    <tr>
                        <td style="font-weight: bold; color: #666;">Subtotal Neto Gravado:</td>
                        <td style="text-align: right; font-weight: bold;">$ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #666;">IVA 21%:</td>
                        <td style="text-align: right; font-weight: bold;">$ {{ number_format($venta->total_iva, 2, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-amount">
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
            {{ date('Y') }} — Sistema de Gestión Integral <strong>MultiPOS SaaS</strong> | Comprobante emitido electrónicamente | Pág. 1 de 1
        </div>
    </div>

</div>

</body>
</html>
