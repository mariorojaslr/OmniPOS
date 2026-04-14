<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$errors = \App\Models\SystemError::orderByDesc('id')->limit(10)->get();
foreach($errors as $e) {
    echo "ID: " . $e->id . " | ";
    echo "MSG: " . substr($e->message, 0, 100) . "...\n";
    echo "FILE: " . $e->file . "\n";
    echo "URL: " . $e->url . "\n";
    echo "-------------------\n";
}
