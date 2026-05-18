<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;
use Illuminate\Support\Str;

/**
 * Modelo B2bConvention
 * Representa una municipalidad o agencia externa asociada mediante convenio B2B para auditar certificados médicos.
 */
class B2bConvention extends Model
{
    use BelongsToEmpresa;

    protected $table = 'b2b_conventions';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'cuit',
        'servicios_requeridos',
        'token_acceso',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'servicios_requeridos' => 'array',
    ];

    /**
     * Generador automático de tokens criptográficos al crear un nuevo convenio.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->token_acceso)) {
                $model->token_acceso = 'CONV-' . strtoupper(Str::random(24));
            }
        });
    }

    /**
     * Relación con los Certificados Médicos emitidos bajo este convenio.
     */
    public function certificates()
    {
        return $this->hasMany(MedicalCertificate::class, 'b2b_convention_id');
    }

    /**
     * Relación con la clínica/empresa dueña del convenio.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
