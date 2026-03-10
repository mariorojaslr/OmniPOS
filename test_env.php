<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'BUNNY URL EN ENV: ' . env('BUNNY_PULL_ZONE_URL') . PHP_EOL;
