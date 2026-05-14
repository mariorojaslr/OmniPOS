<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'client_id',
        'period',
        'amount',
        'due_date',
        'paid_at',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'due_date' => 'date',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
