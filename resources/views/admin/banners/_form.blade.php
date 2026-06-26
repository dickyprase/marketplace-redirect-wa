@php $banner = $banner ?? null; @endphp

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="vstack gap-4">
    <div>
        <label class="form-label fw-medium">Gambar Banner</label>
        @if (! empty($banner?->image_path))
            <img src="{{ asset('storage/'.$banner->image_path) }}" class="rounded mb-2 d-block" style="width:100%;max-height:160px;object-fit:cover" alt="">
        @endif
        <input type="file" name="image" accept="image/*" {{ $banner ? '' : 'required' }} class="form-control">
        <div class="form-text">Ukuran terbaik: 1920×700 px. Maks 20MB.</div>
    </div>
    <div>
        <label class="form-label fw-medium">Link (opsional)</label>
        <input type="text" name="link" value="{{ old('link', $banner->link ?? '') }}" placeholder="https://..." class="form-control">
    </div>
    <div>
        <label class="form-label fw-medium">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="form-control" style="max-width:150px">
    </div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="bannerActive" @checked(old('is_active', $banner->is_active ?? true))>
        <label class="form-check-label" for="bannerActive">Aktif (tampil di homepage)</label>
    </div>
    <div class="d-flex gap-2 pt-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-light">Batal</a>
    </div>
</div>
