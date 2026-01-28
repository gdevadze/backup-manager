<?php

namespace App\Http\Controllers;

use App\Models\BackupServer;
use Illuminate\Http\Request;

class BackupServerController extends Controller
{
    public function index()
    {
        $servers = BackupServer::withCount('jobs')->latest()->get();
        return view('servers.index', compact('servers'));
    }

    public function create()
    {
        return view('servers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|unique:backup_servers,name',
            'host'        => 'required|string',
            'ssh_user'    => 'required|string',
            'ssh_port'    => 'required|integer',
            'remote_path' => 'required|string',
            'exclude_args'=> 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        BackupServer::create($data);

        return redirect()
            ->route('servers.index')
            ->with('ok', 'Server created');
    }

    public function edit(BackupServer $server)
    {
        return view('servers.edit', compact('server'));
    }

    public function update(Request $request, BackupServer $server)
    {
        $data = $request->validate([
            'name'        => 'required|string|unique:backup_servers,name,' . $server->id,
            'host'        => 'required|string',
            'ssh_user'    => 'required|string',
            'ssh_port'    => 'required|integer',
            'remote_path' => 'required|string',
            'exclude_args'=> 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $server->update($data);

        return redirect()
            ->route('servers.index')
            ->with('ok', 'Server updated');
    }

    public function destroy(BackupServer $server)
    {
        $server->delete();

        return redirect()
            ->route('servers.index')
            ->with('ok', 'Server deleted');
    }
}
