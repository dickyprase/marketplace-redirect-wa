@php $category = $category ?? null; @endphp

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="vstack gap-4">
    <div>
        <label class="form-label fw-medium">Nama Kategori</label>
        <input type="text" name="name" required value="{{ old('name', $category->name ?? '') }}" class="form-control">
    </div>
    <div>
        <label class="form-label fw-medium">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="form-control" style="max-width:150px">
    </div>
    <div>
        <label class="form-label fw-medium">Gambar</label>
        @if (! empty($category?->image_path))
            <img src="{{ asset('storage/'.$category->image_path) }}" class="rounded mb-2 d-block" style="width:100px;height:100px;object-fit:cover" alt="">
        @endif
        <input type="file" name="image" accept="image/*" class="form-control">
        <div class="form-text">Ukuran terbaik: 600×400 px (landscape).</div>
    </div>
    <div class="d-flex gap-2 pt-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Batal</a>
    </div>
</div>
