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
use App\Http\Controllers\Empresa\UsuarioDashboardController;
use App\Http\Controllers\Empresa\UsuarioController;
use App\Http\Controllers\Empresa\ReporteController;
use App\Http\Controllers\Empresa\StockController;
use App\Http\Controllers\Empresa\ReplenishmentController;
use App\Http\Controllers\Empresa\RecipeController;
use App\Http\Controllers\Empresa\OrderController;
use App\Http\Controllers\Empresa\ProductionOrderController;
use App\Http\Controllers\Empresa\RubroController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Empresa\ConfiguracionEmpresaController;
use App\Http\Controllers\Empresa\ClientController;
use App\Http\Controllers\Empresa\SupplierController;
use App\Http\Controllers\Empresa\PurchaseController;
use App\Http\Controllers\Empresa\LabelController;
use App\Http\Controllers\Empresa\InventoryController;
use App\Http\Controllers\Empresa\BulkPriceUpdateController;
use App\Http\Controllers\Empresa\ExpenseController;
use App\Http\Controllers\Empresa\ExpenseCategoryController;

// ================= AUTH =================
use App\Http\Controllers\Auth\PasswordController;

// ================= CATÁLOGO =================
use App\Http\Controllers\CatalogController;

/* |-------------------------------------------------------------------------- | RUTA RAÍZ Y CATÁLOGO (PÚBLICO) |-------------------------------------------------------------------------- */
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/c/{empresa}', [CatalogController::class , 'index'])->name('catalog.index');
Route::get('/c/{empresa}/producto/{product}', [CatalogController::class , 'show'])->name('catalog.show');

// MODO DEMO (PUESTA EN MARCHA RÁPIDA - REEMPLAZADO POR DEMO EXPERIENCE)
// Route::get('/demo-mode', [DemoController::class, 'enter'])->name('demo.mode');

/* |-------------------------------------------------------------------------- | AUTH (BREEZE) |-------------------------------------------------------------------------- */
require __DIR__ . '/auth.php';

/* |-------------------------------------------------------------------------- | DASHBOARD Y RUTAS GLOBALES |-------------------------------------------------------------------------- */
Route::middleware('auth')->group(function() {
    
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'owner') { return redirect()->route('owner.dashboard'); }
        if ($user->role === 'usuario') { return redirect()->route('empresa.usuario.dashboard'); }
        return redirect()->route('empresa.dashboard');
    })->name('dashboard');

    // SALIDA DE MIMETIZACIÓN (ÚNICA Y GLOBAL)
    Route::get('/return-to-owner', function(){
        if(session('impersonator_id')){
            $owner = \App\Models\User::find(session('impersonator_id'));
            Auth::login($owner);
            session()->forget('impersonator_id');
            return redirect()->route('owner.dashboard')->with('success', 'Has vuelto a tu sesión de Propietario');
        }
        return redirect('/');
    })->name('owner.return-to-owner');

    // AYUDA CONTEXTUAL (MANUAL INTERACTIVO)
    Route::get('/help/fetch', [App\Http\Controllers\HelpArticleController::class, 'fetch'])->name('help.fetch');
    Route::post('/help/save', [App\Http\Controllers\HelpArticleController::class, 'save'])->name('help.save');
});

