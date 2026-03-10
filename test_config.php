<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'BUNNY CONFIG URL: ' . config('filesystems.disks.bunny_storage.url') . PHP_EOL;
