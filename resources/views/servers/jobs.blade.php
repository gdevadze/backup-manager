@extends('layouts.app')

@section('content')
    <h4 class="fw-bold mb-3">
        {{ $server->name }} — Backup Jobs
    </h4>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('servers.jobs.store', $server) }}" class="row g-3">
                @csrf

                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="files">Files</option>
                        <option value="db">Database</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cron Expression</label>
                    <input type="text"
                           name="cron"
                           class="form-control"
                           placeholder="0 */2 * * *"
                           required>
                    <div class="form-text">
                        Examples:
                        <code>0 * * * *</code> hourly,
                        <code>0 */2 * * *</code> every 2 hours,
                        <code>0 2 * * *</code> daily
                    </div>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle"></i> Add Job
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
            <tr>
                <th>Type</th>
                <th>Cron</th>
                <th>Next Run</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($server->jobs as $job)
                <tr>
                    <td>
                    <span class="badge bg-{{ $job->type === 'db' ? 'primary' : 'secondary' }}">
                        {{ strtoupper($job->type) }}
                    </span>
                    </td>
                    <td><code>{{ $job->cron }}</code></td>
                    <td class="text-muted">
                        {{ \Cron\CronExpression::factory($job->cron)->getNextRunDate()->format('Y-m-d H:i') }}
                    </td>
                    <td>
                        @if($job->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Disabled</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <small class="text-muted">
            Examples:
            <code>0 * * * *</code> hourly,
            <code>0 */2 * * *</code> every 2h,
            <code>0 2 * * *</code> daily
        </small>
    </div>
@endsection
