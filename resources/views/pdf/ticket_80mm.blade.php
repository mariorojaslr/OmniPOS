<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $venta->numero_comprobante ?? $venta->id }}</title>
    <style>
        @page { margin: 0; size: 80mm auto; }
        body { 
            font-family: 'monospace', sans-serif; 
            font-size: 9pt; 
            color: #000; 
            padding: 2mm; 
            margin: 0; 
            width: 76mm; 
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        
        .header { text-align: center; margin-bottom: 5px; }
        .logo { max-width: 45mm; max-height: 18mm; margin-bottom: 5px; }
        .company-name { font-size: 12pt; font-weight: bold; margin-bottom: 2px; }
        .company-info { font-size: 8pt; line-height: 1.2; }
        
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        
        .doc-info { text-align: center; font-weight: bold; margin: 5px 0; }
        .doc-num { font-size: 11pt; }

        .client-info { font-size: 8.5pt; margin-bottom: 5px; line-height: 1.3; }

        .items-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .items-table th { border-bottom: 1px dashed #000; padding: 3px 0; font-size: 8pt; }
        .items-table td { padding: 4px 0; vertical-align: top; font-size: 8.5pt; }
        
        .item-row-divider { border-bottom: 1px dotted #ccc; }

        .totals-box { margin-top: 10px; border-top: 1px solid #000; padding-top: 5px; }
        .total-line { font-size: 13pt; font-weight: bold; display: flex; justify-content: space-between; }

        .extra-info { font-size: 8pt; margin-top: 10px; text-align: center; }

        .afip-footer { margin-top: 15px; width: 100%; border-top: 1px dashed #000; padding-top: 10px; }
        .qr-column { float: left; width: 35mm; }
        .cae-column { float: right; width: 35mm; font-size: 7.5pt; padding-top: 5px; line-height: 1.4; }
        .qr-img { width: 32mm; height: 32mm; }

        .clear { clear: both; }
        .thanks { text-align: center; font-style: italic; font-weight: bold; margin-top: 15px; font-size: 9pt; }
    </style>
</head>
<body>

@php
    $hasCae = !empty($venta->cae);
    $letra = "X";
    $cod_id = "000";
    $esA = false;

    if($hasCae){
        if($empresa->condicion_iva === 'Monotributista'){
            $letra = "C"; $cod_id = "011";
        } else {
            $taxCondition = strtoupper(trim($venta->cliente->condicion_iva ?? ''));
            $esA = ($taxCondition === 'RESPONSABLE INSCRIPTO' || $taxCondition === 'RESPONSABLE_INSCRIPTO');
            $letra = $esA ? "A" : "B";
            $cod_id = $esA ? "001" : "006";
        }
        $titulo_comprobante = "FACTURA " . $letra;
    } else {
        $letra = "X";
        $titulo_comprobante = (strtoupper($venta->tipo_comprobante ?? 'B') === 'TICKET') ? "TICKET" : "DOC. NO FISCAL";
    }
    
    $numeroCompleto = $venta->numero_comprobante ?: (str_pad($empresa->arca_punto_venta ?? '12', 4, '0', STR_PAD_LEFT) . '-' . str_pad($venta->id, 8, '0', STR_PAD_LEFT));
    $itemsCount = $venta->items->sum('cantidad');
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
        Cond. IVA: {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}
    </div>
</div>

<div class="doc-info">
    {{ $titulo_comprobante }} "{{ $letra }}"<br>
    <span class="doc-num">N&deg; {{ $numeroCompleto }}</span>
</div>

<div class="divider"></div>

<div class="client-info">
    <strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y') }} &nbsp; <strong>Hora:</strong> {{ $venta->created_at->format('H:i:s') }}<br>
    <strong>CLIENTE:</strong> {{ strtoupper($venta->cliente->name ?? 'CONSUMIDOR FINAL') }}<br>
    <strong>IVA:</strong> {{ $venta->cliente->condicion_iva ?? 'Consumidor Final' }}<br>
    <strong>CUIT/DNI:</strong> {{ $venta->cliente->document ?? '-' }}
</div>

<div class="divider"></div>

<table class="items-table">
    <thead>
        <tr>
            <th class="text-left">Descripción</th>
            <th class="text-right">Importe</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->items as $item)
        <tr class="item-row-divider">
            <td style="padding-bottom: 2px;">
                <div style="font-size: 7.5pt;">{{ number_format($item->cantidad, 0) }} x $ {{ number_format($item->precio_unitario, 2, ',', '.') }}</div>
                <div class="bold">{{ strtoupper($item->product->name) }}</div>
                @if($item->variant) <small>({{ $item->variant->size }}/{{ $item->variant->color }})</small> @endif
            </td>
            <td class="text-right bold" style="vertical-align: bottom;">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals-box">
    @if($esA)
        <table width="100%" style="font-size: 9pt; margin-bottom: 5px;">
            <tr>
                <td>Subtotal Neto</td>
                <td class="text-right">$ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>IVA 21%</td>
                <td class="text-right">$ {{ number_format($venta->total_iva, 2, ',', '.') }}</td>
            </tr>
        </table>
    @endif
    <table width="100%">
        <tr style="font-size: 13pt; font-weight: 900;">
            <td>TOTAL</td>
            <td class="text-right">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
        </tr>
    </table>
</div>

<div class="extra-info">
    FORMA DE PAGO: {{ strtoupper($venta->metodo_pago ?? 'EFECTIVO') }}<br>
    CANTIDAD DE ARTÍCULOS: {{ (int)$itemsCount }}<br>
    CAJERO: {{ strtoupper($user->name ?? 'SISTEMA') }}
</div>

<div class="afip-footer">
    <div class="qr-column">
        @if($venta->qr_data)
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->generate('https://www.afip.gob.ar/fe/qr/?p=' . $venta->qr_data)) !!}" class="qr-img">
        @endif
    </div>
    <div class="cae-column">
        <strong>Código ARCA:</strong><br>
        CAE: {{ $venta->cae ?? '-' }}<br>
        Vto: {{ $venta->cae_vencimiento ? \Carbon\Carbon::parse($venta->cae_vencimiento)->format('d/m/Y') : '-' }}
    </div>
    <div class="clear"></div>
    <div style="font-size: 7pt; text-align: center; margin-top: 8px;">
        Esta administración no se responsabiliza por los datos declarados en este comprobante.
    </div>
</div>

<div class="thanks">
    ¡GRACIAS POR SU COMPRA!<br>
    MultiPOS SaaS - gentepiola.net
</div>

</body>
</html>
