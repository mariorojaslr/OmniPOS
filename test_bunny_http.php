<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$bunnyKey = '8b078c5f-ad56-4ad8-a4a7b28e775f-63eb-4d16'; // From env example user provided
$bunnyZone = 'gente-piola';
$bunnyHost = 'ny.storage.bunnycdn.com';

$bunnyApiUrl = "https://{$bunnyHost}/{$bunnyZone}/testapi.txt";

$response = \Illuminate\Support\Facades\Http::withHeaders([
    'AccessKey' => $bunnyKey,
    'Content-Type' => 'text/plain'
])->withBody('hello world', 'text/plain')->put($bunnyApiUrl);

if (!$response->successful()) {
    echo 'ERROR: ' . $response->body() . PHP_EOL;
}
else {
    echo 'HTTP UPLOAD OK' . PHP_EOL;
}
