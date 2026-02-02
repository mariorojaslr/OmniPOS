<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Futuro:
| - apps móviles
| - cajeros externos
| - catálogos públicos
| - integraciones
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
