<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'empresa_id', 
        'user_id', 
        'asistencia_id',
        'category_id', 
        'amount', 
        'payment_method',
        'provider',
        'description', 
        'date', 
        'receipt_url'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
