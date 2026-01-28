<?php

namespace App\Jobs;

use App\Models\Backup;
use App\Models\BackupServer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class RunBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;
    public $tries = 1;

    public function __construct(
        public int $serverId,
        public string $type
    ) {}

    public function handle()
    {
        $server = BackupServer::findOrFail($this->serverId);
        $date = now()->format('Y-m-d');

        $path = "/backups/{$server->name}/{$this->type}/{$date}";

        $backup = Backup::create([
            'backup_server_id' => $server->id,
            'type' => $this->type,
            'backup_date' => $date,
            'path' => $path,
        ]);

        $process = new Process([
            '/opt/backup/run_backup.sh',
            $server->name,
            $server->host,
            $server->ssh_user,
            $server->ssh_port,
            $server->remote_path,
            $server->exclude_args ?? '',
            $this->type,
        ]);

        $process->setTimeout(null);
        $process->run();

        $backup->log = $process->getOutput();

        $backup->status = $process->isSuccessful()
            ? 'success'
            : 'failed';

        $backup->save();
    }
}

