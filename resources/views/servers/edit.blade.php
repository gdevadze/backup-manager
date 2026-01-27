@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Edit Server: {{ $server->name }}</h5>
            <form method="post" action="{{ route('servers.update', $server) }}">
                @method('PUT')
                @include('servers._form', ['server' => $server])
            </form>
        </div>
    </div>
@endsection
