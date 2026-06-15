@extends('layouts.admin')
@section('title', 'Edit Tag')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tags.index') }}" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Edit Tag</h5>
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
            @csrf @method('PUT')
            <div class="vstack gap-4">
                <div>
                    <label class="form-label fw-medium">Nama Tag</label>
                    <input type="text" name="name" required value="{{ old('name', $tag->name) }}" class="form-control">
                </div>
                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                    <a href="{{ route('admin.tags.index') }}" class="btn btn-light">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
