<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presupuesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'user_id',
        'client_id',
        'numero',
        'fecha',
        'vencimiento',
        'subtotal',
        'total',
        'notas',
        'estado'
    ];

    protected $casts = [
        'fecha'       => 'date',
        'vencimiento' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PresupuestoItem::class);
    }
}
