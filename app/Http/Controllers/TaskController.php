<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(
          Task::with('sub_tasks')->parents()->ordered()->orderBy('created_at', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $task = $request->user()->tasks()->create([
            'status' => $request->status ?? 'pending',
            'order' => $request->order ?? 0,
            'parent_id' => $request->parent_id ?? null,
            'name' => $request->name
        ]);

        return new TaskResource($task);
    }

    public function update(Task $task, Request $request)
    {
        $task->name = $request->name;
        $task->order = $request->order ?? 0;
        $task->status = $request->status;
        $task->save();

        return new TaskResource($task);
    }
}
