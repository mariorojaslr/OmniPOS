<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel',
        'target_name',
        'target_origin',
        'details',
        'status',
    ];
}
