<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Illuminate\Http\Request;

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
}
