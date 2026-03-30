<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToEmpresa;

class Remito extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'venta_id',
        'user_id',
        'client_id',
        'numero_remito',
        'fecha_entrega',
        'observaciones',
    ];

    /**
     * 📋 Detalle de la entrega
     */
    public function items()
    {
        return $this->hasMany(RemitoItem::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

