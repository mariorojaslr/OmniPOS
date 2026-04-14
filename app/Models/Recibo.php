<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recibo extends Model
{
    use BelongsToEmpresa;
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'client_id',
        'user_id',
        'numero_recibo',
        'monto_total',
        'metodo_pago',
        'referencia',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function imputaciones()
    {
        return $this->hasMany(ReciboImputacion::class);
    }

    public function pagos()
    {
        return $this->hasMany(ReciboPago::class);
    }
}
