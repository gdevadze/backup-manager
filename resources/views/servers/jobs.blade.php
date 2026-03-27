@extends('layouts.app')

@section('title', $server->name . ' — Backup Jobs')

@section('content')

    <h4 class="fw-bold mb-3">{{ $server->name }} — Backup Jobs</h4>

    {{-- ADD JOB --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('servers.jobs.store', $server) }}" class="row g-3 align-items-end">
                @csrf

                <!-- Type -->
                <div class="col-12 col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="files">Files</option>
                        <option value="db">Database</option>
                    </select>
                </div>

                <!-- Cron Expression -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Cron Expression</label>
                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <select id="cronSelect" class="form-select">
                                <option value="">Select Schedule</option>
                                <option value="0 * * * *">Hourly (0 * * * *)</option>
                                <option value="0 */2 * * *">Every 2 Hours (0 */2 * * *)</option>
                                <option value="0 2 * * *">Daily at 02:00 (0 2 * * *)</option>
                                <option value="0 0 * * 0">Weekly Sunday 00:00 (0 0 * * 0)</option>
                                <option value="0 0 1 * *">Monthly 1st day 00:00 (0 0 1 * *)</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <input type="text" name="cron" id="cronInput" class="form-control" placeholder="0 */2 * * *" required>
                        </div>
                    </div>

                </div>

                <!-- Add Job Button -->
                <div class="col-12 col-md-3">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle"></i> Add Job
                    </button>
                </div>

            </form>
        </div>
    </div>



    {{-- JOBS LIST --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Cron</th>
                    <th>Next Run</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($server->jobs as $job)
                    <tr>
                        <td>
                        <span class="badge rounded-pill bg-{{ $job->type === 'db' ? 'primary' : 'secondary' }}">
                            {{ strtoupper($job->type) }}
                        </span>
                        </td>
                        <td><code>{{ $job->cron }}</code></td>
                        <td class="text-muted">
                            {{ \Cron\CronExpression::factory($job->cron)->getNextRunDate()->format('d.m.Y H:i') }}
                        </td>
                        <td>
                        <span class="badge rounded-pill bg-{{ $job->is_active ? 'success' : 'secondary' }}">
                            {{ $job->is_active ? 'Active' : 'Disabled' }}
                        </span>
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('jobs.toggle', [$job]) }}">
                                @csrf

                                <button type="submit"
                                        class="btn btn-sm {{ $job->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    @if($job->is_active)
                                        <i class="bi bi-slash-circle me-1"></i> Disable
                                    @else
                                        <i class="bi bi-check-circle me-1"></i> Enable
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No backup jobs added yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <small class="text-muted">
            You can select common schedules from the dropdown above or enter a custom cron expression manually.
        </small>
    </div>

@endsection

@push('scripts')
    <script>
        const cronSelect = document.getElementById('cronSelect');
        const cronInput = document.getElementById('cronInput');

        cronSelect.addEventListener('change', function() {
            if(this.value) cronInput.value = this.value;
        });

        cronInput.addEventListener('input', function() {
            const options = Array.from(cronSelect.options).map(o => o.value);
            if(!options.includes(this.value)) cronSelect.value = '';
        });
    </script>
@endpush
