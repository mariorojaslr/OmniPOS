<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$results = \Illuminate\Support\Facades\DB::select("DESCRIBE system_errors");
foreach($results as $r) {
    echo $r->Field . " (" . $r->Type . ")\n";
}

echo "\nLATEST 5 ERRORS:\n";
$errors = \App\Models\SystemError::orderByDesc('id')->limit(5)->get();
foreach($errors as $e) {
    echo "ID: " . $e->id . " | ";
    echo "REF: " . ($e->reference ?? 'N/A') . " | ";
    echo "MSG: " . substr($e->message, 0, 50) . "...\n";
}
