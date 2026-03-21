<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Order extends Model
{
    use BelongsToEmpresa;

    const STATUS_PENDIENTE = 'pendiente';
    const STATUS_PROCESO   = 'en_proceso';
    const STATUS_ARMADO    = 'pedido_armado';
    const STATUS_ENVIADO   = 'enviado';
    const STATUS_ENTREGADO = 'entregado';
    const STATUS_CANCELADO = 'cancelado';

    protected $fillable = [
        'empresa_id',
        'client_id',
        'nombre_cliente',
        'email',
        'telefono',
        'direccion',
        'metodo_entrega',
        'metodo_pago',
        'estado',
        'total',
        'venta_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

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

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute()
    {
        return match($this->estado) {
            self::STATUS_PENDIENTE => 'Pendiente/Recibido',
            self::STATUS_PROCESO   => 'En Proceso',
            self::STATUS_ARMADO    => 'Pedido Armado',
            self::STATUS_ENVIADO   => 'Enviado',
            self::STATUS_ENTREGADO => 'Entregado',
            self::STATUS_CANCELADO => 'Cancelado',
            default => 'Desconocido',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->estado) {
            self::STATUS_PENDIENTE => 'secondary',
            self::STATUS_PROCESO   => 'info',
            self::STATUS_ARMADO    => 'warning',
            self::STATUS_ENVIADO   => 'primary',
            self::STATUS_ENTREGADO => 'success',
            self::STATUS_CANCELADO => 'danger',
            default => 'dark',
        };
    }
}
