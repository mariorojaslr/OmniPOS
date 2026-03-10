<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuscripcionPago extends Model
{
    protected $table = 'suscripcion_pagos';

    protected $fillable = [
        'empresa_id',
        'plan_id',
        'monto',
        'fecha_pago',
        'metodo',
        'estado',
        'nro_comprobante',
        'notas',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
