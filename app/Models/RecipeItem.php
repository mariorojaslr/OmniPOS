<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $recipe_id
 * @property int $component_product_id
 * @property float $quantity
 * @property int|null $unit_id
 */
class RecipeItem extends Model
{
    protected $guarded = [];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function component()
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
