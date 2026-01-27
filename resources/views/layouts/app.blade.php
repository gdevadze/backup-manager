<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Backup Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('servers.index') }}">Backup Manager</a>
        <div class="navbar-nav">
            <a class="nav-link" href="{{ route('servers.index') }}">Servers</a>
            <a class="nav-link" href="{{ route('backups.index') }}">Backups</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif
    @if(session('err'))
        <div class="alert alert-danger">{{ session('err') }}</div>
    @endif

    @yield('content')
</div>

</body>
</html>
