<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RemoveOldSoftDeletedTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:remove-soft-deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force delete soft deleted task that is 30 days older';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $oldTasks = Task::onlyTrashed()
            ->where('deleted_at', '<=', Carbon::now()->subDays(30)->toDateTimeString())
            ->get();
        foreach ($oldTasks as $oldTask) {
            $oldTask->forceDelete();
        }
        return 0;
    }
}
