<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToEmpresa;

class MedicalRecord extends Model
{
    use HasFactory, BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'client_id',
        'user_id',
        'specialty',
        'reason_for_visit',
        'diagnosis',
        'treatment',
        'internal_notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
