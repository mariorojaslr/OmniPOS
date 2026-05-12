<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta</title>
    <style>
        /** 
         *  CONFIGURACIÓN DE LA PÁGINA
         *  Aquí definimos los márgenes globales de la hoja A4.
         */
        @page { margin: 1cm; }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            color: #333; 
            line-height: 1.4; 
            margin: 0; 
            padding: 0; 
        }

        /** 
         *  ESTILOS DEL CUADRO PRINCIPAL (MARCO EXTERIOR)
         */
        .invoice-box { 
            border: 1.5px solid #000; 
            padding: 10px 0 0 0; /* Un poco de aire arriba */
            position: relative; 
            min-height: 26.5cm; 
        }

        /* La letra (A, B, C) en el medio del encabezado */
        .header-center {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            border: 1.5px solid #000;
            border-top: none;
            width: 45px;
            height: 45px;
            text-align: center;
            background: #fff;
            z-index: 10;
        }
        .header-center .letter { font-size: 24pt; font-weight: bold; display: block; line-height: 1.1; }
        .header-center .cod { font-size: 7pt; font-weight: bold; }

        /* Tabla del encabezado (Logo a la izquierda, Datos de Factura a la derecha) */
        .header-table { width: 100%; border-bottom: 1.5px solid #000; border-collapse: collapse; margin-bottom: 10px; }
        .header-table td { padding: 10px 15px; vertical-align: top; }
        
        .company-logo { max-height: 65px; margin-bottom: 10px; display: block; }
        .company-name { font-size: 16pt; font-weight: 900; color: #000; text-transform: uppercase; }
        .company-data p { margin: 0; font-size: 8.5pt; color: #444; }

        .doc-title { font-size: 18pt; font-weight: 900; margin: 0; color: #000; }
        .doc-num { font-size: 14pt; font-weight: 900; margin: 5px 0; }
        .doc-data p { margin: 2px 0; font-size: 9pt; }

        /* Franja de Datos del Cliente */
        .section-bar { background: #f9f9f9; padding: 8px 15px; border-bottom: 1.5px solid #000; }
        .label { font-weight: bold; color: #555; font-size: 8pt; text-transform: uppercase; margin-right: 5px; }

        /** 
         *  TABLA DE PRODUCTOS (ITEMS)
         *  Aquí puedes tocar los paddings y tamaños de letra de la lista.
         */
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { 
            background: #000; 
            color: #fff; 
            padding: 6px 8px; 
            text-align: left; 
            font-size: 8pt; 
            text-transform: uppercase; 
        }
        .items-table td { 
            padding: 4px 8px; 
            font-size: 8.5pt; 
            color: #000; 
            border: none; 
        }
        
        /** 
         *  PIE DE PÁGINA (FOOTER) - EL BLOQUE DE ABAJO
         *  Este bloque está "anclado" al fondo con position: absolute.
         */
        .footer-container { 
            position: absolute; 
            bottom: 0; 
            left: 0; 
            width: 100%; 
            padding: 0 25px 5px 25px; 
            border-top: 1.5px solid #000; 
            box-sizing: border-box; 
        }
        
        /* Caja ARCA (QR y Leyenda) */
        .arca-box { float: left; width: 50%; padding-top: 2px; }
        .arca-logo { height: 26px; margin-bottom: 2px; }
        .qr-placeholder { border: 1.2px solid #000; padding: 2px; display: inline-block; background: #fff; vertical-align: top; }
        .qr-img { width: 80px; height: 80px; }
        
        /** 
         *  TOTALES (LO QUE QUERÍAS MOVER)
         *  Ajusta 'padding-right' para alejar el total del borde derecho.
         */
        .totals-section { 
            float: left; 
            width: 45%; 
            margin-left: 2%; 
            text-align: right; 
            padding-right: 3.5cm;
            margin-top: 35px; /* <--- Bajamos el total */
        }
        .totals-table { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .totals-table td { padding: 0; font-size: 11pt; color: #000; }
        
        /* Caja de CAE y Vencimiento */
        .cae-box { text-align: right; margin-top: 35px; padding-right: 3.5cm; }
        .cae-data { 
            font-weight: 900; 
            font-size: 10pt; 
            font-family: 'Courier-Bold', 'Courier', monospace; 
            letter-spacing: 0.5px; 
            line-height: 1.0; 
        }

        /* Líneas punteadas en Subtotales */
        .dotted-row { width: 100%; margin-bottom: 2px; }
        .dotted-label { float: left; background: #fff; padding-right: 5px; }
        .dotted-value { float: right; background: #fff; padding-left: 5px; font-weight: bold; }
        .dotted-filler { border-bottom: 1px dotted #888; height: 14px; margin-top: -4px; }

        .clear { clear: both; }
        
        /* Pequeña firma al final de todo */
        .attribution { 
            text-align: center; 
            font-size: 7pt; 
            color: #888; 
            margin-top: 10px; 
            border-top: 1px solid #eee; 
            padding-top: 5px; 
        }
    </style>
</head>
<body>

    @php
        // Lógica para determinar si es factura A, B o C
        $isTicket = ($venta->tipo_comprobante === 'ticket' || $venta->tipo_comprobante === 'X');
        $letra = "X";
        $cod_id = "000";
        $hasCae = !empty($venta->cae);

        if($hasCae){
            // Si tiene CAE, determinamos la letra según el CUIT del cliente o condición de la empresa
            if($empresa->condicion_iva === 'Monotributista'){
                $letra = "C"; $cod_id = "011";
            } else {
                $esA = ($venta->cliente && $venta->cliente->tax_condition === 'responsable_inscripto');
                $letra = $esA ? "A" : "B";
                $cod_id = $esA ? "001" : "006";
            }
            $titulo_comprobante = "FACTURA " . $letra;
        } else {
            $titulo_comprobante = $isTicket ? "TICKET" : "FACTURA " . $letra;
        }
        
        // Número completo (Sucursal - Número)
        $fullNumero = $venta->numero_comprobante ?: (str_pad($empresa->arca_punto_venta ?? '12', 4, '0', STR_PAD_LEFT) . '-' . str_pad($venta->id, 8, '0', STR_PAD_LEFT));
    @endphp

    <div class="invoice-box">
        
        {{-- ALERTA SI NO ES VÁLIDO --}}
        @if(!$hasCae)
            <div style="background: #ff0000; color: #fff; text-align: center; padding: 5px; font-weight: bold; position: absolute; width: 100%; top: -35px; left: 0;">
                DOCUMENTO NO VÁLIDO COMO FACTURA
            </div>
        @endif

        {{-- EL "CUADRITO" DE LA LETRA EN EL MEDIO --}}
        <div class="header-center">
            <span class="letter">{{ $letra }}</span>
            <span class="cod">Cod. {{ $cod_id }}</span>
        </div>

        {{-- ENCABEZADO: DATOS DE LA EMPRESA Y DE LA FACTURA --}}
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
                <td width="48%" style="text-align: right;">
                    <h1 class="doc-title">{{ $titulo_comprobante }}</h1>
                    <div class="doc-num">N&deg; {{ $fullNumero }}</div>
                    <div class="doc-data">
                        <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
                        <p><strong>CUIT:</strong> {{ $empresa->arca_cuit ?? $empresa->cuit }}</p>
                        <p><strong>I.I.B.B.:</strong> {{ $empresa->iibb ?? '-' }}</p>
                        <p><strong>Inicio Actividades:</strong> {{ $empresa->inicio_actividad ?? '-' }}</p>
                    </div>
                </td>
            </tr>
        </table>

        {{-- DATOS DEL CLIENTE --}}
        <div class="section-bar">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="55%"><span class="label">Señor(es):</span> <span style="font-size: 10pt; font-weight: 900;">{{ strtoupper($venta->cliente->name ?? 'CONSUMIDOR FINAL') }}</span></td>
                    <td width="45%"><span class="label">IVA:</span> {{ $venta->cliente->condicion_iva ?? 'Consumidor Final' }}</td>
                </tr>
                <tr>
                    <td width="55%"><span class="label">Domicilio:</span> {{ $venta->cliente->address ?? '-' }}</td>
                    <td width="45%"><span class="label">CUIT / DNI:</span> {{ $venta->cliente->document ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- TABLA DE PRODUCTOS --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th width="12%">Cod.</th>
                    <th width="53%">Descripción / Detalle</th>
                    <th width="10%" style="text-align: center;">Cant.</th>
                    <th width="12%" style="text-align: right;">P. Unit.</th>
                    <th width="13%" style="text-align: right;">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->items as $item)
                <tr>
                    <td>{{ $item->product->id ?? '-' }}</td>
                    <td>
                        <strong>{{ $item->product->name ?? 'Producto' }}</strong>
                        @if($item->variant) <small>({{ $item->variant->size }} {{ $item->variant->color }})</small> @endif
                    </td>
                    <td style="text-align: center;">{{ number_format($item->cantidad, 0) }}</td>
                    <td style="text-align: right;">$ {{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
                    <td style="text-align: right;"><strong>$ {{ number_format($item->total_item_con_iva, 2, ',', '.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- PIE DE PÁGINA ANCLADO AL FONDO --}}
        <div class="footer-container">
            
            <div class="arca-box">
                @if($hasCae)
                    <div style="display: flex; align-items: flex-start;">
                        @if($venta->qr_data)
                            <div class="qr-placeholder">
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->generate('https://www.afip.gob.ar/fe/qr/?p=' . $venta->qr_data)) !!}" class="qr-img">
                            </div>
                        @endif
                        <div style="display: inline-block; vertical-align: top; margin-left: 15px; margin-top: 35px;">
                            @if(isset($arcaLogoBase64) && $arcaLogoBase64)
                                <img src="{{ $arcaLogoBase64 }}" class="arca-logo" style="display: block; margin-bottom: 3px;">
                            @endif
                            <div style="font-size: 11pt; font-weight: 900; line-height: 1;">Comprobante Autorizado</div>
                            <div style="font-size: 7pt; color: #000; line-height: 1.1; margin-top: 1px;">
                                Esta administración federal no se responsabiliza por los datos declarados.
                            </div>
                        </div>
                    </div>
                @else
                    <div style="border: 2px solid #000; padding: 15px; text-align: center; font-weight: bold; font-size: 12pt;">
                        DOCUMENTO NO VÁLIDO COMO FACTURA <br>
                        <span style="font-size: 9pt; font-weight: normal;">Uso Administrativo Interno</span>
                    </div>
                @endif
            </div>

            <div class="totals-section">
                <div class="totals-table">
                    {{-- SI ES FACTURA A, MOSTRAMOS EL DESGLOSE DE IVA --}}
                    @if($hasCae && isset($esA) && $esA)
                        <div class="dotted-row">
                            <span class="dotted-label">Subtotal Neto</span>
                            <span class="dotted-value">$ {{ number_format($venta->total_sin_iva, 2, ',', '.') }}</span>
                            <div class="dotted-filler"></div>
                        </div>
                        <div class="dotted-row">
                            <span class="dotted-label">IVA 21%</span>
                            <span class="dotted-value">$ {{ number_format($venta->total_iva, 2, ',', '.') }}</span>
                            <div class="dotted-filler"></div>
                        </div>
                    @endif
                    
                    {{-- CUADRO DE IMPORTE TOTAL --}}
                    <div style="margin-top: 10px; border-top: 2.5px solid #000; border-bottom: 2.5px solid #000; padding: 10px 0;">
                        <span style="float: left; font-size: 16pt; font-weight: 900;">IMPORTE TOTAL</span>
                        <span style="float: right; font-size: 19pt; font-weight: 900;">$ {{ number_format($venta->total_con_iva, 2, ',', '.') }}</span>
                        <div style="clear: both;"></div>
                    </div>
                </div>
                
                {{-- DATOS DEL CAE --}}
                <div class="cae-box">
                    <div class="cae-data">CAE: {{ $venta->cae ?? '-' }}</div>
                    <div class="cae-data">Vencimiento CAE: {{ $venta->cae_vencimiento ? \Carbon\Carbon::parse($venta->cae_vencimiento)->format('d/m/Y') : '-' }}</div>
                </div>
            </div>
            
            <div class="clear"></div>

            <div class="attribution">
                MultiPOS SaaS (gentepiola.net) | Emitido Electrónicamente | Pág. 1 de 1
            </div>
        </div>

    </div>

</body>
</html>
