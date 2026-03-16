<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/* |-------------------------------------------------------------------------- | CONTROLLERS |-------------------------------------------------------------------------- */

// ================= OWNER =================
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\EmpresaController;
use App\Http\Controllers\Owner\EmpresaUserController;
use App\Http\Controllers\Owner\PlanController;
use App\Http\Controllers\Owner\SuscripcionPagoController;
use App\Http\Controllers\Owner\SupportTicketController as OwnerSupportTicketController;

// ================= EMPRESA =================
use App\Http\Controllers\Empresa\DashboardController as EmpresaDashboardController;
use App\Http\Controllers\Empresa\SupportTicketController;
use App\Http\Controllers\Empresa\ProductController;
use App\Http\Controllers\Empresa\ProductImageController;
use App\Http\Controllers\Empresa\ProductVideoController;
use App\Http\Controllers\Empresa\POSController;
use App\Http\Controllers\Empresa\VentaController;
Route::get('empresa/ventas/{venta}/pdf', [VentaController::class, 'pdf'])->name('empresa.ventas.pdf')->middleware(['auth', 'empresa', 'empresa.activa']);
use App\Http\Controllers\Empresa\UsuarioDashboardController;
use App\Http\Controllers\Empresa\UsuarioController;
use App\Http\Controllers\Empresa\ReporteController;
use App\Http\Controllers\Empresa\ConfiguracionEmpresaController;
use App\Http\Controllers\Empresa\StockController;
use App\Http\Controllers\Empresa\ClientController;
use App\Http\Controllers\Empresa\SupplierController;
use App\Http\Controllers\Empresa\PurchaseController;

// ================= AUTH =================
use App\Http\Controllers\Auth\PasswordController;

// ================= CATÁLOGO =================
use App\Http\Controllers\CatalogController;

/* |-------------------------------------------------------------------------- | RUTA RAÍZ |-------------------------------------------------------------------------- */
Route::get('/', fn() => redirect()->route('login'));


/* |-------------------------------------------------------------------------- | AUTH (BREEZE) |-------------------------------------------------------------------------- */
require __DIR__ . '/auth.php';


/* |-------------------------------------------------------------------------- | DASHBOARD SEGÚN ROL |-------------------------------------------------------------------------- */
Route::middleware('auth')->get('/dashboard', function () {

    $user = auth()->user();

    if ($user->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }

    if ($user->role === 'usuario') {
        return redirect()->route('empresa.usuario.dashboard');
    }

    return redirect()->route('empresa.dashboard');

})->name('dashboard');


/* |-------------------------------------------------------------------------- | OWNER |-------------------------------------------------------------------------- */
Route::middleware(['auth', 'owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        Route::get('/dashboard', [OwnerDashboardController::class , 'index'])
            ->name('dashboard')
            ->middleware('can:isOwner');

        Route::resource('empresas', EmpresaController::class)->except(['show']);
        Route::resource('planes', PlanController::class)->except(['show'])->parameters(['planes' => 'plan']);

        Route::get('suscripciones', [SuscripcionPagoController::class , 'index'])->name('facturacion.index');
        Route::get('suscripciones/create', [SuscripcionPagoController::class , 'create'])->name('facturacion.create');
        Route::post('suscripciones', [SuscripcionPagoController::class , 'store'])->name('facturacion.store');

        Route::resource('soporte', OwnerSupportTicketController::class)->names('soporte');

        Route::patch('empresas/{empresa}/toggle', [EmpresaController::class , 'toggleStatus'])->name('empresas.toggle');
        Route::patch('empresas/{empresa}/renovar', [EmpresaController::class , 'renovar'])->name('empresas.renovar');

        Route::get('empresas/{empresa}/users', [EmpresaUserController::class , 'index'])->name('empresas.users.index');
        Route::get('empresas/{empresa}/users/create', [EmpresaUserController::class , 'create'])->name('empresas.users.create');
        Route::post('empresas/{empresa}/users', [EmpresaUserController::class , 'store'])->name('empresas.users.store');

        Route::patch('empresas/{empresa}/users/{user}/toggle', [EmpresaUserController::class , 'toggle'])->name('empresas.users.toggle');
        Route::patch('empresas/{empresa}/users/{user}/reset-password', [EmpresaUserController::class , 'resetPassword'])->name('empresas.users.reset');
        Route::get('empresas/{empresa}/users/{user}/impersonate', [EmpresaUserController::class , 'impersonate'])->name('empresas.users.impersonate');
    });


