<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
// =========================================================
// 🔐 AUTENTICACIÓN Y REDIRECCIÓN CENTRAL
// =========================================================
Route::get('/', function () { return view('welcome'); });
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout.get');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// --- RUTAS GLOBALES PARA EL LAYOUT ---
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'owner') return redirect()->route('owner.dashboard');
    return redirect()->route('empresa.dashboard');
})->name('dashboard');

Route::get('/profile/edit', function() { return back()->with('info', 'Módulo de perfil en desarrollo'); })->name('profile.edit');
Route::get('/password/edit', function() { return back()->with('info', 'Módulo de contraseña en desarrollo'); })->name('password.edit');
Route::get('/help/fetch', [App\Http\Controllers\HelpController::class, 'fetch'])->name('help.fetch');
Route::post('/help/save', [App\Http\Controllers\HelpController::class, 'save'])->name('help.save');
Route::get('forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');

// =========================================================
// 🏢 RUTAS DE EMPRESA CLIENTE
// =========================================================
Route::middleware(['auth', 'empresa', 'empresa.activa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Empresa\ProductController::class);
    Route::resource('ventas', App\Http\Controllers\Empresa\VentaController::class);
});

// =========================================================
// 👑 RUTAS DEL OWNER (SISTEMA CENTRAL)
// =========================================================
Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
    
    // Placeholders para que el dashboard no explote
    Route::get('/empresas', function() { return "Listado de Empresas"; })->name('empresas.index');
    Route::get('/empresas/create', function() { return "Crear Empresa"; })->name('empresas.create');
    Route::get('/empresas/{id}/edit', function($id) { return "Editar Empresa $id"; })->name('empresas.edit');
    
    Route::get('/crm', function() { return "CRM"; })->name('crm.index');
    Route::get('/facturacion', function() { return "Facturación"; })->name('facturacion.index');
    Route::get('/soporte', function() { return "Soporte"; })->name('soporte.index');
    Route::get('/planes', function() { return "Planes"; })->name('planes.index');
    Route::get('/updates', function() { return "Actualizaciones"; })->name('updates.index');
    
    Route::post('/settings/update', [App\Http\Controllers\Owner\DashboardController::class, 'updateSettings'])->name('settings.update');
    
    // Mimetización (Entrar como usuario)
    Route::get('/mimetizar/empresa/{empresa}/usuario/{usuario}', function($empresa, $usuario) {
        return "Mimetizando en Empresa $empresa con Usuario $usuario";
    })->name('mimetizar');
    // Perfil y Password
    Route::get('/profile/password', function() { return "Cambiar Password"; })->name('password.edit');
    
    // Sistema de Ayuda (Arti)
    Route::get('/help/fetch', function() { return response()->json(['success' => false]); })->name('help.fetch');
    Route::post('/help/save', function() { return response()->json(['success' => false]); })->name('help.save');

    // Ruta de diagnóstico absoluto
    Route::get('/test-owner', function() {
        return "<h1>✅ AUTENTICACIÓN OK</h1><p>Logueado como: " . auth()->user()->name . " (" . auth()->user()->role . ")</p>";
    });
});

// 🌐 CATÁLOGO PÚBLICO
Route::get('c/{slug_or_id}', [App\Http\Controllers\Empresa\CatalogController::class, 'index'])->name('catalog.index');
