<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class BankAccount extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'holder_type',
        'holder_id',
        'bank_name',
        'account_number',
        'cbu_cvu',
        'alias',
        'account_type',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con el dueño de la cuenta (Client o Supplier)
     */
    public function holder()
    {
        return $this->morphTo();
    }
}
