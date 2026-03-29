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

        .label-card {
            width: {{ (100 / $cols) - 1.5 }}%;
            margin: 0.5%;
            float: left;
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
    <div class="label-grid clearfix">
        @foreach($labels as $l)
            <div class="label-card">
                <div class="product-name">{{ $l['name'] }}</div>
                <div class="price-tag">$ {{ $l['price'] }}</div>
                
                <div class="barcode-container">
                    <img src="data:image/png;base64,{{ $l['barcode'] }}">
                </div>
                
                <span class="barcode-text">{{ $l['code'] }}</span>
                <div class="empresa-footer">{{ $l['empresa'] }}</div>
            </div>
            
            @php
                // Cálculo estimado de etiquetas por página A4
                // A4 es de 210x297mm. 
                // Para 22mm de alto -> aprox 12 filas. 
                // Para 25mm de alto -> aprox 10 filas.
                $rowsPage = ($format == 'small') ? 12 : (($format == 'medium') ? 10 : 5);
                $perPage = $cols * $rowsPage;
            @endphp

            @if(($loop->index + 1) % $perPage == 0 && !$loop->last)
                </div><div class="page-break"></div><div class="label-grid clearfix">
            @endif
        @endforeach
    </div>
</body>
</html>
