<?php

namespace App\Http\Controllers;

use App\Models\BackupServer;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        try {
            // 🔐 SSH test BEFORE save
            $this->testSshConnection(
                $data['host'],
                $data['ssh_user'],
                $data['ssh_port']
            );
        } catch (\Exception $e) {
            return back()->withErrors([
                'ssh' => 'SSH connection failed: ' . $e->getMessage()
            ]);
        }
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

    private function testSshConnection($host, $user, $port)
    {
        $key = '/opt/backup/id_backup';

        $process = new Process([
            'ssh',
            '-i', $key,
            '-p', $port,
            '-o', 'BatchMode=yes',
            '-o', 'StrictHostKeyChecking=no',
            '-o', 'UserKnownHostsFile=/dev/null',
            "{$user}@{$host}",
            'echo SSH_OK'
        ]);

        $process->setTimeout(10);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \Exception(
                "SSH connection failed: " . $process->getErrorOutput()
            );
        }

        if (! str_contains($process->getOutput(), 'SSH_OK')) {
            throw new \Exception('SSH connected but unexpected response');
        }
    }
}
