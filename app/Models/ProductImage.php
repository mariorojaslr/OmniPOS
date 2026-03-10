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

        // Si ya es una URL absoluta, la devolvemos
        if (\Illuminate\Support\Str::startsWith($imgPath, ['http://', 'https://'])) {
            return $imgPath;
        }

        // FORZADO A LOCAL:
        // Usamos una ruta relativa absoluta desde el dominio actual para evitar 
        // bloqueos Mixed Content si el .env de Hostinger tiene APP_URL con http://
        return '/storage/' . ltrim($imgPath, '/');
    }
}