/* |-------------------------------------------------------------------------- | SALIR DEL MODO "ENTRAR COMO USUARIO" (IMPERSONATE) |-------------------------------------------------------------------------- */
Route::middleware('auth')->get('/impersonate/leave', function (Request $request) {
    if (session()->has('impersonate_by')) {
        $ownerId = session()->pull('impersonate_by');
        $owner = \App\Models\User::find($ownerId);

        if ($owner) {
            Auth::login($owner);
            $request->session()->regenerate();
            return redirect()->route('owner.dashboard')->with('success', 'Has vuelto a tu cuenta principal de Owner.');
        }
    }
    return redirect()->route('dashboard');
})->name('impersonate.leave');


/* |-------------------------------------------------------------------------- | EMPRESA |-------------------------------------------------------------------------- */
Route::middleware(['auth', 'empresa', 'empresa.activa'])
    ->prefix('empresa')
    ->name('empresa.')
    ->group(function () {

        /*
     |--------------------------------------------------------------------------
     | DASHBOARD
     |--------------------------------------------------------------------------
     */
        Route::get('/dashboard', [EmpresaDashboardController::class , 'index'])->name('dashboard');
        Route::get('/usuario/dashboard', [UsuarioDashboardController::class , 'index'])->name('usuario.dashboard');

        /*
     |--------------------------------------------------------------------------
     | CLIENTES
     |--------------------------------------------------------------------------
     */
        Route::resource('clientes', ClientController::class)->except(['destroy']);

        /*
     |--------------------------------------------------------------------------
     | PROVEEDORES
     |--------------------------------------------------------------------------
     */
        Route::resource('proveedores', SupplierController::class)->except(['destroy']);

        /*
     |--------------------------------------------------------------------------
     | COMPRAS
     |--------------------------------------------------------------------------
     */
        Route::get('/compras', [PurchaseController::class , 'index'])->name('compras.index');
        Route::get('/compras/create', [PurchaseController::class , 'create'])->name('compras.create');
        Route::post('/compras', [PurchaseController::class , 'store'])->name('compras.store');
        Route::get('/compras/{purchase}', [PurchaseController::class , 'show'])->name('compras.show');
        Route::delete('/compras/{purchase}', [PurchaseController::class , 'destroy'])->name('compras.destroy');

        /*
     |--------------------------------------------------------------------------
     | CONFIGURACIÓN EMPRESA Y SOPORTE
     |--------------------------------------------------------------------------
     */
        Route::get('/configuracion', [ConfiguracionEmpresaController::class , 'index'])->name('configuracion.index');
        Route::post('/configuracion', [ConfiguracionEmpresaController::class , 'save'])->name('configuracion.save');

        Route::resource('soporte', SupportTicketController::class)->names('soporte');

        /*
     |--------------------------------------------------------------------------
     | STOCK / KARDEX
     |--------------------------------------------------------------------------
     */
        Route::get('/stock/kardex/{product}', [StockController::class , 'kardex'])->name('stock.kardex');
        Route::get('/stock/kardex/{product}/pdf', [StockController::class , 'exportPdf'])->name('stock.kardex.pdf');
        Route::get('/stock/kardex/{product}/excel', [StockController::class , 'exportExcel'])->name('stock.kardex.excel');

        Route::get('/stock', [StockController::class , 'index'])->name('stock.index');
        Route::patch('/stock/{product}', [StockController::class , 'update'])->name('stock.update');
        Route::post('/stock/config/{product}', [StockController::class , 'config'])->name('stock.config');

        /*
     |--------------------------------------------------------------------------
     | USUARIOS
     |--------------------------------------------------------------------------
     */
        Route::get('/usuarios', [UsuarioController::class , 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class , 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class , 'store'])->name('usuarios.store');
        Route::patch('/usuarios/{user}/toggle', [UsuarioController::class , 'toggle'])->name('usuarios.toggle');
        Route::patch('/usuarios/{user}/reset-password', [UsuarioController::class , 'resetPassword'])->name('usuarios.reset');
        Route::get('/usuarios/{usuario}/desempeno', [UsuarioController::class , 'desempeno'])->name('usuarios.desempeno');

        /*
     |--------------------------------------------------------------------------
     | REPORTES
     |--------------------------------------------------------------------------
     */
        Route::get('/reportes', [ReporteController::class , 'panel'])->name('reportes.panel');
        Route::get('/reportes/ranking-productos', [ReporteController::class , 'rankingProductos'])->name('reportes.productos');
        Route::get('/reportes/ranking-clientes', [ReporteController::class , 'rankingClientes'])->name('reportes.clientes');
        Route::get('/reportes/ventas-fecha', [ReporteController::class , 'ventasPorFecha'])->name('reportes.ventas_fecha');
        Route::get('/reportes/empresa', [ReporteController::class , 'empresa'])->name('reportes.empresa');
        Route::get('/reportes/export/pdf', [ReporteController::class , 'exportPdf'])->name('reportes.export.pdf');
        Route::get('/reportes/export/excel', [ReporteController::class , 'exportExcel'])->name('reportes.export.excel');

        /*
     |--------------------------------------------------------------------------
     | PRODUCTOS
     |--------------------------------------------------------------------------
     */
        Route::resource('products', ProductController::class)->except(['show', 'destroy']);

        Route::get('products/{product}/images/create', [ProductImageController::class , 'create'])->name('products.images.create');
        Route::post('products/{product}/images', [ProductImageController::class , 'store'])->name('products.images.store');
        Route::delete('products/{product}/images/{image}', [ProductImageController::class , 'destroy'])->name('products.images.destroy');

        Route::prefix('products/{product}/videos')
            ->name('products.videos.')
            ->group(function () {

            Route::get('/', [ProductVideoController::class , 'index'])->name('index');
            Route::post('/', [ProductVideoController::class , 'store'])->name('store');
            Route::delete('/{video}', [ProductVideoController::class , 'destroy'])->name('destroy');

        }
        );

        /*
     |--------------------------------------------------------------------------
     | POS
     |--------------------------------------------------------------------------
     */
        Route::get('/pos', [POSController::class , 'index'])->name('pos.index');
        Route::post('/pos/checkout', [POSController::class , 'store'])->name('pos.checkout');

    });


