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
use Throwable;

class RunBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;
    public $tries = 1;

    public function __construct(
        public int $serverId,
        public string $type // files | db
    ) {}

    public function handle(): void
    {
        $server = BackupServer::with('databases')->findOrFail($this->serverId);
        $date   = now()->format('Y-m-d');

        $path = "/backups/{$server->name}/{$this->type}/{$date}";

        $backup = Backup::create([
            'backup_server_id' => $server->id,
            'type'             => $this->type,
            'backup_date'      => $date,
            'path'             => $path,
            'status'           => 'running',
        ]);

        $fullLog = '';

        try {

            /* ================= FILES BACKUP ================= */
            if ($this->type === 'files') {

                $process = new Process([
                    '/opt/backup/run_backup.sh',
                    $server->name,
                    $server->host,
                    $server->ssh_user,
                    $server->ssh_port,
                    $server->remote_path,
                    $server->exclude_args ?? '',
                    'files',
                ]);

                $process->setTimeout(null);
                $process->run();

                $fullLog .= $process->getOutput();
                if ($process->getErrorOutput()) {
                    $fullLog .= "\n--- STDERR ---\n" . $process->getErrorOutput();
                }

                if (!$process->isSuccessful()) {
                    throw new \RuntimeException('Files backup failed');
                }
            }

            /* ================= DATABASE BACKUP ================= */
            if ($this->type === 'db') {

                $databases = $server->databases->where('is_active', true);

                if ($databases->isEmpty()) {
                    throw new \RuntimeException('No active databases configured');
                }

                foreach ($databases as $db) {

                    $filename = "{$db->name}_{$date}.sql.gz";

                    $fullLog .= "\n\n=== DB: {$db->name} START ===\n";

                    $process = new Process([
                        '/opt/backup/run_db_backup.sh',
                        $server->host,
                        $server->ssh_user,
                        $server->ssh_port,
                        $db->db_host,
                        $db->db_port,
                        $db->db_name,
                        $db->db_user,
                        $db->db_password,
                        $filename,
                    ]);

                    $process->setTimeout(null);
                    $process->run();

                    $fullLog .= $process->getOutput();

                    if ($process->getErrorOutput()) {
                        $fullLog .= "\n--- STDERR ---\n" . $process->getErrorOutput();
                    }

                    if (!$process->isSuccessful()) {
                        $fullLog .= "\n!!! DB {$db->name} FAILED !!!\n";
                    } else {
                        $fullLog .= "\n=== DB {$db->name} DONE ===\n";
                    }
                }
            }

            $backup->status = 'success';

        } catch (Throwable $e) {

            $backup->status = 'failed';
            $fullLog .= "\n\nEXCEPTION:\n" . $e->getMessage()
                . "\n\n" . $e->getTraceAsString();

        } finally {

            $backup->log = trim($fullLog);
            $backup->finished_at = now();
            $backup->save();
        }
    }
}
