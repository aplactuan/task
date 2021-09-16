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
        $task = new Task;
        $task->user_id = $request->user()->id;
        $task->status = $request->status ?? 'pending';
        $task->order = $request->order ?? 0;
        $task->parent_id = $request->parent_id ?? null;
        $task->name = $request->name;

        $task->save();

        return new TaskResource($task);
    }
}
