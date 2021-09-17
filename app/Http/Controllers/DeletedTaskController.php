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

        return $task->restore();
    }

    public function delete($id, Request $request)
    {
        $task = $request->user()->tasks()->onlyTrashed()->find($id);

        if ($request->user()->cannot('forceDelete', $task)) {
            abort(403);
        }

        return $task->forceDelete();
    }
}
