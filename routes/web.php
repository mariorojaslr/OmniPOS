<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Empresa\DashboardController;

// RUTAS DE AUTENTICACIÓN (ESENCIALES)
Route::get('/', function () { return view('welcome'); });
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout.get');

// GRUPO DE EMPRESA (RECONSTRUCCIÓN PROGRESIVA)
Route::middleware(['auth', 'empresa', 'empresa.activa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 📦 ARTICULOS & RUBROS
    Route::resource('products', App\Http\Controllers\Empresa\ProductController::class);
    Route::resource('rubros', App\Http\Controllers\Empresa\RubroController::class);
    Route::get('listados/articulos', [App\Http\Controllers\Empresa\ListadoController::class, 'articulos'])->name('listados.articulos');

    // 📊 STOCK
    Route::resource('stock', App\Http\Controllers\Empresa\StockController::class);
    Route::get('stock-valuation', [App\Http\Controllers\Empresa\StockController::class, 'valuation'])->name('stock.valuation');
    Route::get('stock-faltantes', [App\Http\Controllers\Empresa\StockController::class, 'faltantes'])->name('stock.faltantes');

    // 📑 VENTAS
    Route::resource('ventas', App\Http\Controllers\Empresa\VentaController::class);
    Route::get('ventas-manual', [App\Http\Controllers\Empresa\VentaController::class, 'manual'])->name('ventas.manual');
    Route::resource('pos', App\Http\Controllers\Empresa\POSController::class);
    Route::resource('presupuestos', App\Http\Controllers\Empresa\PresupuestoController::class);

    // 👥 CLIENTES
    Route::resource('clientes', App\Http\Controllers\Empresa\ClientController::class);
    Route::get('clientes-portal', [App\Http\Controllers\Empresa\ClientController::class, 'portalList'])->name('clientes.portal_list');

    // 🚛 PROVEEDORES
    Route::resource('proveedores', App\Http\Controllers\Empresa\ProviderController::class);
    Route::get('proveedores-portal', [App\Http\Controllers\Empresa\ProviderController::class, 'portalList'])->name('proveedores.portal_list');

    // 🏦 TESORERIA & GASTOS
    Route::resource('tesoreria', App\Http\Controllers\Empresa\TesoreriaController::class);
    Route::resource('gastos', App\Http\Controllers\Empresa\GastoController::class);

    // 🚚 LOGISTICA & GPS
    Route::resource('gps', App\Http\Controllers\Empresa\GPSController::class);
    Route::get('gps-rutas', [App\Http\Controllers\Empresa\GPSController::class, 'rutas'])->name('gps.rutas');
    Route::resource('remitos', App\Http\Controllers\Empresa\RemitoController::class);

    // 👥 PERSONAL & EQUIPO
    Route::resource('usuarios', App\Http\Controllers\Empresa\UsuarioController::class);
    Route::get('personal/asistencia-qr', [App\Http\Controllers\Empresa\AsistenciaQrController::class, 'index'])->name('personal.asistencia.qr');

    // 🆘 SOPORTE & TICKETS
    Route::resource('soporte', App\Http\Controllers\Empresa\SupportTicketController::class);

    // 📦 COMPRAS & PROVEEDORES (RESTANTE)
    Route::resource('compras', App\Http\Controllers\Empresa\PurchaseController::class);

    // 🛒 PEDIDOS ONLINE (ÓRDENES)
    Route::resource('orders', App\Http\Controllers\Empresa\OrderController::class);

    // 📊 REPORTES (RESTANTE)
    Route::get('reportes-panel', [App\Http\Controllers\Empresa\ReporteController::class, 'panel'])->name('reportes.panel');

    // ⚙️ CONFIGURACIÓN & AJUSTES
    Route::get('configuracion', [App\Http\Controllers\Empresa\ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::resource('backup', App\Http\Controllers\Empresa\BackupController::class);
    Route::get('units', [App\Http\Controllers\Empresa\UnitController::class, 'index'])->name('units.index');
    Route::get('suscripcion', [App\Http\Controllers\Empresa\SubscriptionController::class, 'index'])->name('suscripcion.index');
    Route::get('novedades', [App\Http\Controllers\Empresa\DashboardController::class, 'novedades'])->name('novedades');
});

// 🌐 CATÁLOGO PÚBLICO (MODO STORE)
Route::get('c/{slug_or_id}', [App\Http\Controllers\Empresa\CatalogController::class, 'index'])->name('catalog.index');

// DIAGNÓSTICO (POR SI ACASO)
Route::get('/debug-error', function() {
    return "EL LOGIN Y DASHBOARD ESTÁN CARGADOS. SI VES ESTO, VAMOS BIEN.";
});

Route::get('/reparar-rutas', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "✅ CACHE LIMPIA.";
});

// PRUEBA DE VIDA DEFINITIVA
Route::get('/hola-mundo', function () {
    return "✅ EL SERVIDOR ESTÁ VIVO Y RESPONDIENDO TEXTO PLANO.";
});

// VISUALIZADOR DE LOGS INTELIGENTE
Route::get('/ver-logs', function() {
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) return "No hay logs disponibles. El archivo no existe.";
    return "<pre>" . htmlspecialchars(file_get_contents($path)) . "</pre>";
});

// DIAGNÓSTICO DE PERMISOS
Route::get('/check-permisos', function() {
    $path = storage_path('logs');
    $isWritable = is_writable($path);
    $user = posix_getpwuid(posix_geteuid());
    
    return [
        'logs_path' => $path,
        'es_escribible' => $isWritable ? '✅ SÍ' : '❌ NO',
        'usuario_php' => $user['name'] ?? 'desconocido',
        'espacio_libre' => disk_free_space("/") / (1024 * 1024) . " MB",
    ];
});

// LIMPIEZA DE LOGS (PARA EMPEZAR DE CERO)
Route::get('/borrar-logs', function() {
    $path = storage_path('logs/laravel.log');
    if (file_exists($path)) {
        unlink($path);
        return "✅ ARCHIVO DE LOGS ELIMINADO. Ahora entrá al Dashboard para generar uno nuevo.";
    }
    return "El archivo ya no existía.";
});
