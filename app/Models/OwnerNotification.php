<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class OwnerNotification extends Model
{
    protected $fillable = [
        'empresa_id',
        'type',
        'message',
        'channel',
        'read_at',
    ];
 
    protected $casts = [
        'read_at' => 'datetime',
    ];
 
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
