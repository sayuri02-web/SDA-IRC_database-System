<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaptismCertificate extends Model
{
    protected $fillable = [
        'certificate_no',
        'member_id',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'father_name',
        'mother_name',
        'baptism_place',
        'officiating_minister',
        'chairman',
        'secretary',
        'fellowship_date',
        'baptism_date',
        'church_fellowship',
        'doc_no',
        'page_no',
        'book_no',
        'series_no',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'fellowship_date' => 'date',
        'baptism_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}