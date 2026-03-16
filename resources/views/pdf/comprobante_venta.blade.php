<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Nº {{ $venta->numero_comprobante }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
            padding: 15px;
        }

        /* ====== CABECERA ====== */
        .cabecera {
            width: 100%;
            border: 1.5px solid #000;
            margin-bottom: 4px;
            display: table;
        }
        .cab-izq {
            display: table-cell;
            width: 42%;
            padding: 8px;
            vertical-align: middle;
            border-right: 1.5px solid #000;
        }
        .cab-centro {
            display: table-cell;
            width: 10%;
            text-align: center;
            vertical-align: middle;
            border-right: 1.5px solid #000;
            padding: 8px;
        }
        .cab-der {
            display: table-cell;
            width: 48%;
            padding: 8px;
            vertical-align: middle;
            text-align: right;
        }

        /* Logo */
        .logo-empresa img { max-height: 55px; }
        .logo-empresa p { font-weight: bold; font-size: 14px; margin-bottom: 4px; }

        /* Empresa datos izq */
        .empresa-datos { font-size: 9px; line-height: 1.6; }
        .empresa-nombre { font-weight: bold; font-size: 12px; margin-bottom: 3px; }

        /* Caja X */
        .caja-x {
            border: 2px solid #000;
            font-size: 28px;
            font-weight: bold;
            padding: 6px 14px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .tipo-x-label { font-size: 8px; }

        /* Datos derecha */
        .comp-titulo { font-size: 16px; font-weight: bold; color: #1a56e8; letter-spacing: 0.5px; }
        .comp-numero { font-size: 14px; font-weight: bold; color: #1a56e8; margin: 2px 0; }
        .comp-fecha  { font-size: 13px; font-weight: bold; margin: 2px 0; }
        .fiscal-info { font-size: 8px; margin-top: 4px; color: #333; }

        /* ====== CLIENTE ====== */
        .bloque-cliente {
            border: 1.5px solid #000;
            border-top: none;
            padding: 5px 8px;
            margin-bottom: 4px;
        }
        .fila-cliente {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }
        .cel-cliente {
            display: table-cell;
            font-size: 9px;
            line-height: 1.6;
        }
        .cel-cliente strong { font-size: 9px; }
        .cel-50 { width: 50%; }
        .cel-33 { width: 33.3%; }

        /* ====== OBSERVACIONES ====== */
        .observaciones {
            border: 1.5px solid #000;
            border-top: none;
            padding: 4px 8px;
            font-size: 9px;
            min-height: 18px;
            margin-bottom: 4px;
        }

        /* ====== TABLA ITEMS ====== */
        .tabla-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            font-size: 9px;
        }
        .tabla-items thead th {
            background: #e8e8e8;
            border: 1px solid #000;
            padding: 4px 5px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
        }
        .tabla-items tbody td {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            border-bottom: 0.5px solid #ccc;
            padding: 3px 5px;
            font-size: 9px;
            vertical-align: top;
        }
        .tabla-items tbody tr:last-child td {
            border-bottom: 1px solid #000;
        }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .col-desc  { width: 55%; }
        .col-cant  { width: 8%; }
        .col-precio{ width: 12%; }
        .col-desc2 { width: 8%; }
        .col-sub   { width: 17%; }

        /* ====== TOTALES ====== */
        .bloque-totales {
            width: 100%;
            display: table;
            margin-top: 6px;
        }
        .tot-espacio { display: table-cell; width: 58%; }
        .tot-tabla   { display: table-cell; width: 42%; }
        .tabla-totales {
            width: 100%;
            border: 1.5px solid #000;
            border-collapse: collapse;
            font-size: 10px;
        }
        .tabla-totales td {
            padding: 4px 8px;
            border-bottom: 1px solid #ccc;
        }
        .tabla-totales tr:last-child td {
            border-bottom: none;
            font-weight: bold;
            font-size: 12px;
            border-top: 1.5px solid #000;
        }
        .tw-bold { font-weight: bold; }
    </style>
</head>
<body>

{{-- ======= CABECERA ======= --}}
<div class="cabecera">

    {{-- IZQUIERDA: Logo + Datos empresa --}}
    <div class="cab-izq">
        @if($empresa->config && $empresa->config->logo)
            <div class="logo-empresa">
                <img src="{{ public_path('storage/' . $empresa->config->logo) }}" alt="Logo">
            </div>
        @else
            <p class="empresa-nombre">{{ strtoupper($empresa->nombre_comercial) }}</p>
        @endif

        <div class="empresa-datos" style="margin-top: 4px;">
            <strong>{{ strtoupper($empresa->razon_social ?? $empresa->nombre_comercial) }}</strong><br>
            {{ $empresa->direccion_fiscal ?? $empresa->direccion }}<br>
            TEL: {{ $empresa->telefono ?? '' }}
        </div>
    </div>

    {{-- CENTRO: Letra del comprobante --}}
    <div class="cab-centro">
        <div class="caja-x">X</div><br>
        <span class="tipo-x-label">COMP. NO<br>RESPALDADO<br>FISCALMENTE</span>
    </div>

    {{-- DERECHA: Datos comprobante --}}
    <div class="cab-der">
        <div class="comp-titulo">COMPROBANTE</div>
        <div class="comp-numero">Nº {{ $venta->numero_comprobante }}</div>
        <div class="comp-fecha">FECHA: {{ $venta->created_at->format('d/m/Y') }}</div>
        <div class="fiscal-info">
            {{ $empresa->condicion_iva ?? 'MONOTRIBUTISTA' }}
            @if($empresa->cuit) &nbsp; CUIT: {{ $empresa->cuit }} @endif<br>
            @if($empresa->inicio_actividades) INICIO ACT.: {{ $empresa->inicio_actividades }} @endif
            @if($empresa->iibb) &nbsp; ING. BRUTOS: {{ $empresa->iibb }} @endif
        </div>
    </div>

</div>

{{-- ======= DATOS CLIENTE ======= --}}
<div class="bloque-cliente">
    <div class="fila-cliente">
        <div class="cel-cliente cel-50">
            <strong>SEÑOR/ES:</strong> {{ optional($venta->cliente)->name ?? 'CONSUMIDOR FINAL' }}
        </div>
        <div class="cel-cliente cel-50">
            <strong>IVA:</strong> {{ optional($venta->cliente)->condicion_iva ?? 'CONSUMIDOR FINAL' }}
        </div>
    </div>
    <div class="fila-cliente">
        <div class="cel-cliente cel-50">
            <strong>DOMICILIO:</strong> {{ optional($venta->cliente)->address ?? '' }}
        </div>
        <div class="cel-cliente cel-50">
            <strong>LOCALIDAD:</strong> {{ optional($venta->cliente)->city ?? '' }}
        </div>
    </div>
    <div class="fila-cliente">
        <div class="cel-cliente cel-33">
            <strong>CUIT:</strong> {{ optional($venta->cliente)->document ?? '' }}
        </div>
        <div class="cel-cliente cel-33">
            <strong>PROVINCIA:</strong> {{ optional($venta->cliente)->province ?? '' }}
        </div>
        <div class="cel-cliente cel-33">
            <strong>CORREO ELECTRÓNICO:</strong> {{ optional($venta->cliente)->email ?? '' }}
        </div>
    </div>
    <div class="fila-cliente">
        <div class="cel-cliente cel-50">
            <strong>VENDEDOR:</strong> {{ optional($venta->user)->name ?? '' }}
        </div>
        <div class="cel-cliente cel-25">
            <strong>CONDICIÓN PAGO:</strong> {{ ucfirst($venta->metodo_pago ?? 'Contado') }}
        </div>
        <div class="cel-cliente cel-25">
            <strong>FECHA VENCIMIENTO:</strong>
        </div>
    </div>
</div>

{{-- ======= OBSERVACIONES ======= --}}
<div class="observaciones">
    <strong>OBSERVACIONES:</strong>
</div>

{{-- ======= TABLA DE ITEMS ======= --}}
<table class="tabla-items">
    <thead>
        <tr>
            <th class="col-desc">Descripción</th>
            <th class="col-cant text-center">Cant.</th>
            <th class="col-precio text-right">Precio Uni.</th>
            <th class="col-desc2 text-center">% Desc</th>
            <th class="col-sub text-right">Sub Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->items as $item)
        @php
            $precioUni = $item->total_item_con_iva / $item->cantidad;
            $desc = 0; // Sin descuento por ahora
            $subtotal = $item->total_item_con_iva;
        @endphp
        <tr>
            <td class="col-desc">
                @if($item->product->sku ?? '')
                    <strong>{{ $item->product->sku }}</strong> -
                @endif
                {{ $item->product->name }}
                @if($item->variant)
                    <br><small>Talle: {{ $item->variant->size }} / Color: {{ $item->variant->color }}</small>
                @endif
            </td>
            <td class="col-cant text-center">{{ number_format($item->cantidad, 2) }}</td>
            <td class="col-precio text-right">{{ number_format($precioUni, 2, ',', '.') }}</td>
            <td class="col-desc2 text-center">{{ number_format($desc, 2) }}</td>
            <td class="col-sub text-right">{{ number_format($subtotal, 2, ',', '.') }}</td>
        </tr>
        @endforeach

        {{-- Filas vacías para completar espacio --}}
        @php $filas_llenas = count($venta->items); $filas_blank = max(0, 20 - $filas_llenas); @endphp
        @for($i = 0; $i < $filas_blank; $i++)
        <tr>
            <td class="col-desc">&nbsp;</td>
            <td class="col-cant">&nbsp;</td>
            <td class="col-precio">&nbsp;</td>
            <td class="col-desc2">&nbsp;</td>
            <td class="col-sub">&nbsp;</td>
        </tr>
        @endfor
    </tbody>
</table>

{{-- ======= TOTALES ======= --}}
<div class="bloque-totales">
    <div class="tot-espacio"></div>
    <div class="tot-tabla">
        <table class="tabla-totales">
            <tr>
                <td>SUBTOTAL:</td>
                <td class="text-right">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>DESCUENTO:</td>
                <td class="text-right">$ 0,00</td>
            </tr>
            <tr>
                <td class="tw-bold">TOTAL:</td>
                <td class="text-right tw-bold">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
