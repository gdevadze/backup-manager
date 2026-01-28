<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use App\Models\BackupJob;
use App\Models\BackupServer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'servers' => BackupServer::count(),
            'jobs'    => BackupJob::where('is_active', true)->count(),
            'today'   => Backup::whereDate('created_at', today())->count(),
        ]);
    }

}
