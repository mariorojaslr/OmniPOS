<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo HospitalizationAdministration
 * Representa el registro de medicamentos, descartables, controles de signos vitales, etc.
 */
class HospitalizationAdministration extends Model
{
    protected $table = 'internacion_administraciones';

    protected $fillable = [
        'internacion_id',
        'user_id',
        'tipo',
        'nombre',
        'cantidad',
        'dosis',
        'frecuencia',
        'via',
        'precio_unitario',
        'fecha_registro',
        'proxima_aplicacion',
        'notas',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'proxima_aplicacion' => 'datetime',
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
    ];

    /**
     * Relación con la Internación.
     */
    public function hospitalization()
    {
        return $this->belongsTo(Hospitalization::class, 'internacion_id');
    }

    /**
     * Relación con el Profesional (usuario) que registró la administración.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
