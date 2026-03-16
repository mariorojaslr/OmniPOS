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
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON EMPRESA
    |--------------------------------------------------------------------------
    */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return asset('images/no-logo.png');
        }

        // Si ya es una URL absoluta, la devolvemos
        if (\Illuminate\Support\Str::startsWith($this->logo, ['http://', 'https://'])) {
            return $this->logo;
        }

        // Soportamos configuración vía config/services.php
        $bunnyUrl = config('services.bunny.url');
        $useBunny = config('services.bunny.enabled');

        if ($useBunny && $bunnyUrl) {
            return rtrim($bunnyUrl, '/') . '/' . ltrim($this->logo, '/');
        }

        return route('local.media', ['path' => ltrim($this->logo, '/')]);
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }
}
