<?php
/**
 * Sincroniza TODAS las imágenes de products/ a Bunny.net
 * Ejecutar: php sync_images_bunny.php
 */
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$bunnyKey  = env('BUNNY_PASSWORD');
$bunnyZone = env('BUNNY_USERNAME');
$bunnyHost = env('BUNNY_HOSTNAME');

echo "=== SYNC IMÁGENES → BUNNY.NET ===\n";
echo "Zone: $bunnyZone @ $bunnyHost\n\n";

if (!$bunnyKey || !$bunnyZone || !$bunnyHost) {
    die("❌ Faltan variables BUNNY_PASSWORD / BUNNY_USERNAME / BUNNY_HOSTNAME en .env\n");
}

$images = \App\Models\ProductImage::all();
$ok = 0; $fail = 0; $skip = 0;

foreach ($images as $img) {
    $localPath = storage_path('app/public/' . $img->path);

    if (!file_exists($localPath)) {
        echo "⚠️  No existe local: {$img->path}\n";
        $skip++;
        continue;
    }

    $bunnyUrl = "https://{$bunnyHost}/{$bunnyZone}/{$img->path}";
    $content  = file_get_contents($localPath);

    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'AccessKey'    => $bunnyKey,
        'Content-Type' => 'image/jpeg',
    ])->withBody($content, 'image/jpeg')->put($bunnyUrl);

    if ($response->successful()) {
        echo "✅ Subida: {$img->path}\n";
        $ok++;
    } else {
        echo "❌ Error ({$response->status()}): {$img->path} → " . $response->body() . "\n";
        $fail++;
    }
}

echo "\n=== RESULTADO ===\n";
echo "✅ Subidas OK:  $ok\n";
echo "❌ Fallidas:    $fail\n";
echo "⚠️  Sin archivo: $skip\n";
