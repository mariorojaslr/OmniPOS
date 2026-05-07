<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Empresa\DashboardController;

// RUTAS DE AUTENTICACIÓN (ESENCIALES)
Route::get('/', function () { return view('welcome'); });
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout.get');

// GRUPO DE EMPRESA (BÁSICO)
Route::middleware(['auth', 'empresa', 'empresa.activa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// DIAGNÓSTICO (POR SI ACASO)
Route::get('/debug-error', function() {
    return "EL LOGIN Y DASHBOARD ESTÁN CARGADOS. SI VES ESTO, VAMOS BIEN.";
});

Route::get('/reparar-rutas', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "✅ CACHE LIMPIA.";
});
