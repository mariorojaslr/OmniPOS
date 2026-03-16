<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$imgPath = 'products/1/1/67cc37af3698d.jpg'; // Una ruta de ejemplo
$bunnyUrl = config('services.bunny.url');
$fullUrl = rtrim($bunnyUrl, '/') . '/' . ltrim($imgPath, '/');

echo "URL CONFIGURADA: " . $fullUrl . PHP_EOL;

try {
    $response = \Illuminate\Support\Facades\Http::get($fullUrl);
    echo "STATUS CODE: " . $response->status() . PHP_EOL;
    if ($response->successful()) {
        echo "LA IMAGEN EXISTE EN BUNNY" . PHP_EOL;
    } else {
        echo "LA IMAGEN NO SE ENCUENTRA EN ESA URL" . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "ERROR DE CONEXIÓN: " . $e->getMessage() . PHP_EOL;
}
