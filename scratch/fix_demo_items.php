<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$empresa_id = 1; 
$product_id = 55;
echo "Reparando pedidos (Intento FINAL)...\n";

$orders = \App\Models\Order::where('empresa_id', $empresa_id)->get();

foreach ($orders as $order) {
    \App\Models\OrderItem::where('order_id', $order->id)->delete();

    \App\Models\OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product_id,
        'nombre_producto' => 'Producto Demo A (Gps Test)',
        'cantidad' => 2,
        'precio_unitario' => 2500,
        'precio' => 2500, 
        'subtotal' => 5000
    ]);

    \App\Models\OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product_id,
        'nombre_producto' => 'Producto Demo B (Gps Test)',
        'cantidad' => 1,
        'precio_unitario' => 5000,
        'precio' => 5000, 
        'subtotal' => 5000
    ]);

    $order->metodo_entrega = 'delivery';
    $order->save();
}

echo "✓ ¡Pedidos reparados con productos! Ahora vamos por la UI.\n";
