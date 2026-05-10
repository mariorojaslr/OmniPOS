<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRead extends Model
{
    protected $table = 'owner_notification_reads';

    protected $fillable = [
        'user_id',
        'owner_notification_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notification()
    {
        return $this->belongsTo(OwnerNotification::class, 'owner_notification_id');
    }
}
