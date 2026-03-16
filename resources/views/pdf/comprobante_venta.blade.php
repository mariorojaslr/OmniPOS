<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Venta #{{ $venta->numero_comprobante }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .header { width: 100%; border: 1px solid #000; padding: 10px; margin-bottom: 5px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .logo-section { width: 40%; vertical-align: middle; }
        .center-section { width: 20%; text-align: center; vertical-align: middle; }
        .comp-section { width: 40%; text-align: right; vertical-align: middle; }
        
        .x-box { border: 1px solid #000; display: inline-block; padding: 5px 15px; font-size: 24px; font-weight: bold; }
        
        .empresa-info { font-weight: bold; font-size: 10px; }
        .empresa-info p { margin: 2px 0; }
        
        .comp-title { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .comp-num { font-size: 14px; font-weight: bold; }
        .comp-date { font-size: 14px; font-weight: bold; }
        
        .details { width: 100%; border: 1px solid #000; padding: 8px; margin-bottom: 10px; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 3px; }
        
        .items-table { width: 100%; border: 1px solid #000; border-collapse: collapse; margin-bottom: 10px; }
        .items-table th { background: #eee; border: 1px solid #000; padding: 5px; text-align: center; }
        .items-table td { border-left: 1px solid #000; border-right: 1px solid #000; padding: 5px; }
        .items-table tfoot td { border-top: 1px solid #000; padding: 5px; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        
        .footer { width: 100%; position: absolute; bottom: 20px; }
        .totals-table { width: 40%; float: right; border: 1px solid #000; border-collapse: collapse; }
        .totals-table td { padding: 5px; border-bottom: 1px solid #ccc; }
        
        .obs-box { border: 1px solid #000; padding: 5px; width: 100%; min-height: 20px; margin-top: 5px; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <table class="header-table">
        <tr>
            <td class="logo-section">
                @if($empresa->config && $empresa->config->logo)
                    <img src="{{ public_path('storage/' . $empresa->config->logo) }}" style="max-height: 50px;">
                @else
                    <h2 style="margin: 0;">{{ $empresa->nombre_comercial }}</h2>
                @endif
                <div class="empresa-info">
                    <p>{{ $empresa->razon_social }}</p>
                    <p>{{ $empresa->direccion_fiscal }}</p>
                    <p>TEL: {{ $empresa->telefono }}</p>
                </div>
            </td>
            <td class="center-section">
                <div class="x-box">X</div>
            </td>
            <td class="comp-section">
                <div class="comp-title">COMPROBANTE</div>
                <div class="comp-num">Nº {{ $venta->numero_comprobante }}</div>
                <div class="comp-date">FECHA: {{ $venta->created_at->format('d/m/Y') }}</div>
                <div style="font-size: 8px; margin-top: 5px;">
                    @if($empresa->condicion_iva) {{ $empresa->condicion_iva }} @endif
                    @if($empresa->cuit) CUIT: {{ $empresa->cuit }} @endif
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="details">
    <table class="details-table">
        <tr>
            <td width="60%">
                <span class="fw-bold">SEÑOR/ES:</span> {{ $venta->client_id ? $venta->cliente->name : 'CONSUMIDOR FINAL' }}
            </td>
            <td width="40%">
                <span class="fw-bold">CUIT:</span> {{ $venta->client_id ? $venta->cliente->document : '' }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="fw-bold">DOMICILIO:</span> {{ $venta->client_id ? $venta->cliente->address : '' }}
            </td>
            <td>
                <span class="fw-bold">LOCALIDAD:</span> {{ $venta->client_id ? $venta->cliente->city : '' }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="fw-bold">VENDEDOR:</span> {{ $venta->user->name ?? '' }}
            </td>
            <td>
                <span class="fw-bold">CONDICION PAGO:</span> {{ ucfirst($venta->metodo_pago) }}
            </td>
        </tr>
    </table>
</div>

<div class="obs-box">
    OBSERVACIONES:
</div>

<table class="items-table">
    <thead>
        <tr>
            <th width="60%">Descripción</th>
            <th width="10%">Cant.</th>
            <th width="10%">Precio Uni.</th>
            <th width="10%">% Desc</th>
            <th width="10%">Sub Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td class="text-center">{{ number_format($item->cantidad, 2) }}</td>
            <td class="text-right">{{ number_format($item->total_item_con_iva / $item->cantidad, 2) }}</td>
            <td class="text-center">0,00</td>
            <td class="text-right">{{ number_format($item->total_item_con_iva, 2) }}</td>
        </tr>
        @endforeach
        
        {{-- Relleno para que la tabla sea larga --}}
        @for($i = count($venta->items); $i < 15; $i++)
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        @endfor
    </tbody>
</table>

<div class="footer">
    <table class="totals-table">
        <tr>
            <td>SUBTOTAL:</td>
            <td class="text-right">$ {{ number_format($venta->total_con_iva, 2) }}</td>
        </tr>
        <tr>
            <td>DESCUENTO:</td>
            <td class="text-right">$ 0,00</td>
        </tr>
        <tr class="fw-bold">
            <td style="border-bottom: none; font-size: 14px;">TOTAL:</td>
            <td class="text-right" style="border-bottom: none; font-size: 14px;">$ {{ number_format($venta->total_con_iva, 2) }}</td>
        </tr>
    </table>
</div>

</body>
</html>
