<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaItem extends Model
{
    protected $fillable = [
        'venta_id',
        'product_id',
        'variant_id',
        'cantidad',
        'precio_unitario_sin_iva',
        'subtotal_item_sin_iva',
        'iva_item',
        'total_item_con_iva',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
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
