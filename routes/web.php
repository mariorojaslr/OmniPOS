<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

// OWNER
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\EmpresaController;
use App\Http\Controllers\Owner\EmpresaUserController;

// EMPRESA
use App\Http\Controllers\Empresa\DashboardController as EmpresaDashboardController;
use App\Http\Controllers\Empresa\ProductController;
use App\Http\Controllers\Empresa\ProductImageController;
use App\Http\Controllers\Empresa\POSController;
use App\Http\Controllers\Empresa\VentaController;
use App\Http\Controllers\Empresa\DashboardController;

// CATÁLOGO PÚBLICO
use App\Http\Controllers\CatalogController;

/*
|--------------------------------------------------------------------------
| Ruta raíz
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Autenticación (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Dashboard inteligente según rol
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {

    $user = auth()->user();

    if ($user->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }

    if (in_array($user->role, ['empresa', 'usuario'])) {
        return redirect()->route('empresa.dashboard');
    }

    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    abort(403);

})->name('dashboard');

/*
|--------------------------------------------------------------------------
| OWNER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('empresas', EmpresaController::class)
            ->except(['show']);

        Route::patch(
            'empresas/{empresa}/toggle',
            [EmpresaController::class, 'toggleStatus']
        )->name('empresas.toggle');

        Route::patch(
            'empresas/{empresa}/renovar',
            [EmpresaController::class, 'renovar']
        )->name('empresas.renovar');

        Route::get(
            'empresas/{empresa}/users',
            [EmpresaUserController::class, 'index']
        )->name('empresas.users.index');

        Route::get(
            'empresas/{empresa}/users/create',
            [EmpresaUserController::class, 'create']
        )->name('empresas.users.create');

        Route::post(
            'empresas/{empresa}/users',
            [EmpresaUserController::class, 'store']
        )->name('empresas.users.store');

        Route::patch(
            'empresas/{empresa}/users/{user}/toggle',
            [EmpresaUserController::class, 'toggle']
        )->name('empresas.users.toggle');

        Route::patch(
            'empresas/{empresa}/users/{user}/reset-password',
            [EmpresaUserController::class, 'resetPassword']
        )->name('empresas.users.reset');
    });

/*
|--------------------------------------------------------------------------
| EMPRESA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'empresa', 'empresa.activa'])
    ->prefix('empresa')
    ->name('empresa.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [EmpresaDashboardController::class, 'index'])
            ->name('dashboard');

        // Catálogo interno
        Route::get('/catalogo', function () {
            return redirect()->route('empresa.products.index');
        })->name('catalogo.index');

        // Ventas
        Route::get('/ventas', [VentaController::class, 'index'])
            ->name('ventas.index');

        Route::post('/ventas', [VentaController::class, 'store'])
            ->name('ventas.store');

        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS
        |--------------------------------------------------------------------------
        */
        Route::resource('products', ProductController::class)
            ->except(['show', 'destroy']);

        // Ver imágenes
        Route::get(
            'products/{product}/images',
            [ProductImageController::class, 'create']
        )->name('products.images.create');

        // Subir imágenes
        Route::post(
            'products/{product}/images',
            [ProductImageController::class, 'store']
        )->name('products.images.store');

        // 🔴 ELIMINAR IMAGEN (CORREGIDO — ahora dentro del grupo)
        Route::delete(
            'products/{product}/images/{image}',
            [ProductImageController::class, 'destroy']
        )->name('products.images.destroy');

        /*
        |--------------------------------------------------------------------------
        | POS
        |--------------------------------------------------------------------------
        */
        Route::get('/pos', [POSController::class, 'index'])
            ->name('pos.index');

        Route::post('/pos/checkout', [POSController::class, 'checkout'])
            ->name('pos.checkout');
    });

/*
|--------------------------------------------------------------------------
| Catálogo público (sin login)
|--------------------------------------------------------------------------
*/
Route::get('/c/{empresa}', [CatalogController::class, 'index'])
    ->name('catalog.index');

Route::get('/c/{empresa}/producto/{product}', [CatalogController::class, 'show'])
    ->name('catalog.show');

    Route::get('/empresa/products/search', [\App\Http\Controllers\Empresa\ProductController::class, 'search'])
    ->name('empresa.products.search');


Route::post('/empresa/pos', [POSController::class, 'store'])->name('empresa.pos.store');

Route::get('/empresa/dashboard/resumen', [DashboardController::class, 'resumen'])
    ->name('empresa.dashboard.resumen');

    Route::post('/empresa/pos/checkout', [POSController::class, 'store'])
    ->name('empresa.pos.checkout');
