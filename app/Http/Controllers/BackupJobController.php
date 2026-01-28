<?php

namespace App\Http\Controllers;

use App\Models\BackupJob;
use App\Models\BackupServer;
use Illuminate\Http\Request;

class BackupJobController extends Controller
{
    public function index(BackupServer $server)
    {
        $server->load('jobs');
        return view('servers.jobs', compact('server'));
    }

    public function store(Request $request, BackupServer $server)
    {
        $data = $request->validate([
            'type' => 'required|in:files,db',
            'cron' => 'required|string',
        ]);

        BackupJob::updateOrCreate(
            [
                'backup_server_id' => $server->id,
                'type'             => $data['type'],
            ],
            [
                'cron'      => $data['cron'],
                'is_active' => true,
            ]
        );

        return back()->with('ok', 'Backup job saved');
    }

    public function toggle(BackupJob $job)
    {
        $job->update(['is_active' => !$job->is_active]);

        return back()->with('ok', 'Job status updated');
    }

    public function destroy(BackupJob $job)
    {
        $job->delete();
        return back()->with('ok', 'Job deleted');
    }
}
