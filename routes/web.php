<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug-error', function() {
    return "LITERALMENTE NO HAY NADA MAS QUE ESTE TEXTO. SI VES ESTO, LARAVEL ESTA SANO.";
});

Route::get('/reparar-rutas', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "✅ CACHE LIMPIA. INTENTA ENTRAR AL LOGIN.";
});
