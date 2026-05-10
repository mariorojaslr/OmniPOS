<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToEmpresa;

class Turno extends Model
{
    use HasFactory, BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'user_id',
        'client_id',
        'servicio_id',
        'cliente_nombre_manual',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'monto',
        'comision_monto',
        'liquidacion_id',
        'notas'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class);
    }
}
