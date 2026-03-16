<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Venta extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'user_id',
        'client_id',
        'tipo_comprobante',
        'numero_comprobante',
        'total_sin_iva',
        'total_iva',
        'total_con_iva',
        'metodo_pago',
        'monto_pagado',
        'vuelto',
    ];

    public function items()
    {
        return $this->hasMany(VentaItem::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }
}
