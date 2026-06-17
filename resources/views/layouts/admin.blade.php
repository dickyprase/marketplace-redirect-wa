<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ \App\Models\Setting::get('site_name', config('app.name')) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 250px;
            --primary: #4361ee;
            --primary-light: #eef0ff;
            --sidebar-bg: #1e293b;
            --sidebar-active: #4361ee;
            --body-bg: #f1f5f9;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: var(--body-bg); }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: transform .3s ease;
            overflow-y: auto;
            display: flex; flex-direction: column;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.2rem; font-weight: 700; color: #fff;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-brand a { color: #fff; text-decoration: none; }
        .sidebar-nav { flex: 1; padding: 1rem 0; }
        .sidebar-nav .nav-section {
            padding: .5rem 1.5rem .25rem;
            font-size: .7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: rgba(255,255,255,.35);
        }
        .sidebar-nav .nav-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem 1.5rem;
            color: rgba(255,255,255,.65);
            font-size: .875rem; font-weight: 500;
            text-decoration: none; transition: all .15s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-nav .nav-link:hover { color: #fff; background: rgba(255,255,255,.05); }
        .sidebar-nav .nav-link.active {
            color: #fff; background: rgba(67,97,238,.15);
            border-left-color: var(--sidebar-active);
        }
        .sidebar-nav .nav-link.active i { color: var(--sidebar-active); }

        /* Main */
        .main { margin-left: var(--sidebar-w); min-height: 100vh; transition: margin .3s ease; }
        .topbar {
            background: #fff; padding: .75rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            position: sticky; top: 0; z-index: 1040;
        }
        .topbar .page-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0; }
        .burger { display: none; background: none; border: none; font-size: 1.4rem; color: #475569; cursor: pointer; }
        .content { padding: 1.5rem; }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--primary); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; font-weight: 700;
        }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.4); z-index: 1045;
        }
        .sidebar-overlay.show { display: block; }

        /* Cards */
        .card { border: none; border-radius: .75rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: .75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .stat-card .stat-icon.purple { background: #ede7f6; color: #7c3aed; }
        .stat-card .stat-icon.blue { background: #e0f2fe; color: #0284c7; }
        .stat-card .stat-icon.green { background: #dcfce7; color: #16a34a; }
        .stat-card .stat-icon.red { background: #fef2f2; color: #dc2626; }
        .stat-card .stat-icon.amber { background: #fef3c7; color: #d97706; }

        /* Table */
        .table { font-size: .875rem; }
        .table th {
            font-weight: 600; color: #64748b; text-transform: uppercase;
            font-size: .75rem; letter-spacing: .03em;
            background: #f8fafc; border-bottom: 2px solid #e2e8f0;
        }
        .table td { vertical-align: middle; }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main { margin-left: 0; }
            .burger { display: block; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">⚡ {{ \App\Models\Setting::get('site_name', config('app.name')) }}</a>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Menu</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section">Data</div>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Produk
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-bookmark-fill"></i> Kategori
            </a>
            <a href="{{ route('admin.tags.index') }}" class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i> Tag
            </a>
            <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> Banner
            </a>

            <div class="nav-section">Pengaturan</div>
            <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> WA Settings
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-fill"></i> Profil
            </a>
            <a href="{{ url('/') }}" class="nav-link" target="_blank">
                <i class="bi bi-shop"></i> Lihat Toko
            </a>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </nav>
    </aside>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Main --}}
    <div class="main">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="burger" id="burgerBtn"><i class="bi bi-list"></i></button>
                <h1 class="page-title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                    <span class="ms-2 fw-semibold d-none d-md-inline text-dark small">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-left me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        document.getElementById('burgerBtn')?.addEventListener('click', () => {
            sidebar.classList.add('show'); overlay.classList.add('show');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('show'); overlay.classList.remove('show');
        });
    </script>
    @stack('scripts')
</body>
</html>
