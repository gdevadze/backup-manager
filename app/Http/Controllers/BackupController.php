<?php

namespace App\Http\Controllers;

use App\Jobs\RunBackupJob;
use App\Models\Backup;
use App\Models\BackupServer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        $servers = BackupServer::query()->orderBy('name')->get();

        $query = Backup::query()->with('server')->latest('backup_date');

        if ($request->filled('server_id')) {
            $query->where('backup_server_id', $request->integer('server_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('date')) {
            $query->where('backup_date', $request->string('date'));
        }

        $backups = $query->paginate(20)->withQueryString();

        return view('backups.index', compact('backups','servers'));
    }

    public function runNow(BackupServer $server)
    {
        if (!$server->is_active) {
            return back()->with('err', 'Server disabled');
        }

        $date = Carbon::now()->format('Y-m-d');

        RunBackupJob::dispatch($server->id, $date);

        return back()->with('ok', "Backup queued for {$server->name} ({$date})");
    }

    public function show(Backup $backup)
    {
        $backup->load('server');
        return view('backups.show', compact('backup'));
    }

    public function download(Backup $backup, Request $request): BinaryFileResponse
    {
        $type = $request->get('type'); // files|db|meta|log
        $base = rtrim($backup->path, '/');

        $file = match ($type) {
            'files' => $base.'/files.tar.gz',
            'db' => $base.'/db.sql.gz',
            'meta' => $base.'/meta.json',
            'log' => $base.'/run.log',
            default => abort(404),
        };

        abort_if(!is_file($file), 404);

        return response()->download($file);
    }
}
