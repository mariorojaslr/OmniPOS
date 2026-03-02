<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPortalToken extends Model
{
    protected $fillable = [
        'empresa_id',
        'client_id',
        'token',
    ];

    /* =========================
       RELACIONES MULTIEMPRESA
    ========================== */

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
