<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'max_users',
        'max_products',
        'max_storage_mb',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'max_storage_mb' => 'decimal:2',
    ];

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
}
