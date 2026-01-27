@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Backup: {{ $backup->server->name }} / {{ $backup->backup_date->format('Y-m-d') }}</h4>
            <small class="text-muted">Status: {{ $backup->status }} @if($backup->error_code) (exit {{ $backup->error_code }}) @endif</small>
        </div>
        <a href="{{ route('backups.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-2"><b>Path:</b> <code>{{ $backup->path }}</code></div>
            <div class="mb-3">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('backups.download', [$backup,'type'=>'files']) }}">Download Files</a>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('backups.download', [$backup,'type'=>'db']) }}">Download DB</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('backups.download', [$backup,'type'=>'meta']) }}">meta.json</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('backups.download', [$backup,'type'=>'log']) }}">run.log</a>
            </div>

            <label class="form-label fw-bold">Job output (DB log)</label>
            <pre class="bg-dark text-light p-3 rounded" style="max-height:520px; overflow:auto; white-space:pre-wrap;">{{ $backup->log ?? '—' }}</pre>
        </div>
    </div>
@endsection
