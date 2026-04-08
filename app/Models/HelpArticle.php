<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpArticle extends Model
{
    protected $fillable = [
        'route_name',
        'title',
        'content',
        'video_url',
        'is_active',
    ];
}