/* |-------------------------------------------------------------------------- | OWNER (ADMINISTRACIÓN MASTER) |-------------------------------------------------------------------------- */
Route::middleware(['auth', 'owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        Route::get('/dashboard', [OwnerDashboardController::class , 'index'])->name('dashboard')->middleware('can:isOwner');
        
        // MIMETIZACIÓN (Ruta Única Anti-Conflicto)
        Route::get('/mimetizar/empresa/{empresaId}/usuario/{usuario}', [App\Http\Controllers\Owner\EmpresaUserController::class, 'impersonate'])->name('empresas.users.impersonate');

        // GESTIÓN DE USUARIOS DE EMPRESA (Restaurado Full - Usa {empresaId} para evitar slug binding)
        Route::get('empresas/{empresaId}/users', [EmpresaUserController::class , 'index'])->name('empresas.users.index');
        Route::get('empresas/{empresaId}/users/create', [EmpresaUserController::class , 'create'])->name('empresas.users.create');
        Route::post('empresas/{empresaId}/users', [EmpresaUserController::class , 'store'])->name('empresas.users.store');
        Route::patch('empresas/{empresaId}/users/{usuario}/toggle', [EmpresaUserController::class , 'toggle'])->name('empresas.users.toggle');
        Route::patch('empresas/{empresaId}/users/{usuario}/reset-password', [EmpresaUserController::class , 'resetPassword'])->name('empresas.users.reset');

        Route::resource('empresas', EmpresaController::class)->except(['show'])->parameters(['empresas' => 'empresaId']);
        Route::resource('planes', PlanController::class)->except(['show'])->parameters(['planes' => 'plan']);
        Route::resource('updates', SystemUpdateController::class);

        Route::get('suscripciones', [SuscripcionPagoController::class , 'index'])->name('facturacion.index');
        Route::post('suscripciones-upload', [SuscripcionPagoController::class, 'store'])->name('facturacion.store');

        Route::resource('soporte', OwnerSupportTicketController::class)->names('soporte');
        Route::post('soporte/upload-media', [OwnerSupportTicketController::class, 'uploadMedia'])->name('soporte.uploadMedia');

        Route::patch('empresas/{empresaId}/toggle', [EmpresaController::class , 'toggleStatus'])->name('empresas.toggle');
        Route::patch('empresas/{empresaId}/renovar', [EmpresaController::class , 'renovar'])->name('empresas.renovar');

        // CRM DE PROSPECTOS (NUEVO)
        Route::get('crm', [App\Http\Controllers\Owner\CRMController::class, 'index'])->name('crm.index');
        Route::post('crm/agent-report', [App\Http\Controllers\Owner\CRMController::class, 'agentReport'])->name('crm.agent-report');
        Route::post('crm/{user}/activate', [App\Http\Controllers\Owner\CRMController::class, 'activate'])->name('crm.activate');
        Route::patch('crm/{user}/notes', [App\Http\Controllers\Owner\CRMController::class, 'updateNotes'])->name('crm.notes');
        Route::post('crm/move', [App\Http\Controllers\Owner\CRMController::class, 'move'])->name('crm.move');
        Route::post('crm/archive', [App\Http\Controllers\Owner\CRMController::class, 'archive'])->name('crm.archive');
        Route::post('crm/delete', [App\Http\Controllers\Owner\CRMController::class, 'delete'])->name('crm.delete');
        Route::post('crm/scan-channel', [App\Http\Controllers\Owner\CRMController::class, 'scanChannel'])->name('crm.scan-channel');
        Route::post('crm/scan-all', [App\Http\Controllers\Owner\CRMController::class, 'scanAll'])->name('crm.scan-all');
        Route::post('crm/promote', [App\Http\Controllers\Owner\CRMController::class, 'promote'])->name('crm.promote');
        Route::post('settings', [OwnerDashboardController::class, 'updateSettings'])->name('settings.update');

    });


/* |-------------------------------------------------------------------------- | MODO DEMOSTRACIÓN (LEAD MAGNET) |-------------------------------------------------------------------------- */
Route::get('/demo-experience', function() {
    
    // 1. Buscamos la Empresa de prueba (ID 1 por defecto en el sistema de desarrollo)
    $demoEmpresa = \App\Models\Empresa::find(1);
    
    if (!$demoEmpresa) {
        // Si no existe, creamos una para no romper el flujo
        $demoEmpresa = \App\Models\Empresa::updateOrCreate(
            ['id' => 1],
            [
                'nombre_comercial' => 'MultiPOS Empresa de Prueba',
                'cuit' => '30-11111111-9',
                'activo' => true,
                'slug' => 'empresa-prueba'
            ]
        );
    }

    // 2. Buscamos o creamos el Usuario Demo solicitado por el USER
    $demoUser = \App\Models\User::updateOrCreate(
        ['email' => 'demo@gmail.com'],
        [
            'name' => 'Demo User',
            'password' => \Illuminate\Support\Facades\Hash::make('demo'),
            'role' => 'empresa',
            'empresa_id' => $demoEmpresa->id,
            'activo' => true,
            'status' => 'activo'
        ]
    );

    // 3. Login automático y redirección instantánea
    \Illuminate\Support\Facades\Auth::login($demoUser);
    
    return redirect()->route('empresa.dashboard')->with('success', 'Bienvenido al Modo Demo. Explora todas las funciones libremente.');

})->name('demo.mode');


