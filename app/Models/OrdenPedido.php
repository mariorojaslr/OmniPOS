<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenPedido extends Model
{
    protected $table = 'ordenes_pedido';

    protected $fillable = [
        'empresa_id',
        'user_id',
        'proveedor_id',
        'numero',
        'fecha',
        'total',
        'notas_generales',
        'estado',
        'token'
    ];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'float',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Supplier::class, 'proveedor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrdenPedidoItem::class, 'orden_pedido_id');
    }
}
