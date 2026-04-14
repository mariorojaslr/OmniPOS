<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenPagoImputacion extends Model
{
    protected $table = 'orden_pago_imputaciones';

    protected $fillable = [
        'orden_pago_id',
        'ledger_id',
        'monto_aplicado',
    ];

    public function ordenPago()
    {
        return $this->belongsTo(OrdenPago::class);
    }

    public function ledger()
    {
        return $this->belongsTo(SupplierLedger::class, 'ledger_id');
    }
}
