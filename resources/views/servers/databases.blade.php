@extends('layouts.app')

@section('content')
    <h4 class="fw-bold mb-3">{{ $server->name }} — Databases</h4>

    {{-- ADD DB --}}
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
                    <input class="form-control" name="db_name" placeholder="database">
                </div>
                <div class="col-md-2">
                    <input class="form-control" name="db_user" placeholder="user">
                </div>
                <div class="col-md-2">
                    <input class="form-control" name="db_password" placeholder="password">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- LIST --}}
    <div class="card shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
            <tr>
                <th>Label</th>
                <th>DB</th>
                <th>User</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($server->databases as $db)
                <tr>
                    <td>{{ $db->name }}</td>
                    <td>{{ $db->db_name }}@{{ $db->db_host }}</td>
                    <td>{{ $db->db_user }}</td>
                    <td>
        <span class="badge bg-{{ $db->is_active ? 'success' : 'secondary' }}">
            {{ $db->is_active ? 'Active' : 'Disabled' }}
        </span>
                    </td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('databases.toggle', $db) }}" class="d-inline">@csrf
                            <button class="btn btn-sm btn-outline-warning">Toggle</button>
                        </form>
                        <form method="POST" action="{{ route('databases.destroy', $db) }}" class="d-inline">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
