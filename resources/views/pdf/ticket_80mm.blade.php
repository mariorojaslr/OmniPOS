<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $venta->numero_comprobante ?? $venta->id }}</title>
    <style>
        @page { margin: 0; size: 80mm 200mm; }
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 8.5pt; 
            color: #000; 
            padding: 4mm; 
            margin: 0; 
            width: 72mm; /* Aprovechamiento máximo para 80mm */
        }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 1.5px dashed #333; padding-bottom: 10px; }
        .logo { max-width: 45mm; max-height: 18mm; margin-bottom: 8px; }
        .company-name { font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .company-info { font-size: 8pt; color: #333; }
        
        .divider { border-top: 1px dashed #333; margin: 8px 0; }
        
        .info-row { margin-bottom: 4px; font-size: 8.5pt; }
        .info-row b { display: inline-block; width: 20mm; }

        .items-table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .items-table th { border-bottom: 1px solid #000; text-align: left; padding-bottom: 5px; font-size: 8pt; text-transform: uppercase; }
        .items-table td { padding: 6px 0; vertical-align: top; border-bottom: 0.5px solid #eee; }
        
        .totals { text-align: right; margin-top: 15px; }
        .total-amount { font-size: 15pt; font-weight: bold; border-top: 2px solid #000; padding-top: 6px; display: inline-block; width: 100%; }

        .footer { text-align: center; margin-top: 20px; font-size: 8pt; border-top: 1.5px dashed #333; padding-top: 15px; }
        .cae-box { margin-bottom: 12px; font-weight: bold; font-family: 'Courier New', monospace; }
        
        .qr-img { width: 40mm; height: 40mm; margin: 10px 0; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>

@php
    $tipo = strtoupper($venta->tipo_comprobante ?? 'B');
    $esA = ($tipo === 'A' || $tipo === 'FACTURA A');
    $letra = $esA ? 'A' : 'B';
    $cod_id = $esA ? '001' : '006';
    
    // El número que viene de AFIP o el interno
    $numeroCompleto = $venta->numero_comprobante ?: (str_pad($empresa->arca_punto_venta ?? '12', 4, '0', STR_PAD_LEFT) . '-' . str_pad($venta->id, 8, '0', STR_PAD_LEFT));
@endphp

<div class="header">
    @if(isset($logoBase64) && $logoBase64)
        <img src="{{ $logoBase64 }}" class="logo">
    @endif
    <div class="company-name">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
    <div class="company-info">
        CUIT: {{ $empresa->cuit }}<br>
        {{ $empresa->direccion_fiscal ?? '-' }}<br>
        {{ $empresa->nombre_comercial ?? 'Casa Central' }}<br>
        Cond. IVA: {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}
    </div>
</div>

<div class="text-center" style="margin-bottom: 2px;">
    <strong>FACTURA "{{ $letra }}"</strong>
</div>
<div class="text-center bold" style="font-size: 10pt; margin-bottom: 4px;">
    {{ $numeroCompleto }}
</div>
<div class="text-center divider">
    {{ $venta->created_at->format('d/m/Y H:i') }} hs
</div>

<div class="info-row"><b>Cliente:</b> {{ substr($venta->cliente->name ?? 'CONSUMIDOR FINAL', 0, 28) }}</div>
<div class="info-row"><b>Doc:</b> {{ $venta->cliente->document ?? '-' }} ({{ $venta->cliente->condicion_iva ?? 'Cons. Final' }})</div>
<div class="info-row"><b>Método:</b> {{ ucfirst($venta->metodo_pago) }}</div>

<table class="items-table">
    <thead>
        <tr>
            <th>DETALLE</th>
            <th class="text-right">SUBTOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->items as $item)
        <tr>
            <td>
                {{ number_format($item->cantidad, 0) }} x {{ substr($item->product->name, 0, 30) }}
                @if($item->variant) 
                    <br><small style="color: #666;">({{ $item->variant->size }}/{{ $item->variant->color }})</small> 
                @endif
            </td>
            <td class="text-right bold">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    @if($esA)
        <div>Subtotal Neto: $ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</div>
        <div>IVA 21%: $ {{ number_format($venta->total_iva, 2, ',', '.') }}</div>
    @endif
    <div class="total-amount">
        TOTAL: $ {{ number_format($venta->total_con_iva, 2, ',', '.') }}
    </div>
</div>

<div class="footer">
    <div class="cae-box">
        CAE: {{ $venta->cae ?? 'PENDIENTE' }}<br>
        VTO: {{ $venta->cae_vencimiento ? \Carbon\Carbon::parse($venta->cae_vencimiento)->format('d/m/Y') : '-' }}
    </div>
    
    @if(isset($qrUrl) && $qrUrl)
    <div class="text-center">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}" class="qr-img">
    </div>
    @endif
    
    <div style="font-size: 7.5pt; line-height: 1.2; margin-top: 5px;">
        Esta administración no se responsabiliza por los datos declarados en este comprobante.
    </div>

    <div class="divider"></div>
    <div style="font-style: italic; font-weight: bold; margin-bottom: 20px;">
        ¡GRACIAS POR SU COMPRA!<br>
        MultiPOS SaaS - gentepiola.net
    </div>
</div>

</body>
</html>
