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

        // FORZADO A LOCAL PARA PRODUCCIÓN: 
        // Desactivamos temporalmente la carga desde BunnyCDN para el cliente
        // hasta que resolvamos 100% los bloqueos en Staging.
        return asset('storage/' . $imgPath);
    }
}
