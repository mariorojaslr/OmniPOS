<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PresupuestoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'presupuesto_id',
        'product_id',
        'variant_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'iva_porcentaje',
        'subtotal',
        'total'
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
