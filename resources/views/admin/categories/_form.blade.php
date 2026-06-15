@php $category = $category ?? null; @endphp

@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input type="text" name="name" required value="{{ old('name', $category->name ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
        @if (! empty($category?->image_path))
            <img src="{{ asset('storage/'.$category->image_path) }}" class="w-24 h-24 object-cover rounded mb-2" alt="">
        @endif
        <input type="file" name="image" accept="image/*"
               class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700">
        <p class="text-xs text-indigo-600 mt-1 font-medium">Ukuran gambar terbaik: 600 x 400 px (landscape). Format: JPG, PNG, atau WEBP.</p>
    </div>
    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500">Batal</a>
    </div>
</div>
