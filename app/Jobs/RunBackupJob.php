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

    public function __construct(public int $serverId, public string $date) {}

    public function handle(): void
    {
        $server = BackupServer::query()->findOrFail($this->serverId);

        $root = rtrim(env('BACKUP_ROOT', '/backups'), '/');
        $script = env('BACKUP_SCRIPT', '/opt/backup/run_backup.sh');

        $path = "{$root}/{$server->name}/{$this->date}";

        $backup = Backup::query()->firstOrCreate(
            ['backup_server_id' => $server->id, 'backup_date' => $this->date],
            ['status' => 'running', 'path' => $path]
        );

        $dbPass = $server->getDbPasswordDecrypted() ?? '';

        $process = new Process([
            $script,
            $server->name,
            $server->host,
            $server->ssh_user,
            (string)$server->ssh_port,
            $server->remote_path,
            $server->db_name ?? '',
            $server->db_user ?? '',
            $dbPass,
            $server->exclude_args ?? '',
        ]);

        $process->setTimeout(3600); // 1 hour
        $process->run();

        $output = trim($process->getOutput() . "\n" . $process->getErrorOutput());

        $backup->log = $output;

        if ($process->isSuccessful()) {
            // try to read size from meta or folder
            $backup->status = 'success';
            $backup->error_code = null;

            // naive size reading via PHP (ok for small meta)
            $metaFile = $path . '/meta.json';
            if (is_file($metaFile)) {
                $meta = json_decode(file_get_contents($metaFile), true);
                if (is_array($meta) && isset($meta['size'])) {
                    $backup->size_bytes = (int)$meta['size'];
                }
            }
        } else {
            $backup->status = 'failed';
            $backup->error_code = (string)$process->getExitCode();
        }

        $backup->save();
    }
}
