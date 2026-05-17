<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaConfig extends Model
{
    protected $table = 'empresa_config';

    protected $fillable = [
        'empresa_id',
        'logo',
        'color_primary',
        'color_secondary',
        'theme',
        'dias_nuevo',
        'mod_orden_pedido',
        'mod_orden_pedido_extra',
        'mod_turnos',
        'mod_unidades_medida',
        'mod_internaciones',
        'mod_campos_dinamicos',
        'mod_afip',
        'mod_pagos',
        'mod_backups',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON EMPRESA
    |--------------------------------------------------------------------------
    */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return asset('images/logo_premium.png');
        }

        // Si ya es una URL absoluta, la devolvemos
        if (\Illuminate\Support\Str::startsWith($this->logo, ['http://', 'https://'])) {
            return $this->logo;
        }

        // El logo siempre se guarda localmente en el disco public de Hostinger a través de ConfiguracionEmpresaController.
        // Por ende, para máxima velocidad y evitar problemas con CDN, lo servimos localmente.
        return '/local-media/' . ltrim($this->logo, '/');
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }
}
