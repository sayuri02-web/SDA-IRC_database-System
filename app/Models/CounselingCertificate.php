<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselingCertificate extends Model
{
    protected $fillable = [
        'certificate_no',
        'member_id',
        'full_name',
        'partner_name',
        'church_name',
        'church_location',
        'purpose',
        'issued_date',
        'chairman_name',
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
