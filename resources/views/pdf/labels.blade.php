<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Etiquetas de Productos</title>
    <style>
        @page { margin: 10px; }
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
            width: 31%; /* 3 por fila */
            height: 120px;
            margin: 1%;
            float: left;
            border: 1px dashed #ccc;
            padding: 10px;
            text-align: center;
            box-sizing: border-box;
            background: #fff;
            overflow: hidden;
        }

        .product-name {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price-tag {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 900;
            margin-bottom: 5px;
        }

        .barcode-container {
            margin-top: 5px;
            height: 40px;
            width: 100%;
        }

        .barcode-container svg {
            width: 100% !important;
            height: 100% !important;
        }

        .barcode-text {
            font-size: 9px;
            color: #555;
            margin-top: 2px;
            display: block;
            letter-spacing: 2px;
        }

        .empresa-footer {
            font-size: 7px;
            color: #999;
            margin-top: 5px;
            text-transform: uppercase;
        }

        /* Limpieza de floats */
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
            
            @if(($loop->index + 1) % 21 == 0 && !$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
</body>
</html>
