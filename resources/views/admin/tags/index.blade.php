@extends('layouts.admin')
@section('title', 'Kelola Tag')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
 <div>
 <h4 class="fw-bold mb-1">Tag Promo</h4>
 <p class="text-muted small mb-0">Kelola tag promo produk</p>
 </div>
 <a href="{{ route('admin.tags.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Tag</a>
</div>

<div class="card">
 <div class="table-responsive">
 <table class="table table-hover mb-0">
 <thead>
 <tr>
 <th class="ps-3">Nama</th>
 <th>Slug</th>
 <th class="text-end pe-3">Aksi</th>
 </tr>
 </thead>
 <tbody>
 @forelse ($tags as $tag)
 <tr>
 <td class="ps-3 fw-medium text-dark">{{ $tag->name }}</td>
 <td class="text-muted small">{{ $tag->slug }}</td>
 <td class="text-end pe-3">
 <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
 <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tag ini?');">
 @csrf @method('DELETE')
 <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="3" class="text-center text-muted py-5">Belum ada tag.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
</div>
<div class="mt-3">{{ $tags->links() }}</div>
@endsection
