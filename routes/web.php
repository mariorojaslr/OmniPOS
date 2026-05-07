<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Bienvenida y Auth base
Route::get('/', function () { return view('welcome'); });

// Rutas de autenticación manuales para control total
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// 👑 RUTAS DEL OWNER (SISTEMA CENTRAL)
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Configuración y Soporte
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
});

// Rutas de Perfil (Globales)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Password reset placeholders
    Route::get('/password/edit', function() { return back(); })->name('password.edit');
});

require __DIR__.'/auth.php';
