@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Dashboard</h4>
    <p class="text-muted small mb-0">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<div class="row g-3">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon purple mb-2"><i class="bi bi-box-seam-fill"></i></div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted">Produk</h6>
                        <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                            <h6 class="fw-bold mb-0 text-dark">Kelola →</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2"><i class="bi bi-bookmark-fill"></i></div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted">Kategori</h6>
                        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">
                            <h6 class="fw-bold mb-0 text-dark">Kelola →</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon green mb-2"><i class="bi bi-images"></i></div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted">Banner</h6>
                        <a href="{{ route('admin.banners.index') }}" class="text-decoration-none">
                            <h6 class="fw-bold mb-0 text-dark">Kelola →</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon red mb-2"><i class="bi bi-gear-fill"></i></div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted">Settings</h6>
                        <a href="{{ route('admin.settings.edit') }}" class="text-decoration-none">
                            <h6 class="fw-bold mb-0 text-dark">Kelola →</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
