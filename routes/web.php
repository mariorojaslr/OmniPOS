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
    Route::get('/empresas/{id}/edit', function($id) { return 'Editar Empresa ' . $id; })->name('empresas.edit');
    
    // Usuarios
    Route::get('/usuarios', function() { return 'Lista de Usuarios'; })->name('usuarios.index');
    
    // Soporte y Facturación
    Route::get('/soporte', function() { return 'Centro de Soporte'; })->name('soporte.index');
    Route::get('/facturacion', function() { return 'Centro Financiero'; })->name('facturacion.index');
    
    // CRM y Marketing
    Route::get('/crm', function() { return 'CRM Estratégico'; })->name('crm.index');
    Route::get('/leads', function() { return 'Leads CRM'; })->name('leads.index');
    
    // Planes y Actualizaciones
    Route::get('/planes', function() { return 'Planes SaaS'; })->name('planes.index');
    Route::get('/updates', function() { return 'Logs de Actualización'; })->name('updates.index');
    
    // Configuración
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
    
    // Impersonación (Entrar como usuario)
    Route::get('/mimetizar/empresa/{empresa}/usuario/{usuario}', function($empresa, $usuario) { 
        return "Mimetizando en empresa $empresa con usuario $usuario"; 
    })->name('mimetizar');
    
    Route::get('/return-to-owner', function() { 
        session()->forget('impersonator_id');
        return redirect()->route('owner.dashboard'); 
    })->name('return-to-owner');
});

// Rutas de Perfil y Globales
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/dashboard', function() {
        return auth()->user()->role === 'owner' ? redirect()->route('owner.dashboard') : redirect('/empresa/dashboard');
    })->name('dashboard');
    
    Route::get('/help/fetch', function() { return response()->json(['status' => 'ok']); })->name('help.fetch');
    Route::get('/password/edit', function() { return back(); })->name('password.edit');
});

require __DIR__.'/auth.php';
