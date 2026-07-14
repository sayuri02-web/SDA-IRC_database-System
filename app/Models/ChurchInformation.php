<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchInformation extends Model
{
    protected $table = 'church_information';

    protected $fillable = [
        'church_image',
        'church_history',
        'mission',
        'vision',
        'core_values',
    ];
}
