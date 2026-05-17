<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObraSocial extends Model
{
    protected $table = 'obras_sociales';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'cuit',
        'activo',
        'api_token',
        'config_json',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'config_json' => 'array',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