/* |-------------------------------------------------------------------------- | ONBOARDING COMERCIAL (PRE-ACTIVACIÓN) |-------------------------------------------------------------------------- */
Route::middleware(['auth'])->group(function () {
    Route::get('/registro-paso-2', [App\Http\Controllers\Auth\RegisteredUserController::class, 'paymentPage'])->name('register.pay');
    Route::post('/registro-voucher', [App\Http\Controllers\Auth\RegisteredUserController::class, 'processPayment'])->name('register.payment.store');
    
    Route::get('/registro-empresa', [App\Http\Controllers\Auth\RegisteredUserController::class, 'companyPage'])->name('register.company');
    Route::post('/registro-empresa', [App\Http\Controllers\Auth\RegisteredUserController::class, 'storeCompany'])->name('register.company.store');
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
        Route::resource('gastos_categorias', ExpenseCategoryController::class)->names('gastos_categorias')->parameters(['gastos_categorias' => 'category']);

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
        Route::post('/configuracion/test-afip', [ConfiguracionEmpresaController::class , 'testAfip'])->name('configuracion.test_afip');
        Route::post('/configuracion/generar-cert', [ConfiguracionEmpresaController::class, 'generateCertificate'])->name('configuracion.generate_cert');
        Route::get('/configuracion/descargar-cert/{type}', [ConfiguracionEmpresaController::class, 'downloadCert'])->name('configuracion.download_cert');
        Route::get('/tax/search-cuit', [ConfiguracionEmpresaController::class, 'searchByCuit'])->name('tax.search_cuit');

        // MI SUSCRIPCIÓN (SaaS CLIENT PORTAL)
        Route::get('/mi-suscripcion', [\App\Http\Controllers\Empresa\SuscripcionController::class, 'index'])->name('suscripcion.index');
        Route::post('/mi-suscripcion/pago', [\App\Http\Controllers\Empresa\SuscripcionController::class, 'reportPayment'])->name('suscripcion.pago');


        // BÓVEDA DE RESGUARDO (BACKUPS)
        Route::get('/backup', [App\Http\Controllers\Empresa\BackupController::class, 'index'])->name('backup.index');

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
        Route::get('/stock/valuation', [StockController::class , 'valuation'])->name('stock.valuation');
        Route::resource('units', App\Http\Controllers\Empresa\UnitController::class)->names('units');
        Route::get('/faltantes', [ReplenishmentController::class , 'index'])->name('stock.faltantes');

        // PRODUCCIÓN Y RECETAS (BOM)
        Route::resource('recipes', RecipeController::class);
        Route::post('/recipes/{recipe}/add-item', [RecipeController::class, 'addItem'])->name('recipes.addItem');
        Route::post('/recipes/{recipe}/produce', [RecipeController::class, 'produce'])->name('recipes.produce');
        Route::delete('/recipe-items/{item}', [RecipeController::class, 'removeItem'])->name('recipes.removeItem');
        Route::resource('production_orders', ProductionOrderController::class)->names('production_orders');
        Route::post('/production_orders/{production_order}/clone', [ProductionOrderController::class, 'clone'])->name('production_orders.clone');
        Route::get('/faltantes/export', [ReplenishmentController::class , 'export'])->name('stock.faltantes.export');
        Route::get('/faltantes/actividad/{product}', [ReplenishmentController::class , 'actividad'])->name('stock.faltantes.actividad');
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
        Route::patch('/usuarios/{usuario}', [UsuarioController::class , 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{usuario}/toggle', [UsuarioController::class , 'toggle'])->name('usuarios.toggle');
        Route::patch('/usuarios/{usuario}/reset-password', [UsuarioController::class , 'resetPassword'])->name('usuarios.reset');


        /*
     |--------------------------------------------------------------------------
     | REPORTES
     |--------------------------------------------------------------------------
     */
        Route::get('/reportes', [ReporteController::class , 'panel'])->name('reportes.panel');
        Route::get('/reportes', [ReporteController::class , 'panel'])->name('reportes.panel');
        Route::get('/reportes/vendedores', [ReporteController::class , 'ventasVendedor'])->name('reportes.vendedores');
        Route::get('/reportes/caja-diaria', [ReporteController::class , 'cajaDiaria'])->name('reportes.caja_diaria');
        Route::get('/reportes/rentabilidad', [ReporteController::class , 'rentabilidad'])->name('reportes.rentabilidad');
        Route::get('/reportes/margen', [ReporteController::class , 'margenProducto'])->name('reportes.margen');
        Route::get('/reportes/categorias', [ReporteController::class , 'ventasCategoria'])->name('reportes.categorias');
        Route::get('/reportes/clientes-frecuentes', [ReporteController::class , 'clientesFrecuentes'])->name('reportes.clientes_frecuentes');
        Route::get('/reportes/por-hora', [ReporteController::class , 'ventasPorHora'])->name('reportes.por_hora');
        Route::get('/reportes/analisis-mensual', [ReporteController::class , 'analisisMensual'])->name('reportes.analisis_mensual');
        
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
     | VENTAS / COMPROBANTES
     |--------------------------------------------------------------------------
     */
        Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
        Route::get('/ventas/manual', [VentaController::class, 'createManual'])->name('ventas.manual');
        Route::post('/ventas/manual', [VentaController::class, 'storeManual'])->name('ventas.manual.store');
        Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show'); // Centro de Control
        Route::get('/ventas/{venta}/pdf', [VentaController::class, 'pdf'])->name('ventas.pdf');

        /*
     |--------------------------------------------------------------------------
     | LOGÍSTICA / REMITOS (Pilar 1)
     |--------------------------------------------------------------------------
     */
        Route::get('/ventas/{venta}/entregar', [App\Http\Controllers\Empresa\RemitoController::class, 'create'])->name('ventas.entregar');
        Route::post('/ventas/{venta}/entregar', [App\Http\Controllers\Empresa\RemitoController::class, 'store'])->name('ventas.entregar.store');
        Route::get('/remitos/{remito}/pdf', [App\Http\Controllers\Empresa\RemitoController::class, 'pdf'])->name('remitos.pdf');

        // PRESUPUESTOS (NUEVO)
        Route::post('/presupuestos/{id}/clone', [App\Http\Controllers\Empresa\PresupuestoController::class, 'clone'])->name('presupuestos.clonar');
        Route::post('/presupuestos/{id}/convertir-factura', [App\Http\Controllers\Empresa\PresupuestoController::class, 'convertirAFactura'])->name('presupuestos.convertir_factura');
        Route::get('/presupuestos/{presupuesto}/pdf', [App\Http\Controllers\Empresa\PresupuestoController::class, 'pdf'])->name('presupuestos.pdf');
        Route::resource('presupuestos', App\Http\Controllers\Empresa\PresupuestoController::class);

        /*
     |--------------------------------------------------------------------------
     | INTELIGENCIA LOGÍSTICA (Pilar 1 - Reportes Globales)
     |--------------------------------------------------------------------------
     */
        Route::get('/logistica/reporte', [App\Http\Controllers\Empresa\LogisticaController::class, 'index'])->name('logistica.reporte');

        /*
     |--------------------------------------------------------------------------
     | GESTIÓN DE PERSONAL (Pilar 2 - Rendimiento Operativo)
     |--------------------------------------------------------------------------
     */
        Route::get('/personal/rendimiento', [App\Http\Controllers\Empresa\PersonalController::class, 'index'])->name('personal.rendimiento');
        Route::get('/usuarios/{user}/desempeno', [App\Http\Controllers\Empresa\PersonalController::class, 'desempeno'])->name('usuarios.desempeno');

        // FICHAJE Y CAJA (Check-in/out)
        Route::post('/personal/check-in', [App\Http\Controllers\Empresa\AsistenciaController::class, 'checkIn'])->name('personal.checkin');
        Route::post('/personal/check-out', [App\Http\Controllers\Empresa\AsistenciaController::class, 'checkOut'])->name('personal.checkout');

        // GESTIÓN DE PUNTOS DE FICHAJE (QR)
        Route::get('/personal/asistencia/qr-management', [App\Http\Controllers\Empresa\AsistenciaQrController::class, 'showQr'])->name('personal.asistencia.qr');
        Route::get('/personal/asistencia/qr/{slug}', [App\Http\Controllers\Empresa\AsistenciaQrController::class, 'qrRegistro'])->name('personal.asistencia.qr-registro')->withoutMiddleware(['auth', 'empresa', 'empresa.activa']);

        // AUDITORÍA DE CAJAS (Pilar 2)
        Route::get('/personal/cajas', [App\Http\Controllers\Empresa\CajaAuditoriaController::class, 'index'])->name('personal.cajas.index');
        Route::get('/personal/cajas/{cierre}', [App\Http\Controllers\Empresa\CajaAuditoriaController::class, 'show'])->name('personal.cajas.show');



        // GASTO RÁPIDO (App Móvil para Campo)
        Route::get('/personal/gastos/rapido', [App\Http\Controllers\Empresa\GastoRapidoController::class, 'create'])->name('gastos.quick');
        Route::post('/personal/gastos/rapido', [App\Http\Controllers\Empresa\GastoRapidoController::class, 'store'])->name('gastos.store-quick');


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
Route::get('/checkout/success/{order}', [CheckoutController::class , 'success'])->name('checkout.success');
Route::get('/api/search-client', [CheckoutController::class , 'searchClient'])->name('checkout.searchClient');


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

// 🚨 RUTA DE EMERGENCIA PARA REPARAR RUTAS EN PRODUCCIÓN 🚨
Route::get('/reparar-rutas', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "✅ Rutas y Caché de Producción actualizadas con éxito. Ya podés entrar al sistema.";
});
