<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\TrackerController;

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

Route::post('/owner/tracker/visit', [TrackerController::class, 'trackVisit']);
Route::post('/owner/tracker/demo', [TrackerController::class, 'trackDemo']);
Route::post('/owner/tracker/webhook-bot', [TrackerController::class, 'receiveBotLead']);