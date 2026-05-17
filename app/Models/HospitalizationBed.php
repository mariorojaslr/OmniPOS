<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo HospitalizationBed
 * Representa el registro histórico y actual de camas o sectores asignados a una internación.
 */
class HospitalizationBed extends Model
{
    protected $table = 'internacion_camas';

    protected $fillable = [
        'internacion_id',
        'cama_id',
        'fecha_desde',
        'fecha_hasta',
        'tipo_internacion',
        'notas',
    ];

    protected $casts = [
        'fecha_desde' => 'datetime',
        'fecha_hasta' => 'datetime',
    ];

    /**
     * Relación con la Internación.
     */
    public function hospitalization()
    {
        return $this->belongsTo(Hospitalization::class, 'internacion_id');
    }

    /**
     * Relación con la Cama asignada.
     */
    public function bed()
    {
        return $this->belongsTo(Bed::class, 'cama_id');
    }
}
