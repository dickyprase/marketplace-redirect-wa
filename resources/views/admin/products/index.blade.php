@extends('layouts.admin')
@section('title', 'Kelola Produk')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
<style>
 .dt-search input,
 .dt-length select { border-radius: .6rem; border-color: #e2e8f0; }
 .dt-paging .page-link { border-radius: .5rem; margin: 0 2px; }
</style>
@endpush

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

<div class="card">
 <div class="card-body">
  <div class="table-responsive">
   <table id="productsTable" class="table table-hover align-middle mb-0 w-100">
    <thead>
     <tr>
      <th>Produk</th>
      <th>Kategori</th>
      <th>Tag</th>
      <th>Harga</th>
      <th>Status</th>
      <th class="text-end">Aksi</th>
     </tr>
    </thead>
    <tbody>
     @forelse ($products as $product)
     <tr>
      <td>
       <div class="d-flex align-items-center gap-3">
        @if ($product->primaryImage)
         <img src="{{ $product->primaryImage->url }}" class="rounded" style="width:44px;height:44px;object-fit:cover" alt="{{ $product->name }}">
        @else
         <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:44px;height:44px"><i class="bi bi-image text-muted"></i></div>
        @endif
        <div>
         <div class="fw-semibold text-dark">{{ $product->name }}</div>
         <small class="text-muted">{{ $product->slug }}</small>
        </div>
       </div>
      </td>
      <td>
       @if ($product->category)
        <span class="d-inline-flex align-items-center gap-1">
         @if ($product->category->image_path)
          <img src="{{ asset('storage/' . $product->category->image_path) }}" class="rounded" style="width:18px;height:18px;object-fit:cover" alt="{{ $product->category->name }}">
         @endif
         {{ $product->category->name }}
        </span>
       @else
        <span class="text-muted">-</span>
       @endif
      </td>
      <td>
       <div class="d-flex flex-wrap gap-1">
        @forelse ($product->tags as $tag)
         <span class="badge bg-light text-dark border">{{ $tag->name }}</span>
        @empty
         <span class="text-muted">-</span>
        @endforelse
       </div>
      </td>
      <td class="text-dark">{{ $product->priceRangeLabel() }}</td>
      <td><x-stock-badge :status="$product->stock_status" /></td>
      <td class="text-end">
       <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
       <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
       </form>
      </td>
     </tr>
     @empty
     @endforelse
    </tbody>
   </table>
  </div>
 </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script>
 document.addEventListener('DOMContentLoaded', function () {
  new DataTable('#productsTable', {
   pageLength: 10,
   lengthMenu: [10, 25, 50, 100],
   order: [[0, 'asc']],
   language: {
    search: 'Cari:',
    lengthMenu: 'Tampilkan _MENU_ produk',
    info: 'Menampilkan _START_–_END_ dari _TOTAL_ produk',
    infoEmpty: 'Belum ada produk',
    zeroRecords: 'Produk tidak ditemukan',
    paginate: { first: 'Awal', last: 'Akhir', next: '›', previous: '‹' }
   },
   columnDefs: [
    { orderable: false, targets: [5] }
   ]
  });
 });
</script>
@endpush
