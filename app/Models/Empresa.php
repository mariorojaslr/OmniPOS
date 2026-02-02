<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Empresa extends Model
{
    use HasFactory;

    /**
     * Tabla asociada
     */
    protected $table = 'empresas';

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'nombre_comercial',
        'razon_social',
        'email',
        'telefono',
        'activo',
        'fecha_vencimiento',
        'configuracion',
    ];

    /**
     * Casts automáticos
     * (CLAVE para que funcionen los botones)
     */
    protected $casts = [
        'activo' => 'boolean',
        'fecha_vencimiento' => 'date',
        'configuracion' => 'array',
    ];

    /**
     * Relación:
     * una empresa tiene muchos usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Estado visual de la empresa
     * - Inactiva
     * - Vencida
     * - Activa
     */
    public function estadoLabel(): string
    {
        if (! $this->activo) {
            return 'Inactiva';
        }

        if ($this->fecha_vencimiento instanceof Carbon && $this->fecha_vencimiento->isPast()) {
            return 'Vencida';
        }

        return 'Activa';
    }

    /**
     * Indica si la empresa está vencida
     */
    public function estaVencida(): bool
    {
        return $this->fecha_vencimiento instanceof Carbon
            && $this->fecha_vencimiento->isPast();
    }

    /**
     * Renueva la empresa X días hacia adelante
     * (usado por el botón "Renovar")
     */
    public function renovar(int $dias = 30): void
    {
        $this->update([
            'fecha_vencimiento' => now()->addDays($dias),
            'activo' => true,
        ]);
    }

    /**
     * Activa o desactiva la empresa
     * (usado por el botón toggle)
     */
    public function toggleActivo(): void
    {
        $this->update([
            'activo' => ! $this->activo,
        ]);
    }
}
