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
            width: {{ (100 / $cols) - 1 }}%;
            margin: 0.5%;
            float: left;
            border: 0.1mm solid #eee;
            text-align: center;
            box-sizing: border-box;
            background: #fff;
            overflow: hidden;
            padding: 5px;
        }

        /* Ajustes por tamaño */
        @if($format == 'small')
            .label-card { height: 35mm; }
            .product-name { font-size: 8px; }
            .price-tag { font-size: 10px; }
            .barcode-container { height: 18mm; }
            .barcode-text { font-size: 7px; }
        @elseif($format == 'medium')
            .label-card { height: 45mm; }
            .product-name { font-size: 10px; }
            .price-tag { font-size: 14px; }
            .barcode-container { height: 25mm; }
            .barcode-text { font-size: 8px; }
        @else
            .label-card { height: 65mm; }
            .product-name { font-size: 13px; }
            .price-tag { font-size: 18px; }
            .barcode-container { height: 40mm; }
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
            margin-top: 2px;
            display: block;
            width: 100%;
        }

        .barcode-container div {
            margin: 0 auto !important;
        }

        .barcode-text {
            color: #333;
            margin-top: 1px;
            display: block;
            letter-spacing: 1px;
        }

        .empresa-footer {
            font-size: 6px;
            color: #aaa;
            margin-top: 2px;
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
                    {!! $l['barcode'] !!}
                </div>
                
                <span class="barcode-text">{{ $l['code'] }}</span>
                <div class="empresa-footer">{{ $l['empresa'] }}</div>
            </div>
            
            @php
                $perPage = $cols * ($format == 'small' ? 8 : ($format == 'medium' ? 6 : 4));
            @endphp

            @if(($loop->index + 1) % $perPage == 0 && !$loop->last)
                </div><div class="page-break"></div><div class="label-grid clearfix">
            @endif
        @endforeach
    </div>
</body>
</html>
