@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Servers</h4>
            <small class="text-muted">Manage remote servers for backups</small>
        </div>
        <a href="{{ route('servers.create') }}" class="btn btn-primary">+ Add Server</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Host</th>
                    <th>SSH</th>
                    <th>Path</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($servers as $s)
                    <tr>
                        <td class="fw-semibold">{{ $s->name }}</td>
                        <td>{{ $s->host }}</td>
                        <td>{{ $s->ssh_user }}:{{ $s->ssh_port }}</td>
                        <td><code>{{ $s->remote_path }}</code></td>
                        <td>
                            @if($s->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Disabled</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <form method="post" action="{{ route('backups.run', $s) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Run now</button>
                            </form>
                            <a href="{{ route('servers.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="post" action="{{ route('servers.destroy', $s) }}" class="d-inline"
                                  onsubmit="return confirm('Delete server?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No servers yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $servers->links() }}
    </div>
@endsection
