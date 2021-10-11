<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class DeletedTaskController extends Controller
{
    public function index(Request $request)
    {
        return TaskResource::collection(
            $request->user()->tasks()->onlyTrashed()->get()
        );
    }

    public function show()
    {
        return view('task.bin');
    }

    public function restore($id, Request $request)
    {
        $task = $request->user()->tasks()->onlyTrashed()->find($id);

        if ($request->user()->cannot('restore', $task)) {
            abort(403);
        }

       $task->restore();

        $this->markChildren($task);

        return $task;
    }

    public function delete($id, Request $request)
    {
        $task = $request->user()->tasks()->onlyTrashed()->find($id);

        if ($request->user()->cannot('forceDelete', $task)) {
            abort(403);
        }

        return $task->forceDelete();
    }

    protected function markChildren($task)
    {
        foreach ($task->sub_tasks as $subTask) {
            $subTask->parent_deleted = false;
            $subTask->save();
            if ($subTask->sub_tasks->count() > 0) {
                $this->markChildren($subTask);
            }
        }
    }
}
