<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Nº {{ $venta->numero_comprobante }}</title>
    <style>
        @page { margin: 0.8cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #000;
            line-height: 1.2;
        }

        /* ====== CABECERA TIPO FACTURA ====== */
        .header-box {
            width: 100%;
            height: 110px;
            border: 1px solid #000;
            position: relative;
            margin-bottom: 5px;
        }

        .header-left {
            width: 48%;
            height: 100%;
            padding: 10px;
            float: left;
        }

        .header-divider {
            position: absolute;
            left: 50%;
            top: 0;
            width: 1px;
            height: 100%;
            background: #000;
        }

        .letter-x {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 40px;
            height: 45px;
            border: 1px solid #000;
            border-top: none;
            background: #fff;
            text-align: center;
            z-index: 20;
        }

        .letter-x .letter {
            font-size: 24px;
            font-weight: bold;
            margin-top: 2px;
        }

        .letter-x .sub {
            font-size: 5px;
            line-height: 1;
            font-weight: bold;
        }

        .header-right {
            width: 48%;
            height: 100%;
            padding: 10px;
            float: right;
            text-align: right;
        }

        .logo {
            max-width: 140px;
            max-height: 50px;
            margin-bottom: 5px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }

        .company-info {
            font-size: 8px;
        }

        .comp-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .comp-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* ====== BLOQUE CLIENTE / DATOS ====== */
        .data-box {
            width: 100%;
            border: 1px solid #000;
            margin-bottom: 5px;
        }

        .data-row {
            width: 100%;
            border-bottom: 1px solid #000;
            display: table;
            table-layout: fixed;
        }

        .data-row:last-child {
            border-bottom: none;
        }

        .data-col {
            display: table-cell;
            padding: 4px 8px;
            border-right: 1px solid #000;
            vertical-align: middle;
        }

        .data-col:last-child {
            border-right: none;
        }

        .label {
            font-weight: bold;
        }

        /* ====== TABLA DE ITEMS ====== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 5px;
        }

        .items-table th {
            background: #e2e2e2;
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-weight: bold;
        }

        .items-table td {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            padding: 4px 6px;
            height: 18px;
        }

        .items-table tr.item-row td {
            border-bottom: 0.5px solid #eee;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* ====== TOTALES ====== */
        .footer-container {
            width: 100%;
        }

        .observations-box {
            width: 63%;
            border: 1px solid #000;
            padding: 5px;
            min-height: 40px;
            float: left;
        }

        .totals-box {
            width: 35%;
            border: 1px solid #000;
            float: right;
        }

        .total-row {
            width: 100%;
            border-bottom: 1px solid #000;
            display: table;
        }

        .total-row:last-child {
            border-bottom: none;
        }

        .total-label {
            display: table-cell;
            padding: 5px 8px;
            width: 50%;
            border-right: 1px solid #000;
            font-weight: bold;
        }

        .total-val {
            display: table-cell;
            padding: 5px 8px;
            text-align: right;
        }

        .grand-total {
            font-size: 12px;
            background: #f2f2f2;
        }

        .clearfix {
            clear: both;
        }

        .footer-text {
            text-align: center;
            font-size: 7px;
            margin-top: 10px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="header-box">
        <div class="header-left">
            @php
                $logoPath = '';
                if ($empresa->config && $empresa->config->logo) {
                    if (str_starts_with($empresa->config->logo, 'http')) {
                        $logoPath = $empresa->config->logo;
                    } else {
                        $logoPath = public_path('storage/' . $empresa->config->logo);
                    }
                }
            @endphp

            @if($logoPath && (file_exists($logoPath) || str_starts_with($logoPath, 'http')))
                <img src="{{ $logoPath }}" class="logo">
            @else
                <span class="company-name">{{ strtoupper($empresa->nombre_comercial) }}</span>
            @endif

            <div class="company-info">
                <p><strong>Razón Social:</strong> {{ $empresa->razon_social ?? $empresa->nombre_comercial }}</p>
                <p><strong>Dirección:</strong> {{ $empresa->direccion_fiscal ?? $empresa->direccion }}</p>
                <p><strong>Teléfono:</strong> {{ $empresa->telefono }}</p>
            </div>
        </div>

        <div class="header-divider"></div>

        <div class="letter-x">
            <div class="letter">X</div>
            <div class="sub">DOC. NO VÁLIDO<br>COMO FACTURA</div>
        </div>

        <div class="header-right">
            <h1 class="comp-title">COMPROBANTE</h1>
            <h2 class="comp-number">Nº {{ $venta->numero_comprobante }}</h2>
            <p><strong>FECHA:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
            
            <div class="company-info" style="margin-top: 5px;">
                <p><strong>CUIT:</strong> {{ $empresa->cuit }}</p>
                <p><strong>IIBB:</strong> {{ $empresa->iibb }}</p>
                <p><strong>Inicio de Actividades:</strong> {{ $empresa->inicio_actividades ? \Carbon\Carbon::parse($empresa->inicio_actividades)->format('d/m/Y') : '-' }}</p>
                <p><strong>Condición IVA:</strong> {{ $empresa->condicion_iva }}</p>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="data-box">
        <div class="data-row">
            <div class="data-col" style="width: 65%;">
                <span class="label">SEÑOR/ES:</span> {{ optional($venta->cliente)->name ?? 'CONSUMIDOR FINAL' }}
            </div>
            <div class="data-col" style="width: 35%;">
                <span class="label">CUIT/DNI:</span> {{ optional($venta->cliente)->document ?? '-' }}
            </div>
        </div>
        <div class="data-row">
            <div class="data-col" style="width: 50%;">
                <span class="label">DOMICILIO:</span> {{ optional($venta->cliente)->address ?? '-' }}
            </div>
            <div class="data-col" style="width: 25%;">
                <span class="label">LOCALIDAD:</span> {{ optional($venta->cliente)->city ?? '-' }}
            </div>
            <div class="data-col" style="width: 25%;">
                <span class="label">PROVINCIA:</span> {{ optional($venta->cliente)->province ?? '-' }}
            </div>
        </div>
        <div class="data-row">
            <div class="data-col" style="width: 33%;">
                <span class="label">IVA:</span> {{ optional($venta->cliente)->condicion_iva ?? 'Consumidor Final' }}
            </div>
            <div class="data-col" style="width: 33%;">
                <span class="label">VENDEDOR:</span> {{ optional($venta->user)->name ?? '-' }}
            </div>
            <div class="data-col" style="width: 33%;">
                <span class="label">CONDICIÓN PAGO:</span> {{ ucfirst($venta->metodo_pago ?? 'Efectivo') }}
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="8%">Cant.</th>
                <th width="60%">Descripción</th>
                <th width="12%">Precio Uni.</th>
                <th width="8%">% Desc</th>
                <th width="12%">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->items as $item)
                <tr class="item-row">
                    <td class="text-center">{{ number_format($item->cantidad, 2) }}</td>
                    <td>
                        {{ $item->product->name }}
                        @if($item->variant)
                            - ({{ $item->variant->size }} / {{ $item->variant->color }})
                        @endif
                    </td>
                    <td class="text-right">$ {{ number_format($item->total_item_con_iva / $item->cantidad, 2, ',', '.') }}</td>
                    <td class="text-center">0,00</td>
                    <td class="text-right">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            
            @php $extra = 20 - count($venta->items); @endphp
            @for($i=0; $i<$extra; $i++)
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

    <div class="footer-container">
        <div class="observations-box">
            <strong>OBSERVACIONES:</strong>
        </div>

        <div class="totals-box">
            <div class="total-row">
                <div class="total-label">SUBTOTAL:</div>
                <div class="total-val">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">DESCUENTO:</div>
                <div class="total-val">$ 0,00</div>
            </div>
            <div class="total-row grand-total">
                <div class="total-label">TOTAL:</div>
                <div class="total-val">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="footer-text">
        <p>Generado por MultiPOS - www.gentepiola.net</p>
    </div>

</body>
</html>
