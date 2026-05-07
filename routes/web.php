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

// Rutas de autenticación
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// 👑 RUTAS DEL OWNER (SISTEMA CENTRAL)
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Empresas
    Route::get('/empresas', function() { return 'Lista de Empresas'; })->name('empresas.index');
    Route::get('/empresas/create', function() { return 'Crear Empresa'; })->name('empresas.create');
    Route::get('/empresas/{id}', function() { return 'Detalle Empresa'; })->name('empresas.show');
    
    // Usuarios
    Route::get('/usuarios', function() { return 'Lista de Usuarios'; })->name('usuarios.index');
    
    // Soporte y CRM
    Route::get('/tickets', function() { return 'Tickets de Soporte'; })->name('tickets.index');
    Route::get('/leads', function() { return 'Leads CRM'; })->name('leads.index');
    
    // Configuración
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
    
    // Impersonación (Entrar como usuario)
    Route::get('/return-to-owner', function() { 
        session()->forget('impersonator_id');
        return redirect()->route('owner.dashboard'); 
    })->name('return-to-owner');
});

// Rutas de Perfil (Globales)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Placeholders para navegación
    Route::get('/dashboard', function() {
        return auth()->user()->role === 'owner' ? redirect()->route('owner.dashboard') : redirect('/empresa/dashboard');
    })->name('dashboard');
    
    Route::get('/help', function() { return 'Ayuda'; })->name('help.fetch');
    Route::get('/password/edit', function() { return 'Editar Password'; })->name('password.edit');
});

require __DIR__.'/auth.php';
