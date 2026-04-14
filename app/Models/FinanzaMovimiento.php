<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class FinanzaMovimiento extends Model
{
    use BelongsToEmpresa;

    protected $table = 'finanzas_movimientos';

    protected $fillable = [
        'empresa_id',
        'cuenta_id',
        'user_id',
        'tipo',
        'monto',
        'fecha',
        'concepto',
        'categoria',
        'reference_type',
        'reference_id',
        'conciliado',
        'comprobante',
        'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'date',
        'conciliado' => 'boolean',
    ];

    public function cuenta()
    {
        return $this->belongsTo(FinanzaCuenta::class, 'cuenta_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
