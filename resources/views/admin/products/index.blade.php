@extends('layouts.admin')
@section('title', 'Kelola Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Produk</h4>
        <p class="text-muted small mb-0">Kelola semua produk Anda</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-admin mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3">Produk</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="rounded" style="width:44px;height:44px;object-fit:cover" alt="">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:44px;height:44px">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold text-dark">{{ $product->name }}</div>
                                    @if ($product->category)
                                        <small class="text-muted">{{ $product->category->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-dark">{{ $product->formatted_price }}</td>
                        <td><x-stock-badge :status="$product->stock_status" /></td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-5">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $products->links() }}</div>
@endsection
