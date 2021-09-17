<?php

namespace App\Exports;

use App\Models\Task;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class TasksExport implements FromQuery
{
    use Exportable;
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function query()
    {
        return Task::query()->where('user_id', $this->user->id);
    }
}
