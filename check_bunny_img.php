<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$product = \App\Models\Product::where('name', 'Remera Mundial 2026 Mod 1')->first();
$img = $product->images()->first();

echo "=== DIAGNÓSTICO BUNNY.NET ===\n\n";
echo "Path en BD:         " . $img->path . "\n";
echo "BUNNY_URL en .env:  " . env('BUNNY_URL') . "\n";
echo "BUNNY_ENABLED:      " . (env('BUNNY_ENABLED', true) ? 'true' : 'false') . "\n\n";

$bunnyUrl = env('BUNNY_URL');
$useBunny = env('BUNNY_ENABLED', true);

if ($useBunny && $bunnyUrl) {
    $urlBunny = rtrim($bunnyUrl, '/') . '/' . ltrim($img->path, '/');
    echo "URL Bunny.net:      " . $urlBunny . "\n";

    // Verificar si el archivo existe en Bunny CDN
    $headers = get_headers($urlBunny, 1);
    $status = $headers[0] ?? 'sin respuesta';
    echo "Estado HTTP CDN:    " . $status . "\n";
} else {
    echo "Bunny.net está DESACTIVADO o sin URL configurada\n";
    echo "URL local:          /storage/" . ltrim($img->path, '/') . "\n";
}
