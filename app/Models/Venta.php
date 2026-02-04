<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'empresa_id',
        'user_id',
        'total_sin_iva',
        'total_iva',
        'total_con_iva',
    ];

    public function items()
    {
        return $this->hasMany(VentaItem::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
