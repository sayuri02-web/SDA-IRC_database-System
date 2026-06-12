<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members'; 
    protected $primaryKey = 'id';
    protected $fillable = [

    'first_name',
    'middle_initial',
    'last_name',
    'suffix',

    'gender',
    'birthdate',
    'birthplace',
    'mobile',

    'father_name',
    'mother_name',

    'street',
    'barangay',
    'city',
    'province',
    'region',

    'church_id',
    'cluster',

    // MEMBERSHIP
    'membership_status',

    // BAPTISM / DEDICATION
    'baptism_date',
    'baptism_place',
    'officiating_minister',

    // PHOTO
    'photo',
];
    
    public function getFullNameAttribute()
    {
        return trim(
            $this->first_name . ' ' .
            ($this->middle_initial ? $this->middle_initial . '. ' : '') .
            $this->last_name . ' ' .
            ($this->suffix ?? '')
        );
    }

    public function getAgeAttribute()
    {
        return $this->birthdate
            ? Carbon::parse($this->birthdate)->age
            : null;
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }
}