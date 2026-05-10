<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\BelongsToEmpresa;

class Servicio extends Model
{
    use HasFactory, BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'categoria',
        'precio',
        'duracion_minutos',
        'comision_porcentaje',
        'activo'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
