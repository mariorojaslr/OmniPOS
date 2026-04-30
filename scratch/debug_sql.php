<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Venta;
use Illuminate\Support\Facades\DB;

try {
    $empresa_id = 1; // ID de prueba
    $data = Venta::where('ventas.empresa_id', $empresa_id)
        ->join('clients', 'ventas.client_id', '=', 'clients.id')
        ->whereNotNull('clients.lat')
        ->whereNotNull('clients.lng')
        ->select('clients.lat', 'clients.lng', DB::raw('SUM(ventas.total_con_iva) as total'))
        ->groupBy('clients.lat', 'clients.lng')
        ->get();
    echo "SUCCESS: COUNT " . count($data) . "\n";
    foreach($data as $d) {
        echo "Lat: {$d->lat}, Lng: {$d->lng}, Total: {$d->total}\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
