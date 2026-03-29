<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Etiquetas de Productos</title>
    <style>
        @page { margin: 5mm; }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .page-break { page-break-after: always; }
        
        .label-grid {
            width: 100%;
            display: block;
        }

        .label-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        .label-cell {
            padding: 0;
            margin: 0;
            vertical-align: top;
            border: none;
        }

        .label-card {
            width: 96%; /* Casi todo el ancho de la celda */
            margin: 2% auto;
            border: 0.1mm solid #eee;
            text-align: center;
            box-sizing: border-box;
            background: #fff;
            overflow: hidden;
            padding: 5px;
        }


        /* Ajustes por tamaño estándar Argentina */
        @if($format == 'small')
            /* Estándar 33x22mm aprox */
            .label-card { height: 22mm; }
            .product-name { font-size: 7px; height: 3mm; line-height: 3mm; }
            .price-tag { font-size: 9px; height: 4mm; line-height: 4mm; }
            .barcode-container { height: 10mm; }
            .barcode-container img { height: 8mm; width: 90%; }
            .barcode-text { font-size: 6px; }
        @elseif($format == 'medium')
            /* Estándar 50x25mm aprox */
            .label-card { height: 25mm; }
            .product-name { font-size: 9px; height: 4mm; line-height: 4mm; }
            .price-tag { font-size: 13px; height: 5mm; line-height: 5mm; }
            .barcode-container { height: 12mm; }
            .barcode-container img { height: 10mm; width: 95%; }
            .barcode-text { font-size: 7px; }
        @else
            /* Estándar 100x50mm aprox */
            .label-card { height: 50mm; }
            .product-name { font-size: 13px; height: 8mm; line-height: 8mm; }
            .price-tag { font-size: 20px; height: 12mm; line-height: 12mm; }
            .barcode-container { height: 25mm; }
            .barcode-container img { height: 22mm; width: 100%; }
            .barcode-text { font-size: 10px; }
        @endif

        .product-name {
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price-tag {
            color: #000;
            font-weight: 900;
            margin-bottom: 2px;
        }

        .barcode-container {
            margin-top: 1px;
            display: block;
            width: 100%;
            text-align: center;
        }

        .barcode-text {
            color: #333;
            margin-top: 0px;
            display: block;
            letter-spacing: 1px;
        }

        .empresa-footer {
            font-size: 5px;
            color: #aaa;
            margin-top: 1px;
            text-transform: uppercase;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    @php
        // Ajuste de filas por página según formato (debe coincidir con LabelController)
        $perPageRows = ($format == 'small') ? 13 : (($format == 'medium') ? 8 : 5);
        $totalItems = count($labels);
        $chunks = array_chunk($labels, $cols);
        $chunkedPages = array_chunk($chunks, $perPageRows);
    @endphp

    @foreach($chunkedPages as $page)
        <table class="label-table" cellspacing="0" cellpadding="0">
            @foreach($page as $row)
                <tr>
                    @foreach($row as $l)
                        <td class="label-cell">
                            <div class="label-card">
                                <div class="product-name">{{ $l['name'] }}</div>
                                <div class="price-tag">$ {{ $l['price'] }}</div>
                                
                                <div class="barcode-container">
                                    <img src="data:image/png;base64,{{ $l['barcode'] }}">
                                </div>
                                
                                <span class="barcode-text">{{ $l['code'] }}</span>
                                <div class="empresa-footer">{{ $l['empresa'] }}</div>
                            </div>
                        </td>
                    @endforeach
                    {{-- Llenar celdas vacías si la fila no está completa --}}
                    @for($i = count($row); $i < $cols; $i++)
                        <td class="label-cell empty"></td>
                    @endfor
                </tr>
            @endforeach
        </table>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>

