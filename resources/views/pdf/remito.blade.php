<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Remito {{ $remito->id }}</title>
    <style>
        @page { margin: 1cm; size: A4; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            color: #333; 
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        .invoice-box { width: 100%; position: relative; min-height: 27cm; }

        /* Encabezado */
        .header-table { width: 100%; border-bottom: 1px solid #000; margin-bottom: 10px; table-layout: fixed; }
        .header-table td { vertical-align: top; padding: 5px; }
        
        .company-name { font-size: 16pt; font-weight: bold; color: #000; margin-bottom: 2px; }
        .company-data { font-size: 8pt; color: #444; }

        /* Letra R Central */
        .doc-type-box {
            position: absolute;
            top: 0;
            left: 50%;
            margin-left: -20px;
            width: 40px;
            height: 40px;
            border: 1px solid #000;
            background: #fff;
            text-align: center;
            z-index: 10;
        }
        .doc-type-letter { font-size: 24pt; font-weight: bold; line-height: 35px; }
        .doc-type-code { font-size: 5pt; font-weight: bold; margin-top: -5px; display: block; }

        .doc-title { font-size: 14pt; font-weight: bold; margin: 0; text-transform: uppercase; }
        .doc-num { font-size: 11pt; font-weight: bold; margin: 5px 0; }

        .section-bar { 
            background: #f0f0f0; 
            padding: 5px 10px; 
            border: 1px solid #ccc; 
            font-weight: bold; 
            font-size: 9pt; 
            margin: 10px 0;
            text-transform: uppercase;
        }

        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { background: #333; color: #fff; text-align: left; padding: 7px 10px; font-size: 8.5pt; }
        .items-table td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 9pt; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .footer-container { position: absolute; bottom: 0; width: 100%; border-top: 2px solid #000; padding-top: 10px; }
        .obs-box { font-size: 8pt; color: #666; padding: 10px; background: #f9f9f9; border: 1px dashed #ccc; width: 50%; float: left; }
        .signature-box { float: right; width: 40%; border-top: 1px solid #000; margin-top: 30px; text-align: center; padding-top: 5px; font-size: 8pt; }
    </style>
</head>
<body>

@php
    $empresa = $remito->venta->empresa;
    $venta = $remito->venta;
@endphp

<div class="invoice-box">
    <div class="doc-type-box">
        <span class="doc-type-letter">R</span>
        <span class="doc-type-code">REMITO</span>
    </div>

    <table class="header-table">
        <tr>
            <td width="48%">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" style="max-height: 80px; margin-bottom: 5px;">
                @else
                    <div class="company-name">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
                @endif
                <div class="company-data">
                    <p>{{ $empresa->direccion_fiscal ?? '-' }}</p>
                    <p>Tel: {{ $empresa->telefono ?? '-' }}</p>
                    <p>CUIT: {{ $empresa->arca_cuit ?? $empresa->cuit }}</p>
                </div>
            </td>
            <td width="4%"></td>
            <td width="48%" style="text-align: right;">
                <h1 class="doc-title">REMITO DE ENTREGA</h1>
                <div class="doc-num">{{ $remito->numero_remito ?: 'REM-'.str_pad($remito->id, 8, '0', STR_PAD_LEFT) }}</div>
                <div class="company-data">
                    <p><strong>Fecha Entrega:</strong> {{ $remito->fecha_entrega->format('d/m/Y') }}</p>
                    <p><strong>Hora:</strong> {{ $remito->fecha_entrega->format('H:i') }} hs</p>
                    <p><strong>Usuario:</strong> {{ $remito->user->name }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-bar">Datos del Destinatario</div>
    <div style="padding: 10px;">
        <table width="100%">
            <tr>
                <td><strong>Entregado a:</strong> {{ $remito->cliente->name ?? 'Consumidor Final' }}</td>
                <td style="text-align: right;"><strong>CUIT/DNI:</strong> {{ $remito->cliente->document ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Domicilio Entrega:</strong> {{ $remito->cliente->address ?? '-' }}</td>
                <td style="text-align: right;"><strong>Venta Origen:</strong> #{{ $venta->id }}</td>
            </tr>
        </table>
    </div>

    <div class="section-bar">Mercadería Detallada</div>
    <table class="items-table">
        <thead>
            <tr>
                <th width="15%" class="text-center">Cant.</th>
                <th width="65%">Descripción del Producto</th>
                <th width="20%">Código / Ref.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($remito->items as $item)
            <tr>
                <td class="text-center"><strong>{{ number_format($item->cantidad, 2, ',', '.') }}</strong></td>
                <td>
                    {{ $item->product->name }}
                    @if($item->variant) <small style="color: #666;">[{{ $item->variant->size }}/{{ $item->variant->color }}]</small> @endif
                </td>
                <td>{{ $item->product->sku ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-container" style="margin-top: 50px;">
        <div class="obs-box">
            <strong>OBSERVACIONES:</strong><br>
            {{ $remito->observaciones ?: 'Sin observaciones.' }}
        </div>
        
        <div class="signature-box">
            CONFORMIDAD DEL CLIENTE<br>
            <span style="font-size: 7pt; color: #666;">Firma, Aclaración y DNI</span>
        </div>

        <div style="clear: both;"></div>

        <div style="font-size: 8pt; color: #888; text-align: center; margin-top: 20px;">
            MultiPOS Commercial Suite | EL PRESENTE REMITO NO ES VÁLIDO COMO FACTURA
        </div>
    </div>
</div>

</body>
</html>
