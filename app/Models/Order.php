<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'empresa_id',
        'nombre_cliente',
        'email',
        'telefono',
        'direccion',
        'metodo_entrega',
        'metodo_pago',
        'estado',
        'total',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
