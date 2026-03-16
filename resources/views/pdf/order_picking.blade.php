<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .total { text-align: right; margin-top: 20px; font-size: 14px; font-weight: bold; }
        .checkbox { width: 20px; height: 20px; border: 1px solid #000; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%;">
                    <div class="title">HOJA DE ARMADO #{{ $order->id }}</div>
                    <div>Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</div>
                </td>
                <td style="border: none; width: 50%; text-align: right;">
                    <strong>Empresa:</strong> {{ $empresa->nombre_comercial }}<br>
                    <strong>Estado:</strong> {{ $order->status_label }}
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; background: #f9f9f9;">
        <strong>CLIENTE:</strong> {{ $order->nombre_cliente }}<br>
        <strong>TELEFONO:</strong> {{ $order->telefono }}<br>
        <strong>METODO ENTREGA:</strong> {{ strtoupper($order->metodo_entrega) }}<br>
        <strong>DIRECCIÓN:</strong> {{ $order->direccion ?: 'RETIRO EN LOCAL' }}
    </div>

    <h3>PRODUCTOS A PREPARAR</h3>
    <table>
        <thead>
            <tr>
                <th width="50">Visto</th>
                <th>Producto</th>
                <th width="100">Cantidad</th>
                <th width="100">Ubicación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="text-align: center;"><div class="checkbox"></div></td>
                <td>
                    <strong>{{ $item->product->name }}</strong>
                    @if($item->variant)
                        <br><small>Variante: {{ $item->variant->size }} / {{ $item->variant->color }}</small>
                    @endif
                </td>
                <td style="font-size: 14px; text-align: center;"><strong>{{ number_format($item->cantidad, 0) }}</strong></td>
                <td>-</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL PEDIDO: $ {{ number_format($order->total, 2, ',', '.') }}
    </div>

    <div style="margin-top: 40px;">
        <p>Observaciones de Armado:</p>
        <div style="height: 100px; border: 1px solid #ccc;"></div>
    </div>

</body>
</html>
