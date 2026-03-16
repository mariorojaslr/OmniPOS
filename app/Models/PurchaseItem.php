<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'empresa_id',
        'purchase_id',
        'product_id',
        'variant_id',
        'quantity',
        'cost',
        'iva',
        'subtotal'
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
