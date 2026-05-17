<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerConfig extends Model
{
    protected $fillable = [
        'user_id',
        'brand_name',
        'logo_path',
        'primary_color',
        'owner_commission_percentage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
