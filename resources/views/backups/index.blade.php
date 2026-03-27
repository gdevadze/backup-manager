@extends('layouts.app')

@section('content')
    <h4 class="fw-bold mb-3">Backups</h4>

    <div class="card shadow-sm p-3 mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>End Date</th>
                    <th>Server</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Status</th>
                    <th class="text-end">View</th>
                </tr>
                </thead>
                <tbody>
                @foreach($backups as $b)
                    <tr>
                        <td>{{ $b->backup_date->format('d.m.Y H:i') }}</td>
                        <td>{{ $b->finished_at ? $b->finished_at->format('d.m.Y H:i') : '-' }}</td>
                        <td>{{ $b->server->name }} @if($b->type == 'db') - {{ $b->database_name }} @endif</td>
                        <td>
                        <span class="badge bg-{{ $b->type === 'db' ? 'primary' : 'secondary' }}">
                            {{ strtoupper($b->type) }}
                        </span>
                        </td>
                        <td>{{ $b->size_human }}</td>
                        <td>
                            @switch($b->status)
                                @case('success')
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Success</span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Failed</span>
                                    @break
                                @default
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Running</span>
                            @endswitch
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
    </div>
@endsection
