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
        $basePath = $backup->path;

        if ($file === 'files') {
            $filePath = $basePath . '/files.tar.gz';
            $downloadName = $backup->server->name . '_files_' . $backup->backup_date . '.tar.gz';
        } elseif ($file === 'db') {
            $filePath = $basePath . '/db.sql.gz';
            $downloadName = $backup->server->name . '_db_' . $backup->backup_date . '.sql.gz';
        } else {
            abort(404);
        }

        if (!file_exists($filePath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($filePath, $downloadName);
    }
}
