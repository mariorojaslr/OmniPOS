<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToEmpresa;

class ClientStudy extends Model
{
    use HasFactory, BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'client_id',
        'turno_id',
        'file_name',
        'file_path',
        'file_size',
    ];

    public function patient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
}
