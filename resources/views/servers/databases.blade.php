@extends('layouts.app')

@section('title', $server->name . ' — Databases')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">{{ $server->name }} — Databases</h4>
            <small class="text-muted">Manage server databases</small>
        </div>
    </div>

    {{-- ADD DATABASE --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('servers.databases.store', $server) }}" class="row g-3">
                @csrf
                <div class="col-md-2">
                    <input class="form-control" name="name" placeholder="Label (main_db)">
                </div>
                <div class="col-md-2">
                    <input class="form-control" name="db_host" value="localhost">
                </div>
                <div class="col-md-1">
                    <input class="form-control" name="db_port" value="3306">
                </div>
                <div class="col-md-2">
                    <input class="form-control" name="db_name" placeholder="Database">
                </div>
                <div class="col-md-2">
                    <input class="form-control" name="db_user" placeholder="User">
                </div>
                <div class="col-md-2">
                    <input class="form-control" name="db_password" placeholder="Password">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- LIST DATABASES --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Label</th>
                    <th>DB</th>
                    <th>User</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($server->databases as $db)
                    <tr>
                        <td class="fw-semibold">{{ $db->name }}</td>
                        <td>{{ "{$db->db_name}@{$db->db_host}" }}</td>
                        <td>{{ $db->db_user }}</td>
                        <td>
                        <span class="badge rounded-pill bg-{{ $db->is_active ? 'success' : 'secondary' }}">
                            {{ $db->is_active ? 'Active' : 'Disabled' }}
                        </span>
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('databases.toggle', $db) }}" class="d-inline">@csrf
                                <button class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-toggle-on"></i> Toggle
                                </button>
                            </form>
                            <form method="POST" action="{{ route('databases.destroy', $db) }}" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No databases added yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
