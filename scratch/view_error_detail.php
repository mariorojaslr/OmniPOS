<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$error = \App\Models\SystemError::orderByDesc('id')->first();
if ($error) {
    echo "ID: " . $error->id . "\n";
    echo "REF: " . ($error->reference ?? 'N/A') . "\n";
    echo "MSG: " . $error->message . "\n";
    echo "FILE: " . $error->file . "\n";
    echo "LINE: " . $error->line . "\n";
    // Search for the line that mentions manual.blade.php inside the message
} else {
    echo "NO ERRORS FOUND.\n";
}
