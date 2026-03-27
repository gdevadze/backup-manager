@extends('layouts.app')

@section('title', 'Dashboard')
@push('css')
    <style>
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
    </style>
@endpush
@section('content')

    <!-- STAT CARDS -->
    <div class="row g-4 mb-5">

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Servers</div>
                        <div class="fs-2 fw-bold">{{ $servers }}</div>
                    </div>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-hdd-network"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Active Jobs</div>
                        <div class="fs-2 fw-bold">{{ $jobs }}</div>
                    </div>
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Backups Today</div>
                        <div class="fs-2 fw-bold">{{ $today }}</div>
                    </div>
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-cloud-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">Failed Backups</div>
                        <div class="fs-2 fw-bold text-danger">{{ $failed }}</div>
                    </div>
                    <div class="stat-icon bg-danger-subtle text-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- CHART -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">Backups (Last 7 Days)</h6>
                    <small class="text-muted">Daily completed backups</small>
                </div>
                <canvas id="backupChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- RECENT BACKUPS TABLE -->
    <div class="row">
        <div class="col-12">
            <div class="card p-4 shadow-sm">
                <h6 class="fw-semibold mb-3">Recent Backups</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Server</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Size</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentBackups as $backup)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $backup->server->name }}</td>
                                <td>
                                    @if($backup->status === 'success')
                                        <span class="badge bg-success">Success</span>
                                    @elseif($backup->status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Running</span>
                                    @endif
                                </td>
                                <td>{{ $backup->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $backup->size_human }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('backupChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart['labels']) !!},
                datasets: [{
                    label: 'Successful Backups',
                    data: {!! json_encode($chart['success']) !!},
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.1)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 4
                },{
                    label: 'Failed Backups',
                    data: {!! json_encode($chart['failed']) !!},
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.1)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision:0 } }
                }
            }
        });
    </script>
@endpush
