@extends('layouts.app')

@section('title', 'Servers')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Servers</h4>
            <small class="text-muted">Manage backup targets</small>
        </div>
        <a href="{{ route('servers.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
            <i class="bi bi-plus me-1"></i> Add Server
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                        <td>{{ "{$s->ssh_user}@{$s->host}:{$s->ssh_port}" }}</td>
                        <td>
                            @if($s->is_active)
                                <span class="badge bg-success rounded-pill">Active</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">Disabled</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-dark rounded-pill">{{ $s->jobs_count }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('servers.databases', $s) }}" class="btn btn-sm btn-outline-dark me-1" title="Databases">
                                <i class="bi bi-server"></i>
                            </a>
                            <a href="{{ route('servers.jobs', $s) }}" class="btn btn-sm btn-outline-dark me-1" title="Jobs">
                                <i class="bi bi-clock-history"></i>
                            </a>
                            <a href="{{ route('servers.edit', $s) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-gear"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                @if($servers->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No servers added yet.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
