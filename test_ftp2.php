<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$files = Illuminate\Support\Facades\Storage::disk('bunny_storage')->allFiles();

print_r($files);
