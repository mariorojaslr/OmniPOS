<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Hospitalization
 * Representa un registro de internación / hospitalización clínica de un paciente.
 */
class Hospitalization extends Model
{
    protected $table = 'internaciones';

    protected $fillable = [
        'empresa_id',
        'client_id',
        'fecha_ingreso',
        'fecha_egreso',
        'estado',
        'motivo_ingreso',
        'diagnostico_ingreso',
        'diagnostico_egreso',
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'fecha_egreso' => 'datetime',
    ];

    /**
     * Relación con el Paciente (tabla clients).
     */
    public function patient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Historial de camas/complejidades que ha tenido esta internación.
     */
    public function assignments()
    {
        return $this->hasMany(HospitalizationBed::class, 'internacion_id')->orderBy('fecha_desde', 'desc');
    }

    /**
     * Obtiene la cama actualmente asignada al paciente.
     */
    public function currentBedAssignment()
    {
        return $this->hasOne(HospitalizationBed::class, 'internacion_id')
            ->whereNull('fecha_hasta');
    }

    /**
     * Relación con las administraciones de medicamentos y controles clínicos registrados.
     */
    public function administrations()
    {
        return $this->hasMany(HospitalizationAdministration::class, 'internacion_id')->orderBy('fecha_registro', 'desc');
    }
}
