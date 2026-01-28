<?php

namespace App\Http\Controllers;

use App\Models\BackupDatabase;
use App\Models\BackupServer;
use Illuminate\Http\Request;

class BackupDatabaseController extends Controller
{
    public function index(BackupServer $server)
    {
        $server->load('databases');
        return view('servers.databases', compact('server'));
    }

    public function store(Request $request, BackupServer $server)
    {
        $data = $request->validate([
            'name'        => 'required|string',
            'db_host'     => 'required|string',
            'db_port'     => 'required|integer',
            'db_name'     => 'required|string',
            'db_user'     => 'required|string',
            'db_password' => 'required|string',
        ]);

        $server->databases()->create($data);

        return back()->with('ok', 'Database added');
    }

    public function toggle(BackupDatabase $database)
    {
        $database->update(['is_active' => !$database->is_active]);
        return back();
    }

    public function destroy(BackupDatabase $database)
    {
        $database->delete();
        return back();
    }
}
