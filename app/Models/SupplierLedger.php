<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class SupplierLedger extends Model
{
    use BelongsToEmpresa;

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
