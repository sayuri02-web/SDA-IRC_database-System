<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $task = Task::create([
            'name'      => $request->name,
            'completed' => false,
            'status'    => 'pending',
            'due_date'  => $request->due_date ?? now()->toDateString(),
        ]);

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function toggle($id)
    {
        $task = Task::findOrFail($id);
        $task->completed = !$task->completed;
        $task->status = $task->completed ? 'completed' : 'pending';
        $task->save();

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status'  => 'required|in:pending,ongoing,completed',
        ]);

        $task = Task::findOrFail($request->task_id);
        $task->status = $request->status;
        $task->completed = $request->status === 'completed';
        $task->save();

        return response()->json(['success' => true, 'task' => $task]);
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
        $tasks = Task::whereDate('created_at', $date)->get(['id', 'name', 'status']);

        return response()->json($tasks);
    }

    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
