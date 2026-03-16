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

        // Soportamos BUNNY_URL (local) y BUNNY_PULL_ZONE_URL (staging/producción)
        $bunnyUrl = env('BUNNY_URL') ?: env('BUNNY_PULL_ZONE_URL');
        $useBunny = env('BUNNY_ENABLED', true);

        if ($useBunny && $bunnyUrl) {
            return rtrim($bunnyUrl, '/') . '/' . ltrim($imgPath, '/');
        }

        // FALLBACK A LOCAL
        return '/storage/' . ltrim($imgPath, '/');
    }
}
