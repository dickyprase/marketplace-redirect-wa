@php
    $product = $product ?? null;
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

<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
        <input type="text" name="name" id="name" required
               value="{{ old('name', $product->name ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
        <input type="number" step="0.01" min="0" name="price" id="price" required
               value="{{ old('price', $product->price ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-1">Status Stok</label>
        <select name="stock_status" id="stock_status" required
                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            @php $current = old('stock_status', $product->stock_status ?? 'tersedia'); @endphp
            <option value="tersedia" @selected($current === 'tersedia')>Tersedia</option>
            <option value="tidak tersedia" @selected($current === 'tidak tersedia')>Tidak Tersedia</option>
            <option value="pre order" @selected($current === 'pre order')>Pre Order</option>
        </select>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea name="description" id="description" rows="5"
                  class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
        @if (! empty($product?->image_path))
            <img src="{{ asset('storage/' . $product->image_path) }}" class="w-24 h-24 object-cover rounded mb-2" alt="">
        @endif
        <input type="file" name="image" id="image" accept="image/*"
               class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        <p class="text-xs text-gray-400 mt-1">Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">
            Simpan
        </button>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
    </div>
</div>
