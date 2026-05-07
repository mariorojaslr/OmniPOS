<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenPedidoItem extends Model
{
    protected $table = 'orden_pedido_items';

    protected $fillable = [
        'orden_pedido_id',
        'product_id',
        'variant_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'precio_anterior',
        'instrucciones',
        'subtotal',
        'is_manual'
    ];

    protected $casts = [
        'cantidad' => 'float',
        'precio_unitario' => 'float',
        'precio_anterior' => 'float',
        'subtotal' => 'float',
        'is_manual' => 'boolean',
    ];

    public function ordenPedido()
    {
        return $this->belongsTo(OrdenPedido::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
