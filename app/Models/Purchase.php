<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Purchase extends Model
{
    use BelongsToEmpresa;

    protected $table = 'purchases';

    /*
    |--------------------------------------------------------------------------
    | CAMPOS PERMITIDOS (IMPORTANTE)
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'empresa_id',
        'supplier_id',
        'parent_id',
        'purchase_date',
        'invoice_type',
        'invoice_number',
        'total',
        'payment_type',
        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function ledgerRecord()
    {
        return $this->morphOne(SupplierLedger::class, 'reference');
    }

    /**
     * 🔄 Notas de Crédito asociadas a esta compra
     */
    public function creditNotes()
    {
        return $this->hasMany(Purchase::class, 'parent_id');
    }
}
