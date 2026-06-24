<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCertificates extends Model
{
    protected $table = 'student_certificates';

    protected $fillable = [
        'certificate_no',
        'member_id',
        'full_name',
        'church_name',
        'church_location',
        'issued_date',
        'elder_1',
        'elder_2',
        'elder_3',
        'elder_4',
        'issued_by',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
