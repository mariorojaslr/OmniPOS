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
use App\Http\Controllers\Owner\SystemUpdateController;

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
use App\Http\Controllers\DemoController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Empresa\ConfiguracionEmpresaController;
use App\Http\Controllers\Empresa\StockController;
use App\Http\Controllers\Empresa\ClientController;
use App\Http\Controllers\Empresa\SupplierController;
use App\Http\Controllers\Empresa\PurchaseController;
use App\Http\Controllers\Empresa\OrderController;
use App\Http\Controllers\Empresa\LabelController;
use App\Http\Controllers\Empresa\InventoryController;
use App\Http\Controllers\Empresa\RubroController;
use App\Http\Controllers\Empresa\BulkPriceUpdateController;
use App\Http\Controllers\Empresa\ExpenseController;
use App\Http\Controllers\Empresa\ExpenseCategoryController;

// ================= AUTH =================
use App\Http\Controllers\Auth\PasswordController;

// ================= CATÁLOGO =================
use App\Http\Controllers\CatalogController;

/* |-------------------------------------------------------------------------- | RUTA RAÍZ |-------------------------------------------------------------------------- */
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/demo-mode', [DemoController::class, 'enter'])->name('demo.mode');


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
        Route::resource('updates', SystemUpdateController::class);

        Route::get('suscripciones', [SuscripcionPagoController::class , 'index'])->name('facturacion.index');
        Route::get('suscripciones/create', [SuscripcionPagoController::class , 'create'])->name('facturacion.create');
        Route::post('suscripciones', [SuscripcionPagoController::class , 'store'])->name('facturacion.store');

        Route::resource('soporte', OwnerSupportTicketController::class)->names('soporte');
        Route::post('soporte/upload-media', [OwnerSupportTicketController::class, 'uploadMedia'])->name('soporte.uploadMedia');

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
        Route::get('/novedades', [EmpresaDashboardController::class , 'novedades'])->name('novedades');

        /*
     |--------------------------------------------------------------------------
     | CLIENTES
     |--------------------------------------------------------------------------
     */
        Route::get('clientes/export', [ClientController::class , 'export'])->name('clientes.export');
        Route::post('clientes/import', [ClientController::class , 'import'])->name('clientes.import');
        Route::resource('clientes', ClientController::class)->except(['destroy']);

        /*
     |--------------------------------------------------------------------------
     | PROVEEDORES
     |--------------------------------------------------------------------------
     */
        Route::resource('proveedores', SupplierController::class)->except(['destroy']);

        /*
      |--------------------------------------------------------------------------
      | GASTOS / EGRESOS
      |--------------------------------------------------------------------------
     */
        Route::resource('gastos', ExpenseController::class)->names('gastos');
        Route::post('gastos/upload-media', [ExpenseController::class, 'uploadMedia'])->name('gastos.uploadMedia');
        Route::resource('gastos-categorias', ExpenseCategoryController::class)->names('gastos_categorias');

        /*
     |--------------------------------------------------------------------------
     | COMPRAS
     |--------------------------------------------------------------------------
     */
        Route::get('/compras', [PurchaseController::class , 'index'])->name('compras.index');
        Route::get('/compras/create', [PurchaseController::class , 'create'])->name('compras.create');
        Route::post('/compras', [PurchaseController::class , 'store'])->name('compras.store');
        Route::get('/compras/{purchase}', [PurchaseController::class , 'show'])->name('compras.show');
        Route::get('/compras/{purchase}/edit', [PurchaseController::class , 'edit'])->name('compras.edit');
        Route::put('/compras/{purchase}', [PurchaseController::class , 'update'])->name('compras.update');
        Route::delete('/compras/{purchase}', [PurchaseController::class , 'destroy'])->name('compras.destroy');
        Route::get('/compras/ultimo-precio/{product}/{variant?}', [PurchaseController::class, 'getLastPrice'])->name('compras.ultimo_precio');

        Route::post('/proveedores/{supplier}/pago', [SupplierController::class, 'recordPayment'])->name('proveedores.pago');

        /*
     |--------------------------------------------------------------------------
     | CONFIGURACIÓN EMPRESA Y SOPORTE
     |--------------------------------------------------------------------------
     */
        Route::get('/configuracion', [ConfiguracionEmpresaController::class , 'index'])->name('configuracion.index');
        Route::post('/configuracion', [ConfiguracionEmpresaController::class , 'save'])->name('configuracion.save');

        Route::resource('soporte', SupportTicketController::class)->names('soporte');
        Route::post('soporte/upload-media', [SupportTicketController::class, 'uploadMedia'])->name('soporte.uploadMedia');

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
        Route::get('/reportes/ventas-fecha-detalle', [ReporteController::class , 'ventasDetallePorFecha'])->name('reportes.ventas_detalle');
        Route::get('/reportes/empresa', [ReporteController::class , 'empresa'])->name('reportes.empresa');
        Route::get('/reportes/export/pdf', [ReporteController::class , 'exportPdf'])->name('reportes.export.pdf');
        Route::get('/reportes/export/excel', [ReporteController::class , 'exportExcel'])->name('reportes.export.excel');

        /*
     |--------------------------------------------------------------------------
     | PRODUCTOS Y RUBROS
     |--------------------------------------------------------------------------
     */
        Route::get('products/export', [ProductController::class , 'export'])->name('products.export');
        Route::post('products/import', [ProductController::class , 'import'])->name('products.import');
        
        // Actualización masiva de precios
        Route::get('products/bulk-price-update', [BulkPriceUpdateController::class, 'index'])->name('products.bulk-price-update');
        Route::post('products/bulk-price-update', [BulkPriceUpdateController::class, 'update'])->name('products.bulk-price-update.update');

        Route::resource('products', ProductController::class)->except(['show', 'destroy']);
        Route::get('products/{product}/labels', [LabelController::class, 'printSingle'])->name('products.labels.single');
        Route::get('labels-hub', [LabelController::class, 'index'])->name('labels.index');
        Route::post('labels-hub/generate', [LabelController::class, 'generate'])->name('labels.generate');

        // INVENTARIO MÓVIL (ESCÁNER)
        Route::get('inventory/scan', [App\Http\Controllers\Empresa\InventoryController::class, 'index'])->name('inventory_scan');
        Route::post('inventory/adjust', [App\Http\Controllers\Empresa\InventoryController::class, 'adjust'])->name('inventory_adjust');
        
        // Sesiones colaborativas
        Route::post('inventory/start', [App\Http\Controllers\Empresa\InventoryController::class, 'startSession'])->name('inventory_start');
        Route::post('inventory/stop', [App\Http\Controllers\Empresa\InventoryController::class, 'stopSession'])->name('inventory_stop');

        Route::resource('rubros', RubroController::class);

        Route::get('products/{product}/images/create', [ProductImageController::class , 'create'])->name('products.images.create');
        Route::post('products/{product}/images', [ProductImageController::class , 'store'])->name('products.images.store');
        Route::delete('products/{product}/images/{image}', [ProductImageController::class , 'destroy'])->name('products.images.destroy');

        Route::group(['prefix' => 'products/{product}/videos', 'as' => 'products.videos.'], function () {
            Route::get('/', [ProductVideoController::class , 'index'])->name('index');
            Route::post('/', [ProductVideoController::class , 'store'])->name('store');
            Route::delete('/{video}', [ProductVideoController::class , 'destroy'])->name('destroy');
        });

        /*
     |--------------------------------------------------------------------------
     | POS
     |--------------------------------------------------------------------------
     */
        Route::get('/pos', [POSController::class , 'index'])->name('pos.index');
        Route::get('/pos/barcode', [POSController::class , 'buscarPorBarcode'])->name('pos.barcode');
        Route::post('/pos/checkout', [POSController::class , 'store'])->name('pos.checkout');

        /*
     |--------------------------------------------------------------------------
     | PEDIDOS POR CATÁLOGO
     |--------------------------------------------------------------------------
     */
        Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/pedidos/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/pedidos/{order}/etiqueta', [OrderController::class, 'printLabel'])->name('orders.label');
        Route::get('/pedidos/{order}/picking', [OrderController::class, 'printPicking'])->name('orders.picking');

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

    $fullPath = storage_path('app/public/' . ltrim($path, '/'));

    if (!file_exists($fullPath)) {
        // Si no existe lo que pide, mandamos el logo por defecto para que no se vea feo
        $defaultPath = public_path('images/logo_premium.png');
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath, ['Content-Type' => 'image/png']);
        }
        abort(404);
    }

    // Usamos una detección de MIME más robusta (no depende de extensiones de PHP que pueden faltar)
    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mimes = [
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'webp' => 'image/webp'
    ];
    $contentType = $mimes[$extension] ?? 'image/png';

    // Desactivamos caché para evitar ver logos viejos tras subir uno nuevo
    return response()->file($fullPath, [
        'Content-Type' => $contentType,
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0'
    ]);
})->where('path', '.*')->name('local.media');

// RUTA PÚBLICA PARA ESCANEO POR QR (SIN AUTH)
Route::get('v/inv/{uuid}', [App\Http\Controllers\Empresa\InventoryController::class, 'guestAccess'])->name('inventory.guest-access');
Route::post('v/inv/adjust', [App\Http\Controllers\Empresa\InventoryController::class, 'adjust'])->name('inventory.guest-adjust');
