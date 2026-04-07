<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'name',
        'short_name',
        'base_unit_id',
        'conversion_factor',
        'active'
    ];

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
