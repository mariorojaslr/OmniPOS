<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$empresa_id = 1; 
echo "Garantizando que los pedidos de la Empresa 1 sean de DELIVERY...\n";

$orders = \App\Models\Order::where('empresa_id', $empresa_id)->get();

foreach ($orders as $order) {
    // 1. Aseguramos que el cliente tenga dirección y coordenadas
    $client = $order->client;
    if ($client) {
        $client->address = $client->address ?: 'Av. Corrientes 1234';
        $client->lat = $client->lat ?: -34.6037;
        $client->lng = $client->lng ?: -58.3816;
        $client->save();
    }

    // 2. Pasamos el pedido a delivery con dirección válida
    $order->metodo_entrega = 'delivery';
    $order->direccion = $client->address;
    $order->estado = 'pedido_armado'; // El mapa busca este estado
    $order->save();
}

echo "✓ ¡Pedidos actualizados a DELIVERY con éxito!\n";
