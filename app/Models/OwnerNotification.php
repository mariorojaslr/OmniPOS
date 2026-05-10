<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class OwnerNotification extends Model
{
    protected $fillable = [
        'empresa_id',
        'title',
        'type',
        'message',
        'channel',
        'media_url',
        'media_type',
        'read_at',
    ];
 
    protected $casts = [
        'read_at' => 'datetime',
    ];
 
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function reads()
    {
        return $this->hasMany(\App\Models\NotificationRead::class, 'owner_notification_id');
    }
}
