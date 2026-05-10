<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToEmpresa;

class AcuerdoProfesional extends Model
{
    use BelongsToEmpresa;

    protected $table = 'acuerdos_profesionales';

    protected $fillable = [
        'empresa_id',
        'user_id',
        'servicio_id',
        'tipo_comision',
        'valor',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profesional()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
