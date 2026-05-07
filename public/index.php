<?php
die("<h1>🚨 EL SERVIDOR ESTA LEYENDO EL INDEX.PHP CORRECTO</h1>");

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// 🚨 CAPTURADOR NUCLEAR (PUNTO DE ENTRADA)
set_exception_handler(function ($e) {
    $logPath = __DIR__.'/../storage/logs/test_manual.txt';
    $msg = "\n\n--- [NUCLEO] ERROR [" . date('Y-m-d H:i:s') . "] ---\n" . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
    @file_put_contents($logPath, $msg, FILE_APPEND);
    die("<div style='background:#f00;color:#fff;padding:20px;font-family:sans-serif'><h1>🚨 ERROR DE ARRANQUE</h1><p><b>" . $e->getMessage() . "</b></p><pre>" . $e->getFile() . ":" . $e->getLine() . "</pre></div>");
});

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
