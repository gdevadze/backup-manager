@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Add Server</h5>
            {{-- ❌ Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($errors->has('ssh'))
                <div class="alert alert-danger">
                    <b>SSH Error:</b> {{ $errors->first('ssh') }}

                    <hr>

                    <p class="mb-1">
                        You can manually test SSH connection using password:
                    </p>

                    <div class="d-flex gap-2">
                        <button
                                class="btn btn-sm btn-outline-dark"
                                onclick="document.getElementById('sshCmd').classList.toggle('d-none')">
                            Show SSH command
                        </button>

                        <button
                                class="btn btn-sm btn-outline-secondary"
                                onclick="copySSH()">
                            Copy
                        </button>
                    </div>

                    <pre id="sshCmd"
                         class="mt-3 bg-dark text-light p-2 rounded d-none">
ssh -i /opt/backup/id_backup -p {{ old('ssh_port', 22) }} {{ old('ssh_user') }}@{{ old('host') }}
        </pre>
                </div>
            @endif
            
            {{-- ✅ Success --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="post" action="{{ route('servers.store') }}">
                @include('servers._form')
            </form>
        </div>
    </div>
@endsection
