<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} - {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('mazer/static/images/logo/favicon.svg') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/css/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #fff;
            --sidebar-border: #e9ecef;
            --sidebar-text: #6c757d;
            --sidebar-text-active: #6366f1;
            --sidebar-icon-active: #6366f1;
            --sidebar-hover-bg: #f3f4ff;
            --main-bg: #f2f7ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--main-bg);
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            z-index: 1050;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        #sidebar .sidebar-wrapper {
            flex: 1;
            overflow: hidden;
        }
        #sidebar .ps {
            height: calc(100vh - 70px);
        }
        .sidebar-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .sidebar-header .logo img {
            height: 30px;
        }
        .sidebar-header .logo span {
            font-size: 1.3rem;
            font-weight: 800;
            color: #6366f1;
            letter-spacing: -0.5px;
        }
        .sidebar-menu .menu {
            list-style: none;
            padding: 0.5rem 0;
        }
        .sidebar-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #a0a4b0;
            padding: 1.2rem 1.5rem 0.5rem;
        }
        .sidebar-item .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
            border-radius: 0;
            gap: 0.8rem;
        }
        .sidebar-item .sidebar-link i {
            font-size: 1.15rem;
            width: 24px;
            text-align: center;
        }
        .sidebar-item .sidebar-link:hover {
            background: var(--sidebar-hover-bg);
            color: var(--sidebar-text-active);
        }
        .sidebar-item.active .sidebar-link {
            background: var(--sidebar-hover-bg);
            color: var(--sidebar-text-active);
        }
        .sidebar-item.active .sidebar-link i {
            color: var(--sidebar-icon-active);
        }

        /* Main Content */
        #main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Top navbar (inside main) */
        .topbar {
            background: #fff;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sidebar-border);
            position: sticky;
            top: 0;
            z-index: 1040;
        }
        .burger-btn {
            display: none;
            font-size: 1.5rem;
            color: #333;
            cursor: pointer;
            background: none;
            border: none;
        }
        .topbar .dropdown .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Page content */
        .page-heading {
            padding: 1.5rem 1.5rem 0;
        }
        .page-heading h3 {
            font-weight: 700;
            font-size: 1.25rem;
            color: #333;
        }
        .page-content {
            padding: 1rem 1.5rem 2rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 1.25rem;
        }
        .card-header h4 {
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
            color: #333;
        }
        .card-body {
            padding: 1.25rem;
        }

        /* Stats icons */
        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .stats-icon.purple { background: #ede7f6; color: #7c3aed; }
        .stats-icon.blue { background: #e0f2fe; color: #0284c7; }
        .stats-icon.green { background: #dcfce7; color: #16a34a; }
        .stats-icon.red { background: #fef2f2; color: #dc2626; }

        /* Responsive */
        @media (max-width: 1199.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.active {
                transform: translateX(0);
            }
            #main {
                margin-left: 0;
            }
            .burger-btn {
                display: block;
            }
            #sidebar .sidebar-hide {
                position: absolute;
                top: 1rem;
                right: 1rem;
                font-size: 1.5rem;
                color: #666;
                cursor: pointer;
            }
        }

        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1045;
        }
        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    {{-- Sidebar --}}
    <div id="sidebar" class="sidebar-wrapper">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.dashboard') }}" class="logo text-decoration-none">
                    <span>⚡ {{ config('app.name') }}</span>
                </a>
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
        <div class="sidebar-menu perfect-scrollbar">
            <ul class="menu">
                <li class="sidebar-title">Main</li>
                <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">Data</li>
                <li class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}" class="sidebar-link">
                        <i class="bi bi-box-seam-fill"></i><span>Produk</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-link">
                        <i class="bi bi-bookmark-fill"></i><span>Kategori</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tags.index') }}" class="sidebar-link">
                        <i class="bi bi-tags-fill"></i><span>Tag</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.banners.index') }}" class="sidebar-link">
                        <i class="bi bi-images"></i><span>Banner</span>
                    </a>
                </li>

                <li class="sidebar-title">Pengaturan</li>
                <li class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.edit') }}" class="sidebar-link">
                        <i class="bi bi-gear-fill"></i><span>Settings</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.profile.edit') }}" class="sidebar-link">
                        <i class="bi bi-person-fill"></i><span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('/') }}" class="sidebar-link" target="_blank">
                        <i class="bi bi-shop"></i><span>Lihat Toko</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start">
                            <i class="bi bi-box-arrow-left"></i><span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Main --}}
    <div id="main">
        <div class="topbar">
            <button class="burger-btn" id="burgerBtn">
                <i class="bi bi-list"></i>
            </button>
            <div></div>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="avatar avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:0.85rem;font-weight:700;">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="ms-2 fw-semibold d-none d-md-inline" style="color:#333;">{{ Auth::user()->name ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-left me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="container-fluid px-4 pt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const burger = document.getElementById('burgerBtn');

        burger?.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
        document.querySelector('.sidebar-hide')?.addEventListener('click', (e) => {
            e.preventDefault();
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // Perfect scrollbar
        if (window.innerWidth >= 1200) {
            new PerfectScrollbar('.sidebar-menu');
        }
    </script>
    @stack('scripts')
</body>
</html>
