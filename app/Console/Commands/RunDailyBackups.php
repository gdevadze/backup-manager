<?php

namespace App\Console\Commands;

use App\Jobs\RunBackupJob;
use App\Models\BackupServer;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RunDailyBackups extends Command
{
    protected $signature = 'backups:daily {--date=}';
    protected $description = 'Queue backups for all active servers';

    public function handle(): int
    {
        $date = $this->option('date') ?: Carbon::now()->format('Y-m-d');

        $servers = BackupServer::query()->where('is_active', true)->get();

        foreach ($servers as $server) {
            RunBackupJob::dispatch($server->id, $date);
            $this->info("Queued: {$server->name} ($date)");
        }

        return self::SUCCESS;
    }
}
