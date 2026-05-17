<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class AffiliatePlan extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'name',
        'description',
        'price',
        'features',
        'active',
    ];

    protected $casts = [
        'features' => 'array',
        'active' => 'boolean',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
