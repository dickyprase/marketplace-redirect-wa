@extends('layouts.admin')
@section('title', 'Edit Kategori')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Edit Kategori</h5>
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.categories._form', ['category' => $category])
        </form>
    </div>
</div>
@endsection
