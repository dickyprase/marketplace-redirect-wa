@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h5 class="fw-bold mb-1">Dashboard</h5>
    <p class="text-muted small mb-0">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<div class="row g-3">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon purple"><i class="bi bi-box-seam-fill"></i></div>
                <div>
                    <div class="text-muted small">Produk</div>
                    <a href="{{ route('admin.products.index') }}" class="fw-bold text-dark text-decoration-none">Kelola →</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon blue"><i class="bi bi-bookmark-fill"></i></div>
                <div>
                    <div class="text-muted small">Kategori</div>
                    <a href="{{ route('admin.categories.index') }}" class="fw-bold text-dark text-decoration-none">Kelola →</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon green"><i class="bi bi-images"></i></div>
                <div>
                    <div class="text-muted small">Banner</div>
                    <a href="{{ route('admin.banners.index') }}" class="fw-bold text-dark text-decoration-none">Kelola →</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon red"><i class="bi bi-gear-fill"></i></div>
                <div>
                    <div class="text-muted small">Settings</div>
                    <a href="{{ route('admin.settings.edit') }}" class="fw-bold text-dark text-decoration-none">Kelola →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