/* |-------------------------------------------------------------------------- | CATÁLOGO PÚBLICO |-------------------------------------------------------------------------- */
Route::get('/c/{empresa}', [CatalogController::class , 'index'])->name('catalog.index');
Route::get('/c/{empresa}/producto/{product}', [CatalogController::class , 'show'])->name('catalog.show');


/* |-------------------------------------------------------------------------- | CHECKOUT |-------------------------------------------------------------------------- */
use App\Http\Controllers\CheckoutController;

Route::get('/checkout', [CheckoutController::class , 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class , 'store'])->name('checkout.store');


/* |-------------------------------------------------------------------------- | API DASHBOARD RESUMEN |-------------------------------------------------------------------------- */
Route::middleware('auth')->get(
    '/empresa/dashboard/resumen',
[EmpresaDashboardController::class , 'resumen']
)->name('empresa.dashboard.resumen');


/* |-------------------------------------------------------------------------- | LOGOUT UNIVERSAL |-------------------------------------------------------------------------- */
Route::get('/logout', function (Request $request) {

    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');

})->name('logout.get');


/* |-------------------------------------------------------------------------- | CARRITO |-------------------------------------------------------------------------- */
use App\Http\Controllers\CartController;

Route::get('/cart', [CartController::class , 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class , 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class , 'remove'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class , 'update'])->name('cart.update');

/* |-------------------------------------------------------------------------- | LOCAL MEDIA FALLBACK  |-------------------------------------------------------------------------- | Ruta de emergencia que sirve el archivo de forma directa a través | de PHP para saltarse el bloqueo de Symphly Links de Hostinger. */
Route::get('/local-media/{path}', function ($path) {
    if (strpos($path, '..') !== false) {
        abort(404);
    }

    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }

    $mimeType = mime_content_type($fullPath);
    return response()->file($fullPath, ['Content-Type' => $mimeType]);
})->where('path', '.*')->name('local.media');
