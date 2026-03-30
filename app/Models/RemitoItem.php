<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemitoItem extends Model
{
    protected $fillable = [
        'remito_id',
        'venta_item_id',
        'product_id',
        'variant_id',
        'cantidad',
    ];

    public function remito()
    {
        return $this->belongsTo(Remito::class);
    }

    public function ventaItem()
    {
        return $this->belongsTo(VentaItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}

