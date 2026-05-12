<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta</title>
    <style>
        @page { margin: 1cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            color: #333; 
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        /* Contenedor Principal */
        .invoice-box {
            width: 100%;
            position: relative;
            min-height: 27cm; /* Asegura que el pie de página quede abajo */
        }

        /* Encabezado */
        .header-table { width: 100%; border-bottom: 1px solid #000; margin-bottom: 10px; table-layout: fixed; }
        .header-table td { vertical-align: top; padding: 5px; }
        
        .company-logo { max-height: 80px; max-width: 250px; margin-bottom: 5px; }
        .company-name { font-size: 16pt; font-weight: bold; color: #000; margin-bottom: 2px; }
        .company-data { font-size: 8pt; color: #444; }
        .company-data p { margin: 2px 0; }

        /* Letra del Comprobante (El cuadro del medio) */
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
        .doc-type-code { font-size: 6pt; font-weight: bold; margin-top: -5px; display: block; }

        .doc-title { font-size: 14pt; font-weight: bold; margin: 0; text-transform: uppercase; }
        .doc-num { font-size: 11pt; font-weight: bold; margin: 5px 0; }
        .doc-data p { margin: 3px 0; font-size: 8.5pt; }

        /* Barras de Sección */
        .section-bar { 
            background: #f0f0f0; 
            padding: 5px 10px; 
            border: 1px solid #ccc; 
            font-weight: bold; 
            font-size: 9pt; 
            margin: 10px 0;
            text-transform: uppercase;
        }

        /* Tablas de Datos */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .data-table td { padding: 4px 10px; vertical-align: top; }
        .label { font-weight: bold; color: #555; margin-right: 5px; }

        /* Tabla de Items */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { 
            background: #333; 
            color: #fff; 
            text-align: left; 
            padding: 7px 10px; 
            font-size: 8.5pt; 
            text-transform: uppercase;
        }
        .items-table td { 
            padding: 7px 10px; 
            border-bottom: 1px solid #eee; 
            font-size: 9pt; 
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Pie de Página / Totales */
        .footer-container { 
            position: absolute; 
            bottom: 0; 
            width: 100%; 
            padding-top: 10px;
            border-top: 2px solid #000;
        }

        .totals-section { 
            float: right; 
            width: 40%; 
            margin-bottom: 20px;
        }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 3px 0; font-size: 10pt; }
        .total-row { font-size: 14pt !important; font-weight: bold; color: #000; border-top: 1px solid #333; }

        /* Bloque ARCA / AFIP */
        .arca-container { clear: both; width: 100%; margin-top: 20px; table-layout: fixed; }
        .arca-box-left { width: 60%; vertical-align: middle; }
        .arca-box-right { width: 40%; text-align: right; vertical-align: middle; }
        
        .qr-img { width: 90px; height: 90px; margin-right: 15px; float: left; }
        .arca-logo { height: 28px; margin-bottom: 5px; display: block; }
        .arca-legal { font-size: 7.5pt; color: #666; font-style: italic; line-height: 1.1; }
        
        .cae-data { font-size: 10pt; font-weight: bold; color: #000; }
        .cae-label { color: #555; font-size: 9pt; font-weight: normal; margin-right: 5px; }
    </style>
</head>
<body>

@php
    $hasCae = !empty($venta->cae);
    $letra = "X";
    $cod_id = "000";

    if($hasCae){
        $taxCondEmpresa = strtoupper(trim($empresa->condicion_iva ?? ''));
        if(strpos($taxCondEmpresa, 'MONOTRIBUTO') !== false){
            $letra = "C"; $cod_id = "011";
        } else {
            $taxCondition = strtoupper(trim($venta->cliente->tax_condition ?? ''));
            $esA = (strpos($taxCondition, 'RESPONSABLE INSCRIPTO') !== false || strpos($taxCondition, 'RESPONSABLE_INSCRIPTO') !== false);
            $letra = $esA ? "A" : "B";
            $cod_id = $esA ? "001" : "006";
        }
    }
    
    $titulo_comprobante = $hasCae ? "FACTURA " . $letra : "COMPROBANTE DE GESTIÓN";
    $fullNumero = $venta->numero_comprobante;
@endphp

<div class="invoice-box">
    
    {{-- CUADRO DE LETRA CENTRAL --}}
    <div class="doc-type-box">
        <span class="doc-type-letter">{{ $letra }}</span>
        <span class="doc-type-code">COD. {{ $cod_id }}</span>
    </div>

    {{-- ENCABEZADO --}}
    <table class="header-table">
        <tr>
            <td width="48%">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" class="company-logo">
                @else
                    <div class="company-name">{{ $empresa->razon_social ?? $empresa->nombre_comercial }}</div>
                @endif
                <div class="company-data">
                    <p><strong>{{ $empresa->nombre_comercial ?? '' }}</strong></p>
                    <p>{{ $empresa->direccion_fiscal ?? '-' }}</p>
                    <p>Tel: {{ $empresa->telefono ?? '-' }}</p>
                    <p><strong>Cond. IVA:</strong> {{ $empresa->condicion_iva ?? 'Responsable Inscripto' }}</p>
                </div>
            </td>
            <td width="4%"></td>
            <td width="48%" style="text-align: right;">
                <h1 class="doc-title">{{ $titulo_comprobante }}</h1>
                <div class="doc-num">N&deg; {{ $fullNumero }}</div>
                <div class="doc-data">
                    <p><span class="label">Fecha de Emisión:</span> {{ $venta->created_at->format('d/m/Y') }}</p>
                    <p><span class="label">CUIT:</span> {{ $empresa->arca_cuit ?? $empresa->cuit }}</p>
                    <p><span class="label">Ingresos Brutos:</span> {{ $empresa->iibb ?? '-' }}</p>
                    <p><span class="label">Inicio de Actividades:</span> {{ $empresa->inicio_actividad ?? '-' }}</p>
                </div>
            </td>
        </tr>
    </table>

    {{-- DATOS DEL CLIENTE --}}
    <div class="section-bar">Datos del Cliente</div>
    <table class="data-table">
        <tr>
            <td width="55%">
                <span class="label">Apellido y Nombre / Razón Social:</span> {{ $venta->cliente->name ?? 'Consumidor Final' }}
            </td>
            <td width="45%">
                <span class="label">CUIT / DNI:</span> {{ $venta->cliente->document ?? '-' }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Condición frente al IVA:</span> {{ $venta->cliente->tax_condition ?? 'Consumidor Final' }}
            </td>
            <td>
                <span class="label">Domicilio:</span> {{ $venta->cliente->address ?? '-' }}
            </td>
        </tr>
    </table>

    {{-- LISTADO DE PRODUCTOS --}}
    <table class="items-table">
        <thead>
            <tr>
                <th width="10%">Cant.</th>
                <th width="50%">Descripción</th>
                <th width="20%" class="text-right">Precio Unit.</th>
                <th width="20%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->items as $item)
                <tr>
                    <td class="text-center">{{ number_format($item->cantidad, 2) }}</td>
                    <td>
                        {{ $item->product->name }}
                        @if($item->variant) - {{ $item->variant->name }} @endif
                    </td>
                    <td class="text-right">${{ number_format($item->precio_unitario_sin_iva * 1.21, 2, ',', '.') }}</td>
                    <td class="text-right">${{ number_format($item->total_item_con_iva, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PIE DE PÁGINA (TOTALES Y ARCA) --}}
    <div class="footer-container">
        
        {{-- SECCIÓN DE TOTALES --}}
        <div class="totals-section">
            <table class="totals-table">
                @if($letra === 'A')
                    <tr>
                        <td class="label">Importe Neto Gravado:</td>
                        <td class="text-right">${{ number_format($venta->total_sin_iva, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">IVA 21%:</td>
                        <td class="text-right">${{ number_format($venta->total_iva, 2, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td class="text-right">${{ number_format($venta->total_con_iva, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        {{-- BLOQUE FISCAL ARCA --}}
        <table class="arca-container">
            <tr>
                <td class="arca-box-left">
                    @if($venta->qr_data)
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->generate('https://www.afip.gob.ar/fe/qr/?p=' . $venta->qr_data)) !!}" class="qr-img">
                    @endif
                    <div style="float: left; width: 60%;">
                        <img src="{{ public_path('images/arca_logo.png') }}" class="arca-logo" onerror="this.src='https://www.afip.gob.ar/images/logo_afip.png'; this.style.height='24px';">
                        <div class="arca-legal">
                            Comprobante Autorizado por AFIP/ARCA.<br>
                            Esta Administración Federal no se responsabiliza por los datos declarados.
                        </div>
                    </div>
                </td>
                <td class="arca-box-right">
                    @if($hasCae)
                        <p class="cae-data"><span class="cae-label">CAE N&deg;:</span> {{ $venta->cae }}</p>
                        <p class="cae-data"><span class="cae-label">Vto. CAE:</span> {{ \Carbon\Carbon::parse($venta->cae_vencimiento)->format('d/m/Y') }}</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
