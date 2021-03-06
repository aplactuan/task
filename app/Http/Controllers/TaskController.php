<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
       // dd($request->user()->tasks()->incomplete()->count());
        return TaskResource::collection(
          Task::with('sub_tasks')->where('user_id', $request->user()->id)->parents()->ordered()->orderBy('created_at', 'desc')->get()
        )->additional([
            'complete' => $request->user()->tasks()->complete()->count(),
            'incomplete' => $request->user()->tasks()->incomplete()->count()
        ]);
    }

    public function store(Request $request)
    {
        $task = $request->user()->tasks()->create([
            'status' => $request->status ?? 'pending',
            'order' => $request->order ?? 0,
            'parent_id' => $request->parent_id ?? null,
            'name' => $request->name
        ]);

        return (new TaskResource($task))->additional([
            'complete' => $request->user()->tasks()->complete()->count(),
            'incomplete' => $request->user()->tasks()->incomplete()->count()
        ]);
    }

    public function update(Task $task, Request $request)
    {
        if ($request->user()->cannot('update', $task)) {
            abort(403);
        }
        $task->name = $request->name;
        $task->order = $request->order ?? 0;
        $task->status = $request->status;
        $task->save();

        return (new TaskResource($task))->additional([
            'complete' => $request->user()->tasks()->complete()->count(),
            'incomplete' => $request->user()->tasks()->incomplete()->count()
        ]);
    }

    public function destroy(Task $task, Request $request)
    {
        if ($request->user()->cannot('delete', $task)) {
            abort(403);
        }

        $this->markChildren($task);

        $task->delete();

        return TaskResource::collection(
            Task::with('sub_tasks')->where('user_id', $request->user()->id)->parents()->ordered()->orderBy('created_at', 'desc')->get()
        )->additional([
            'complete' => $request->user()->tasks()->complete()->count(),
            'incomplete' => $request->user()->tasks()->incomplete()->count()
        ]);
    }

    protected function markChildren($task)
    {
        foreach ($task->sub_tasks as $subTask) {
            $subTask->parent_deleted = true;
            $subTask->save();
            if ($subTask->sub_tasks->count() > 0) {
                $this->markChildren($subTask);
            }
        }
    }
}
