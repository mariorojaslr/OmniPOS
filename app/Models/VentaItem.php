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
        'cantidad_entregada',
        'precio_unitario_sin_iva',
        'subtotal_item_sin_iva',
        'iva_item',
        'total_item_con_iva',
    ];

    /**
     * ⚖️ Saldo pendiente de entrega (En guarda)
     */
    public function getCantidadPendienteAttribute()
    {
        return $this->cantidad - $this->cantidad_entregada;
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function remitoItems()
    {
        return $this->hasMany(RemitoItem::class);
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
