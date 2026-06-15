<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 260px; min-height: 100vh; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);">
    <a href="{{ route('admin.products.index') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="bi bi-grid-1x2-fill me-2 fs-4"></i>
        <span class="fs-5 fw-semibold">Admin Panel</span>
    </a>
    <hr class="border-secondary">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="{{ route('admin.products.index') }}" 
               class="nav-link text-white d-flex align-items-center rounded-3 {{ request()->routeIs('admin.products.*') ? 'active bg-primary bg-opacity-75 shadow-sm' : 'text-white-50 hover-white' }}">
                <i class="bi bi-box-seam me-2"></i>
                Produk
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('admin.categories.index') }}" 
               class="nav-link text-white d-flex align-items-center rounded-3 {{ request()->routeIs('admin.categories.*') ? 'active bg-primary bg-opacity-75 shadow-sm' : 'text-white-50 hover-white' }}">
                <i class="bi bi-bookmark me-2"></i>
                Kategori
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('admin.tags.index') }}" 
               class="nav-link text-white d-flex align-items-center rounded-3 {{ request()->routeIs('admin.tags.*') ? 'active bg-primary bg-opacity-75 shadow-sm' : 'text-white-50 hover-white' }}">
                <i class="bi bi-tags me-2"></i>
                Tag
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('admin.banners.index') }}" 
               class="nav-link text-white d-flex align-items-center rounded-3 {{ request()->routeIs('admin.banners.*') ? 'active bg-primary bg-opacity-75 shadow-sm' : 'text-white-50 hover-white' }}">
                <i class="bi bi-images me-2"></i>
                Banner
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('admin.settings.edit') }}" 
               class="nav-link text-white d-flex align-items-center rounded-3 {{ request()->routeIs('admin.settings.*') ? 'active bg-primary bg-opacity-75 shadow-sm' : 'text-white-50 hover-white' }}">
                <i class="bi bi-gear me-2"></i>
                Pengaturan
            </a>
        </li>
    </ul>
    <hr class="border-secondary">
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
            <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                <i class="bi bi-person-fill"></i>
            </div>
            <span class="text-truncate" style="max-width: 140px;">{{ Auth::user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark shadow">
            <li class="dropdown-item-text text-white-50 small px-3 py-1">{{ Auth::user()->email }}</li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2"></i>Profile
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
