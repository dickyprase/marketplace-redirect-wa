@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Dashboard</h4>
    <p class="text-muted small mb-0">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<div class="row g-3">
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.products.index') }}" class="card stat-card border-0 shadow-sm text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary bg-opacity-10 p-3"><i class="bi bi-box-seam text-primary fs-4"></i></div>
                <div><div class="fw-bold text-dark">Produk</div><small class="text-muted">Kelola produk</small></div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.categories.index') }}" class="card stat-card border-0 shadow-sm text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-success bg-opacity-10 p-3"><i class="bi bi-grid text-success fs-4"></i></div>
                <div><div class="fw-bold text-dark">Kategori</div><small class="text-muted">Kelola kategori</small></div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.banners.index') }}" class="card stat-card border-0 shadow-sm text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-warning bg-opacity-10 p-3"><i class="bi bi-image text-warning fs-4"></i></div>
                <div><div class="fw-bold text-dark">Banner</div><small class="text-muted">Kelola banner</small></div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.settings.edit') }}" class="card stat-card border-0 shadow-sm text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-info bg-opacity-10 p-3"><i class="bi bi-gear text-info fs-4"></i></div>
                <div><div class="fw-bold text-dark">Pengaturan</div><small class="text-muted">WA & kontak</small></div>
            </div>
        </a>
    </div>
</div>
@endsection
