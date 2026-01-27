@extends('layouts.app')

@section('content')
    <div class="mb-3">
        <h4 class="fw-bold mb-0">Backups</h4>
        <small class="text-muted">View backup history and logs</small>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Server</label>
                    <select name="server_id" class="form-select">
                        <option value="">All</option>
                        @foreach($servers as $s)
                            <option value="{{ $s->id }}" @selected(request('server_id')==$s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        @foreach(['running','success','failed'] as $st)
                            <option value="{{ $st }}" @selected(request('status')===$st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Server</th>
                    <th>Status</th>
                    <th>Size</th>
                    <th>Path</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($backups as $b)
                    <tr>
                        <td class="fw-semibold">{{ $b->backup_date->format('Y-m-d') }}</td>
                        <td>{{ $b->server->name }}</td>
                        <td>
                            @if($b->status==='success')
                                <span class="badge bg-success">success</span>
                            @elseif($b->status==='failed')
                                <span class="badge bg-danger">failed</span>
                            @else
                                <span class="badge bg-warning text-dark">running</span>
                            @endif
                        </td>
                        <td>
                            @if($b->size_bytes)
                                {{ number_format($b->size_bytes/1024/1024, 2) }} MB
                            @else
                                —
                            @endif
                        </td>
                        <td><code>{{ $b->path }}</code></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-dark" href="{{ route('backups.show', $b) }}">View</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('backups.download', [$b,'type'=>'files']) }}">Files</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('backups.download', [$b,'type'=>'db']) }}">DB</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('backups.download', [$b,'type'=>'log']) }}">Log</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No backups found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $backups->links() }}
    </div>
@endsection
