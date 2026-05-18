<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Bed
 * Representa las camas clínicas de cada habitación.
 */
class Bed extends Model
{
    protected $table = 'camas';

    protected $fillable = [
        'empresa_id',
        'habitacion_id',
        'nombre',
        'estado',
        'qr_token',
    ];

    /**
     * Boot del modelo para auto-generación de tokens QR únicos.
     */
    protected static function booted()
    {
        static::creating(function ($bed) {
            if (empty($bed->qr_token)) {
                $bed->qr_token = 'BED-' . strtoupper(\Illuminate\Support\Str::random(12));
            }
        });
    }

    /**
     * Relación con la Habitación a la que pertenece.
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'habitacion_id');
    }

    /**
     * Obtiene la asignación activa actual de la cama (si está ocupada).
     */
    public function activeAssignment()
    {
        return $this->hasOne(HospitalizationBed::class, 'cama_id')
            ->whereNull('fecha_hasta');
    }
}
