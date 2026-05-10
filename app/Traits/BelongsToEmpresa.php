<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait BelongsToEmpresa
 * 
 * Este trait asegura que cualquier consulta a este modelo esté Scopeada 
 * automáticamente a la empresa del usuario autenticado, asegurando el Multitenancy.
 */
trait BelongsToEmpresa
{
    /**
     * El método "booted" del trait.
     * Se ejecuta automáticamente al arrancar el modelo.
     */
    protected static function bootBelongsToEmpresa()
    {
        // 1. Añadimos el Global Scope (Aislamiento de lectura)
        static::addGlobalScope('empresa', function (Builder $builder) {
            // Detección robusta de portal: si la URL contiene 'portal', 'public' o es una ruta de portal
            if (str_contains(request()->url(), '/portal/') || 
                str_contains(request()->url(), '/public/') || 
                request()->is('portal/*') || 
                request()->routeIs('*.portal') ||
                request()->routeIs('portal.*')) {
                return;
            }

            if (auth()->check() && auth()->user()->empresa_id) {
                // Usamos el nombre de la tabla del modelo directamente para evitar problemas con alias de subconsultas
                $builder->where($builder->getModel()->getTable() . '.empresa_id', auth()->user()->empresa_id);
            }
        });

        // 2. Evento Creating (Asignación automática de empresa en escritura)
        static::creating(function ($model) {
            if (!$model->empresa_id && auth()->check() && auth()->user()->empresa_id) {
                $model->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    /**
     * Relación directa a la Empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }
}
