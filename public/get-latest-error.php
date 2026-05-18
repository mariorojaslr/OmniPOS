<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$latest = \DB::table('system_errors')->orderBy('id', 'desc')->first();
if ($latest) {
    echo "ID: {$latest->id}\n";
    echo "Class: {$latest->exception_class}\n";
    echo "Message: {$latest->message}\n";
    echo "File: {$latest->file} (Línea {$latest->line})\n";
} else {
    echo "No errors logged yet.\n";
}
