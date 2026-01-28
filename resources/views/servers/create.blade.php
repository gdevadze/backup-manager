@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Add Server</h5>
            <form method="post" action="{{ route('servers.store') }}">
                @include('servers._form')
            </form>
        </div>
    </div>
@endsection
