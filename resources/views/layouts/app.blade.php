<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Backup Manager')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('css')
    <style>
        :root {
            --bg: #f7f8fa;
            --sidebar: #0f172a;
            --panel: #ffffff;
            --border: #e5e7eb;
            --text: #0f172a;
            --muted: #6b7280;
            --accent: #2563eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding-left: 240px;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: var(--sidebar);
            color: #cbd5f5;
            height: 100vh;
            position: fixed;
            inset: 0 auto 0 0;
            padding: 1.5rem;
            transition: transform .25s ease;
            z-index: 1040;
        }

        .brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .sidebar .nav-link {
            color: #cbd5f5;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: -14px;
            top: 8px;
            bottom: 8px;
            width: 4px;
            background: var(--accent);
            border-radius: 4px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        /* Overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.55);
            backdrop-filter: blur(2px);
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
            z-index: 1030;
        }

        .sidebar-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        /* Main */
        .main-content {
            padding: 2rem;
        }

        /* Topbar */
        .topbar {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 6px 18px rgba(0,0,0,.04);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .badge-status {
            background: #ecfdf5;
            color: #065f46;
            border-radius: 999px;
            padding: 6px 14px;
            font-size: .75rem;
            font-weight: 600;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            body {
                padding-left: 0;
            }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: 8px 0 24px rgba(0,0,0,.25);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            body.sidebar-open {
                overflow: hidden;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="brand">🗄 Backup Manager</div>

    <nav class="nav flex-column">
        <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('servers.index') }}" class="nav-link {{ request()->is('servers*') ? 'active' : '' }}">
            <i class="bi bi-hdd-network"></i> Servers
        </a>
        <a href="{{ route('backups.index') }}" class="nav-link {{ request()->is('backups*') ? 'active' : '' }}">
            <i class="bi bi-cloud-arrow-down"></i> Backups
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-gear"></i> Settings
        </a>
    </nav>

    <div class="mt-auto small text-muted position-absolute bottom-0 mb-3">
        © {{ date('Y') }} Backup Manager
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Main -->
<main class="main-content">

    <!-- Topbar -->
    <div class="topbar">
        <div>
            <button class="btn btn-dark d-lg-none me-2" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <strong>@yield('title', 'Dashboard')</strong><br>
            <small class="text-muted">Backup monitoring & management</small>
        </div>

        <div class="d-flex align-items-center gap-3">

            <span class="badge-status d-none d-sm-inline">
                <i class="bi bi-check-circle me-1"></i> System OK
            </span>

            <div class="dropdown">
                <button class="btn btn-light d-flex align-items-center gap-2 dropdown-toggle"
                        data-bs-toggle="dropdown">
                    <div class="user-avatar">G</div>
                    <div class="text-start d-none d-md-block">
                        <div class="fw-semibold">გიო</div>
                        <small class="text-muted">giodevadze01@gmail.com</small>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="#">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Alerts -->
    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    @if(session('err'))
        <div class="alert alert-danger">{{ session('err') }}</div>
    @endif

    <!-- Content -->
    <div class="card p-4">
        @yield('content')
    </div>

</main>
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    toggle?.addEventListener('click', () => {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.classList.add('sidebar-open');
    });

    overlay.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', e => e.key === 'Escape' && closeSidebar());

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.classList.remove('sidebar-open');
    }
</script>

</body>
</html>
