<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

/*
|--------------------------------------------------------------------------
| Rutas EMPRESA
|--------------------------------------------------------------------------
| Todas las rutas internas de empresa que no son CRUD
| (POS, Catálogo interno, etc.)
*/

Route::middleware(['auth', 'empresa', 'empresa.activa'])
    ->prefix('empresa')
    ->name('empresa.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Catálogo interno (empresa logueada)
        |--------------------------------------------------------------------------
        | Usa el mismo controller del catálogo público,
        | pero accesible desde el dashboard de empresa
        */
        Route::get('/catalogo', [CatalogController::class, 'index'])
            ->name('catalogo.index');

    });
