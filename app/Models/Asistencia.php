<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Asistencia extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'user_id',
        'empresa_id',
        'entrada',
        'salida',
        'ip_entrada',
        'ip_salida',
        'observaciones',
        'vuelto_inicial',
        'vuelto_final',
    ];

    protected $casts = [
        'entrada' => 'datetime',
        'salida'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
