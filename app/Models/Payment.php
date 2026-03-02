<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
