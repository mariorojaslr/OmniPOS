<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class FinanzaCuenta extends Model
{
    use BelongsToEmpresa;

    protected $table = 'finanzas_cuentas';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'tipo',
        'moneda',
        'numero_cuenta',
        'cbu_cvu',
        'saldo_inicial',
        'saldo_actual',
        'activo',
    ];

    protected $casts = [
        'saldo_inicial' => 'decimal:2',
        'saldo_actual' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function movimientos()
    {
        return $this->hasMany(FinanzaMovimiento::class, 'cuenta_id');
    }
}
