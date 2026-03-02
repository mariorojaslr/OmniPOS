<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    protected $fillable = [
        'empresa_id',
        'supplier_id',
        'type',
        'amount',
        'description',
        'paid'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
