<?php

namespace App\Jobs;

use App\Models\Backup;
use App\Models\BackupServer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Throwable;
use App\Services\TelegramService;

class RunBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;
    public $tries = 1;

    private int $serverId;
    private string $type;

    public function __construct(int $serverId, string $type)
    {
        $this->serverId = $serverId;
        $this->type = $type;
    }

    public function handle(): void
    {
        date_default_timezone_set('Asia/Tbilisi');
        $now = now('Asia/Tbilisi');

        $server = BackupServer::with('databases')->findOrFail($this->serverId);

        $date = $now->format('Y-m-d');
        $basePath = "/backups/{$server->name}/{$this->type}/{$date}";
        $env = ['TZ' => 'Asia/Tbilisi'];

        /* ================= FILES BACKUP ================= */
        if ($this->type === 'files') {

            if (!is_dir($basePath)) {
                mkdir($basePath, 0755, true);
            }

            $backup = Backup::create([
                'backup_server_id' => $server->id,
                'type'             => 'files',
                'database_name'    => null,
                'backup_date'      => $now,
                'path'             => $basePath,
                'status'           => 'running',
            ]);

            $fullLog = '';

            try {

                $process = new Process([
                    '/opt/backup/run_backup.sh',
                    $server->name,
                    $server->host,
                    $server->ssh_user,
                    $server->ssh_port,
                    $server->remote_path,
                    $server->exclude_args ?? '',
                    'files',
                ], null, $env);

                $process->setTimeout(null);
                $process->run();

                $fullLog .= $process->getOutput() . $process->getErrorOutput();

                if (!$process->isSuccessful()) {
                    throw new \RuntimeException('Files backup failed');
                }

                $backup->update([
                    'status'      => 'success',
                    'log'         => trim($fullLog),
                    'size'        => $this->calculateSize($basePath),
                    'finished_at' => now('Asia/Tbilisi'),
                ]);

            } catch (Throwable $e) {

                $backup->update([
                    'status'      => 'failed',
                    'log'         => trim($fullLog . "\n\nERROR: " . $e->getMessage()),
                    'finished_at' => now('Asia/Tbilisi'),
                ]);

                $this->notifyFailure($backup, $e->getMessage());
                throw $e;
            }

            return;
        }

        /* ================= DB BACKUP ================= */
        if ($this->type === 'db') {

            $databases = $server->databases->where('is_active', true);

            if ($databases->isEmpty()) {
                throw new \RuntimeException('No active databases configured');
            }

            foreach ($databases as $db) {

                $dateNow = Carbon::now('Asia/Tbilisi')->format('Y-m-d-H-i');
                $dbPath = "{$basePath}/{$db->db_name}";
                $filename = "{$dbPath}/{$db->db_name}_{$dateNow}.sql.gz";

                if (!is_dir($dbPath)) {
                    mkdir($dbPath, 0755, true);
                }

                $backup = Backup::create([
                    'backup_server_id' => $server->id,
                    'type'             => 'db',
                    'database_name'    => $db->db_name, // 🔥 მნიშვნელოვანი
                    'backup_date'      => now('Asia/Tbilisi'),
                    'path'             => $dbPath,
                    'status'           => 'running',
                ]);

                $fullLog = "=== DB {$db->db_name} START @ {$dateNow} ===\n";

                try {

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
                    ], null, $env);

                    $process->setTimeout(null);
                    $process->run();

                    $fullLog .= $process->getOutput() . $process->getErrorOutput();

                    if (!$process->isSuccessful()) {
                        throw new \RuntimeException("DB {$db->db_name} backup failed");
                    }

                    $fullLog .= "\n=== DB {$db->db_name} DONE ===\n";

                    $backup->update([
                        'status'      => 'success',
                        'log'         => trim($fullLog),
                        'size'        => file_exists($filename) ? filesize($filename) : null,
                        'finished_at' => now('Asia/Tbilisi'),
                    ]);

                } catch (Throwable $e) {

                    $backup->update([
                        'status'      => 'failed',
                        'log'         => trim($fullLog . "\n\nERROR: " . $e->getMessage()),
                        'finished_at' => now('Asia/Tbilisi'),
                    ]);

                    $this->notifyFailure($backup, $e->getMessage());
                }
            }
        }
    }

    private function calculateSize(string $path): ?int
    {
        if (!is_dir($path)) {
            return null;
        }

        $size = 0;

        foreach (glob("$path/*.{gz,sql,tar.gz}", GLOB_BRACE) as $file) {
            if (file_exists($file)) {
                $size += filesize($file);
            }
        }

        return $size ?: null;
    }

    private function notifyFailure(Backup $backup, string $error): void
    {
        TelegramService::send(
            "🚨 <b>Backup FAILED</b>\n\n"
            . "🖥 Server: <b>{$backup->server->name}</b>\n"
            . "📦 Backup ID: <b>#{$backup->id}</b>\n"
            . "🗄 Database: <b>{$backup->database_name}</b>\n"
            . "🕒 Time: " . now()->format('Y-m-d H:i:s') . "\n\n"
            . "<pre>{$error}</pre>"
        );
    }
}
