<?php
/**
 * NUCLEAR 3.0 - Simulador de Vuelo
 */
header('Content-Type: text/html; charset=utf-8');
echo "<body style='background:#050505; color:#0f0; font-family:monospace; padding:30px;'>";
echo "<h1>🚀 NUCLEAR 3.0: SIMULADOR DE ERROR</h1>";

// 1. FORZAR VISUALIZACIÓN DE ERRORES DE PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. BOOTSTRAP DE LARAVEL
echo "<h2>🛠️ CARGANDO NÚCLEO DE LARAVEL...</h2>";
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Si llegamos acá, Laravel cargó.
    echo "✅ Laravel cargado correctamente.<br>";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "<h2>🕵️ SIMULANDO RUTA 'owner/dashboard':</h2>";
    
    // Intentamos instanciar el controlador directamente
    $controller = new \App\Http\Controllers\Owner\DashboardController();
    
    // Simulamos que somos el usuario ID 1 (Owner)
    $user = \App\Models\User::where('role', 'owner')->first();
    if ($user) {
        auth()->login($user);
        echo "👤 Simunlando login como: " . $user->email . "<br>";
    } else {
        echo "⚠️ No se encontró un usuario OWNER en la DB.<br>";
    }

    echo "🎬 Ejecutando DashboardController@index...<br>";
    
    // Esto debería disparar el error real si existe
    $response = $controller->index();
    
    echo "✅ El controlador respondió sin morir.<br>";
    echo "<div style='border:1px solid #0f0; padding:10px; margin-top:20px; color:#fff;'>";
    echo "REPORTE: El controlador parece estar sano. Si ves esto, el error es en el ROUTER o en el MIDDLEWARE.";
    echo "</div>";

} catch (\Throwable $e) {
    echo "<div style='background:red; color:white; padding:20px; margin-top:20px; font-size:18px;'>";
    echo "🔥 ¡ERROR DETECTADO EN EL SIMULADOR!<br><br>";
    echo "<b>Mensaje:</b> " . $e->getMessage() . "<br>";
    echo "<b>Archivo:</b> " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "</div>";
    
    echo "<h3>Stack Trace:</h3>";
    echo "<pre style='font-size:10px;'>" . $e->getTraceAsString() . "</pre>";
}

echo "</body>";
