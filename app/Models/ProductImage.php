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
        $bunnyUrl = env('BUNNY_PULL_ZONE_URL');

        if (\Illuminate\Support\Str::startsWith($imgPath, ['http://', 'https://'])) {
            return $imgPath;
        }
        elseif ($bunnyUrl) {
            return rtrim($bunnyUrl, '/') . '/' . ltrim($imgPath, '/');
        }

        return asset('storage/' . $imgPath);
    }
}
