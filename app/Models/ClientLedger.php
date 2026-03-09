<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientLedger extends Model
{
    use BelongsToEmpresa;

    use HasFactory;

    protected $table = 'client_ledgers';

    protected $fillable = [
        'empresa_id',
        'client_id',
        'type',
        'amount',
        'description',
        'paid',
        'created_at',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * =====================================================
     * BLOQUEO CONTABLE - MOVIMIENTOS INMUTABLES
     * =====================================================
     */

    protected static function boot()
    {
        parent::boot();

        // BLOQUEAR EDICION
        static::updating(function ($model) {
            throw new \Exception("No se permite modificar movimientos contables. Debe generar un movimiento compensatorio.");
        });

        // BLOQUEAR ELIMINACION
        static::deleting(function ($model) {
            throw new \Exception("No se permite eliminar movimientos contables. Debe generar un movimiento compensatorio.");
        });
    }

    /**
     * RELACIONES
     */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
