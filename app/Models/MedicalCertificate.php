<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo MedicalCertificate
 * Representa un certificado médico oficial emitido por la clínica con código QR de verificación externa.
 */
class MedicalCertificate extends Model
{
    protected $table = 'medical_certificates';

    protected $fillable = [
        'empresa_id',
        'client_id',
        'user_id',
        'codigo_validacion',
        'tipo',
        'contenido',
        'vigencia_hasta',
    ];

    protected $casts = [
        'vigencia_hasta' => 'date',
    ];

    /**
     * Relación con el Paciente (tabla clients).
     */
    public function patient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Relación con el Médico Emisor (tabla users).
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con la Empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
