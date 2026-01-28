<?php

namespace App\Console\Commands;

use App\Jobs\RunBackupJob;
use App\Models\BackupJob;
use Cron\CronExpression;
use Illuminate\Console\Command;

class RunScheduledBackups extends Command
{
    protected $signature = 'backups:run-scheduled';

    public function handle()
    {
        $now = now();

        BackupJob::with('server')
            ->where('is_active', true)
            ->get()
            ->each(function ($job) use ($now) {
                if (!$job->backup_server->is_active) return;
                #info("Backup");
                #info($job->type);
                if (CronExpression::factory($job->cron)->isDue($now)) {
                    RunBackupJob::dispatch(
                        $job->backup_server_id,
                        $job->type
                    );
                }
            });
    }
}

