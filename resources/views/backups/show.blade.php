@extends('layouts.app')

@section('content')
    <h4 class="fw-bold mb-4">
        <i class="bi bi-hdd-network me-2"></i>
        {{ $backup->server->name }} — <span class="text-uppercase">{{ $backup->type }} (@if($backup->type == 'db'){{ $backup->database_name }}@endif)</span>
    </h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <b>Status:</b>
                    @switch($backup->status)
                        @case('success')
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Success</span>
                            @break
                        @case('failed')
                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Failed</span>
                            @break
                        @default
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Running</span>
                    @endswitch
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    @if($backup->status === 'success')
                        @if($backup->type === 'files')
                            <a href="{{ route('backups.download', [$backup, 'files']) }}" class="btn btn-sm btn-primary me-1">
                                <i class="bi bi-download me-1"></i> Download Files
                            </a>
                        @endif

                        @if($backup->type === 'db')
                            <a href="{{ route('backups.download', [$backup, 'db']) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-database me-1"></i> Download Database
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <b>Path:</b>
                    <div class="text-truncate" style="max-width: 100%;">
                        <code>{{ $backup->path }}</code>
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <b>Size:</b>
                    <span class="badge bg-info">{{ $backup->size_human }}</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Log output</label>
                <pre class="bg-dark text-light p-3 rounded shadow-sm"
                     style="max-height:500px; overflow:auto; font-size:0.875rem;">
{{ $backup->log }}
            </pre>
            </div>
        </div>
    </div>
@endsection
