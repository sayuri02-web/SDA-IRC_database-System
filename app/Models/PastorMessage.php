<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PastorMessage extends Model
{
    protected $fillable = [
        'member_id',
        'title',
        'content',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
