<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'announcement_date',
        'location',
        'featured_image',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'announcement_date' => 'date',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
