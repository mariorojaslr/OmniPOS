<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Bootstrap de la aplicación (Laravel 11 / 12)
|--------------------------------------------------------------------------
| Este archivo define:
| - rutas
| - middlewares
| - manejo de excepciones
|
| En Laravel 11+ los aliases de middleware SE DEFINEN ACÁ
| (no más en Kernel.php).
*/

return Application::configure(basePath: dirname(__DIR__))

    /*
    |--------------------------------------------------------------------------
    | Rutas del sistema
    |--------------------------------------------------------------------------
    */
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Middlewares
    |--------------------------------------------------------------------------
    | Acá se registran los aliases usados en las rutas.
    | Esto es CLAVE en Laravel 11 / 12.
    */
    ->withMiddleware(function (Middleware $middleware) {

        /*
        |--------------------------------------------------------------
        | Aliases de negocio (MultiPOS)
        |--------------------------------------------------------------
        */
        $middleware->alias([

            // Roles principales
            'owner'   => \App\Http\Middleware\OwnerMiddleware::class,
            'empresa' => \App\Http\Middleware\EmpresaMiddleware::class,

            // Estado de la empresa
            'empresa.activa' => \App\Http\Middleware\EmpresaActivaMiddleware::class,
        ]);
    })

    /*
    |--------------------------------------------------------------------------
    | Manejo de excepciones
    |--------------------------------------------------------------------------
    | Punto único para customizar errores a futuro.
    */
    ->withExceptions(function (Exceptions $exceptions) {
        // Bloque de reporte automático desactivado temporalmente para diagnosticar Error 500
    })

    /*
    |--------------------------------------------------------------------------
    | Crear aplicación
    |--------------------------------------------------------------------------
    */
    ->create();
