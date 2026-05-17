<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Room
 * Representa las habitaciones en el módulo de internación/hospitalización.
 */
class Room extends Model
{
    protected $table = 'habitaciones';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'tipo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con la Empresa (Tenant).
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relación con las Camas asociadas a la habitación.
     */
    public function beds()
    {
        return $this->hasMany(Bed::class, 'habitacion_id');
    }
}
