<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembersAffiliateCertificates extends Model
{
    protected $table = 'membersaffiliate_certificates';

    protected $fillable = [
        'certificate_no',
        'member_id',
        'full_name',
        'church_name',
        'church_location',
        'residence_cert_no',
        'residence_issued_at',
        'residence_issued_date',
        'done_date',
        'elder_name',
        'secretary_name',
        'chairman_name',
        'issued_by',
    ];

    protected $casts = [
        'residence_issued_date' => 'date',
        'done_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
