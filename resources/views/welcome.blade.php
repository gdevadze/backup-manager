<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Backup Manager — Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f7f8fa;
            --panel: #ffffff;
            --accent: #2563eb;
            --text: #0f172a;
            --muted: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: var(--panel);
            border-radius: 16px;
            box-shadow: 0 12px 28px rgba(0,0,0,.08);
            padding: 2rem;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header .user-avatar {
            width: 60px;
            height: 60px;
            margin: 0 auto 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #fff;
            font-size: 1.5rem;
        }

        .login-header h4 {
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .login-header small {
            color: var(--muted);
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(37,99,235,.25);
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: #1e40af;
            border-color: #1e40af;
        }

        .text-center a {
            color: var(--accent);
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="login-header">
        <div class="user-avatar">
            <i class="bi bi-lock-fill"></i>
        </div>
        <h4>Welcome to Backup Manager</h4>
        <small>Please login to your account</small>
    </div>

    <form method="POST" action="#">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   placeholder="you@example.com">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   required
                   placeholder="••••••••">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>

        <div class="text-center">
            <small>
                Don't have an account? <a href="#">Sign up</a>
            </small>
        </div>
    </form>
</div>

</body>
</html>
