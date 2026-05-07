<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Empresa\DashboardController;

// RUTAS DE AUTENTICACIÓN (ESENCIALES)
Route::get('/', function () { return view('welcome'); });
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout.get');

// GRUPO DE EMPRESA (RECONSTRUCCIÓN PROGRESIVA)
Route::middleware(['auth', 'empresa', 'empresa.activa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 📦 ARTICULOS & RUBROS
    Route::resource('products', App\Http\Controllers\Empresa\ProductController::class);
    Route::resource('rubros', App\Http\Controllers\Empresa\RubroController::class);
    Route::get('listados/articulos', [App\Http\Controllers\Empresa\ListadoController::class, 'articulos'])->name('listados.articulos');

    // 📊 STOCK
    Route::resource('stock', App\Http\Controllers\Empresa\StockController::class);
    Route::get('stock-valuation', [App\Http\Controllers\Empresa\StockController::class, 'valuation'])->name('stock.valuation');
    Route::get('stock-faltantes', [App\Http\Controllers\Empresa\StockController::class, 'faltantes'])->name('stock.faltantes');

    // 📑 VENTAS
    Route::resource('ventas', App\Http\Controllers\Empresa\VentaController::class);
    Route::get('ventas-manual', [App\Http\Controllers\Empresa\VentaController::class, 'manual'])->name('ventas.manual');
    Route::resource('pos', App\Http\Controllers\Empresa\POSController::class);
    Route::resource('presupuestos', App\Http\Controllers\Empresa\PresupuestoController::class);
});

// DIAGNÓSTICO (POR SI ACASO)
Route::get('/debug-error', function() {
    return "EL LOGIN Y DASHBOARD ESTÁN CARGADOS. SI VES ESTO, VAMOS BIEN.";
});

Route::get('/reparar-rutas', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "✅ CACHE LIMPIA.";
});
