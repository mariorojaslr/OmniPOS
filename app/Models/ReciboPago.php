<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReciboPago extends Model
{
    protected $table = 'recibo_pagos';

    protected $fillable = [
        'recibo_id',
        'metodo_pago',
        'monto',
        'referencia',
        'banco',
        'fecha_emision',
        'fecha_acreditacion',
    ];

    public function recibo()
    {
        return $this->belongsTo(Recibo::class);
    }
}
