<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class ClientPortalToken extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'client_id',
        'token',
    ];

    /* =========================
       RELACIONES MULTIEMPRESA
    ========================== */

    

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
