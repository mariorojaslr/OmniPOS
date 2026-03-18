<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Nº {{ $venta->numero_comprobante }}</title>
    <style>
        @page { margin: 1cm; }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #222;
            line-height: 1.5;
            background: #fff;
        }

        /* ====== CABECERA PREMIUM ====== */
        .header-box {
            width: 100%;
            border: 0.6pt solid #222;
            margin-bottom: 20px;
            position: relative;
            background: #fff;
        }

        .header-left {
            width: 48%;
            padding: 15px;
            float: left;
        }

        .header-divider {
            position: absolute;
            left: 50%;
            top: 45px; /* Justo debajo del cuadro X */
            bottom: 0px;
            width: 0.6pt;
            background: #222;
        }

        /* CUADRO X - DOBLE BORDÉ DISEÑO */
        .letter-container {
            position: absolute;
            left: 50%;
            top: -1px;
            margin-left: -20px;
            width: 40px;
            height: 45px;
            border: 0.6pt solid #222;
            background: #fff;
            text-align: center;
            z-index: 200;
        }

        .letter-container .letter {
            font-size: 28px;
            font-weight: 900;
            line-height: 32px;
            display: block;
            color: #000;
        }

        .letter-container .sub-text {
            font-size: 4.5px;
            line-height: 1;
            font-weight: 900;
            display: block;
            text-transform: uppercase;
            margin-top: -2px;
        }

        .header-right {
            width: 48%;
            padding: 15px;
            float: right;
            text-align: right;
        }

        .logo {
            max-width: 160px;
            max-height: 55px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: 800;
            color: #000;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }

        .company-info {
            font-size: 9px;
            color: #444;
            line-height: 1.3;
        }

        .comp-title {
            font-size: 22px;
            font-weight: 900;
            margin-bottom: 2px;
            color: #111;
            letter-spacing: 1px;
        }

        .comp-number {
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }

        /* ====== CLIENTE / DATOS ====== */
        .data-box {
            width: 100%;
            border: 0.6pt solid #222;
            margin-bottom: 20px;
            background: #fdfdfd;
        }

        .data-row {
            width: 100%;
            border-bottom: 0.6pt solid #eee;
            clear: both;
        }

        .data-row:last-child {
            border-bottom: none;
        }

        .data-col {
            padding: 8px 12px;
            float: left;
            border-right: 0.6pt solid #eee;
        }

        .data-col:last-child {
            border-right: none;
        }

        .label {
            font-weight: 800;
            color: #777;
            font-size: 8px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        
        .val {
            font-size: 11px;
            color: #000;
            font-weight: 600;
        }

        /* ====== TABLA PRODUCTOS ====== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 0.6pt solid #222;
            margin-bottom: 25px;
            table-layout: fixed;
        }

        .items-table th {
            background: #111;
            color: #fff;
            padding: 10px 8px;
            text-align: center;
            font-weight: 800;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 8px 10px;
            border-bottom: 0.5pt solid #eee;
            font-size: 10px;
            word-wrap: break-word;
        }

        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* ====== TOTALS ====== */
        .totals-box {
            width: 38%;
            border: 0.6pt solid #222;
            float: right;
        }

        .total-item {
            padding: 8px 12px;
            border-bottom: 0.6pt solid #eee;
            clear: both;
        }

        .total-item-label {
            float: left;
            font-weight: 700;
            font-size: 9px;
            color: #555;
            text-transform: uppercase;
        }

        .total-item-val {
            float: right;
            font-weight: 800;
            font-size: 12px;
        }

        .grand-total {
            background: #111;
            color: #fff;
            padding: 12px;
        }

        .grand-total .total-item-label, .grand-total .total-item-val {
            color: #fff;
            font-size: 16px;
        }

        .clearfix { clear: both; }

        .footer-legal {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 50px;
            border-top: 0.6pt solid #eee;
            padding-top: 15px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header-box">
        <div class="header-left">
            @php
                $logoPath = '';
                if ($empresa->config && $empresa->config->logo) {
                    $possible = storage_path('app/public/' . $empresa->config->logo);
                    if (file_exists($possible)) {
                        $logoPath = $possible;
                    }
                }
            @endphp

            @if($logoPath && file_exists($logoPath))
                <img src="{{ $logoPath }}" class="logo">
            @else
                <div style="margin-bottom: 10px;">
                    <span class="company-name">{{ strtoupper($empresa->nombre_comercial ?? 'EMPRESA') }}</span>
                </div>
            @endif

            <div class="company-info">
                <p><strong>Razón Social:</strong> {{ $empresa->razon_social ?? $empresa->nombre_comercial }}</p>
                <p><strong>Dirección:</strong> {{ $empresa->direccion_fiscal ?? $empresa->direccion }}</p>
                <p><strong>Teléfono:</strong> {{ $empresa->telefono }}</p>
                <p><strong>Email:</strong> {{ $empresa->email }}</p>
            </div>
        </div>

        <div class="header-divider"></div>

        <div class="letter-container">
            <span class="letter">X</span>
            <span class="sub-text">Doc. No Válido<br>como Factura</span>
        </div>

        <div class="header-right">
            <h1 class="comp-title">COMPROBANTE</h1>
            <h2 class="comp-number">Nº {{ $venta->numero_comprobante }}</h2>
            <p><strong>FECHA DE EMISIÓN:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
            
            <div class="company-info" style="margin-top: 8px;">
                <p><strong>CUIT:</strong> {{ $empresa->arca_cuit ?? $empresa->cuit }}</p>
                <p><strong>Ingresos Brutos:</strong> {{ $empresa->iibb }}</p>
                <p><strong>Condición IVA:</strong> {{ $empresa->condicion_iva }}</p>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="data-box">
        <div class="data-row">
            <div class="data-col" style="width: 65%;">
                <span class="label">Señor/es</span>
                <span class="val">{{ optional($venta->cliente)->name ?? 'CONSUMIDOR FINAL' }}</span>
            </div>
            <div class="data-col" style="width: 35%;">
                <span class="label">CUIT/DNI</span>
                <span class="val">{{ optional($venta->cliente)->document ?? 'CF' }}</span>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="data-row">
            <div class="data-col" style="width: 50%;">
                <span class="label">Domicilio</span>
                <span class="val">{{ optional($venta->cliente)->address ?? '-' }}</span>
            </div>
            <div class="data-col" style="width: 25%;">
                <span class="label">Localidad</span>
                <span class="val">{{ optional($venta->cliente)->city ?? '-' }}</span>
            </div>
            <div class="data-col" style="width: 25%;">
                <span class="label">Provincia</span>
                <span class="val">{{ optional($venta->cliente)->province ?? '-' }}</span>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="data-row">
            <div class="data-col" style="width: 33%;">
                <span class="label">Condición IVA</span>
                <span class="val">{{ optional($venta->cliente)->condicion_iva ?? 'Consumidor Final' }}</span>
            </div>
            <div class="data-col" style="width: 33%;">
                <span class="label">Vendedor</span>
                <span class="val">{{ optional($venta->user)->name ?? 'Sistema' }}</span>
            </div>
            <div class="data-col" style="width: 33%;">
                <span class="label">Medio de Pago</span>
                <span class="val">{{ ucfirst($venta->metodo_pago ?? 'Efectivo') }}</span>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="items-container">
        <table class="items-table">
            <thead>
                <tr>
                    <th width="10%">Cant.</th>
                    <th width="55%">Descripción del Producto</th>
                    <th width="15%">Precio Unit.</th>
                    <th width="20%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->items as $item)
                    <tr>
                        <td class="text-center">{{ number_format($item->cantidad, 2) }}</td>
                        <td>
                            {{ $item->product->name }}
                            @if($item->variant)
                                <br><small style="color: #666">({{ $item->variant->size }} / {{ $item->variant->color }})</small>
                            @endif
                        </td>
                        <td class="text-right">$ {{ number_format($item->total_item_con_iva / $item->cantidad, 2, ',', '.') }}</td>
                        <td class="text-right">$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                
                {{-- Relleno para que siempre tenga un tamaño mínimo estético --}}
                @php $extra = max(0, 12 - count($venta->items)); @endphp
                @for($i=0; $i<$extra; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div class="footer-section">
        <div class="obs-box">
            <span class="label">Observaciones</span>
            <p>{{ $venta->observaciones ?? 'Muchas gracias por su compra.' }}</p>
        </div>

        <div class="totals-box">
            <div class="total-item">
                <span class="total-item-label">Subtotal</span>
                <span class="total-item-val">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</span>
                <div class="clearfix"></div>
            </div>
            <div class="total-item">
                <span class="total-item-label">Descuento</span>
                <span class="total-item-val">$ 0,00</span>
                <div class="clearfix"></div>
            </div>
            <div class="total-item grand-total">
                <span class="total-item-label">Total</span>
                <span class="total-item-val">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</span>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="footer-legal">
        <p>Este documento es un comprobante de control interno emitido por el sistema MultiPOS SaaS.</p>
        <p>MultiPOS SaaS v4.0 - El Cerebro de tu Negocio - www.gentepiola.net</p>
    </div>

</body>
</html>
