<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchFeaturedLeader extends Model
{
    protected $table = 'church_featured_leaders';

    protected $fillable = ['member_id', 'sort_order'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
