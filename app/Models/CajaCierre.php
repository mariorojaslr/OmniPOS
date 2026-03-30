<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CajaCierre extends Model
{
    protected $fillable = [
        'empresa_id',
        'user_id',
        'asistencia_id',
        'fecha_apertura',
        'fecha_cierre',
        'saldo_inicial',
        'ventas_efectivo',
        'ventas_digital',
        'otros_ingresos',
        'egresos',
        'saldo_esperado',
        'saldo_real',
        'diferencia',
        'observaciones',
        'estado',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
        'saldo_inicial' => 'decimal:2',
        'ventas_efectivo' => 'decimal:2',
        'ventas_digital' => 'decimal:2',
        'otros_ingresos' => 'decimal:2',
        'egresos' => 'decimal:2',
        'saldo_esperado' => 'decimal:2',
        'saldo_real' => 'decimal:2',
        'diferencia' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function asistencia(): BelongsTo
    {
        return $this->belongsTo(Asistencia::class);
    }
}
