<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $empresa_id
 * @property int $product_id
 * @property string $name
 * @property bool $is_active
 * @property-read \App\Models\Product $product
 */
class Recipe extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
