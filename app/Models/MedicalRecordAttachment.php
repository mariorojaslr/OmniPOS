<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecordAttachment extends Model
{
    protected $fillable = [
        'medical_record_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
