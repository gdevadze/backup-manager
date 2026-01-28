@extends('layouts.app')

@section('content')
    <h4 class="fw-bold mb-3">
        {{ $backup->server->name }} — {{ strtoupper($backup->type) }}
    </h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <b>Status:</b>
                    <span class="badge bg-{{ $backup->status === 'success' ? 'success' : 'danger' }}">
                    {{ $backup->status }}
                </span>
                </div>
                <div class="col-md-6 text-end">
                    @if($backup->status === 'success')
                        @if($backup->type === 'files')
                            <a href="{{ route('backups.download', [$backup, 'files']) }}"
                               class="btn btn-sm btn-primary">
                                ⬇ Download Files
                            </a>
                        @endif

                        @if($backup->type === 'db')
                            <a href="{{ route('backups.download', [$backup, 'db']) }}"
                               class="btn btn-sm btn-success">
                                ⬇ Download Database
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <b>Path:</b>
                <code>{{ $backup->path }}</code>
            </div>

            <label class="form-label fw-bold">Log output</label>
            <pre class="bg-dark text-light p-3 rounded"
                 style="max-height:500px; overflow:auto">{{ $backup->log }}</pre>
        </div>
    </div>
@endsection
