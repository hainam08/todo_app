<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\TaskReminderNotification;
use Illuminate\Support\Carbon;
use App\Models\Task;

class SendTaskReminderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $nextMinute = $now->copy()->addMinute();

        $tasks = Task::where('status', '!=', 'Completed')
            ->whereNotNull('remind_at')
            ->where('is_reminder_enabled', true)
            ->whereBetween('remind_at', [$now, $nextMinute])
            ->with('user')
            ->get();

        foreach ($tasks as $task) {
            if ($task->user) {
                $task->user->notify(new TaskReminderNotification($task));
            }
        }
    }
}
