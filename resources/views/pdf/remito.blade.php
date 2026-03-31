<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Remito {{ $remito->id }}</title>
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
        
        .doc-title { font-size: 16pt; font-weight: 900; margin: 0 0 8px 0; text-align: right; color: #000; line-height: 1; padding-right: 0.5cm; }

        .doc-num { font-size: 14pt; font-weight: 900; text-align: right; margin-bottom: 8px; padding-right: 0.5cm; }
        .doc-data p { margin: 0 0 5px 0; font-size: 9pt; text-align: right; padding-right: 0.5cm; }

        /* SECCIONES TRANSVERSALES */
        .section-bar { background: #f5f5f5; border-top: 1.2px solid #000; border-bottom: 1.2px solid #000; padding: 8px 15px; margin-top: -1px; }
        .label { font-weight: bold; color: #000; text-transform: uppercase; font-size: 8.5pt; margin-right: 5px; }

        /* TABLA DE ITEMS */
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { background: #000; color: #fff; padding: 8px; text-transform: uppercase; font-size: 8.5pt; text-align: center; border: 1px solid #000; }
        .items-table td { padding: 10px 8px; font-size: 9.5pt; color: #000; border-bottom: 1px solid #eee; }
        
        /* FOOTER CON FIRMA */
        .footer-container { 
            position: absolute; 
            bottom: 0; 
            left: 0; 
            width: 100%; 
            padding: 30px 25px; 
            box-sizing: border-box; 
        }
        
        .footer-separator { border-top: 1.5px solid #000; margin-bottom: 25px; width: 100%; }
        
        .signature-box { float: right; width: 45%; border-top: 1px solid #000; margin-top: 40px; text-align: center; padding-top: 5px; }
        .obs-box { float: left; width: 50%; padding: 8px; background: #f9f9f9; border: 1px dashed #bbb; min-height: 60px; }

        .attribution { text-align: center; font-size: 7.5pt; color: #666; width: 100%; position: absolute; bottom: 10px; font-style: italic; }
        .clear { clear: both; }

        /* LOGO CABECERA */
        .company-logo { max-width: 220px; max-height: 85px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>

@php
    $empresa = $remito->venta->empresa;
    $venta = $remito->venta;
@endphp

<div class="invoice-box">
    
    <div class="header-center">
        <span class="letter">R</span>
        <span class="cod">No Válido Fac</span>
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
                <h1 class="doc-title">REMITO DE ENTREGA</h1>
                <div class="doc-num">{{ $remito->numero_remito ?: 'REM-'.str_pad($remito->id, 8, '0', STR_PAD_LEFT) }}</div>
                <div class="doc-data">
                    <p><strong>Fecha Entrega:</strong> {{ $remito->fecha_entrega->format('d/m/Y') }}</p>
                    <p><strong>Hora:</strong> {{ $remito->fecha_entrega->format('H:i') }} hs</p>
                    <p><strong>Venta Origen:</strong> #{{ $venta->id }}</p>
                    <p><strong>Usuario:</strong> {{ $remito->user->name }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-bar">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="55%"><span class="label">Entregado a:</span> <span style="font-size: 10pt; font-weight: 900;">{{ strtoupper($remito->cliente->name ?? 'CONSUMIDOR FINAL') }}</span></td>
                <td width="45%"><span class="label">CUIT / DNI:</span> {{ $remito->cliente->document ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px;"><span class="label">Domicilio Entrega:</span> {{ $remito->cliente->address ?? '-' }}</td>
                <td style="padding-top: 5px;"><span class="label">Cond. IVA:</span> {{ $remito->cliente->condicion_iva ?? 'Consumidor Final' }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="15%">Cod.</th>
                <th width="65%" style="text-align: left;">Descripción de Mercadería Entregada</th>
                <th width="20%">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($remito->items as $item)
            <tr>
                <td style="text-align: center; color: #444;">{{ $item->product->sku ?? $item->product_id }}</td>
                <td>
                    <span style="font-weight: bold;">{{ $item->product->name }}</span>
                    @if($item->variant) <small style="color: #666;">[{{ $item->variant->size }}/{{ $item->variant->color }}]</small> @endif
                    <div style="font-size: 7.5pt; color: #666; margin-top: 3px;">
                        Facturado en Venta #{{ $venta->id }}
                    </div>
                </td>
                <td style="text-align: center; font-size: 11pt; font-weight: bold;">
                    {{ number_format($item->cantidad, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-container">
        
        <div class="footer-separator"></div>

        <div class="obs-box">
            <span class="label" style="font-size: 7pt;">OBSERVACIONES / DATOS DE RETIRO:</span><br>
            <div style="font-size: 8.5pt; margin-top: 5px;">
                {{ $remito->observaciones ?: 'Sin observaciones.' }}
            </div>
        </div>

        <div class="signature-box">
            <span class="label">CONFORMIDAD DEL CLIENTE</span><br>
            <span style="font-size: 7pt; color: #444;">Firma, Aclaración y DNI</span>
        </div>
        
        <div class="clear"></div>

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; font-size: 7pt; color: #666;">
            * El presente remito no es válido como factura. Los productos detallados fueron facturados previamente en la venta indicada en la cabecera.
        </div>

        <div class="attribution">
            MultiPOS SaaS (gentepiola.net) | Comprobante de Entrega Física | Pág. 1 de 1
        </div>
    </div>

</div>

</body>
</html>
