<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Order extends Model
{
    use BelongsToEmpresa;

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

    
}
