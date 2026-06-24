<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodMoralCertificates extends Model
{
    protected $table = 'goodmoral_certificates';

    protected $fillable = [
        'certificate_no',
        'member_id',
        'full_name',
        'church_name',
        'church_location',
        'purpose',
        'issued_date',
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
