<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 10pt; 
            width: 70mm; /* Ajustado para dejar margen derecho en papel de 80mm */
            margin: 0;
            padding: 2mm;
            color: #000;
        }

        .header { text-align: center; margin-bottom: 5mm; }
        .logo { max-width: 60mm; max-height: 25mm; margin-bottom: 3mm; }
        .company-name { font-size: 12pt; font-weight: bold; margin-bottom: 1mm; text-transform: uppercase; }
        .company-info { font-size: 8pt; line-height: 1.2; margin-bottom: 2mm; }

        .divider { border-top: 1px dashed #000; margin: 3mm 0; }
        .double-divider { border-top: 2px double #000; margin: 3mm 0; }

        .doc-info { text-align: center; margin-bottom: 4mm; }
        .doc-title { font-size: 11pt; font-weight: bold; text-transform: uppercase; }
        .doc-num { font-size: 10pt; font-weight: bold; }

        .data-row { font-size: 8pt; margin-bottom: 1mm; }
        .label { font-weight: bold; }

        .items-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
        .items-table th { text-align: left; border-bottom: 1px solid #000; padding: 1mm 0; }
        .items-table td { padding: 1.5mm 0; vertical-align: top; }
        .text-right { text-align: right; }

        .totals-box { margin-top: 4mm; }
        .total-row { font-size: 13pt; font-weight: bold; }

        .qr-section { text-align: center; margin-top: 6mm; }
        .qr-img { width: 40mm; height: 40mm; margin-bottom: 3mm; }
        .arca-legal { font-size: 7pt; line-height: 1.1; margin-top: 2mm; }
        .cae-box { font-size: 9pt; font-weight: bold; margin-top: 2mm; }

        .footer-msg { text-align: center; font-size: 8pt; font-style: italic; margin-top: 5mm; }
    </style>
</head>
<body>

@php
    $hasCae = !empty($venta->cae);
    $letra = "X";
    
    if($hasCae){
        $taxCondEmpresa = strtoupper(trim($empresa->condicion_iva ?? ''));
        if(strpos($taxCondEmpresa, 'MONOTRIBUTO') !== false){
            $letra = "C";
        } else {
            $taxCondition = strtoupper(trim($venta->cliente->tax_condition ?? ''));
            $esA = (strpos($taxCondition, 'RESPONSABLE INSCRIPTO') !== false || strpos($taxCondition, 'RESPONSABLE_INSCRIPTO') !== false);
            $letra = $esA ? "A" : "B";
        }
    }
    
    $titulo_comprobante = $hasCae ? "FACTURA " . $letra : "TICKET DE GESTIÓN";
    $numeroCompleto = $venta->numero_comprobante;
@endphp

<div class="header">
    @if(isset($logoBase64) && $logoBase64)
        <img src="{{ $logoBase64 }}" class="logo">
    @else
        <div class="company-name">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
    @endif
    <div class="company-info">
        CUIT: {{ $empresa->arca_cuit ?? $empresa->cuit }}<br>
        IIBB: {{ $empresa->iibb ?? '-' }}<br>
        {{ $empresa->direccion_fiscal ?? '-' }}<br>
        Inicio Act: {{ $empresa->inicio_actividad ?? '-' }}<br>
        Cond. IVA: {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}
    </div>
</div>

<div class="doc-info">
    <div class="doc-title">{{ $titulo_comprobante }}</div>
    <div class="doc-num">N&deg; {{ $numeroCompleto }}</div>
</div>

<div class="divider"></div>

<div class="data-row"><span class="label">FECHA:</span> {{ $venta->created_at->format('d/m/Y H:i:s') }}</div>
<div class="data-row"><span class="label">CLIENTE:</span> {{ $venta->cliente->name ?? 'Consumidor Final' }}</div>
<div class="data-row"><span class="label">CUIT/DNI:</span> {{ $venta->cliente->document ?? '-' }}</div>
<div class="data-row"><span class="label">IVA:</span> {{ $venta->cliente->tax_condition ?? 'Consumidor Final' }}</div>

<div class="divider"></div>

<table class="items-table">
    <thead>
        <tr>
            <th width="15%">Cant</th>
            <th width="50%">Desc</th>
            <th width="35%" class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->items as $item)
            <tr>
                <td>{{ number_format($item->cantidad, 0) }} x</td>
                <td>{{ $item->product->name }} @if($item->variant) - {{ $item->variant->name }} @endif</td>
                <td class="text-right">${{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="double-divider"></div>

<div class="totals-box">
    @if($letra === 'A')
        <table width="100%" style="font-size: 9pt;">
            <tr>
                <td>SUBTOTAL NETO:</td>
                <td class="text-right">${{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>IVA 21%:</td>
                <td class="text-right">${{ number_format($venta->total_iva, 2, ',', '.') }}</td>
            </tr>
        </table>
        <div class="divider"></div>
    @endif
    
    <table width="100%" class="total-row">
        <tr>
            <td>TOTAL:</td>
            <td class="text-right">${{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
        </tr>
    </table>
</div>

<div class="divider"></div>

<div class="data-row"><span class="label">PAGO:</span> {{ strtoupper($venta->metodo_pago) }}</div>
<div class="data-row"><span class="label">CAJERO:</span> {{ strtoupper($venta->user->name) }}</div>

@if($hasCae)
    <div class="qr-section">
        @if($venta->qr_data)
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('https://www.afip.gob.ar/fe/qr/?p=' . $venta->qr_data)) !!}" class="qr-img">
        @endif
        <div style="font-size: 16pt; font-weight: 900; color: #000; margin-bottom: -2px;">ARCA</div>
        <div style="font-size: 5pt; font-weight: bold; color: #000; text-transform: uppercase; margin-bottom: 3px;">Agencia de Recaudación y Control Aduanero</div>
        <div class="cae-box">
            CAE: {{ $venta->cae }}<br>
            Vto. CAE: {{ \Carbon\Carbon::parse($venta->cae_vencimiento)->format('d/m/Y') }}
        </div>
        <div class="arca-legal">
            Comprobante Autorizado por AFIP/ARCA.<br>
            QR generado según RG AFIP 4892.
        </div>
    </div>
@endif

<div class="footer-msg">
    ¡Gracias por su compra!<br>
    {{ $empresa->nombre_comercial }}
</div>

</body>
</html>
