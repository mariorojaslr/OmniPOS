<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'user_id',
        'description',
        'model_type',
        'model_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Registra una actividad en el sistema.
     */
    public static function log($description, $model = null, $properties = [])
    {
        $user = Auth::user();
        if (!$user || !$user->empresa_id) return null;

        return self::create([
            'empresa_id'  => $user->empresa_id,
            'user_id'     => $user->id,
            'description' => $description,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model ? $model->id : null,
            'properties'  => $properties,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }
}
