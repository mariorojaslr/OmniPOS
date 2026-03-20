<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante {{ $venta->numero_comprobante ?? $venta->id }}</title>
    <style>
        @page { margin: 0.5cm; size: A4; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #000; padding: 0; margin: 0; }
        .invoice-box { width: 100%; border: 1px solid #000; min-height: 27cm; position: relative; }
        
        /* HEADER TABLE ESTRUCTURAL */
        table.header-table { width: 100%; border-bottom: 2px solid #000; border-collapse: collapse; }
        table.header-table td { vertical-align: top; padding: 15px; }
        .header-center { width: 60px; text-align: center; border: 2px solid #000; border-top: none; background: #fff; position: relative; padding: 0 !important; }
        .header-center .letter { font-size: 32pt; font-weight: bold; line-height: 1.1; display: block; }
        .header-center .cod { font-size: 7.5pt; font-weight: bold; display: block; margin-top: -5px; padding-bottom: 5px; }
        
        .header-info p { margin: 0 0 3px 0; font-size: 8.5pt; }
        .doc-title { font-size: 20pt; font-weight: bold; margin: 0 0 5px 0; text-align: right; text-transform: uppercase; }
        .doc-data p { margin: 0 0 4px 0; font-size: 10pt; text-align: right; }

        /* SECCIONES TRANSVERSALES */
        .section-separator { width: 100%; border-bottom: 1px solid #000; padding: 10px 15px; }
        .label { font-weight: bold; margin-right: 5px; }

        /* TABLA DE ITEMS */
        .items-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; }
        .items-table th { background: #f2f2f2; border-bottom: 1px solid #000; padding: 10px 5px; text-transform: uppercase; font-size: 9pt; }
        .items-table td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 9pt; }
        
        /* FOOTER FIJO */
        .footer-fixed { position: absolute; bottom: 0; width: 100%; padding: 20px 15px; border-top: 1px solid #000; }
        .totals-table { float: right; width: 40%; border-collapse: collapse; }
        .totals-table td { padding: 5px; font-size: 10pt; }
        .total-row { font-weight: bold; font-size: 14pt; border-top: 2px solid #000; }

        .qr-box { float: left; width: 55%; }
        .cae-data { text-align: right; font-weight: bold; font-size: 10pt; margin-top: 10px; }
        .arca-logo { height: 35px; margin-bottom: 8px; }

        .attribution { text-align: center; font-size: 8pt; color: #888; font-style: italic; margin-top: 40px; border-top: 1px solid #eee; padding-top: 10px; }

        .clear { clear: both; }
    </style>
</head>
<body>

@php
    $tipo = strtoupper($venta->tipo_comprobante ?? 'B');
    $esA = ($tipo === 'A');
    $titulo_doc = $esA ? "FACTURA A" : "FACTURA B";
    $cod_id = $esA ? '001' : '006';
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://www.afip.gob.ar/genericos/comprobante/cae.aspx?cuit=".$empresa->cuit."&tipoComprobante=".$cod_id."&puntoVenta=".str_pad($empresa->arca_punto_venta ?? '1', 5, '0', STR_PAD_LEFT)."&cae=".$venta->cae."&fecha=".$venta->created_at->format('Y-m-d');
@endphp

<div class="invoice-box">
    
    <!-- ENCABEZADO ROBUSTO (TABLA) -->
    <table class="header-table">
        <tr>
            <td width="47%">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" style="max-width: 180px; max-height: 80px; margin-bottom: 10px;">
                @endif
                <div style="font-size: 15pt; font-weight: bold;">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
                <div class="header-info" style="margin-top: 8px;">
                    <p><strong>{{ $empresa->nombre_comercial ?? 'Sede Central' }}</strong></p>
                    <p>{{ $empresa->direccion_fiscal ?? 'Dirección no disponible' }}</p>
                    <p>Tel: {{ $empresa->telefono ?? '-' }}</p>
                    <p><strong>Condición IVA:</strong> {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}</p>
                </div>
            </td>
            <td class="header-center">
                <span class="letter">{{ $tipo }}</span>
                <span class="cod">Cod. {{ $cod_id }}</span>
            </td>
            <td width="47%" style="text-align: right;">
                <h1 class="doc-title">{{ $titulo_doc }}</h1>
                <div style="text-align: right; font-size: 9pt; margin-bottom: 10px; font-weight: bold;">ORIGINAL</div>
                <div class="doc-data">
                    <p><strong>Número:</strong> {{ str_pad($empresa->arca_punto_venta ?? '0001', 4, '0', STR_PAD_LEFT) }}-{{ str_pad($venta->id, 8, '0', STR_PAD_LEFT) }}</p>
                    <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
                    <p><strong>CUIT:</strong> {{ $empresa->cuit }}</p>
                    <p><strong>IIBB:</strong> {{ $empresa->iibb ?? $empresa->cuit }}</p>
                    <p><strong>Inicio Act.:</strong> {{ $empresa->inicio_actividad ?? '-' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATOS RECEPTOR -->
    <div class="section-separator">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="55%"><span class="label">Cliente:</span> {{ strtoupper($venta->cliente->name ?? 'CONSUMIDOR FINAL') }}</td>
                <td width="45%"><span class="label">IVA:</span> {{ $venta->cliente->condicion_iva ?? 'Consumidor Final' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px;"><span class="label">Domicilio:</span> {{ $venta->cliente->address ?? '-' }}</td>
                <td style="padding-top: 5px;"><span class="label">CUIT:</span> {{ $venta->cliente->document ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px;"><span class="label">Ciudad:</span> {{ $venta->cliente->city ?? '-' }} ({{ $venta->cliente->province ?? '-' }})</td>
                <td style="padding-top: 5px;"><span class="label">Cond. Venta:</span> {{ strtoupper($venta->metodo_pago ?? 'Contado') }}</td>
            </tr>
        </table>
    </div>

    @if($venta->observaciones)
    <div class="section-separator" style="border-bottom: 1px solid #000;">
        <span class="label">OBSERVACIONES:</span> {{ $venta->observaciones }}
    </div>
    @endif

    <!-- TABLA DE ARTICULOS -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="10%">COD</th>
                <th width="45%" style="text-align: left;">DESCRIPCIÓN</th>
                <th width="10%">CANT</th>
                <th width="15%" style="text-align: right;">PRECIO UNIT</th>
                @if($esA) <th width="10%">% IVA</th> @endif
                <th width="15%" style="text-align: right;">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->items as $item)
            <tr>
                <td style="text-align: center;">{{ $item->product->sku ?? $item->product_id }}</td>
                <td>
                    {{ $item->product->name }}
                    @if($item->variant) <small style="color: #666;">({{ $item->variant->size }} / {{ $item->variant->color }})</small> @endif
                </td>
                <td style="text-align: center;">{{ number_format($item->cantidad, 0) }}</td>
                <td style="text-align: right;">$ {{ number_format($esA ? $item->precio_unitario_sin_iva : ($item->total_item_con_iva / $item->cantidad), 2, ',', '.') }}</td>
                @if($esA) <td style="text-align: center;">21,00 %</td> @endif
                <td style="text-align: right;">$ {{ number_format($esA ? $item->subtotal_item_sin_iva : $item->total_item_con_iva, 2, ',', '.') }}</td>
            </tr>
            @endforeach

            {{-- Espaciado estético --}}
            @php $fillLines = 15 - count($venta->items); @endphp
            @for($i=0; $i < $fillLines; $i++)
                <tr><td colspan="{{ $esA ? 6 : 5 }}">&nbsp;</td></tr>
            @endfor
        </tbody>
    </table>

    <!-- PIE DE PÁGINA / TOTALES -->
    <div class="footer-fixed">
        <div class="qr-box">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/e0/Logo_ARCA_Argentina.png" class="arca-logo"><br>
            <div style="border: 1px solid #000; padding: 5px; display: inline-block; width: 100px; height: 100px; text-align: center;">
                <img src="{{ $qrUrl }}" style="width: 100%;">
            </div>
            <div style="display: inline-block; vertical-align: top; margin-left: 15px; padding-top: 10px; width: 250px;">
                <strong style="font-size: 11pt;">Comprobante Autorizado</strong><br>
                <small style="font-size: 7.5pt;">Esta administración no se responsabiliza por los datos declarados en el detalle del comprobante.</small>
            </div>
        </div>

        <div class="totals-section" style="width: 40%; float: right;">
            <table class="totals-table">
                @if($esA)
                    <tr>
                        <td style="font-weight: bold;">Neto Gravado:</td>
                        <td style="text-align: right;">$ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">IVA (21%):</td>
                        <td style="text-align: right;">$ {{ number_format($venta->total_iva, 2, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td style="text-align: right;">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
                </tr>
            </table>
            <div class="clear"></div>
            <div class="cae-data">
                CAE Nº: {{ $venta->cae ?? '74123456789012' }}<br>
                Vto. CAE: {{ $venta->cae_vto ?? '20/12/2026' }}
            </div>
        </div>
        <div class="clear"></div>

        <div class="attribution">
            {{ date('Y') }} — Desarrollado por <strong>MultiPOS SaaS</strong> (gentepiola.net) | Pág. 1 de 1
        </div>
    </div>

</div>

</body>
</html>
