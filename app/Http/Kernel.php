<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /*
    |--------------------------------------------------------------------------
    | Global HTTP Middleware
    |--------------------------------------------------------------------------
    | Se ejecutan en TODAS las requests (web y api).
    | Acá va solo infraestructura, nunca lógica de negocio.
    */
    protected $middleware = [
        \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Middleware Groups
    |--------------------------------------------------------------------------
    | Separación clara entre Web y API desde el inicio.
    */
    protected $middlewareGroups = [

        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases (Laravel 11 / 12)
    |--------------------------------------------------------------------------
    | ESTE es el lugar correcto para registrar middlewares por alias.
    | $routeMiddleware YA NO SE USA.
    */
    protected $middlewareAliases = [

        /*
        |--------------------------------------------------------------
        | Laravel base
        |--------------------------------------------------------------
        */
        'auth'      => \App\Http\Middleware\Authenticate::class,
        'guest'     => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'verified'  => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'throttle'  => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        /*
        |--------------------------------------------------------------
        | MultiPOS – Arquitectura de negocio
        |--------------------------------------------------------------
        */
        'owner'          => \App\Http\Middleware\OwnerMiddleware::class,
        'empresa'        => \App\Http\Middleware\EmpresaMiddleware::class,
        'empresa.activa' => \App\Http\Middleware\EmpresaActivaMiddleware::class,

        /*
        |--------------------------------------------------------------
        | Futuro (ya previsto)
        |--------------------------------------------------------------
        */
        // 'role'       => \App\Http\Middleware\RoleMiddleware::class,
        // 'permission' => \App\Http\Middleware\PermissionMiddleware::class,
    ];
}
