@extends('layouts.admin')
@section('title', 'Kelola Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Banner</h4>
        <p class="text-muted small mb-0">Kelola banner slider homepage</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Banner</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-admin mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3">Gambar</th>
                    <th>Link</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($banners as $b)
                    <tr>
                        <td class="ps-3"><img src="{{ asset('storage/'.$b->image_path) }}" class="rounded" style="width:120px;height:48px;object-fit:cover" alt=""></td>
                        <td class="text-muted small">{{ $b->link ?? '-' }}</td>
                        <td>{{ $b->sort_order }}</td>
                        <td>
                            @if ($b->is_active)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.banners.edit', $b) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.banners.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus banner ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">Belum ada banner.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $banners->links() }}</div>
@endsection
