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

        // Logística para Bunny.net
        $bunnyUrl = env('BUNNY_URL'); // e.g. https://gente-piola.b-cdn.net
        $useBunny = env('BUNNY_ENABLED', true); // Permitir desactivarlo desde .env

        if ($useBunny && $bunnyUrl) {
            return rtrim($bunnyUrl, '/') . '/' . ltrim($imgPath, '/');
        }

        // FALLBACK A LOCAL:
        // Usamos una ruta relativa absoluta desde el dominio actual para evitar 
        // bloqueos Mixed Content
        return '/storage/' . ltrim($imgPath, '/');
    }
}
