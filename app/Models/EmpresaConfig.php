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

        // Soportamos configuración vía config/services.php (BunnyCDN)
        $bunnyUrl = config('services.bunny.url');
        $useBunny = config('services.bunny.enabled');

        // Solo usamos Bunny si está habilitado Y estamos en producción REAL
        // Esto evita bloqueos de seguridad (ORB) en el servidor de Staging
        if ($useBunny && $bunnyUrl && app()->environment('production') && !str_contains(request()->getHost(), 'staging')) {
            return rtrim($bunnyUrl, '/') . '/' . ltrim($this->logo, '/');
        }

        // Para evitar problemas con APP_URL en .env incorrecto en Hostinger, usamos ruta relativa a la raíz
        return '/local-media/' . ltrim($this->logo, '/');
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }
}
