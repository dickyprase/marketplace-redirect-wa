<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #6366f1;
            --sidebar-width: 260px;
        }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1040;
            transition: transform .3s ease;
            overflow-y: auto;
        }
        .admin-sidebar .sidebar-brand {
            padding: 1.25rem 1.5rem;
            display: flex; align-items: center; gap: .75rem;
            text-decoration: none;
        }
        .admin-sidebar .sidebar-brand .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--sidebar-active);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem;
        }
        .admin-sidebar .sidebar-brand span {
            color: #fff; font-weight: 700; font-size: 1.1rem;
        }
        .admin-sidebar .nav-section {
            padding: .5rem 1rem .25rem;
            font-size: .7rem; font-weight: 600; letter-spacing: .05em;
            text-transform: uppercase; color: #94a3b8;
        }
        .admin-sidebar .nav-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem 1.25rem; margin: .125rem .75rem;
            border-radius: .5rem; color: #cbd5e1;
            font-size: .875rem; font-weight: 500;
            transition: all .15s;
        }
        .admin-sidebar .nav-link:hover {
            background: var(--sidebar-hover); color: #fff;
        }
        .admin-sidebar .nav-link.active {
            background: var(--sidebar-active); color: #fff;
        }
        .admin-sidebar .nav-link i { font-size: 1.1rem; width: 1.25rem; text-align: center; }

        /* Main content */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin .3s ease;
        }
        .admin-topbar {
            background: #fff; padding: .75rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            position: sticky; top: 0; z-index: 1020;
        }
        .admin-content { padding: 1.5rem; }

        /* Mobile */
        @media (max-width: 991.98px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .sidebar-overlay {
                display: none; position: fixed; inset: 0;
                background: rgba(0,0,0,.4); z-index: 1035;
            }
            .sidebar-overlay.show { display: block; }
        }

        /* Cards */
        .stat-card {
            border: none; border-radius: .75rem;
            transition: transform .15s, box-shadow .15s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08); }

        /* Tables */
        .table-admin { font-size: .875rem; }
        .table-admin th { font-weight: 600; color: #64748b; text-transform: uppercase; font-size: .75rem; letter-spacing: .03em; }
        .table-admin td { vertical-align: middle; }

        /* Buttons */
        .btn { border-radius: .5rem; font-weight: 500; }
        .btn-sm { font-size: .8125rem; }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="{{ route('admin.products.index') }}" class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-bag-check"></i></div>
            <span>{{ config('app.name', 'Admin') }}</span>
        </a>
        <hr class="border-secondary my-0 mx-3 opacity-25">
        <nav class="mt-2">
            <div class="nav-section">Kelola</div>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Produk
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Kategori
            </a>
            <a href="{{ route('admin.tags.index') }}" class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Tag Promo
            </a>
            <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="bi bi-image"></i> Banner
            </a>
            <div class="nav-section mt-3">Pengaturan</div>
            <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Pengaturan WA
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profile
            </a>
        </nav>
    </aside>

    {{-- Main --}}
    <div class="admin-main">
        {{-- Topbar --}}
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn d-lg-none p-0 border-0 fs-4 text-secondary" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-semibold d-none d-sm-block">@yield('title', 'Dashboard')</h5>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:34px;height:34px;font-size:.85rem;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="d-none d-sm-inline text-dark fw-medium small">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Flash Messages --}}
        <div class="admin-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
