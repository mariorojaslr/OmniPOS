<?php

use Illuminate\Support\Facades\Route;

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

// CATÁLOGO
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
Route::middleware(['auth'])->get('/dashboard', function () {

    $user = auth()->user();

    if ($user->isOwner()) {
        return redirect()->route('owner.dashboard');
    }

    if ($user->isEmpresa()) {
        return redirect()->route('empresa.dashboard');
    }

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

        Route::get('/dashboard', [EmpresaDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        | Productos
        */
        Route::resource('products', ProductController::class)
            ->except(['show', 'destroy']);

        Route::get(
            'products/{product}/images',
            [ProductImageController::class, 'create']
        )->name('products.images.create');

        Route::post(
            'products/{product}/images',
            [ProductImageController::class, 'store']
        )->name('products.images.store');

        /*
        | POS
        */
        Route::get('/pos', [POSController::class, 'index'])
            ->name('pos.index');

        Route::post('/pos/checkout', [POSController::class, 'checkout'])
            ->name('pos.checkout');

        /*
        | Ventas (registro)
        */
        Route::post('/ventas', [VentaController::class, 'store'])
            ->name('ventas.store');
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


/*
|--------------------------------------------------------------------------
| Rutas empresa adicionales
|--------------------------------------------------------------------------
*/
require __DIR__ . '/empresa.php';
