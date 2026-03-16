<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Nº {{ $venta->numero_comprobante }}</title>
    <style>
        @page { margin: 1cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }

        .header-container {
            width: 100%;
            border: 1px solid #333;
            margin-bottom: 20px;
            position: relative;
        }

        .header-left {
            width: 45%;
            padding: 15px;
            float: left;
            min-height: 100px;
        }

        .header-center {
            width: 10%;
            position: absolute;
            left: 45%;
            top: 0;
            text-align: center;
            border-left: 1px solid #333;
            border-right: 1px solid #333;
            border-bottom: 1px solid #333;
            height: 60px;
            background: #fff;
            z-index: 10;
        }

        .header-right {
            width: 45%;
            padding: 15px;
            float: right;
            text-align: right;
            min-height: 100px;
        }

        .letter-box {
            font-size: 30px;
            font-weight: bold;
            margin-top: 5px;
        }

        .letter-sub {
            font-size: 7px;
            line-height: 1;
        }

        .logo {
            max-width: 180px;
            max-height: 80px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }

        .invoice-number {
            font-size: 14px;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px;
            border: 1px solid #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #f2f2f2;
            padding: 8px;
            border: 1px solid #333;
            text-align: left;
        }

        .items-table td {
            padding: 8px;
            border-left: 1px solid #333;
            border-right: 1px solid #333;
            border-bottom: 0.5px solid #ccc;
        }

        .items-table tr.last-row td {
            border-bottom: 1px solid #333;
        }

        .totals-container {
            width: 100%;
            float: right;
        }

        .totals-table {
            width: 35%;
            float: right;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px;
            border: 1px solid #333;
        }

        .totals-table .total-row {
            font-weight: bold;
            font-size: 14px;
        }

        .clearfix {
            clear: both;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="header-left">
            @php
                $logoUrl = optional($empresa->config)->logo_url;
                // Para DomPDF usaremos public_path si es local o base64 si es remoto por seguridad
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
                <img src="{{ $logoPath }}" class="logo" alt="Logo">
            @else
                <span class="company-name">{{ strtoupper($empresa->nombre_comercial ?? $empresa->razon_social) }}</span>
            @endif

            <p><strong>Razón Social:</strong> {{ $empresa->razon_social }}</p>
            <p><strong>Dirección:</strong> {{ $empresa->direccion_fiscal ?? $empresa->direccion }}</p>
            <p><strong>Teléfono:</strong> {{ $empresa->telefono }}</p>
        </div>

        <div class="header-center">
            <div class="letter-box">X</div>
            <div class="letter-sub">DOC. NO VÁLIDO<br>COMO FACTURA</div>
        </div>

        <div class="header-right">
            <h1 class="invoice-title">COMPROBANTE</h1>
            <p class="invoice-number">Nº {{ $venta->numero_comprobante }}</p>
            <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
            <div style="margin-top: 10px; font-size: 9px;">
                <p><strong>CUIT:</strong> {{ $empresa->cuit }}</p>
                <p><strong>IIBB:</strong> {{ $empresa->iibb }}</p>
                <p><strong>Inicio de Actividades:</strong> {{ $empresa->inicio_actividades ? \Carbon\Carbon::parse($empresa->inicio_actividades)->format('d/m/Y') : '-' }}</p>
                <p><strong>Condición IVA:</strong> {{ $empresa->condicion_iva }}</p>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <table class="info-table">
        <tr>
            <td width="60%"><strong>Cliente:</strong> {{ optional($venta->cliente)->name ?? 'Consumidor Final' }}</td>
            <td width="40%"><strong>CUIT/DNI:</strong> {{ optional($venta->cliente)->document ?? '99-99999999-9' }}</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong> {{ optional($venta->cliente)->address ?? '-' }}</td>
            <td><strong>Condición IVA:</strong> {{ optional($venta->cliente)->condicion_iva ?? 'Consumidor Final' }}</td>
        </tr>
        <tr>
            <td><strong>Localidad:</strong> {{ optional($venta->cliente)->city ?? '-' }}</td>
            <td><strong>Condición de Pago:</strong> {{ ucfirst($venta->metodo_pago ?? 'Contado') }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="10%">Cantidad</th>
                <th width="50%">Descripción</th>
                <th width="20%">Precio Unit.</th>
                <th width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->items as $index => $item)
                <tr class="{{ $index === count($venta->items) - 1 ? 'last-row' : '' }}">
                    <td>{{ number_format($item->cantidad, 2) }}</td>
                    <td>
                        {{ $item->product->name }}
                        @if($item->variant)
                           <br><small style="color: #666;">Variante: {{ $item->variant->size }} / {{ $item->variant->color }}</small>
                        @endif
                    </td>
                    <td>$ {{ number_format($item->total_item_con_iva / $item->cantidad, 2, ',', '.') }}</td>
                    <td>$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            
            {{-- Espacio para que la tabla sea larga --}}
            @php $extraRows = 15 - count($venta->items); @endphp
            @for($i = 0; $i < $extraRows; $i++)
                <tr class="{{ $i === $extraRows - 1 ? 'last-row' : '' }}">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="totals-container">
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td>$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Descuento</td>
                <td>$ 0,00</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL</td>
                <td>$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
            </tr>
        </table>
        <div class="clearfix"></div>
    </div>

    <div class="footer">
        <p>Software de Gestión MultiPOS - www.gentepiola.net</p>
    </div>

</body>
</html>
