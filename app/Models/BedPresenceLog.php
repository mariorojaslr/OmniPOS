<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo BedPresenceLog
 * Registro inmutable de trazabilidad presencial mediante QR en cama.
 */
class BedPresenceLog extends Model
{
    protected $table = 'bed_presence_logs';

    protected $fillable = [
        'empresa_id',
        'bed_id',
        'user_id',
        'internacion_id',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    /**
     * Relación con la Cama.
     */
    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }

    /**
     * Relación con el Usuario (Enfermero o Médico).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con la Internación del paciente.
     */
    public function hospitalization()
    {
        return $this->belongsTo(Hospitalization::class, 'internacion_id');
    }

    /**
     * Relación con la Empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
