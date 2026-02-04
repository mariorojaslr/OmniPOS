<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos permitidos para asignación masiva
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'empresa_id',
        'activo',
        'email_verified_at',
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts automáticos
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo'            => 'boolean',
    ];

    /* =========================
       Roles
    ========================= */

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Empresa / Usuario (ambos trabajan dentro de una empresa)
     */
    public function isEmpresa(): bool
    {
        return in_array($this->role, ['empresa', 'usuario']);
    }

    /* =========================
       Estado
    ========================= */

    public function estaActivo(): bool
    {
        return $this->activo === true;
    }

    /* =========================
       Relaciones
    ========================= */

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
