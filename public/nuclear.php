<?php
/**
 * NUCLEAR 3.2 - Petición Simulada
 */
header('Content-Type: text/html; charset=utf-8');
echo "<body style='background:#000; color:#0f0; font-family:monospace; padding:30px;'>";
echo "<h1>🚀 NUCLEAR 3.2: ESCÁNER DE CÓDIGO FINAL</h1>";

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    echo "🛠️ BOOTEANDO LARAVEL...<br>";
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap(); 

    // INYECTAR REQUEST (Esto corrige el error anterior)
    $request = \Illuminate\Http\Request::capture();
    $app->instance('request', $request);
    echo "✅ Petición capturada e inyectada.<br>";
    
    echo "<h2>🎬 EJECUTANDO LÓGICA DE DASHBOARD:</h2>";
    
    $user = \App\Models\User::where('role', 'owner')->first();
    if ($user) {
        // Usamos el guard de web explícitamente
        \Auth::guard('web')->setUser($user);
        echo "👤 Usuario Owner identificado: " . $user->email . "<br>";
    }

    echo "🛰️ Llamando a DashboardController@index...<br><hr>";
    
    $controller = new \App\Http\Controllers\Owner\DashboardController();
    $result = $controller->index();
    
    echo "<div style='color:white; background:green; padding:20px;'>";
    echo "✅ ¡DASHBOARD RENDERIZADO CON ÉXITO!<br>";
    echo "Si ves este mensaje verde, el código NO TIENE ERRORES. El problema es de Hostinger o del Router.";
    echo "</div>";
    
    echo "<h3>VISTA PREVIA (HTML):</h3>";
    echo "<div style='background:#fff; color:#333; padding:10px; height:300px; overflow:auto;'>";
    echo htmlspecialchars(substr($result, 0, 2000)) . "...";
    echo "</div>";

} catch (\Throwable $e) {
    echo "<div style='background:red; color:white; padding:20px; margin-top:20px;'>";
    echo "🔥 ¡ERROR DE CÓDIGO DETECTADO!<br><br>";
    echo "<b>Mensaje:</b> " . $e->getMessage() . "<br>";
    echo "<b>Archivo:</b> " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "</div>";
    echo "<h3>Stack Trace:</h3><pre style='font-size:10px;'>" . $e->getTraceAsString() . "</pre>";
}
echo "</body>";
