@extends('layouts.app')

@section('content')
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Servers</div>
                    <div class="fs-3 fw-bold">{{ $servers }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Active jobs</div>
                    <div class="fs-3 fw-bold">{{ $jobs }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Backups today</div>
                    <div class="fs-3 fw-bold">{{ $today }}</div>
                </div>
            </div>
        </div>

    </div>
@endsection
