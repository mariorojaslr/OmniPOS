<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Chequera extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'banco',
        'tipo',
        'sucursal',
        'numero_cuenta',
        'tipo_cuenta',
        'desde',
        'hasta',
        'proximo_numero',
        'activo',
        'notas',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'desde' => 'integer',
        'hasta' => 'integer',
        'proximo_numero' => 'integer',
    ];

    /**
     * Cheques emitidos desde esta chequera
     */
    public function cheques()
    {
        return $this->hasMany(Cheque::class);
    }

    /**
     * Cantidad total de cheques en el rango
     */
    public function getTotalChequesAttribute()
    {
        return $this->hasta - $this->desde + 1;
    }

    /**
     * Cantidad de cheques ya emitidos
     */
    public function getEmitidosAttribute()
    {
        return $this->proximo_numero - $this->desde;
    }

    /**
     * Cantidad de cheques disponibles
     */
    public function getDisponiblesAttribute()
    {
        return max(0, $this->hasta - $this->proximo_numero + 1);
    }

    /**
     * Porcentaje de uso
     */
    public function getPorcentajeUsoAttribute()
    {
        $total = $this->total_cheques;
        if ($total <= 0) return 0;
        return round(($this->emitidos / $total) * 100);
    }

    /**
     * ¿Agotada?
     */
    public function getAgotadaAttribute()
    {
        return $this->proximo_numero > $this->hasta;
    }

    /**
     * Emitir el próximo cheque propio y retornar su número
     */
    public function emitirCheque($monto, $fechaPago, $supplierId = null, $empresaId = null, $numeroManual = null)
    {
        if ($this->tipo === 'fisica' && $this->agotada) {
            throw new \Exception("La chequera {$this->banco} #{$this->numero_cuenta} no tiene más cheques disponibles.");
        }

        $numeroCheque = ($this->tipo === 'echeck') ? ($numeroManual ?? $this->proximo_numero) : $this->proximo_numero;

        $cheque = Cheque::create([
            'empresa_id'    => $empresaId ?? $this->empresa_id,
            'chequera_id'   => $this->id,
            'numero'        => $numeroCheque,
            'banco'         => $this->banco,
            'emisor'        => 'Propio',
            'monto'         => $monto,
            'fecha_emision' => now()->toDateString(),
            'fecha_pago'    => $fechaPago,
            'estado'        => 'entregado',
            'tipo'          => 'propio',
            'supplier_id'   => $supplierId,
        ]);

        if ($this->tipo === 'fisica' || ($this->tipo === 'echeck' && $numeroManual === null)) {
            $this->increment('proximo_numero');
        }

        return $cheque;
    }
}
