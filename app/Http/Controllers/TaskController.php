<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        Task::create([
            'name' => $request->name,
            'completed' => false
        ]);

        return response()->json(['success' => true]);
    }

    public function toggle($id)
    {
        $task = Task::findOrFail($id);
        $task->completed = !$task->completed;
        $task->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}