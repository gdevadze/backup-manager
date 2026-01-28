@extends('layouts.app')

@section('content')
    <h4 class="fw-bold mb-3">
        {{ $backup->server->name }} — {{ strtoupper($backup->type) }}
    </h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-2">
                <b>Status:</b> {{ $backup->status }}
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
