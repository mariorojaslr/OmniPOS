<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use \App\Traits\BelongsToEmpresa;

    protected $fillable = ['empresa_id', 'nombre', 'activo'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
