@extends('layouts.admin')
@section('title', 'Edit Banner')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.banners.index') }}" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Edit Banner</h5>
        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.banners._form', ['banner' => $banner])
        </form>
    </div>
</div>
@endsection
