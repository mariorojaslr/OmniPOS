<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Ventas de empresa_id = 1 (la de prueba)
$ventas = App\Models\Venta::where('empresa_id', 1)
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();

echo "=== VENTAS EMPRESA ID=1 ===\n\n";
foreach($ventas as $v) {
    $items = $v->items()->count();
    echo "ID:{$v->id} | N°:{$v->numero_comprobante} | tipo:{$v->tipo_comprobante} | total:\${$v->total_con_iva} | items:{$items} | {$v->created_at}\n";
}
echo "\nTotal ventas empresa 1: " . App\Models\Venta::where('empresa_id',1)->count() . "\n";

// Punto de venta configurado
$empresa = App\Models\Empresa::find(1);
echo "Punto de venta config: " . ($empresa->punto_venta ?? 'NO DEFINIDO') . "\n";
