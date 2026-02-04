<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VentaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'product_id',
        'cantidad',
        'precio_unitario_sin_iva',
        'subtotal_item_sin_iva',
        'iva_item',
        'total_item_con_iva',
    ];

    /* =========================
       Relaciones
    ========================= */

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
