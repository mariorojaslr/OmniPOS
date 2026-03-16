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

        // Soportamos BUNNY_URL (local) y BUNNY_PULL_ZONE_URL (staging/producción)
        $bunnyUrl = env('BUNNY_URL') ?: env('BUNNY_PULL_ZONE_URL');
        $useBunny = env('BUNNY_ENABLED', true);

        if ($useBunny && $bunnyUrl) {
            return rtrim($bunnyUrl, '/') . '/' . ltrim($this->logo, '/');
        }

        return asset('storage/' . $this->logo);
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }
}
