<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReciboImputacion extends Model
{
    protected $table = 'recibo_imputaciones';

    protected $fillable = [
        'recibo_id',
        'ledger_id',
        'monto_aplicado',
    ];

    public function recibo()
    {
        return $this->belongsTo(Recibo::class);
    }

    public function ledger()
    {
        return $this->belongsTo(ClientLedger::class, 'ledger_id');
    }
}
