<?php

namespace App\Http\Controllers;

use App\Models\BackupServer;
use Illuminate\Http\Request;

class BackupServerController extends Controller
{
    public function index()
    {
        $servers = BackupServer::query()
            ->withCount('backups')
            ->latest()
            ->paginate(15);

        return view('servers.index', compact('servers'));
    }

    public function create()
    {
        return view('servers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80','unique:backup_servers,name'],
            'host' => ['required','string','max:255'],
            'ssh_user' => ['required','string','max:120'],
            'ssh_port' => ['required','integer','min:1','max:65535'],
            'remote_path' => ['required','string','max:255'],
            'exclude_args' => ['nullable','string'],

            'db_name' => ['nullable','string','max:120'],
            'db_user' => ['nullable','string','max:120'],
            'db_password' => ['nullable','string','max:255'],

            'is_active' => ['nullable','boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        BackupServer::create($data);

        return redirect()->route('servers.index')->with('ok', 'Server added');
    }

    public function edit(BackupServer $server)
    {
        return view('servers.edit', compact('server'));
    }

    public function update(Request $request, BackupServer $server)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80','unique:backup_servers,name,'.$server->id],
            'host' => ['required','string','max:255'],
            'ssh_user' => ['required','string','max:120'],
            'ssh_port' => ['required','integer','min:1','max:65535'],
            'remote_path' => ['required','string','max:255'],
            'exclude_args' => ['nullable','string'],

            'db_name' => ['nullable','string','max:120'],
            'db_user' => ['nullable','string','max:120'],
            'db_password' => ['nullable','string','max:255'],

            'is_active' => ['nullable','boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        // თუ პაროლი ცარიელია და არ გინდა შეცვლა:
        if (empty($data['db_password'])) {
            unset($data['db_password']);
        }

        $server->update($data);

        return redirect()->route('servers.index')->with('ok', 'Server updated');
    }

    public function destroy(BackupServer $server)
    {
        $server->delete();
        return redirect()->route('servers.index')->with('ok', 'Server deleted');
    }
}
