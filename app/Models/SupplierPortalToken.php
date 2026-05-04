<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class SupplierPortalToken extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'supplier_id',
        'token',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
