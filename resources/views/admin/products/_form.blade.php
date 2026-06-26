@php
    $product = $product ?? null;
    $oldSizes = old('sizes');
    if ($oldSizes === null) {
        $oldSizes = $product
            ? $product->sizes->map(fn ($s) => [
                'label' => $s->label,
                'price' => $s->price,
                'stock_status' => $s->stock_status,
            ])->values()->all()
            : [];
    } else {
        $oldSizes = array_values($oldSizes);
    }
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="vstack gap-4">
    {{-- Nama --}}
    <div>
        <label for="name" class="form-label fw-medium">Nama Produk</label>
        <input type="text" name="name" id="name" required value="{{ old('name', $product->name ?? '') }}" class="form-control">
    </div>

    <div class="row g-3">
        {{-- Harga --}}
        <div class="col-md-6">
            <label for="price" class="form-label fw-medium">Harga Dasar (Rp)</label>
            <input type="number" step="0.01" min="0" name="price" id="price" required value="{{ old('price', $product->price ?? '') }}" class="form-control">
            <div class="form-text">Dipakai bila produk TIDAK memiliki ukuran.</div>
        </div>
        {{-- Status Stok --}}
        <div class="col-md-6">
            <label for="stock_status" class="form-label fw-medium">Status Stok</label>
            @php $current = old('stock_status', $product->stock_status ?? 'tersedia'); @endphp
            <select name="stock_status" id="stock_status" required class="form-select">
                <option value="tersedia" @selected($current === 'tersedia')>Tersedia</option>
                <option value="tidak tersedia" @selected($current === 'tidak tersedia')>Tidak Tersedia</option>
                <option value="pre order" @selected($current === 'pre order')>Pre Order</option>
            </select>
        </div>
    </div>

    <div class="row g-3">
        {{-- Kategori --}}
        <div class="col-md-6">
            <label for="category_id" class="form-label fw-medium">Kategori</label>
            <select name="category_id" id="category_id" class="form-select">
                <option value="">— Tanpa kategori —</option>
                @foreach (($categories ?? collect()) as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? null) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- Tag Promo --}}
        <div class="col-md-6">
            <label class="form-label fw-medium">Tag Promo</label>
            @php $selectedTagIds = old('tags', isset($product) ? $product->tags->pluck('id')->all() : []); @endphp
            <div class="d-flex flex-wrap gap-2">
                @foreach (($tags ?? collect()) as $tag)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}" @checked(in_array($tag->id, $selectedTagIds))>
                        <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                    </div>
                @endforeach
                @if (($tags ?? collect())->isEmpty())
                    <p class="text-muted small">Belum ada tag.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Deskripsi --}}
    <div>
        <label for="description" class="form-label fw-medium">Deskripsi</label>
        <textarea name="description" id="description" class="ckeditor-rich">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    {{-- Ukuran --}}
    <div x-data="sizeEditor(@js($oldSizes))" class="card border">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-semibold">Ukuran (opsional)</h6>
                <small class="text-muted">Tiap ukuran punya harga & status sendiri.</small>
            </div>
            <button type="button" @click="addRow()" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus"></i> Tambah Ukuran
            </button>
        </div>
        <div class="card-body">
            <template x-if="rows.length === 0">
                <p class="text-muted small mb-0">Belum ada ukuran. Produk akan memakai harga dasar.</p>
            </template>
            <div class="vstack gap-2">
                <template x-for="(row, idx) in rows" :key="idx">
                    <div class="row g-2 align-items-center">
                        <div class="col-3">
                            <input type="text" :name="`sizes[${idx}][label]`" x-model="row.label" placeholder="Ukuran (mis. M)" class="form-control form-control-sm">
                        </div>
                        <div class="col-4">
                            <input type="number" step="0.01" min="0" :name="`sizes[${idx}][price]`" x-model="row.price" placeholder="Harga" class="form-control form-control-sm">
                        </div>
                        <div class="col-4">
                            <select :name="`sizes[${idx}][stock_status]`" x-model="row.stock_status" class="form-select form-select-sm">
                                <option value="tersedia">Tersedia</option>
                                <option value="tidak tersedia">Tidak Tersedia</option>
                                <option value="pre order">Pre Order</option>
                            </select>
                        </div>
                        <div class="col-1 text-center">
                            <button type="button" @click="removeRow(idx)" class="btn btn-sm btn-link text-danger p-0" title="Hapus"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Size Chart --}}
    <div>
        <label for="size_chart" class="form-label fw-medium">Size Chart (tabel)</label>
        <div class="form-text mt-0 mb-2">Akan tampil sebagai modal "Size Chart" di halaman produk.</div>
        <textarea name="size_chart" id="size_chart" class="ckeditor-table">{{ old('size_chart', $product->size_chart ?? '') }}</textarea>
    </div>

    {{-- Gambar --}}
    <div>
        <label class="form-label fw-medium">Gambar Produk</label>
        @if ($product && $product->images->isNotEmpty())
            <div class="row g-3 mb-3">
                @foreach ($product->images as $image)
                    <div class="col-4 col-sm-3 col-lg-2">
                        <div class="card border text-center p-2">
                            <img src="{{ $image->url }}" class="rounded mb-2" style="height:80px;object-fit:cover" alt="">
                            <div class="form-check form-check-inline small">
                                <input class="form-check-input" type="radio" name="primary_image" value="{{ $image->id }}" id="primary-{{ $image->id }}" @checked($image->is_primary)>
                                <label class="form-check-label small" for="primary-{{ $image->id }}">Utama</label>
                            </div>
                            <div class="form-check small">
                                <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="del-{{ $image->id }}">
                                <label class="form-check-label small text-danger" for="del-{{ $image->id }}">Hapus</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <input type="file" name="images[]" id="images" accept="image/*" multiple class="form-control">
        <div class="form-text">Bisa pilih beberapa gambar. Maks 20MB per gambar. Ukuran terbaik: 800×800 px.</div>
    </div>

    {{-- Submit --}}
    <div class="d-flex gap-2 pt-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        function sizeEditor(initial) {
            return {
                rows: (initial || []).map(r => ({ label: r.label ?? '', price: r.price ?? '', stock_status: r.stock_status ?? 'tersedia' })),
                addRow() { this.rows.push({ label: '', price: '', stock_status: 'tersedia' }); },
                removeRow(idx) { this.rows.splice(idx, 1); },
            };
        }
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.ckeditor-rich').forEach(el => ClassicEditor.create(el).catch(console.error));
            document.querySelectorAll('.ckeditor-table').forEach(el => {
                ClassicEditor.create(el, {
                    toolbar: ['heading', '|', 'bold', 'italic', '|', 'insertTable', '|', 'undo', 'redo'],
                    table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
                }).catch(console.error);
            });
        });
    </script>
@endpush
