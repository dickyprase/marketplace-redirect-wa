@extends('layouts.admin')
@section('title', 'Kelola Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
 <div>
 <h4 class="fw-bold mb-1">Kategori</h4>
 <p class="text-muted small mb-0">Kelola kategori produk</p>
 </div>
 <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Kategori</a>
</div>

<div class="card">
 <div class="table-responsive">
 <table class="table table-hover mb-0">
 <thead>
 <tr>
 <th class="ps-3">Gambar</th>
 <th>Nama</th>
 <th>Slug</th>
 <th>Urutan</th>
 <th class="text-end pe-3">Aksi</th>
 </tr>
 </thead>
 <tbody>
 @forelse ($categories as $cat)
 <tr>
 <td class="ps-3">
 @if ($cat->image_path)
 <img src="{{ asset('storage/'.$cat->image_path) }}" class="rounded" style="width:44px;height:44px;object-fit:cover" alt="">
 @else
 <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:44px;height:44px"><i class="bi bi-image text-muted"></i></div>
 @endif
 </td>
 <td class="fw-medium text-dark">{{ $cat->name }}</td>
 <td class="text-muted small">{{ $cat->slug }}</td>
 <td>{{ $cat->sort_order }}</td>
 <td class="text-end pe-3">
 <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
 <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?');">
 @csrf @method('DELETE')
 <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="5" class="text-center text-muted py-5">Belum ada kategori.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
</div>
<div class="mt-3">{{ $categories->links() }}</div>
@endsection
