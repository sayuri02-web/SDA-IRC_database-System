<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name', 'icon', 'color', 'bg_color'];

    public function members()
    {
        return Member::where('organization', $this->name)->where('is_leader', true)->get();
    }

    public function membersCount()
    {
        return Member::where('organization', $this->name)->where('is_leader', true)->count();
    }
}
