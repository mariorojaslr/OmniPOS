<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
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
use App\Http\Controllers\Empresa\UsuarioDashboardController;
use App\Http\Controllers\Empresa\UsuarioController;
use App\Http\Controllers\Empresa\ReporteController;
use App\Http\Controllers\Empresa\ConfiguracionEmpresaController;

// AUTH
use App\Http\Controllers\Auth\PasswordController;

// CATÁLOGO
use App\Http\Controllers\CatalogController;


/*
|--------------------------------------------------------------------------
| RUTA RAÍZ
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));


/*
|--------------------------------------------------------------------------
| AUTH BREEZE
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| DASHBOARD SEGÚN ROL
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {

    $user = auth()->user();

    if ($user->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }

    if ($user->role === 'empresa') {
        return redirect()->route('empresa.dashboard');
    }

    if ($user->role === 'usuario') {
        return redirect()->route('empresa.usuario.dashboard');
    }

    Auth::logout();
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

        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

        Route::resource('empresas', EmpresaController::class)->except(['show']);

        Route::patch('empresas/{empresa}/toggle', [EmpresaController::class, 'toggleStatus'])->name('empresas.toggle');
        Route::patch('empresas/{empresa}/renovar', [EmpresaController::class, 'renovar'])->name('empresas.renovar');

        Route::get('empresas/{empresa}/users', [EmpresaUserController::class, 'index'])->name('empresas.users.index');
        Route::get('empresas/{empresa}/users/create', [EmpresaUserController::class, 'create'])->name('empresas.users.create');
        Route::post('empresas/{empresa}/users', [EmpresaUserController::class, 'store'])->name('empresas.users.store');

        Route::patch('empresas/{empresa}/users/{user}/toggle', [EmpresaUserController::class, 'toggle'])->name('empresas.users.toggle');
        Route::patch('empresas/{empresa}/users/{user}/reset-password', [EmpresaUserController::class, 'resetPassword'])->name('empresas.users.reset');
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

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [EmpresaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/usuario/dashboard', [UsuarioDashboardController::class, 'index'])->name('usuario.dashboard');


        /*
        |--------------------------------------------------------------------------
        | CONFIGURACIÓN EMPRESA (LOGO + COLORES + TEMA)
        |--------------------------------------------------------------------------
        */
        Route::get('/configuracion', [ConfiguracionEmpresaController::class, 'index'])
            ->name('configuracion.index');

        Route::post('/configuracion', [ConfiguracionEmpresaController::class, 'save'])
            ->name('configuracion.save');


        /*
        |--------------------------------------------------------------------------
        | USUARIOS
        |--------------------------------------------------------------------------
        */
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

        Route::patch('/usuarios/{user}/toggle', [UsuarioController::class, 'toggle'])->name('usuarios.toggle');
        Route::patch('/usuarios/{user}/reset-password', [UsuarioController::class, 'resetPassword'])->name('usuarios.reset');

        Route::get('/usuarios/{usuario}/desempeno', [UsuarioController::class, 'desempeno'])->name('usuarios.desempeno');


        /*
        |--------------------------------------------------------------------------
        | REPORTES
        |--------------------------------------------------------------------------
        */
        Route::get('/reportes', [ReporteController::class, 'panel'])->name('reportes.panel');
        Route::get('/reportes/ranking-productos', [ReporteController::class, 'rankingProductos'])->name('reportes.productos');
        Route::get('/reportes/ranking-clientes', [ReporteController::class, 'rankingClientes'])->name('reportes.clientes');
        Route::get('/reportes/ventas-fecha', [ReporteController::class, 'ventasPorFecha'])->name('reportes.ventas_fecha');
        Route::get('/reportes/empresa', [ReporteController::class, 'empresa'])->name('reportes.empresa');

        Route::get('/reportes/export/pdf', [ReporteController::class, 'exportPdf'])->name('reportes.export.pdf');
        Route::get('/reportes/export/excel', [ReporteController::class, 'exportExcel'])->name('reportes.export.excel');


        /*
        |--------------------------------------------------------------------------
        | CATÁLOGO INTERNO
        |--------------------------------------------------------------------------
        */
        Route::get('/catalogo', fn () => redirect()->route('empresa.products.index'))
            ->name('catalogo.index');


        /*
        |--------------------------------------------------------------------------
        | VENTAS
        |--------------------------------------------------------------------------
        */
        Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
        Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');


        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS
        |--------------------------------------------------------------------------
        */
        Route::resource('products', ProductController::class)->except(['show', 'destroy']);

        Route::get('products/{product}/images/create', [ProductImageController::class, 'create'])->name('products.images.create');
        Route::post('products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
        Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');


        /*
        |--------------------------------------------------------------------------
        | POS
        |--------------------------------------------------------------------------
        */
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [POSController::class, 'store'])->name('pos.checkout');
    });


/*
|--------------------------------------------------------------------------
| CATÁLOGO PÚBLICO
|--------------------------------------------------------------------------
*/
Route::get('/c/{empresa}', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/c/{empresa}/producto/{product}', [CatalogController::class, 'show'])->name('catalog.show');


/*
|--------------------------------------------------------------------------
| APIs INTERNAS
|--------------------------------------------------------------------------
*/
Route::get('/empresa/products/search', [ProductController::class, 'search'])->name('empresa.products.search');
Route::get('/empresa/dashboard/resumen', [EmpresaDashboardController::class, 'resumen'])->name('empresa.dashboard.resumen');


/*
|--------------------------------------------------------------------------
| CAMBIO DE CONTRASEÑA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/password', fn () => view('auth.passwords.change'))->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});
