<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \Illuminate\Support\Facades\Storage::disk('bunny_storage')->put('test.txt', 'probando');
    echo 'FTP OK';

}
catch (\Exception $e) {
    echo 'ERROR: ' . $e->getMessage();

}
