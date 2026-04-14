<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$error = \App\Models\SystemError::where('reference_code', 'CEC995F0')->first();
if ($error) {
    echo "ERROR FOUND:\n";
    echo "Message: " . $error->message . "\n";
    echo "File: " . $error->file . "\n";
    echo "Line: " . $error->line . "\n";
} else {
    echo "ERROR NOT FOUND FOR REF CEC995F0\n";
    // Let's get the absolute latest error
    $latest = \App\Models\SystemError::latest()->first();
    if ($latest) {
        echo "LATEST ERROR:\n";
        echo "Ref: " . $latest->reference_code . "\n";
        echo "Message: " . $latest->message . "\n";
        echo "File: " . $latest->file . "\n";
        echo "Line: " . $latest->line . "\n";
    }
}
