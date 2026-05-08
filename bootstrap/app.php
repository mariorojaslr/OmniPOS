<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'owner' => \App\Http\Middleware\OwnerMiddleware::class,
        ]);
        
        // Asegurar que las sesiones sean estables
        $middleware->use([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Desactivar el renderizado de errores personalizados si estamos en debug
        if (config('app.debug')) {
            $exceptions->shouldRenderHtmlWhen(function() { return true; });
        }
    })->create();
