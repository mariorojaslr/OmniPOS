<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenPagoPago extends Model
{
    protected $table = 'orden_pago_pagos';

    protected $fillable = [
        'orden_pago_id',
        'metodo_pago',
        'monto',
        'referencia',
        'cheque_id',
    ];

    public function ordenPago()
    {
        return $this->belongsTo(OrdenPago::class);
    }

    public function cheque()
    {
        return $this->belongsTo(Cheque::class);
    }
}
