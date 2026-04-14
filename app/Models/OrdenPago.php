<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class OrdenPago extends Model
{
    use BelongsToEmpresa;

    protected $table = 'ordenes_pago';

    protected $fillable = [
        'empresa_id',
        'supplier_id',
        'user_id',
        'numero_orden',
        'monto_total',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'fecha' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pagos()
    {
        return $this->hasMany(OrdenPagoPago::class);
    }

    public function imputaciones()
    {
        return $this->hasMany(OrdenPagoImputacion::class);
    }

    public function ledgerRecord()
    {
        return $this->morphOne(SupplierLedger::class, 'reference');
    }
}
