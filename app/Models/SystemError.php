<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemError extends Model
{
    protected $fillable = [
        'empresa_id',
        'user_id',
        'exception_class',
        'message',
        'file',
        'line',
        'url',
        'status',
        'severity'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
