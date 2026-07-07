<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    protected $fillable = ['title', 'description', 'icon', 'gradient_from', 'gradient_to', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class, 'album_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getGradientAttribute()
    {
        return "linear-gradient(135deg, {$this->gradient_from}, {$this->gradient_to})";
    }
}
