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
        $exceptions->reportable(function (\Throwable $e) {
            try {
                $user = auth()->user();
                // 1. Guardamos el reporte tecnico seco
                \App\Models\SystemError::create([
                    'empresa_id' => $user->empresa_id ?? null,
                    'user_id' => $user->id ?? null,
                    'exception_class' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'url' => request()->fullUrl(),
                    'status' => 'pendiente',
                    'severity' => 'medio'
                ]);

                // 2. CREAMOS EL TICKET DE SOPORTE VIVO PARA EL OWNER (VIP)
                \App\Models\SupportTicket::create([
                    'empresa_id' => $user->empresa_id ?? null,
                    'user_id' => $user->id ?? null,
                    'subject' => '🔥 [CRÍTICO] ERROR DE SISTEMA: ' . substr($e->getMessage(), 0, 40) . '...',
                    'message' => "ERROR AUTOMÁTICO DETECTADO POR EL SISTEMA:\n\n" . 
                                 "MENSAJE: " . $e->getMessage() . "\n" .
                                 "ARCHIVO: " . $e->getFile() . " (Línea: " . $e->getLine() . ")\n" .
                                 "URL: " . request()->fullUrl() . "\n" .
                                 "USER: " . ($user->email ?? 'Visitante') . "\n\n" .
                                 "-- EL SISTEMA YA LOGUEO EL ORIGEN. PROCEDER A REPARAR URGENTE.",
                    'status' => 'abierto',
                    'priority' => 'critica'
                ]);

            } catch (\Throwable $loggingError) {
                // Si falla el logueo del error, no hacemos nada para evitar un bucle infinito
            }
        });
    })

    /*
    |--------------------------------------------------------------------------
    | Crear aplicación
    |--------------------------------------------------------------------------
    */
    ->create();
