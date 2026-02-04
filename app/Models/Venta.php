<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'user_id',
        'total_sin_iva',
        'total_iva',
        'total_con_iva',
    ];

    /* =========================
       Relaciones
    ========================= */

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
