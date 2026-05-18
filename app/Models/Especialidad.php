<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToEmpresa;

class Especialidad extends Model
{
    use HasFactory, BelongsToEmpresa;

    protected $table = 'especialidades';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'descripcion',
        'activo'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
