<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'module',
        'action',
        'record_id',
        'description',
    ];

    /**
     * Log an activity.
     */
    public static function log(string $module, string $action, string $description, ?int $recordId = null): self
    {
        return self::create([
            'user_id'     => auth()->id(),
            'user_name'   => auth()->user()->name ?? 'Admin',
            'module'      => $module,
            'action'      => $action,
            'record_id'   => $recordId,
            'description' => $description,
        ]);
    }
}
