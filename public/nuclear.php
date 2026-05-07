<?php
/**
 * NUCLEAR 3.1 - Arranque Forzado
 */
header('Content-Type: text/html; charset=utf-8');
echo "<body style='background:#050505; color:#0f0; font-family:monospace; padding:30px;'>";
echo "<h1>🚀 NUCLEAR 3.1: RECONSTRUCCIÓN DE PUENTE DB</h1>";

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    echo "<h2>🛠️ BOOTEANDO LARAVEL...</h2>";
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // ESTO ES LO QUE FALTABA: Arrancar el Kernel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap(); 
    
    echo "✅ Kernel de Laravel Booteado.<br>";
    
    echo "<h2>🕵️ PROBANDO CONEXIÓN DB:</h2>";
    $dbName = \DB::connection()->getDatabaseName();
    echo "📦 Conectado a base de datos: <b>$dbName</b><br>";

    echo "<h2>🎬 SIMULANDO DASHBOARD:</h2>";
    
    $user = \App\Models\User::where('role', 'owner')->first();
    if ($user) {
        auth()->login($user);
        echo "👤 Login simulado: " . $user->email . "<br>";
    }

    $controller = new \App\Http\Controllers\Owner\DashboardController();
    $response = $controller->index();
    
    echo "✅ ¡ÉXITO! El controlador respondió.<br>";
    echo "Si ves esto, el error real está en las RUTAS o en el MIDDLEWARE.";

} catch (\Throwable $e) {
    echo "<div style='background:red; color:white; padding:20px; margin-top:20px; border-radius:10px;'>";
    echo "🔥 ¡ERROR CAPTURADO!<br><br>";
    echo "<b>Mensaje:</b> " . $e->getMessage() . "<br>";
    echo "<b>Archivo:</b> " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "</div>";
    echo "<h3>Stack Trace:</h3><pre style='font-size:10px;'>" . $e->getTraceAsString() . "</pre>";
}
echo "</body>";
