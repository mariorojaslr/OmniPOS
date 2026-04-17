<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$empresa_id = 1; 
echo "Limpiando y re-inyectando en Empresa ID 1...\n";

// Borramos pedidos viejos de la empresa 1 para no mezclar
\App\Models\Order::where('empresa_id', $empresa_id)->delete();
\App\Models\Client::where('empresa_id', $empresa_id)->where('name', 'like', '%GPS-TEST%')->delete();

$clientes = [
    ['name' => '1. GPS-TEST: Kiosco Sol', 'lat' => -34.6037, 'lng' => -58.3816, 'address' => 'Av. Corrientes 1234'],
    ['name' => '2. GPS-TEST: Almacen Esquina', 'lat' => -34.6137, 'lng' => -58.3916, 'address' => 'Rivadavia 2500'],
    ['name' => '3. GPS-TEST: Maxi Kiosco', 'lat' => -34.5937, 'lng' => -58.4016, 'address' => 'Santa Fe 3000'],
];

foreach ($clientes as $c) {
    // 1. Crear el cliente con todas las de la ley
    $cliente = \App\Models\Client::create([
        'empresa_id' => $empresa_id,
        'name' => $c['name'],
        'lat' => $c['lat'],
        'lng' => $c['lng'],
        'address' => $c['address'],
        'direccion' => $c['address'],
        'email' => rand(1,999).'@test.com',
        'phone' => '11223344',
        'active' => 1,
        'type' => 'minorista'
    ]);

    // 2. Crear el pedido para hoy
    \App\Models\Order::create([
        'empresa_id' => $empresa_id,
        'client_id' => $cliente->id,
        'nombre_cliente' => $cliente->name,
        'email' => $cliente->email,
        'telefono' => $cliente->phone,
        'direccion' => $cliente->direccion,
        'metodo_entrega' => 'delivery',
        'metodo_pago' => 'efectivo',
        'estado' => 'pedido_armado',
        'total' => 10000,
        'created_at' => now(), // ¡Importante!
        'updated_at' => now()
    ]);
}

echo "✓ ¡Inyección completa en Empresa 1! Los clientes empiezan con 'GPS-TEST' para que los ubiques rápido.\n";
