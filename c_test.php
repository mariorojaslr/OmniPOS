<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$img = App\Models\ProductImage::latest('id')->first();
$path = $img ? ltrim($img->path, '/') : '';
echo "PATH DB: " . $path . "\n";

$storagePath = storage_path('app/public/' . $path);
echo "En storage/app/public/ existe? " . (file_exists($storagePath) ? 'SI' : 'NO') . "\n";

$publicPath = public_path('storage/' . $path);
echo "En public/storage/ existe (Test Symlink)? " . (file_exists($publicPath) ? 'SI' : 'NO') . "\n";

if ($img) {
    echo "URL asset() => " . asset('storage/' . $path) . "\n";
    echo "URL route() => " . route('local.media', ['path' => $path]) . "\n";
}
