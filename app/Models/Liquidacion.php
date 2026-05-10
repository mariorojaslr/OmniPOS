<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Liquidacion extends Model
{
    use BelongsToEmpresa;

    protected $table = 'liquidaciones';

    protected $fillable = [
        'empresa_id',
        'user_id',
        'fecha_emision',
        'monto_total',
        'periodo_desde',
        'periodo_hasta',
        'estado',
        'metodo_pago',
        'notas'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'periodo_desde' => 'date',
        'periodo_hasta' => 'date',
        'monto_total' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * El profesional (usuario) al que pertenece la liquidación.
     */
    public function profesional()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Los turnos incluidos en esta liquidación.
     */
    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }
}
