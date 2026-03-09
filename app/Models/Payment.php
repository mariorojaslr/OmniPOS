<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Payment extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'client_id',
        'amount',
        'method',
        'reference',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    
}
