<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 5px; }
        body { font-family: sans-serif; font-size: 10px; margin: 0; padding: 10px; border: 1px solid #000; height: 95%; }
        .header { text-align: center; border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        .empresa { font-weight: bold; font-size: 12px; }
        .recipient { margin-bottom: 10px; }
        .label { font-weight: bold; text-transform: uppercase; font-size: 8px; color: #555; }
        .data { font-size: 14px; font-weight: bold; margin-bottom: 5px; display: block; }
        .qr-container { text-align: center; margin-top: 10px; }
        .order-id { font-size: 18px; font-weight: bold; background: #000; color: #fff; padding: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <span class="empresa">{{ $empresa->nombre_comercial }}</span>
        <div style="font-size: 8px;">Remitente: {{ $empresa->direccion }}</div>
    </div>

    <div class="recipient">
        <span class="label">Destinatario:</span>
        <span class="data">{{ $order->nombre_cliente }}</span>
        
        <span class="label">Dirección:</span>
        <span class="data">{{ $order->direccion ?: 'Retiro en local' }}</span>
        
        <span class="label">Teléfono:</span>
        <span class="data">{{ $order->telefono }}</span>
    </div>

    <div style="text-align: center;">
        <div class="order-id">#{{ $order->id }}</div>
        <div style="margin-top: 5px; font-weight: bold;">{{ strtoupper($order->metodo_entrega) }}</div>
    </div>

    <div class="qr-container">
        <img src="{{ $qrUrl }}" width="100">
        <div style="font-size: 7px; margin-top: 2px;">Escaneo de Control Interno</div>
    </div>
</body>
</html>
