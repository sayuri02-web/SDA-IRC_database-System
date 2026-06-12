<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateLog extends Model
{
    protected $fillable = [
        'member_id',
        'certificate_type',
        'certificate_number',
        'printed_by',
        'printed_at'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
