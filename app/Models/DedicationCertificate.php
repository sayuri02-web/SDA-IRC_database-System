<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DedicationCertificate extends Model
{
    protected $fillable = [
        'certificate_no',
        'member_id',
        'child_name',
        'birth_place',
        'birth_date',
        'father_name',
        'mother_name',
        'dedication_date',
        'church_name',
        'church_location',
        'chairman_name',
        'minister_name',
        'witnesses',
        'issued_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'dedication_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
