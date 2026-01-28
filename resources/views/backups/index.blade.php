@extends('layouts.app')

@section('content')
    <h4 class="fw-bold mb-3">Backups</h4>

    <div class="card shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Server</th>
                <th>Type</th>
                <th>Status</th>
                <th class="text-end">View</th>
            </tr>
            </thead>
            <tbody>
            @foreach($backups as $b)
                <tr>
                    <td>{{ $b->backup_date }}</td>
                    <td>{{ $b->server->name }}</td>
                    <td>
                    <span class="badge bg-{{ $b->type === 'db' ? 'primary' : 'secondary' }}">
                        {{ strtoupper($b->type) }}
                    </span>
                    </td>
                    <td>
                        @if($b->status === 'success')
                            <span class="badge bg-success">Success</span>
                        @elseif($b->status === 'failed')
                            <span class="badge bg-danger">Failed</span>
                        @else
                            <span class="badge bg-warning text-dark">Running</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('backups.show', $b) }}" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
