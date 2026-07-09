<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Apply automatic overdue logic to a collection of tasks.
     * - If completed, never mark as overdue.
     * - If not completed and end_date < today, mark as overdue.
     */
    private function applyOverdueLogic($tasks)
    {
        $today = Carbon::today();

        foreach ($tasks as $task) {
            if (!$task->completed && $task->end_date && $task->end_date->lt($today)) {
                if ($task->status !== 'overdue') {
                    $task->status = 'overdue';
                    $task->save();
                }
            }
        }

        return $tasks;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $task = Task::create([
            'name'       => $request->name,
            'completed'  => false,
            'status'     => 'upcoming',
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'due_date'   => $request->end_date,
        ]);

        // Apply overdue logic immediately
        $this->applyOverdueLogic(collect([$task]));

        return response()->json([
            'success' => true,
            'task' => [
                'id'         => $task->id,
                'name'       => $task->name,
                'status'     => $task->status,
                'completed'  => $task->completed,
                'start_date' => $task->start_date?->format('Y-m-d'),
                'end_date'   => $task->end_date?->format('Y-m-d'),
            ]
        ]);
    }

    public function toggle($id)
    {
        $task = Task::findOrFail($id);

        if (!$task->completed) {
            // Marking as completed — store previous status for potential restore
            $task->completed = true;
            $task->status = 'completed';
        } else {
            // Unchecking — restore appropriate status
            $task->completed = false;
            $today = Carbon::today();

            if ($task->end_date && $task->end_date->lt($today)) {
                $task->status = 'overdue';
            } elseif ($task->start_date && $task->start_date->gt($today)) {
                $task->status = 'upcoming';
            } else {
                $task->status = 'ongoing';
            }
        }

        $task->save();

        return response()->json([
            'success' => true,
            'task' => [
                'id'         => $task->id,
                'name'       => $task->name,
                'status'     => $task->status,
                'completed'  => $task->completed,
                'start_date' => $task->start_date?->format('Y-m-d'),
                'end_date'   => $task->end_date?->format('Y-m-d'),
            ]
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status'  => 'required|in:upcoming,ongoing,overdue,completed',
        ]);

        $task = Task::findOrFail($request->task_id);
        $task->status = $request->status;
        $task->completed = $request->status === 'completed';
        $task->save();

        return response()->json([
            'success' => true,
            'task' => [
                'id'         => $task->id,
                'name'       => $task->name,
                'status'     => $task->status,
                'completed'  => $task->completed,
                'start_date' => $task->start_date?->format('Y-m-d'),
                'end_date'   => $task->end_date?->format('Y-m-d'),
            ]
        ]);
    }

    public function getDates()
    {
        $dates = Task::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderByDesc('date')
            ->pluck('date');

        return response()->json($dates);
    }

    public function getByDate(Request $request)
    {
        $date = $request->query('date');
        $tasks = Task::whereDate('created_at', $date)->get();

        // Apply overdue logic
        $this->applyOverdueLogic($tasks);

        return response()->json($tasks->map(fn($t) => [
            'id'     => $t->id,
            'name'   => $t->name,
            'status' => $t->status,
        ]));
    }

    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
