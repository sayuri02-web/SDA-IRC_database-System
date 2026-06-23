<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipCertificate extends Model
{
    protected $fillable = [
        'certificate_no',
        'member_id',
        'full_name',
        'church_name',
        'church_location',
        'position',
        'issued_date',
        'secretary_name',
        'elder_name',
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
