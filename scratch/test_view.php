<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Empresa\VentaController;
use App\Models\User;

try {
    $u = User::where('role', 'empresa')->whereNotNull('empresa_id')->first();
    if (!$u) die("No empresa user found");
    
    auth()->login($u);

    session(['prefill_factura' => [
        'presupuesto_id'  => 1,
        'presupuesto_ref' => 'PRE-0001',
        'client_id'       => 1,
        'items'           => [
            [
                'product_id'  => 1,
                'descripcion' => 'Test',
                'qty'         => 1,
                'price'       => 100
            ]
        ]
    ]]);
    
    $controller = app(VentaController::class);
    echo $controller->createManual()->render();
    echo "\n\nSUCCESS! View rendered.\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "LINE: " . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
