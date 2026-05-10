<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\SystemError;

$err = SystemError::where('exception_class', '!=', 'Psy\Exception\ParseErrorException')
    ->latest()
    ->first();

if($err) {
    echo "URL: " . $err->url . "\n";
    echo "MSG: " . $err->message . "\n";
    echo "FILE: " . $err->file . "\n";
    echo "LINE: " . $err->line . "\n";
    echo "TRACE: " . substr($err->trace, 0, 1000) . "\n";
} else {
    echo "NO RELEVANT WEB ERRORS FOUND\n";
}
