<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\BelongsToEmpresa;

class ProfesionalConfig extends Model
{
    use HasFactory, BelongsToEmpresa;

    protected $table = 'profesionales_config';

    protected $fillable = [
        'empresa_id',
        'user_id',
        'tipo_contrato',
        'sueldo_base',
        'tipo_comision',
        'valor_comision',
        'token_portal',
        'especialidades'
    ];

    protected $casts = [
        'especialidades' => 'array',
        'sueldo_base' => 'decimal:4',
        'valor_comision' => 'decimal:4'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
