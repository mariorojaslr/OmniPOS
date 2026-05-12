<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // 🛡️ BLINDAJE DE PRODUCCIÓN: 
        if (config('app.env') !== 'local') {
            config(['app.debug' => false]);
        }

        // 🔔 NOTIFICACIONES DEL SISTEMA (LAYOUT EMPRESA)
        view()->composer('layouts.empresa', function ($view) {
            if (auth()->check() && auth()->user()->empresa_id) {
                // Contar productos con stock por debajo del mínimo (usando el Global Scope de BelongsToEmpresa)
                $lowStockCount = \App\Models\Product::whereRaw('stock <= stock_min')->count();
                $view->with('lowStockCount', $lowStockCount);
            }
        });
    }
}
