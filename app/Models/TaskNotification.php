<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskNotification extends Model
{
    protected $table = 'task_notifications';

    protected $fillable = [
        'task_id',
        'type',
        'message',
        'notification_date',
        'read_at',
    ];

    protected $casts = [
        'notification_date' => 'date',
        'read_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
