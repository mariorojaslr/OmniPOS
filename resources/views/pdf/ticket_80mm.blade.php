<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $venta->numero_comprobante ?? $venta->id }}</title>
    <style>
        @page { margin: 0; size: 80mm 200mm; }
        body { 
            font-family: 'Courier', 'Arial', sans-serif; 
            font-size: 8pt; 
            color: #000; 
            padding: 5mm; 
            margin: 0; 
            width: 70mm;
        }
        .header { text-align: center; margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .logo { max-width: 50mm; max-height: 20mm; margin-bottom: 5px; }
        .company-name { font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 3px; }
        
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        
        .info-row { margin-bottom: 2px; }
        .info-row b { display: inline-block; width: 25mm; }

        .items-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .items-table th { border-bottom: 1px solid #000; text-align: left; padding-bottom: 3px; font-size: 7.5pt; }
        .items-table td { padding: 4px 0; vertical-align: top; }
        
        .totals { text-align: right; margin-top: 10px; }
        .total-amount { font-size: 14pt; font-weight: bold; border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 100%; }

        .footer { text-align: center; margin-top: 20px; font-size: 7pt; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>

@php
    $cod_id = ($venta->tipo_comprobante === 'A') ? '001' : '006';
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=https://www.afip.gob.ar/genericos/comprobante/cae.aspx?cuit=".$empresa->cuit."&tipoComprobante=".$cod_id."&puntoVenta=".str_pad($empresa->arca_punto_venta ?? '1', 5, '0', STR_PAD_LEFT)."&cae=".$venta->cae."&fecha=".$venta->created_at->format('Y-m-d');
@endphp

<div class="header">
    @if(isset($logoBase64) && $logoBase64)
        <img src="{{ $logoBase64 }}" class="logo">
    @endif
    <div class="company-name">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
    <div>Cuit: {{ $empresa->cuit }}</div>
    <div>{{ $empresa->direccion_fiscal ?? '-' }}</div>
    <div>{{ $empresa->nombre_comercial }}</div>
</div>

<div class="info-row text-center">
    <b style="width:100%; font-size: 9pt;">{{ strtoupper($venta->tipo_comprobante ?? 'Ticket') }} {{ $venta->numero_comprobante }}</b>
</div>
<div class="info-row text-center">
    {{ $venta->created_at->format('d/m/Y H:i') }} hs
</div>

<div class="divider"></div>

<div class="info-row"><b>Cliente:</b> {{ substr($venta->cliente->name ?? 'CONSUMIDOR FINAL', 0, 20) }}</div>
<div class="info-row"><b>IVA:</b> {{ $venta->cliente->condicion_iva ?? 'Cons. Final' }}</div>
<div class="info-row"><b>Pago:</b> {{ ucfirst($venta->metodo_pago) }}</div>

<table class="items-table">
    <thead>
        <tr>
            <th>DETALLE</th>
            <th class="text-right">SUBT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->items as $item)
        <tr>
            <td>
                {{ $item->cantidad }} x {{ substr($item->product->name, 0, 25) }}
                @if($item->variant) 
                    <br><small>({{ $item->variant->size }}/{{ $item->variant->color }})</small> 
                @endif
            </td>
            <td class="text-right">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    @if($venta->tipo_comprobante === 'A')
        <div>Subtotal: $ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</div>
        <div>IVA 21%: $ {{ number_format($venta->total_iva, 2, ',', '.') }}</div>
    @endif
    <div class="total-amount">
        TOTAL: $ {{ number_format($venta->total_con_iva, 2, ',', '.') }}
    </div>
</div>

<div class="footer">
    <div class="bold">Comprobante Autorizado</div>
    <div>CAE: {{ $venta->cae ?? 'N/A' }}</div>
    <div>Vto: {{ $venta->cae_vto ?? 'N/A' }}</div>
    
    <div class="text-center">
        <img src="{{ $qrUrl }}" style="width: 35mm; margin-top: 10px;">
    </div>
    
    <div class="divider"></div>
    <div style="font-style: italic;">
        Gracias por su compra<br>
        MultiPOS SaaS - gentepiola.net
    </div>
</div>

</body>
</html>
