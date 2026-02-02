<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'empresa_id',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
    ];

    /* =========================
       Roles
    ========================= */

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isEmpresa(): bool
    {
        return $this->role === 'empresa';
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
