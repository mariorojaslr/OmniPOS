<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCombo extends Model
{
    protected $table = 'product_combos';

    protected $fillable = [
        'parent_product_id',
        'child_product_id',
        'quantity',
    ];

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }

    public function child()
    {
        return $this->belongsTo(Product::class, 'child_product_id');
    }
}
