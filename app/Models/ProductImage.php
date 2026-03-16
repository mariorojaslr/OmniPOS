<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'is_main',
        'order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute()
    {
        $imgPath = $this->path;

        if (!$imgPath) {
            return asset('images/no-image.png');
        }

        // Si ya es una URL absoluta, la devolvemos
        if (\Illuminate\Support\Str::startsWith($imgPath, ['http://', 'https://'])) {
            return $imgPath;
        }

        // Soportamos configuración vía config/services.php
        $bunnyUrl = config('services.bunny.url');
        $useBunny = config('services.bunny.enabled');

        if ($useBunny && $bunnyUrl) {
            return rtrim($bunnyUrl, '/') . '/' . ltrim($imgPath, '/');
        }

        // FALLBACK A LOCAL (Usando ruta de emergencia para Hostinger/Symlinks)
        return route('local.media', ['path' => ltrim($imgPath, '/')]);
    }
}
