@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <h4 class="fw-bold mb-0">Servers</h4>
            <small class="text-muted">Manage backup targets</small>
        </div>
        <a href="{{ route('servers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Server
        </a>
    </div>

    <div class="card shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
            <tr>
                <th>Server</th>
                <th>Host</th>
                <th>Status</th>
                <th>Jobs</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($servers as $s)
                <tr>
                    <td class="fw-semibold">{{ $s->name }}</td>
                    <td>{{ $s->ssh_user }}@ {{ $s->host }}:{{ $s->ssh_port }}</td>
                    <td>
                        @if($s->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Disabled</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-dark">{{ $s->jobs_count }}</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('servers.databases', $s) }}" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-clock"></i>
                        </a>
                        <a href="{{ route('servers.jobs', $s) }}" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-clock"></i>
                        </a>
                        <a href="{{ route('servers.edit', $s) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-gear"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
