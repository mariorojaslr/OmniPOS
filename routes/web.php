<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Empresa\DashboardController;

// =========================================================
// 🚨 HERRAMIENTAS DE DIAGNÓSTICO (ARRIBA DE TODO)
// =========================================================

Route::get('/hola-mundo', function () {
    return "✅ EL SERVIDOR ESTÁ VIVO Y RESPONDIENDO TEXTO PLANO.";
});

Route::get('/forzar-error', function() {
    throw new \Exception("PRUEBA DE LOG: SI VES ESTO, EL LOG FUNCIONA.");
});

Route::get('/check-permisos', function() {
    $path = storage_path('logs');
    $isWritable = is_writable($path);
    return [
        'logs_path' => $path,
        'es_escribible' => $isWritable ? '✅ SÍ' : '❌ NO',
        'espacio_libre_mb' => round(disk_free_space("/") / (1024 * 1024), 2),
        'php_version' => PHP_VERSION
    ];
});

Route::get('/ver-logs', function() {
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) return "No hay logs disponibles. El archivo no existe.";
    return "<pre>" . htmlspecialchars(file_get_contents($path)) . "</pre>";
});

Route::get('/borrar-logs', function() {
    $path = storage_path('logs/laravel.log');
    if (file_exists($path)) {
        unlink($path);
        return "✅ ARCHIVO DE LOGS ELIMINADO. Ahora entrá al Dashboard para generar uno nuevo.";
    }
    return "El archivo ya no existía.";
});

Route::get('/reparar-rutas', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "✅ CACHE LIMPIA Y RUTAS RESETEADAS.";
});

// =========================================================
// 🔐 AUTENTICACIÓN
// =========================================================

Route::get('/', function () { return view('welcome'); });
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout.get');

// =========================================================
// 🏢 ZONA EMPRESA (MULTI-TENANT)
// =========================================================

Route::middleware(['auth', 'empresa', 'empresa.activa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Módulos básicos (Restaurados)
    Route::resource('products', App\Http\Controllers\Empresa\ProductController::class);
    Route::resource('rubros', App\Http\Controllers\Empresa\RubroController::class);
    Route::resource('stock', App\Http\Controllers\Empresa\StockController::class);
    Route::resource('ventas', App\Http\Controllers\Empresa\VentaController::class);
    Route::resource('pos', App\Http\Controllers\Empresa\POSController::class);
    Route::resource('clientes', App\Http\Controllers\Empresa\ClientController::class);
    Route::resource('proveedores', App\Http\Controllers\Empresa\ProviderController::class);
    Route::resource('gastos', App\Http\Controllers\Empresa\GastoController::class);
    Route::resource('usuarios', App\Http\Controllers\Empresa\UsuarioController::class);
    Route::resource('soporte', App\Http\Controllers\Empresa\SupportTicketController::class);
    Route::resource('compras', App\Http\Controllers\Empresa\PurchaseController::class);
    Route::resource('orders', App\Http\Controllers\Empresa\OrderController::class);

    // Extras
    Route::get('configuracion', [App\Http\Controllers\Empresa\ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::get('reportes-panel', [App\Http\Controllers\Empresa\ReporteController::class, 'panel'])->name('reportes.panel');
});

// 🌐 CATÁLOGO PÚBLICO
Route::get('c/{slug_or_id}', [App\Http\Controllers\Empresa\CatalogController::class, 'index'])->name('catalog.index');
