<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Empresa\DashboardController;

// =========================================================
// 🚨 CAPTURADOR NUCLEAR DE ERRORES (ESCRIBE EN test_manual.txt)
// =========================================================
set_exception_handler(function ($e) {
    $path = storage_path('logs/test_manual.txt');
    $msg = "\n\n--- ERROR CAPTURADO [" . date('Y-m-d H:i:s') . "] ---\n";
    $msg .= "MENSAJE: " . $e->getMessage() . "\n";
    $msg .= "ARCHIVO: " . $e->getFile() . " (Línea: " . $e->getLine() . ")\n";
    $msg .= "TRACE: " . substr($e->getTraceAsString(), 0, 1000) . "...\n";
    file_put_contents($path, $msg, FILE_APPEND);
    
    // Devolvemos el error en pantalla también para no dejar dudas
    die("<h1>🚨 ERROR CRÍTICO CAPTURADO</h1><p><b>Mensaje:</b> " . $e->getMessage() . "</p><p><b>Archivo:</b> " . $e->getFile() . ":" . $e->getLine() . "</p><p>El error fue guardado en test_manual.txt</p>");
});

// =========================================================
// 🛠️ HERRAMIENTAS DE DIAGNÓSTICO
// =========================================================

Route::get('/hola-mundo', function () {
    return "✅ SERVIDOR VIVO - HORA: " . date('H:i:s') . " - SI ESTA HORA NO CAMBIA, HAY CACHE.";
});

Route::get('/ver-logs', function() {
    $path = storage_path('logs/test_manual.txt');
    if (!file_exists($path)) return "No hay logs disponibles en test_manual.txt";
    return "<pre>" . htmlspecialchars(file_get_contents($path)) . "</pre>";
});

Route::get('/limpiar-memoria', function() {
    if (function_exists('opcache_reset')) {
        opcache_reset();
        return "✅ MEMORIA OPCACHE REINICIADA. Ahora el servidor debería ver el código nuevo.";
    }
    return "❌ OPCACHE NO ESTÁ DISPONIBLE EN ESTE SERVIDOR.";
});

Route::get('/reparar-rutas', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "✅ CACHE LIMPIA Y RUTAS RESETEADAS.";
});

Route::get('/test-escritura-manual', function() {
    $path = storage_path('logs/test_manual.txt');
    file_put_contents($path, "\nESCRITURA MANUAL EXITOSA: " . date('Y-m-d H:i:s'), FILE_APPEND);
    return "✅ ESCRIBIENDO EN EL LOG ALTERNATIVO...";
});

// =========================================================
// 🔐 AUTENTICACIÓN Y EMPRESA
// =========================================================
Route::get('/', function () { return view('welcome'); });
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout.get');

Route::middleware(['auth', 'empresa', 'empresa.activa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Empresa\ProductController::class);
    Route::resource('ventas', App\Http\Controllers\Empresa\VentaController::class);
});

// 🌐 CATÁLOGO PÚBLICO
Route::get('c/{slug_or_id}', [App\Http\Controllers\Empresa\CatalogController::class, 'index'])->name('catalog.index');
