<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskNotificationController extends Controller
{
    /**
     * Generate all pending notifications for tasks.
     * Called on every fetch to ensure notifications are up-to-date.
     */
    public function generate()
    {
        $today = Carbon::today();

        // Get all incomplete tasks
        $tasks = Task::where('completed', false)->whereNotNull('end_date')->get();

        foreach ($tasks as $task) {
            // A. Reminder: exactly 2 days before end_date
            $reminderDate = $task->end_date->copy()->subDays(2);
            if ($today->equalTo($reminderDate)) {
                TaskNotification::firstOrCreate([
                    'task_id' => $task->id,
                    'type' => 'reminder',
                    'notification_date' => $today->toDateString(),
                ], [
                    'message' => 'Task "' . $task->name . '" will end on ' . $task->end_date->format('F d') . '.',
                ]);
            }

            // B. Overdue: one notification per day after end_date
            if ($today->gt($task->end_date)) {
                $daysOverdue = $today->diffInDays($task->end_date);
                TaskNotification::firstOrCreate([
                    'task_id' => $task->id,
                    'type' => 'overdue',
                    'notification_date' => $today->toDateString(),
                ], [
                    'message' => 'Task "' . $task->name . '" is overdue by ' . $daysOverdue . ' ' . ($daysOverdue === 1 ? 'day' : 'days') . '.',
                ]);
            }
        }

        // C. Completed late: tasks completed after end_date
        $lateCompletedTasks = Task::where('completed', true)
            ->whereNotNull('end_date')
            ->whereColumn('updated_at', '>', 'end_date')
            ->get();

        foreach ($lateCompletedTasks as $task) {
            $completedDate = $task->updated_at->startOfDay();
            $daysLate = $completedDate->diffInDays($task->end_date);
            if ($daysLate > 0) {
                TaskNotification::firstOrCreate([
                    'task_id' => $task->id,
                    'type' => 'completed_late',
                    'notification_date' => $completedDate->toDateString(),
                ], [
                    'message' => 'Task "' . $task->name . '" was completed ' . $daysLate . ' ' . ($daysLate === 1 ? 'day' : 'days') . ' late.',
                ]);
            }
        }
    }

    /**
     * Get all notifications (generates pending ones first).
     */
    public function index()
    {
        $this->generate();

        $notifications = TaskNotification::with('task')
            ->orderByDesc('created_at')
            ->take(50)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'task_id' => $n->task_id,
                'type' => $n->type,
                'message' => $n->message,
                'notification_date' => $n->notification_date->format('Y-m-d'),
                'read_at' => $n->read_at,
                'time_ago' => $n->created_at->diffForHumans(),
            ]);

        $unreadCount = TaskNotification::unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        TaskNotification::unread()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead($id)
    {
        $notification = TaskNotification::findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count only (lightweight for badge polling).
     */
    public function unreadCount()
    {
        $this->generate();

        return response()->json([
            'unread_count' => TaskNotification::unread()->count(),
        ]);
    }
}
