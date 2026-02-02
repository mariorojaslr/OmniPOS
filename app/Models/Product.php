<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'empresa_id',
        'name',
        'price',
        'active'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function video()
    {
        return $this->hasOne(ProductVideo::class);
    }
}
