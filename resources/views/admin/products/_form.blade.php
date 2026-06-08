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
    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-5">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
        <input type="text" name="name" id="name" required
               value="{{ old('name', $product->name ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
            Harga Dasar (Rp)
        </label>
        <input type="number" step="0.01" min="0" name="price" id="price" required
               value="{{ old('price', $product->price ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        <p class="text-xs text-gray-400 mt-1">Dipakai bila produk TIDAK memiliki ukuran. Bila ada ukuran, harga diambil dari tiap ukuran.</p>
    </div>

    <div>
        <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-1">Status Stok (produk tanpa ukuran)</label>
        <select name="stock_status" id="stock_status" required
                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            @php $current = old('stock_status', $product->stock_status ?? 'tersedia'); @endphp
            <option value="tersedia" @selected($current === 'tersedia')>Tersedia</option>
            <option value="tidak tersedia" @selected($current === 'tidak tersedia')>Tidak Tersedia</option>
            <option value="pre order" @selected($current === 'pre order')>Pre Order</option>
        </select>
    </div>

    {{-- Deskripsi (CKEditor) --}}
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea name="description" id="description" class="ckeditor-rich">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    {{-- Ukuran --}}
    <div x-data="sizeEditor(@js($oldSizes))" class="border border-gray-200 rounded-lg p-4">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Ukuran (opsional)</h3>
                <p class="text-xs text-gray-400">Tambahkan ukuran bila produk punya varian ukuran. Tiap ukuran punya harga & status sendiri.</p>
            </div>
            <button type="button" @click="addRow()"
                    class="text-sm bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-medium px-3 py-1.5 rounded-md">
                + Tambah Ukuran
            </button>
        </div>

        <template x-if="rows.length === 0">
            <p class="text-sm text-gray-400 py-2">Belum ada ukuran. Produk akan memakai harga & status dasar di atas.</p>
        </template>

        <div class="space-y-2">
            <template x-for="(row, idx) in rows" :key="idx">
                <div class="grid grid-cols-12 gap-2 items-center">
                    <input type="text" :name="`sizes[${idx}][label]`" x-model="row.label" placeholder="Ukuran (mis. M)"
                           class="col-span-3 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <input type="number" step="0.01" min="0" :name="`sizes[${idx}][price]`" x-model="row.price" placeholder="Harga"
                           class="col-span-4 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <select :name="`sizes[${idx}][stock_status]`" x-model="row.stock_status"
                            class="col-span-4 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="tersedia">Tersedia</option>
                        <option value="tidak tersedia">Tidak Tersedia</option>
                        <option value="pre order">Pre Order</option>
                    </select>
                    <button type="button" @click="removeRow(idx)"
                            class="col-span-1 text-red-500 hover:text-red-700 text-sm" title="Hapus">&times;</button>
                </div>
            </template>
        </div>
    </div>

    {{-- Size chart (CKEditor dengan tabel) --}}
    <div>
        <label for="size_chart" class="block text-sm font-medium text-gray-700 mb-1">Size Chart (tabel)</label>
        <p class="text-xs text-gray-400 mb-1">Isi tabel ukuran. Akan tampil sebagai modal "Size Chart" di halaman produk.</p>
        <textarea name="size_chart" id="size_chart" class="ckeditor-table">{{ old('size_chart', $product->size_chart ?? '') }}</textarea>
    </div>

    {{-- Gambar --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>

        @if ($product && $product->images->isNotEmpty())
            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-3">
                @foreach ($product->images as $image)
                    <div class="border rounded-lg p-2 text-center">
                        <img src="{{ $image->url }}" class="w-full h-24 object-cover rounded mb-2" alt="">
                        <label class="flex items-center justify-center gap-1 text-xs text-gray-600">
                            <input type="radio" name="primary_image" value="{{ $image->id }}" @checked($image->is_primary)>
                            Utama
                        </label>
                        <label class="flex items-center justify-center gap-1 text-xs text-red-600 mt-1">
                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                            Hapus
                        </label>
                    </div>
                @endforeach
            </div>
        @endif

        <input type="file" name="images[]" id="images" accept="image/*" multiple
               class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        <p class="text-xs text-gray-400 mt-1">Bisa pilih beberapa gambar sekaligus. Maksimal 2MB per gambar.</p>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">
            Simpan
        </button>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        function sizeEditor(initial) {
            return {
                rows: (initial || []).map(r => ({
                    label: r.label ?? '',
                    price: r.price ?? '',
                    stock_status: r.stock_status ?? 'tersedia',
                })),
                addRow() {
                    this.rows.push({ label: '', price: '', stock_status: 'tersedia' });
                },
                removeRow(idx) {
                    this.rows.splice(idx, 1);
                },
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Editor deskripsi (tanpa tabel).
            document.querySelectorAll('.ckeditor-rich').forEach(function (el) {
                ClassicEditor.create(el).catch(console.error);
            });
            // Editor size chart (dengan tabel).
            document.querySelectorAll('.ckeditor-table').forEach(function (el) {
                ClassicEditor.create(el, {
                    toolbar: ['heading', '|', 'bold', 'italic', '|', 'insertTable', '|', 'undo', 'redo'],
                    table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
                }).catch(console.error);
            });
        });
    </script>
@endpush
