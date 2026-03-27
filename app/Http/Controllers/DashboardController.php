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
        $recentBackups = Backup::with('server')
            ->latest()
            ->take(10)
            ->get();

        $chartLabels = collect(range(6,0))->map(fn($i) => now()->subDays($i)->format('D'));

        $chartSuccess = collect(range(6,0))->map(fn($i) =>
        Backup::whereDate('created_at', now()->subDays($i))
            ->where('status','success')->count()
        );

        $chartFailed = collect(range(6,0))->map(fn($i) =>
        Backup::whereDate('created_at', now()->subDays($i))
            ->where('status','failed')->count()
        );

        return view('dashboard', [
            'servers' => BackupServer::count(),
            'jobs' => Backup::where('status','running')->count(),
            'today' => Backup::whereDate('created_at', today())->count(),
            'failed' => Backup::whereDate('created_at', today())->where('status','failed')->count(),
            'recentBackups' => $recentBackups,
            'chart' => [
                'labels' => $chartLabels,
                'success' => $chartSuccess,
                'failed' => $chartFailed
            ]
        ]);
    }

}
