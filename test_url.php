<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$img = App\Models\ProductImage::latest('id')->first();
if ($img) {
    echo "URL generada: " . $img->url . "\n";
    echo "Path interno: " . $img->path . "\n";
}
