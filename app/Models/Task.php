<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'completed', 'status', 'due_date', 'start_date', 'end_date'];

    protected $casts = [
        'completed'  => 'boolean',
        'due_date'   => 'date',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];
}
