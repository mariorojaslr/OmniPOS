<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventorySession extends Model
{
    protected $fillable = [
        'empresa_id',
        'uuid',
        'created_by_id',
        'active',
        'expires_at'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
