<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::with('server')
            ->latest()
            ->paginate(20);

        return view('backups.index', compact('backups'));
    }

    public function show(Backup $backup)
    {
        $backup->load('server');
        return view('backups.show', compact('backup'));
    }

    public function download(Backup $backup, string $file): BinaryFileResponse
    {
        $path = $backup->path;

        if ($backup->type === 'files') {
            $file = $path . '/files.tar.gz';
        } else { // db
            $files = glob($path . '/*.sql.gz');

            if (empty($files)) {
                abort(404, 'Database backup file not found');
            }

            // თუ ერთია — ავიღოთ პირველი
            $file = $files[0];
        }

        if (!file_exists($file)) {
            abort(404, 'Backup file not found');
        }


        return response()->download($file, basename($file));
    }
}
